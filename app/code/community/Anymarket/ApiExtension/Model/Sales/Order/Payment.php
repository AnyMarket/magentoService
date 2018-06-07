<?php

class Anymarket_ApiExtension_Model_Sales_Order_Payment extends Mage_Catalog_Model_Api_Resource
{
    
    public function getAllPaymentMethods()
    {
        $payments = Mage::getSingleton('payment/config')->getActiveMethods();
        $paymentData = array();
        foreach($payments as $code => $method){
            $paymentTitle = Mage::getStoreConfig("payment/$code/title");
            $paymentData[] = array('value'=>$code,'label'=>$paymentTitle);
        }
        return $paymentData;
    }

}