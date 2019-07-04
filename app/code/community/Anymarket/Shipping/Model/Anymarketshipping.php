<?php

class Anymarket_Shipping_Model_Anymarketshipping extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface
{
    protected $_code = 'anymarket_shipping';
    protected $_isFixed = true;

    public function collectRates(Mage_Shipping_Model_Rate_Request $request) {
        return Mage::getModel('shipping/rate_result');
    }

    public function isActive () {
        return false;
    }

    public function getAllowedMethods() {
        return array(
            'anymarket' => $this->getConfigData('name'),
        );
    }

}