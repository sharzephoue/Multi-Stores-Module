<?php
//Requête SQL création table store_employee

$sql_requests = [];
 
$sql_requests[] = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'ms_employee
(
    `id_store_employee` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_store` INT(1),
    `id_employee` INT(1)   
)
ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';

$sql_requests[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ms_customer
(
    `id_store_customer` INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_customer` INT(1) NOT NULL,
    `store_name` VARCHAR(128) NOT NULL
)
ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';
