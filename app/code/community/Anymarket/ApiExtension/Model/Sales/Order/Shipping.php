<?php

class Anymarket_ApiExtension_Model_Sales_Order_Shipping extends Mage_Catalog_Model_Api_Resource
{
    
    public function getAllShippingMethods()
    {
        $carriers = Mage::getsingleton("shipping/config")->getAllCarriers();
        $carriersData = array();
        foreach($carriers as $code => $method){
            $carrierTitle = Mage::getStoreConfig("carriers/$code/title");
            $carriersData[] = array('value'=>$code,'label'=>$carrierTitle);
        }
        return $carriersData;
    }

}