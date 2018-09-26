<?php
	
class Anymarket_ApiExtension_Block_Adminhtml_Anymarketorder_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "entity_id";
				$this->_blockGroup = "apiextension";
				$this->_controller = "adminhtml_anymarketorder";
				$this->_updateButton("save", "label", Mage::helper("apiextension")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("apiextension")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("apiextension")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("anymarketorder_data") && Mage::registry("anymarketorder_data")->getId() ){

				    return Mage::helper("apiextension")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("anymarketorder_data")->getId()));

				} 
				else{

				     return Mage::helper("apiextension")->__("Add Item");

				}
		}
}