<?php

namespace Panama\Port\Controller\Addupsell;
use Magento\Framework\View\Result\PageFactory;


class Checkquote extends \Magento\Framework\App\Action\Action {

	protected $_storeManager;
	protected $_categoryRepository;
	protected $_url;
	protected $_catalogSession;
	protected $formKey;
	protected $resultPageFactory;
	protected $_cart;
	protected $productRepository;
	protected $resultJsonFactory;
	
    public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
		\Magento\Framework\UrlInterface $url,
		\Magento\Catalog\Model\Session $catalogSession,
		\Magento\Framework\Data\Form\FormKey $formKey,
		PageFactory $resultPageFactory,
		\Magento\Checkout\Model\Cart $cart,
    	\Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
    	\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
	){
		parent::__construct($context);
		$this->_storeManager = $storeManager;
        $this->_categoryRepository = $categoryRepository;
		$this->_url = $url;
		$this->_catalogSession = $catalogSession;
		$this->formKey = $formKey;
		$this->resultPageFactory = $resultPageFactory;
		$this->_cart = $cart;
    	$this->productRepository = $productRepository;
    	$this->resultJsonFactory = $resultJsonFactory;
	}

    public function execute() {
		$items = $this->_cart->getQuote()->getAllVisibleItems();
		$itemCount = $this->_cart->getQuote()->getItemsCount();
		$portability = false;
		foreach($items as $item) {
			if($item->getIsPortable() == 'yes') {
				$portability = true;
				break;
			}
		}
		if($portability){
			$portSessionVal = 'yes';
		}else{
		$portSessionVal = $this->_catalogSession->getPort();
		}
		$product_id = $this->_catalogSession->getPortProductId();
		$currentServiceSessionVal = $this->_catalogSession->getCurrentService();
		$buySmartphoneSessionVal = $this->_catalogSession->getBuySmartphone();

		$data = '';
		$price = $this->getRequest()->getParam('price');
		
		$resultJson = $this->resultJsonFactory->create();
	

		 if(isset($product_id) && $product_id != ''){
		 	$product = $this->productRepository->getById($product_id);
		 $name = $product->getName();
		 $plan_price = $product->getFinalPrice();
		 $total = $price+$plan_price;

				$upsel = '<ol class="products list items product-items"><li style="width: 35%;border: 4px solid green;" id="plan_53" class="item product product-item plan_detail"><div class="product-item-info ">
				                    <div class="product details product-item-details">
				                        <strong class="product name product-item-name"><a class="product-item-link" title="'.$name.'" href="javascript:void(0)">'.$name.'</a>
				                        </strong>
										<strong class="product name product-item-name">
										<a class="product-item-link" title="53" href="javascript:void(0)">
				                            Smartphone Price:<span class="device_price_upsel">'.$price.'.00</span></a>
				                        </strong><br>+
				                        <div class="price-box price-final_price" data-role="priceBox" data-product-id="53">
				<span class="price-container price-final_price tax weee">
				        <span class="price">'.$plan_price.'</span>    </span>
				        </div></div></li></ol>
				            <input type="hidden" value="'.$product_id.'" name="upsell_id" id="upsell_id">
							Plan + SIM:        $   '.$plan_price.' <br>
							Handset:           $  '.$price.'<br>
							<p style="color:green">Final price:  $'.$total.'</p><br>';
				        }


		 if($product_id != ''){

			$data = array(
			'portable' => $portSessionVal,
			'service' => $currentServiceSessionVal,
			'smartphone' => $buySmartphoneSessionVal,
			'product_id' => $product_id,
			'upsel_data' => $upsel
			);
		 } else if($product_id == '' && $itemCount) {
			 $data = array(
			'portable' => $portSessionVal,
			'service' => $currentServiceSessionVal,
			'smartphone' => $buySmartphoneSessionVal,
			'product_id' => $product_id,
			'upsel_data' => ''
			);
		 }

    return $resultJson->setData($data);



		
		
    }
}
