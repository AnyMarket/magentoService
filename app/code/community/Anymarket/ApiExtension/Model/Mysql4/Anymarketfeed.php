<?php
class Anymarket_ApiExtension_Model_Mysql4_Anymarketfeed extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("apiextension/anymarketfeed", "entity_id");
    }

}