<?php
class Anymarket_ApiExtension_Block_Adminhtml_Anymarketorder_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("apiextension_form", array("legend"=>Mage::helper("apiextension")->__("Item information")));

				
						$fieldset->addField("id_anymarket", "text", array(
						"label" => Mage::helper("apiextension")->__("Identification"),
						"name" => "id_anymarket",
						));
					
						$fieldset->addField("id_magento", "text", array(
						"label" => Mage::helper("apiextension")->__("Magento Identification"),
						"name" => "id_magento",
						));

                        $fieldset->addField("oi", "text", array(
                            "label" => Mage::helper("apiextension")->__("OI"),
                            "name" => "oi",
                        ));
					

				if (Mage::getSingleton("adminhtml/session")->getAnymarketorderData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getAnymarketorderData());
					Mage::getSingleton("adminhtml/session")->setAnymarketorderData(null);
				} 
				elseif(Mage::registry("anymarketorder_data")) {
				    $form->setValues(Mage::registry("anymarketorder_data")->getData());
				}
				return parent::_prepareForm();
		}
}
