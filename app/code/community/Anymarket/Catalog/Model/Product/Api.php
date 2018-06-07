<?php
/**
 * Anymarket Catalog product api extension
 * This class add the option to add a configurable product
 * reference: http://www.stephenrhoades.com/?p=338
 *
 * @category   Anymarket
 * @package    Anymarket_Catalog
 */
class Anymarket_Catalog_Model_Product_Api extends Mage_Catalog_Model_Product_Api {
	
	/**
	* Create new product.
	*
	* Enhancements:
	* <ol>
	*   <li><tt>websites</tt> can be comma-separated string</li>
	*   <li><del>'Manage Stock' will be set to 'Use Config'</del></li>
	* </ol>
	*
	* @param string $type
	* @param int $set
	* @param string $sku
	* @param array $productData
	* @param string $store
	* @return int
	*/
	public function create($type, $set, $sku, $productData, $store = null) {
		if (is_string($productData['websites'])) {
			$productData['websites'] = explode(',', $productData['websites']);
		}
		
		$productId = parent::create($type, $set, $sku, $productData, $store);
		Mage::log("Reindexing product stock status");
		$stockIndexer = Mage::getSingleton('index/indexer')->getProcessByCode('cataloginventory_stock');
		$stockIndexer->reindexEverything();
		// DISABLED: Too slow, not worth it 
// 		$product = Mage::getModel('catalog/product');
// 		if ($store != null) $product->setStoreId($store);
// 		$product->load($productId);
		
// 		if (!$product->getId()) {
// 			$this->_fault('not_exists');
// 		}
		
// 		if (!$stockData = $product->getStockData()) {
// 			$stockData = array();
// 		}
// 		$stockData['use_config_manage_stock'] = 1;

// 		$product->setStockData($stockData);
		
// 		try {
// 			$product->save();
// 		} catch (Mage_Core_Exception $e) {
// 			$this->_fault('not_updated', $e->getMessage());
// 		}
		
		return $productId;
	}

	/**
	 * Retrieve product info
	 *
	 * @param int|string $productId
	 * @param string|int $store
	 * @param array $attributes
	 * @return array
	 */
	public function info($productId, $store = null, $attributes = null, $identifierType = null) {
		
		$result = parent::info ( $productId, $store, $attributes, $identifierType );
		
		if ($result ['type'] == Mage_Catalog_Model_Product_Type_Configurable::TYPE_CODE) {
			
			$product = Mage::getModel ( 'catalog/product' )->load ( $result ['product_id'] );
			
			if ($product->isConfigurable ()) {
				$ids = $product->getTypeInstance ( true )->getUsedProductIds ( $product );
				$result ['subproduct_ids'] = $ids;
			}
		}
		
		return $result;
	}
	
	/**
	 * Set additional data before product saved
	 *
	 * @param    Mage_Catalog_Model_Product $product
	 * @param    array $productData
	 * @return	  object
	 */
	protected function _prepareDataForSave($product, $productData) {
		
		parent::_prepareDataForSave ( $product, $productData );
		//Mage::log('Anymarket prepareDataForSave called');
		
		if (isset ( $productData ['configurable_products_data'] ) && is_array ( $productData ['configurable_products_data'] )) {
			Mage::log('Setting configurable_products_data ' . var_export($productData['configurable_products_data'], true));
			$product->setConfigurableProductsData ( $productData ['configurable_products_data'] );
		}
		
		/*
		 * Check for configurable products array passed through API Call
		 */
		if (isset ( $productData ['configurable_attributes_data'] ) && is_string( $productData ['configurable_attributes_data'] )) {
			$productData ['configurable_attributes_data'] = json_decode($productData ['configurable_attributes_data'], true);
		}
		if (isset ( $productData ['configurable_attributes_data'] ) && is_array ( $productData ['configurable_attributes_data'] )) {
			Mage::log('Setting configurable_attributes_data ' . var_export($productData['configurable_attributes_data'], true));
			foreach ( $productData ['configurable_attributes_data'] as $key => $data ) {
				//Check to see if these values exist, otherwise try and populate from existing values
				$data ['label'] = (! empty ( $data ['label'] )) ? $data ['label'] : $product->getResource ()->getAttribute ( $data ['attribute_code'] )->getStoreLabel ();
				$data ['frontend_label'] = (! empty ( $data ['frontend_label'] )) ? $data ['frontend_label'] : $product->getResource ()->getAttribute ( $data ['attribute_code'] )->getFrontendLabel ();
				$productData ['configurable_attributes_data'] [$key] = $data;
			}
			$product->setConfigurableAttributesData ( $productData ['configurable_attributes_data'] );
			$product->setCanSaveConfigurableAttributes ( 1 );
		}
	}
	
	/**
	* Retrieve products list by filters Include Price and Description etc.
	*
	* @param array $filters
	* @param string|int $store
	* @return array
	* 
	*/
	public function itemsEx($filters = null, $store = null)
	{
		$collection = Mage::getModel('catalog/product')->getCollection()
		->addStoreFilter($this->_getStoreId($store))
		->addAttributeToSelect('name')
		->addAttributeToSelect('price')
		->addAttributeToSelect('description');
		
	
		if (is_array($filters)) {
			try {
				foreach ($filters as $field => $value) {
					if (isset($this->_filtersMap[$field])) {
						$field = $this->_filtersMap[$field];
					}
	
					$collection->addFieldToFilter($field, $value);
				}
			} catch (Mage_Core_Exception $e) {
				$this->_fault('filters_invalid', $e->getMessage());
			}
		}
	
		$result = array();
	
		foreach ($collection as $product) {
			//            $result[] = $product->getData();
			$categoryIds = $product->getCategoryIds();
			
				
			$result[] = array( // Basic product data
	                'product_id' => $product->getId(),
	                'sku'        => $product->getSku(),
	                'name'       => $product->getName(),
	                'set'        => $product->getAttributeSetId(),
	                'type'       => $product->getTypeId(),
					'price'      => $product->getPrice(),
					'cost'       => $product->getCost(),
					'description'      => $product->getDescription(),
					'category_ids'       => $categoryIds,
					'category_id'       => !empty($categoryIds) ? $categoryIds[0] : null
			);
		}
		
		return $result;
	}
	
