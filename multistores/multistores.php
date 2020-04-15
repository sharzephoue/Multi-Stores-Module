<?php

// On inclut la/les classes créées
//require_once(dirname(__FILE__).'/classes/.php');

// sécurité de base
if (!defined('_PS_VERSION_')){
    exit;
}

class MultiStores extends Module {
    
    public function __construct()
    {
        //Informations de base du module
        $this->name = 'multistores';
        $this->displayName = $this->l('Multi-Stores');
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Guillaume Muller';
        $this->description = $this->l('Multiple stores manager');
        $this->ps_versions_compliancy =['min' => '1.6', 'max' => _PS_VERSION_];
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module ?');

        // Compatibilité 1.6
        $this->bootstrap = true;

        // On fait appel au parent
        parent::__construct();
    }


    // On ajoute des actions à l'installation
    public function install() {

        //Ajout d'un transporteur
        $carrier = $this->addCarrier();
        $this->addZones($carrier);
        $this->addGroups($carrier);
        $this->addRanges($carrier);

        if (!parent::install() 
        //Hooks
        || !$this->registerHook('displayCarrierExtraContent')
        || !$this->registerHook('displayHeader')
        //Menu
        || !$this->_installTab('AdminParentOrders','AdminMultiStores', $this->l('Store orders'))
        //Table
        || !$this->_installSql()
        ){          
            return false;
        }
        return true;
    }


    public function uninstall() {
        if (!parent::uninstall()

        //Désinstallation Menu
        || !$this->_uninstallTab('AdminMultiStores')
        //Supression Transporteur
        || !$this->_uninstallCarrier()
        || !Configuration::deleteByName('MULTISTORES_CARRIER_ID')

        ){
            return false;
        }
        return true;
    }

    //Chargement fichier JS + CSS
    public function hookDisplayHeader($params)
    {

        $js = [
            $this->_path . 'views/js/multistores.js',
        ];

        $css = [
            $this->_path . 'views/css/multistores.css',
        ];

        $this->context->controller->addJS($js);
        $this->context->controller->addCSS($css);

    }

    //Afficher la liste sous le transporteur
    public function hookDisplayCarrierExtraContent($params) {

        
        //Traduction
        $id_lang = (int)$this->context->language->id;

        //Récupération des magasins
        $stores = Store::getStores($id_lang);

        $imageRetriever = new \PrestaShop\PrestaShop\Adapter\Image\ImageRetriever($this->context->link);
        
        foreach ($stores as &$store) {
            unset($store['active']);
            $store['image'] = $imageRetriever->getImage(new Store($store['id_store']), $store['id_store']);

            $temp = json_decode($store['hours'], true);
            unset($store['hours']);
            $store['business_hours'] = [
                [
                    'day' => $this->trans('Monday', [], 'Shop.Theme.Global'),
                    'hours' => $temp[0],
                ], [
                    'day' => $this->trans('Tuesday', [], 'Shop.Theme.Global'),
                    'hours' => $temp[1],
                ], [
                    'day' => $this->trans('Wednesday', [], 'Shop.Theme.Global'),
                    'hours' => $temp[2],
                ], [
                    'day' => $this->trans('Thursday', [], 'Shop.Theme.Global'),
                    'hours' => $temp[3],
                ], [
                    'day' => $this->trans('Friday', [], 'Shop.Theme.Global'),
                    'hours' => $temp[4],
                ], [
                    'day' => $this->trans('Saturday', [], 'Shop.Theme.Global'),
                    'hours' => $temp[5],
                ], [
                    'day' => $this->trans('Sunday', [], 'Shop.Theme.Global'),
                    'hours' => $temp[6],
                ],
            ];
        }

        $this->context->smarty->assign('stores',$stores);

        //Test voir résultat retourné
        //dump($this->context->cookie);die;

        return $this->display(__FILE__, 'displayCarrierExtraContent.tpl');

        
    }


    // Créer un menu dans le back-office
    private function _installTab($parent, $class_name, $name) {
    $tab = new Tab();
    $tab->id_parent = (int)Tab::getIdFromClassName($parent);
    $tab->class_name = $class_name;
    $tab->module = $this->name;

    // Gérer les langues
    $tab->name = [];
    foreach(Language::getLanguages(true) as $lang) {
        $tab->name[$lang['id_lang']] = $name;
    }

        return $tab->save();
    }

