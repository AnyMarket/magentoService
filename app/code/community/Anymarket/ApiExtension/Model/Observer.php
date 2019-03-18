<?php

class Anymarket_ApiExtension_Model_Observer
{

    private function sendToFeed($id, $type, $oi)
    {
        $modelFeed = Mage::getModel("apiextension/anymarketfeed");
        $modelFeed->setIdItem($id);
        $modelFeed->setType($type);
        $modelFeed->setOi($oi);
        $modelFeed->save();
    }

    private function doCallAnymarket($host, $oi, $storeCode, $itemId)
    {
        $body = array(
            "oi" => $oi,
            "version" => "MAGENTO_1",
            "storeCode" => $storeCode,
            "idItem" => $itemId
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $host,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($body),
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


    private function isNewOrder($order)
    {
        if ($order->getUpdateAt() == null) {
            return true;
        }

        if ($order->getCreateAt() == $order->getUpdateAt()) {
            return true;
        }
        return false;
    }

    /**
     * @param $observer
     */
    public function updateOrderAnyMarketObs($observer)
    {
        if ($observer->getEvent()->getOrder()) {
            $storeID = $observer->getEvent()->getOrder()->getStoreId();
            $orderID = $observer->getEvent()->getOrder()->getIncrementId();
            $order = new Mage_Sales_Model_Order();
            $order->loadByIncrementId($orderID);

            $oi = Mage::getStoreConfig('anymarket_new_section/anymarket_new_access_group/anymarket_new_oi_field', $storeID);
            $feed = Mage::getStoreConfig('anymarket_new_section/anymarket_new_access_group/anymarket_using_feed_order_field', $storeID);
            if ($feed == "1") {
                $this->sendToFeed($orderID, "1", $oi);
            } else {
                $host = Mage::getStoreConfig('anymarket_new_section/anymarket_new_access_group/anymarket_new_host_field', $storeID);
                $host = $host . "/public/api/anymarketcallback/order";
                $this->doCallAnymarket($host, $oi, $storeID, $orderID);
            }

        }
    }

    /**
     * @param $observer
     */
    public function sendProdAnyMarket($observer)
    {
        if ($observer->getEvent()->getProduct()) {
            $productId = $observer->getEvent()->getProduct()->getId();
            $storeID = $observer->getEvent()->getProduct()->getStoreId();

            $product = Mage::getModel('catalog/product')->setStoreId($storeID)->load($productId);

            $oi = Mage::getStoreConfig('anymarket_new_section/anymarket_new_access_group/anymarket_new_oi_field', $storeID);
            $feed = Mage::getStoreConfig('anymarket_new_section/anymarket_new_access_group/anymarket_using_feed_product_field', $storeID);
            if ($feed == "1") {
                $this->sendToFeed($product->getSku(), "2", $oi);
            } else {
                $host = Mage::getStoreConfig('anymarket_new_section/anymarket_new_access_group/anymarket_new_host_field', $storeID);
                $host = $host . "/public/api/anymarketcallback/product";
                $this->doCallAnymarket($host, $oi, $storeID, $product->getSku());
            }
        }
    }

    /**
     * @param $observer
     */
    public function catalogInventorySave($observer)
    {
        $event = $observer->getEvent();
        $_item = $event->getItem();
        $product = Mage::getModel('catalog/product')->load($_item->getProductId());

        if ($_item->getData('store_id') != null && $_item->getData('store_id') != "0") {
            $storeID = $_item->getData('store_id');
            $host = Mage::getStoreConfig('anymarket_new_section/anymarket_new_access_group/anymarket_new_host_field', $storeID);
            $oi = Mage::getStoreConfig('anymarket_new_section/anymarket_new_access_group/anymarket_new_oi_field', $storeID);

            $feed = Mage::getStoreConfig('anymarket_new_section/anymarket_new_access_group/anymarket_using_feed_stock_field', $storeID);
            if ($feed == "1") {
                $this->sendToFeed($product->getSku(), "0", $oi);
            } else {
                $host = $host . "/public/api/anymarketcallback/stockPrice";
                $this->doCallAnymarket($host, $oi, "", $product->getSku());
            }
        } else {
            foreach ($product->getStoreIds() as $storeID) {
                $host = Mage::getStoreConfig('anymarket_new_section/anymarket_new_access_group/anymarket_new_host_field', $storeID);
                if ($host != null && $host != "") {
                    $oi = Mage::getStoreConfig('anymarket_new_section/anymarket_new_access_group/anymarket_new_oi_field', $storeID);
                    $feed = Mage::getStoreConfig('anymarket_new_section/anymarket_new_access_group/anymarket_using_feed_stock_field', $storeID);
                    if ($feed == "1") {
                        $this->sendToFeed($product->getSku(), "0", $oi);
                    } else {
                        $host = $host . "/public/api/anymarketcallback/stockPrice";
                        $this->doCallAnymarket($host, $oi, "", $product->getSku());
                    }
                }
            }
        }
    }

}

?>