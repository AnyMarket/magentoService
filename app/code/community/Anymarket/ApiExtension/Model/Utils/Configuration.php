<?php

class Anymarket_ApiExtension_Model_Utils_Configuration extends Mage_Catalog_Model_Api_Resource
{
    
    public function updateConfiguration($oi, $host, $storeCode = null)
    {
        $store = Mage::getModel('core/store')->load($storeCode);

        Mage::getConfig()->saveConfig('anymarket_new_section/anymarket_new_access_group/anymarket_new_oi_field', $oi, 'stores', $store->getId());
        Mage::getConfig()->saveConfig('anymarket_new_section/anymarket_new_access_group/anymarket_new_host_field', $host, 'stores', $store->getId());

        Mage::log($oi . " | " . $host . " | " . $storeCode, null, 'errorAPI.log');
        return "1.0.2";
    }

}