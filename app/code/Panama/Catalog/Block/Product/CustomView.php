<?php

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Panama\Catalog\Block\Product;

use Magento\Framework\App\ObjectManager;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Model\ProductFactory;
use Magento\Customer\Api\Data\GroupInterface;
use Magento\Catalog\Model\Product;
/**
 * Product View block
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CustomView extends AbstractProduct {

    protected $_customerGroup;
    protected $storeManager;
	protected $_productRepository;
	protected $_productFactory;
    public function __construct(
	ProductFactory $productFactory,
		\Magento\Catalog\Model\ProductRepository $productRepository,
		  \Magento\Catalog\Block\Product\Context $context ,	
	  
            array $data = array()) {
       		$this->_productRepository = $productRepository;
		$this->_productFactory = $productFactory;
		 $this->storeManager = $context->getStoreManager();
		parent::__construct($context, $data);
		
		
    }

    /**
     * Get the Custom cart url
     * To add all options of bundled product to cart
     * @return Object
     */
    public function getCustomCartUrl($productId) {
        if($productId != ''){
           return $this->getUrl('customcatalog/cart/add/', array('id' => $productId)); 
        }else{
            return "Product ID is Empty";
        }
    }
	
	public function getAddCartUrl($sku) {
        if($sku != ''){
           return $this->getUrl('checkout/cart/add/', array('sku' => $sku)); 
           exit;
        }else{
            return "Product ID is Empty";
        }
    }
}