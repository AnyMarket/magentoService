<?php
class Anymarket_ApiExtension_Block_Adminhtml_Anymarketorder_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("anymarketorder_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("apiextension")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("apiextension")->__("Item Information"),
				"title" => Mage::helper("apiextension")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("apiextension/adminhtml_anymarketorder_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
