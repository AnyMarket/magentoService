<?php

class Anymarket_ApiExtension_Model_Product_Attribute_Option extends Mage_Catalog_Model_Api_Resource
{
    public function create($attributeId, $attributeOptions, $store = null)
    {

        if (!$attributeId || !$attributeOptions) {
            $this->_fault('data_invalid', "At least one of the required parameters was not set.");
        }

        // get attribute
        try {
            $storeId = $this->_getStoreId($store);
            $attribute = $this->getAttribute($storeId, $attributeId);
        } catch (Exception $e) {
            $this->_fault('not_exist');
        }

        /* @var $attribute Mage_Catalog_Model_Entity_Attribute */
        if (!$attribute) {
            $this->_fault('not_exist');
        }

        // if we were only passed a single option, let's just make it an array with a single value
        if (!is_array($attributeOptions)) {
            $attributeOptions = array($attributeOptions);
        }


        // if option already exists, we will ignore it
        $_options = $this->getAttributeOptions($attribute);

        // only throw errors if it fails to save a new option
        $model = Mage::getModel('eav/entity_setup');
        $result = array();
        foreach ($attributeOptions as $attributeOption)
        {
            if (!isset($_options[$attributeOption])) {
                $option = array();
                $option['attribute_id'] = $attribute->getId();
                $option['value'][0][$storeId] = $attributeOption;
                try {
                    $model->addAttributeOption($option);
                    $result[$attributeOption] = 'success';
                } catch (Exception $e) {
                    $result[$attributeOption] = 'fail';
                }
            } else {
                $result[$attributeOption] = 'already exists';
            }
        }
        
        // compose response
        $response = array();
        $attribute = $this->getAttribute($storeId, $attributeId);
        $_options = $this->getAttributeOptions($attribute); // get updated list of options
        foreach ($result as $o => $s) {
            $id = '';
            if ($s != 'fail') {
                $id = $_options[$o];
            }
            $response[] = array(
                                    'option' => $o,
                                    'status' => $s,
                                    'id'     => $id
                                );
        }
        

        return $response;
    }
    
    protected function getAttribute($storeId, $attributeId) 
    {
        return Mage::getModel('eav/config')->getAttribute('catalog_product', $attributeId);
    }
    
    protected function getAttributeOptions($attribute)
    {
        $_options = array();
        foreach ($attribute->getSource()->getAllOptions(false) as $_option) {
            $_options[$_option['label']] = $_option['value'];
        }
        Mage::log($_options);
        return $_options;
    }

    /**
     * Create attribute options
     *
     * @param string $attributeId
     * @param array $attributeOptions
     * @return int
     */
    public function addoptions($attributeId, $attributeOptions) {
        $setup = new Mage_Eav_Model_Entity_Setup ( 'core_setup' );

        foreach ($attributeOptions as $label) {
            $option = array ();
            $option ['attribute_id'] = $attributeId;
            $option ['value'] = array( array(0 => $label) );

            $setup->addAttributeOption ( $option );
        }

        return true;
    }

    /**
     * Delete product attribute.
     *
     * @param string $attributeName
     * @param string|int $store
     * @return int
     */
    public function delete($attributeName, $store = null) {
        $storeId = $this->_getStoreId ( $store );
        $attribute = Mage::getModel ( 'catalog/product' )->setStoreId ( $storeId )->getResource ()->getAttribute ( $attributeName );

        if (! $attribute) {
            $this->_fault ( 'not_exists' );
        }

        try {
            $attribute->delete ();
        } catch ( Mage_Core_Exception $e ) {
            $this->_fault ( 'not_deleted', $e->getMessage () );

            return false;
        }

        return true;
    }

    /**
     * Retrieve all attributes
     *
     * @return array
     */
    public function listAll()
    {
        $attrs = Mage::getResourceModel('catalog/product_attribute_collection');
        Mage::log('Anymarket_Catalog_Model_Product_Attribute_Api.listAll:'. count($attrs) . ' attributes total');
        $attrs_data = $attrs->load()->getData();
        return $attrs_data;
    }

    /**
     * Retrieve all attribute options
     */
    public function optionsAll()
    {
        $allOptions = array();
        $attributes = Mage::getResourceModel('catalog/product_attribute_collection');
        $attributes->addFieldToFilter('is_user_defined', array('eq' => '1'));
        $attributes->addFieldToFilter('is_configurable', array('eq' => '1'));
        $attributes->addFieldToFilter('frontend_input', array('eq' => 'select'));
        foreach ($attributes as $attr) {
            if ($attr->usesSource()) {
                $source = $attr->getSource();
                foreach ($source->getAllOptions() as $optionOrder => $optionValue) {
                    if (empty($optionOrder) || empty($optionValue))
                        continue;
                    $allOptions[] = array(
                        'attribute_id' => $attr->getId(),
                        'option_order' => $optionOrder,
                        'option_id' => $optionValue['value'],
                        'option_value' => $optionValue['label']);
                }
            }
        }
        return $allOptions;
    }

    /**
     * Retrieve attribute sets including child groups and attributes
     *
     * @return array
     */
    public function listFlat()
    {
        $entityType = Mage::getModel('catalog/product')->getResource()->getEntityType();
        $collection = Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter($entityType->getId());

        $result = array();
        foreach ($collection as $attributeSet) {
            $groups = Mage::getModel('eav/entity_attribute_group')
                ->getResourceCollection()
                ->setAttributeSetFilter($attributeSet->getId())
                ->load();

            foreach ($groups as $group) {
                $groupAttributesCollection = Mage::getModel('eav/entity_attribute')
                    ->getResourceCollection()
                    ->setAttributeGroupFilter($group->getId())
                    ->load();

                foreach ($groupAttributesCollection as $attr) {
                    $result[] = array('attribute_id' => $attr->getId(),
                        'attribute_code' => $attr->getAttributeCode(),
                        'group_id' => $group->getId(),
                        'group_name' => $group->getAttributeGroupName(),
                        'attribute_set_id' => $attributeSet->getId(),
                        'attribute_set_name' => $attributeSet->getAttributeSetName(),
                    );

                }
            }

        }

        return $result;
    }

}