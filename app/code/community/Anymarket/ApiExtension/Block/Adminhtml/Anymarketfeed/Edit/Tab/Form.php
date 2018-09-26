<?php
class Anymarket_ApiExtension_Block_Adminhtml_Anymarketfeed_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("apiextension_form", array("legend"=>Mage::helper("apiextension")->__("Item information")));

								
						 $fieldset->addField('type', 'select', array(
						'label'     => Mage::helper('apiextension')->__('Type'),
						'values'   => Anymarket_ApiExtension_Block_Adminhtml_Anymarketfeed_Grid::getValueArray0(),
						'name' => 'type',
						));
						$fieldset->addField("id_item", "text", array(
						"label" => Mage::helper("apiextension")->__("Identification"),
						"name" => "id_item",
						));
                        $fieldset->addField("oi", "text", array(
                            "label" => Mage::helper("apiextension")->__("OI"),
                            "name" => "oi",
                        ));
					

				if (Mage::getSingleton("adminhtml/session")->getAnymarketfeedData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getAnymarketfeedData());
					Mage::getSingleton("adminhtml/session")->setAnymarketfeedData(null);
				} 
				elseif(Mage::registry("anymarketfeed_data")) {
				    $form->setValues(Mage::registry("anymarketfeed_data")->getData());
				}
				return parent::_prepareForm();
		}
}
