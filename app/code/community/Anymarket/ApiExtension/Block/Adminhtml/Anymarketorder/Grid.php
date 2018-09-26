<?php

class Anymarket_ApiExtension_Block_Adminhtml_Anymarketorder_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("anymarketorderGrid");
				$this->setDefaultSort("entity_id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("apiextension/anymarketorder")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("entity_id", array(
				"header" => Mage::helper("apiextension")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "entity_id",
				));
                
				$this->addColumn("id_anymarket", array(
				"header" => Mage::helper("apiextension")->__("Identification"),
				"index" => "id_anymarket",
				));
				$this->addColumn("id_magento", array(
				"header" => Mage::helper("apiextension")->__("Magento Identification"),
				"index" => "id_magento",
				));
                $this->addColumn("oi", array(
                    "header" => Mage::helper("apiextension")->__("OI"),
                    "index" => "oi",
                ));
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('entity_id');
			$this->getMassactionBlock()->setFormFieldName('entity_ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_anymarketorder', array(
					 'label'=> Mage::helper('apiextension')->__('Remove Anymarketorder'),
					 'url'  => $this->getUrl('*/adminhtml_anymarketorder/massRemove'),
					 'confirm' => Mage::helper('apiextension')->__('Are you sure?')
				));
			return $this;
		}
			

}