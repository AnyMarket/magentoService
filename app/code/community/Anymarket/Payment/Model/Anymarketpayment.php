<?php

class Anymarket_Payment_Model_Anymarketpayment extends Mage_Payment_Model_Method_Abstract
{
    // This is the identifier of our payment method
    protected $_code = 'anymarket_payment';
    protected $_isInitializeNeeded      = true;
    protected $_canUseInternal          = false;
    protected $_canUseForMultishipping  = false;

    public function isAvailable($quote = null) {
        if($quote == null ){
            return false;
        }
        if($quote->getData('customer_suffix') == null || $quote->getData('customer_suffix') != 'ANYMARKET') {
            return false;
        }
        return parent::isAvailable($quote);
    }

}