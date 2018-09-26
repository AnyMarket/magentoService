<?php


class Anymarket_ApiExtension_Block_Adminhtml_Anymarketorder extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_anymarketorder";
	$this->_blockGroup = "apiextension";
	$this->_headerText = Mage::helper("apiextension")->__("Anymarket Order");
	$this->_addButtonLabel = Mage::helper("apiextension")->__("Add New Item");
	parent::__construct();
	
	}

}