    // Supprimer un menu du back-office
    private function _uninstallTab($class_name) {
        $id_tab = Tab::getIdFromClassName($class_name);
        $tab = new Tab((int)$id_tab);

        return $tab->delete();

    }

    //Ajout de transporteur
    protected function addCarrier()
	{

        if (Configuration::get('MULTISTORES_CARRIER_INSTALLED') == null) {
		    $carrier = new Carrier();

		    $carrier->name = $this->l('Multi-Stores Pick-Up');
		    $carrier->is_module = true;
		    $carrier->is_free = true;
		    $carrier->active = 1;
		    $carrier->range_behavior = 1;
		    $carrier->need_range = 1;
		    $carrier->shipping_external = true;
		    $carrier->range_behavior = 0;
		    $carrier->external_module_name = $this->name;
		    $carrier->shipping_method = 2;

		    foreach (Language::getLanguages() as $lang)
		    	$carrier->delay[$lang['id_lang']] = $this->l('Pick-up in store');

		    if ($carrier->add() == true)
		    {
		    	@copy(dirname(__FILE__).'/views/img/carrier.png', _PS_SHIP_IMG_DIR_ . '/' . (int)$carrier->id . '.png');
			    Configuration::updateValue('MULTISTORES_CARRIER_ID', (int)$carrier->id);
			    return $carrier;
		    }

            Configuration::updateValue('MULTISTORES_CARRIER_INSTALLED', 1);

        }

		return false;
	}

	protected function addGroups($carrier)
	{
		$groups_ids = array();
		$groups = Group::getGroups(Context::getContext()->language->id);
		foreach ($groups as $group)
			$groups_ids[] = $group['id_group'];

		$carrier->setGroups($groups_ids);
	}

	protected function addRanges($carrier)
	{
		$range_price = new RangePrice();
		$range_price->id_carrier = $carrier->id;
		$range_price->delimiter1 = '0';
		$range_price->delimiter2 = '10000';
		$range_price->add();

		$range_weight = new RangeWeight();
		$range_weight->id_carrier = $carrier->id;
		$range_weight->delimiter1 = '0';
		$range_weight->delimiter2 = '10000';
		$range_weight->add();
	}

	protected function addZones($carrier)
	{
		$zones = Zone::getZones();

		foreach ($zones as $zone)
			$carrier->addZone($zone['id_zone']);
	}

    //Suppression transporteur
    private function _uninstallCarrier()
    {
        $carrier = new Carrier(Configuration::get('MULTISTORES_CARRIER_ID'));
        $carrier->delete();

        return true;
    }

    //Création table
    private function _installSql() {
 
        //Récupération requête pour la table ms_store_employee
        include(dirname(__FILE__).'/sql/install.php');
        $result = true;
        foreach($sql_requests as $request) {
            if (!empty($request)) {
                $result &= Db::getInstance()->execute($request);
            }
        }
        return true;
    }

    //Traitement requête Ajax
    public static function ajaxGetSelection(){
        $store = Tools::getValue('store');
        $store_id = Tools::getValue('store_id');

        $message['success'] = true;
        $message['store'] = $store;
        $message['store_id'] = $store_id;

        return $message;
    }


    //Récupération table Stores
    private function getStores() {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('store', 's');
        $sql->where('s.active = 1 AND sl.id_lang='.Context::getContext()->language->id);
        $sql->innerJoin('store_lang', 'sl', 's.id_store = sl.id_store');    
        $stores = Db::getInstance()->executeS($sql);       
 
        return $stores;

    }

    //Récupération table Employee
    private function getEmployee() {

        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('employee', 'e');
        $sql->where('e.active = 1');
        $employee = Db::getInstance()->executeS($sql);
    
        return $employee;
    }


    //Configuration du module
    public function getContent() { 

        $stores = $this->getStores();
        $employee = $this->getEmployee();

        $this->context->smarty->assign([
            'module_version' => $this->version,
            'stores' => $stores,
            'employee' => $employee
        ]);
 
        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
 
    }
 
}