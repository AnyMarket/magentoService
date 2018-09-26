<?php
class Anymarket_ApiExtension_Model_Mysql4_Anymarketorder extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("apiextension/anymarketorder", "entity_id");
    }
}