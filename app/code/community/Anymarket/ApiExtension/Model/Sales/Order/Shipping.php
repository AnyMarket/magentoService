<?php

class Anymarket_ApiExtension_Model_Sales_Order_Shipping extends Mage_Catalog_Model_Api_Resource
{

    public function getAllShippingMethods()
    {
        $carriers = Mage::getsingleton("shipping/config")->getAllCarriers();
        $carriersData = array();
        foreach ($carriers as $carrierCode => $carrierModel) {
            $carrierTitle = Mage::getStoreConfig("carriers/$carrierCode/title");

            $options = array();
            if ($carrierMethods = $carrierModel->getAllowedMethods()) {
                foreach ($carrierMethods as $methodCode => $method) {
                    $code = $carrierCode . '_' . $methodCode;
                    $options[] = array('value' => $code, 'label' => $method);
                }
            }

            $carriersData[] = array('value' => $carrierCode, 'label' => $carrierTitle, 'options' => $options);
        }
        return $carriersData;
    }

}