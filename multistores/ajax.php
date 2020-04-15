<?php

// On ajoute ces require pour profiter des class PRestashop
require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');

// On ajoute notre module
require_once(dirname(__FILE__).'/multistores.php');

$action = Tools::getValue('action');

$message = [];
$status = 'success';

switch ($action) {
    case 'selectedStore':
        $store = Tools::getValue('store');
        $store_id = Tools::getValue('store_id');
        $context = Context::getContext();
        $context->cookie->__set('store_selection', $store);
        $context->cookie->__set('id_store_selection', $store_id);
        $context->cookie->write();
        $message = MultiStores::ajaxGetSelection();
        $id_customer = $context->customer->id;
        Db::getInstance()->insert('ms_store_customer', array( 'id_customer' => (int)$id_customer, 'store_name' => pSQL($store), ));
    break;
}

header('Content-Type: application/json');
die(json_encode($message));