<?php

class Anymarket_ApiExtension_Model_Feed_Api
{

    public function list_ex($filters)
    {
        $collection = Mage::getModel("apiextension/anymarketfeed")->getCollection();

        $apiHelper = Mage::helper('api');
        $filters = $apiHelper->parseFilters($filters);
        try {
            foreach ($filters as $field => $value) {
                if ($field != "qtyItens") {
                    $collection->addFieldToFilter($field, $value);
                }
            }
        } catch (Mage_Core_Exception $e) {
            $this->_fault('filters_invalid', $e->getMessage());
        }
        $collection->setPageSize($filters['qtyItens'])->setCurPage(1);

        $result = array();
        foreach ($collection as $anymarketorder) {
            $result[] = $anymarketorder->getData();
        }
        return $result;


    }

    public function check($idFeed)
    {
        Mage::getModel("apiextension/anymarketfeed")->load($idFeed)->delete();
    }

}
