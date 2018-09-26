<?php

class Anymarket_ApiExtension_Adminhtml_AnymarketorderController extends Mage_Adminhtml_Controller_Action
{
		protected function _isAllowed()
		{
		//return Mage::getSingleton('admin/session')->isAllowed('apiextension/anymarketorder');
			return true;
		}

		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("apiextension/anymarketorder")->_addBreadcrumb(Mage::helper("adminhtml")->__("Anymarketorder  Manager"),Mage::helper("adminhtml")->__("Anymarket Order"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("ApiExtension"));
			    $this->_title($this->__("Manager Anymarketorder"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("ApiExtension"));
				$this->_title($this->__("Anymarketorder"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("apiextension/anymarketorder")->load($id);
				if ($model->getId()) {
					Mage::register("anymarketorder_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("apiextension/anymarketorder");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Anymarket Order"), Mage::helper("adminhtml")->__("Anymarket Order"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Anymarketorder Description"), Mage::helper("adminhtml")->__("Anymarketorder Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("apiextension/adminhtml_anymarketorder_edit"))->_addLeft($this->getLayout()->createBlock("apiextension/adminhtml_anymarketorder_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("apiextension")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("ApiExtension"));
		$this->_title($this->__("Anymarketorder"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("apiextension/anymarketorder")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("anymarketorder_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("apiextension/anymarketorder");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Anymarket Order"), Mage::helper("adminhtml")->__("Anymarket Order"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Anymarketorder Description"), Mage::helper("adminhtml")->__("Anymarketorder Description"));


		$this->_addContent($this->getLayout()->createBlock("apiextension/adminhtml_anymarketorder_edit"))->_addLeft($this->getLayout()->createBlock("apiextension/adminhtml_anymarketorder_edit_tabs"));

		$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();


				if ($post_data) {

					try {

						

						$model = Mage::getModel("apiextension/anymarketorder")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Anymarketorder was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setAnymarketorderData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setAnymarketorderData($this->getRequest()->getPost());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					return;
					}

				}
				$this->_redirect("*/*/");
		}



		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("apiextension/anymarketorder");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}

		
		public function massRemoveAction()
		{
			try {
				$ids = $this->getRequest()->getPost('entity_ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("apiextension/anymarketorder");
					  $model->setId($id)->delete();
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
		/**
		 * Export order grid to CSV format
		 */
		public function exportCsvAction()
		{
			$fileName   = 'anymarketorder.csv';
			$grid       = $this->getLayout()->createBlock('apiextension/adminhtml_anymarketorder_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'anymarketorder.xml';
			$grid       = $this->getLayout()->createBlock('apiextension/adminhtml_anymarketorder_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}
