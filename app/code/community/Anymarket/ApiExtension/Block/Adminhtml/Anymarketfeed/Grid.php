<?php

class Anymarket_ApiExtension_Block_Adminhtml_Anymarketfeed_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("anymarketfeedGrid");
				$this->setDefaultSort("entity_id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("apiextension/anymarketfeed")->getCollection();
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
                
						$this->addColumn('type', array(
						'header' => Mage::helper('apiextension')->__('Type'),
						'index' => 'type',
						'type' => 'options',
						'options'=>Anymarket_ApiExtension_Block_Adminhtml_Anymarketfeed_Grid::getOptionArray0(),				
						));
						
				$this->addColumn("id_item", array(
				"header" => Mage::helper("apiextension")->__("Identification"),
				"index" => "id_item",
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
			$this->getMassactionBlock()->addItem('remove_anymarketfeed', array(
					 'label'=> Mage::helper('apiextension')->__('Remove Anymarketfeed'),
					 'url'  => $this->getUrl('*/adminhtml_anymarketfeed/massRemove'),
					 'confirm' => Mage::helper('apiextension')->__('Are you sure?')
				));
			return $this;
		}
			
		static public function getOptionArray0()
		{
            $data_array=array(); 
			$data_array[0]='STOCKPRICE';
			$data_array[1]='ORDER';
			$data_array[2]='PRODUCT';
            return($data_array);
		}
		static public function getValueArray0()
		{
            $data_array=array();
			foreach(Anymarket_ApiExtension_Block_Adminhtml_Anymarketfeed_Grid::getOptionArray0() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		

}