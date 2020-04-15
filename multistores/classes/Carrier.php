<?php

class Carrier extends ObjectModel
{
    // On déclare les champs de la table
    public $name;

    public $delay;

    public $active = true;

    public $is_free = true;

    public $range_behavior;

    public $need_range = 0;

    public $shipping_external = 0;

    public $external_module_name = null;

    public $shipping_method = 0;


    // On définit les champs
    public static $definition = [
        'table' => 'carrier',
        'primary' => 'id_carrier',
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => [
            'name' => ['type' => self::TYPE_STRING, 'validate' => 'isCarrierName', 'required' => true, 'size' => 64],
            'delay' => ['type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 512],
            'active' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true],
            'is_free' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'range_behavior' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'need_range' => ['type' => self::TYPE_BOOL],
            'shipping_external' => ['type' => self::TYPE_BOOL],
            'external_module_name' => ['type' => self::TYPE_STRING, 'size' => 64],
            'shipping_method' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
        ]
    ];

    public function __construct($id = null, $id_lang = null)
    {
        parent::__construct($id, $id_lang);
    }

}
