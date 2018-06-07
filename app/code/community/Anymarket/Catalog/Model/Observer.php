<?php
	class Anymarket_Catalog_Model_Observer {

		private function doCallAnymarket($host){
	        $curl = curl_init();
	        curl_setopt_array($curl, array(
	            CURLOPT_URL => $host,
	            CURLOPT_RETURNTRANSFER => true,
	            CURLOPT_ENCODING => "",
	            CURLOPT_MAXREDIRS => 10,
	            CURLOPT_TIMEOUT => 30,
	            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	            CURLOPT_CUSTOMREQUEST => "POST",
	            CURLOPT_POSTFIELDS => "",
	            CURLOPT_HTTPHEADER => array(
	                "cache-control: no-cache",
	                "content-type: application/json"
	            ),
	        ));
	        $response = curl_exec($curl);
	        $err = curl_error($curl);
	        curl_close($curl);
	        if ($err) {
	            return array("error" => "1", "message" => $err);
	        } else {
	            return array("error" => "0", "message" => $response);
	        }
	    }

	    /**
	     * @param $observer
	     *
	     * @return $this
	     */
	    public function updateOrderAnyMarketObs($observer){
	        if( $observer->getEvent()->getOrder() ) {
	            $storeID = $observer->getEvent()->getOrder()->getStoreId();
	            $OrderID = $observer->getEvent()->getOrder()->getIncrementId();
	            $order = new Mage_Sales_Model_Order();
	            $order->loadByIncrementId($OrderID);

				$host  = Mage::getStoreConfig('anymarket_new_section/anymarket_new_access_group/anymarket_new_host_field', $storeID);
				$oi  = Mage::getStoreConfig('anymarket_new_section/anymarket_new_access_group/anymarket_new_oi_field', $storeID);
	            
		        $host = $host . "/public/api/anymarketcallback/order/" . $oi . "/MAGENTO_1/" . $storeID . "/" . $OrderID;
		        $this->doCallAnymarket($host);
	        }
	    }


		/**
	     * @param $observer
		 * @return array
	     */
	    public function sendProdAnyMarket($observer) {
	        if( $observer->getEvent()->getProduct() ) {
	        	$productId = $observer->getEvent()->getProduct()->getId();
	            $storeID   = $observer->getEvent()->getProduct()->getStoreId();

	            $product = Mage::getModel('catalog/product')->setStoreId($storeID)->load($productId);

				$host  = Mage::getStoreConfig('anymarket_new_section/anymarket_new_access_group/anymarket_new_host_field', $storeID);
				$oi  = Mage::getStoreConfig('anymarket_new_section/anymarket_new_access_group/anymarket_new_oi_field', $storeID);

				$host = $host."/public/api/anymarketcallback/product/" . $oi . "/MAGENTO_1/" . $storeID . "/" . $product->getSku();
		        $this->doCallAnymarket($host);
	        }
	  	}

		/**
	     * @param $observer
		 * @return array
	     */
	    public function catalogInventorySave($observer) {
			$productId = $observer->getEvent()->getItem()->getProductId();
            $storeID   = $observer->getEvent()->getItem()->getStoreId();

			$product = Mage::getModel('catalog/product')->load($productId);

			$host  = Mage::getStoreConfig('anymarket_new_section/anymarket_new_access_group/anymarket_new_host_field', $storeID);
			$oi  = Mage::getStoreConfig('anymarket_new_section/anymarket_new_access_group/anymarket_new_oi_field', $storeID);

			$host = $host."/public/api/anymarketcallback/stockPrice/".$oi."/".$product->getSku();
	        $this->doCallAnymarket($host);


	  	}

	}
?>