	/**
	* Assign associated products for a configurable product.
	*
	* @param int $configurableProductId Configurable (parent) Product ID.
	* @param array $childrenIds Array of product IDs, or a comma-separated string of IDs.
	* @return true
	*/
	public function associate($configurableProductId, $childrenIds) {
		$product = Mage::getModel('catalog/product')->load($configurableProductId);
		if (is_string($childrenIds))
			$childrenIds = explode(',', $childrenIds);
		$productsData = array();
		foreach ($childrenIds as $childId) {
			$productsData[trim($childId)] = array();
		}
		$product->setConfigurableProductsData($productsData);
		$product->save();
		return true;
	}
	
	/**
	 * Retrieve the URL Path, Name, and 50x50 image for
	 * a list of products.
	 * @param array $skus Array of SKUs.
	 * @return array Associative array containing url_path, name, image_50x50, shop_id (if exists).
	 */
	public function getRefs($skus) {
		Mage::log('getRefs '. var_export($skus, true));
		/* @var $imageHelper Mage_Catalog_Helper_Image */
		$imageHelper = Mage::helper('catalog/image');
		$_product = Mage::getModel('catalog/product');
		
		$result = array();
		foreach ($skus as $sku) {
			$_product->load($_product->getIdBySku($sku));
		
			/* @var $image Mage_Catalog_Model_Product_Image */
			$imageHelper->init($_product, 'small_image')->resize(50, 50);
			$photoId = (string) $imageHelper;
			$productRef = array(
					'url_path' => $_product->getUrlPath(),
					'name' => $_product->getName(),
					'image_50x50' => $photoId,
					'shop_id' => $_product->getShopId() );
			$result[$sku] = $productRef;
		}
		Mage::log('getRefs result: '. var_export($result, true));
		
		return $result;
	}
	
	/**
	 * Retrieve list of products with basic info (id, sku, type, set, name)
	 * plus several more user-defined attributes.
	 *
	 * @param null|object|array $filters
	 * @param string|int $store
	 * @param array $attributesToSelect Array of attribute codes to select.
	 * @return array
	 */
	public function itemsPlus($filters = null, $store = null, $attributesToSelect = array())
	{
		/* @var $collection Mage_Catalog_Model_Resource_Product_Collection */
		$collection = Mage::getModel('catalog/product')->getCollection()
			->addStoreFilter($this->_getStoreId($store))
			->addAttributeToSelect('name');
		
		foreach ($attributesToSelect as $attrCode) {
			$collection->addAttributeToSelect($attrCode);
		}
	
		/* @var $apiHelper Mage_Api_Helper_Data */
		$apiHelper = Mage::helper('api');
		$filters = $apiHelper->parseFilters($filters, $this->_filtersMap);
		try {
			foreach ($filters as $field => $value) {
				$collection->addFieldToFilter($field, $value);
			}
		} catch (Mage_Core_Exception $e) {
			$this->_fault('filters_invalid', $e->getMessage());
		}
		$result = array();
		foreach ($collection as $product) {
			$row = array(
                'product_id'   => $product->getId(),
                'sku'          => $product->getSku(),
                'name'         => $product->getName(),
                'set'          => $product->getAttributeSetId(),
                'type'         => $product->getTypeId(),
                'category_ids' => $product->getCategoryIds(),
                'website_ids'  => $product->getWebsiteIds(),
			);
			$data = $product->getData();
			foreach ($attributesToSelect as $attrCode) {
				$row[$attrCode] = $data[$attrCode]; 
			}
			$result[] = $row;
		}
		return $result;
	}
	
	public function updatePrice($pproducts) {
		Mage::log('Update Products Price: '. var_export($pproducts, true));
// 		$result = array();
		foreach ($pproducts as $pproduct) {
			/* @var $product Mage_Catalog_Model_Product */
// 			Mage::log('loading ' . $pproduct['sku']);
			$product = Mage::getModel('catalog/product')
				->loadByAttribute('sku', $pproduct['sku']);
// 			Mage::log('product '. $product->getSku());
			$product->setLocalPrice((real)$pproduct['local_price']);
			$product->setPrice($pproduct['price']);
			
// 			$productResult = array(
// 					'product_id'   => $product->getId(),
// 					'sku'          => $product->getSku(),
// 					'name'         => $product->getName(),
// 					'set'          => $product->getAttributeSetId(),
// 					'type'         => $product->getTypeId(),
// 					'category_ids' => $product->getCategoryIds(),
// 					'website_ids'  => $product->getWebsiteIds(),
// 					'local_price'  => $product->getLocalPrice(),
// 					'price' 	   => $product->getPrice() );
// 			$result[$sku] = $productResult;
			
			$product->save();
		}
// 		return $result;
	}
}
