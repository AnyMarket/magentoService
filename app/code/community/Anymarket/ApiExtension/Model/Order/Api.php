<?php

class Anymarket_ApiExtension_Model_Order_Api extends Mage_Sales_Model_Order_Api
{

    public function list_ex($filters = null)
    {
        $collection = Mage::getModel("apiextension/anymarketorder")->getCollection();

        $apiHelper = Mage::helper('api');
        $filters = $apiHelper->parseFilters($filters);
        try {
            foreach ($filters as $field => $value) {
                $collection->addFieldToFilter($field, $value);
            }
        } catch (Mage_Core_Exception $e) {
            $this->_fault('filters_invalid', $e->getMessage());
        }

        $result = array();
        foreach ($collection as $anymarketorder) {
            $result[] = $anymarketorder->getData();
        }
        return $result;
    }

}
