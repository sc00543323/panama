<?php

namespace Panama\Port\Controller\Addupsell;
use Magento\Framework\View\Result\PageFactory;


class Credit extends \Magento\Framework\App\Action\Action {

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
    	\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
    	\Panama\Handset\Model\HandsetFactory $handsetFactory
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
    	$this->handsetFactory = $handsetFactory;
	}

    public function execute() {
		
		$data = '';
		$product_id = $this->getRequest()->getParam('product_id');
		$price = $this->getRequest()->getParam('price');		
		$resultJson = $this->resultJsonFactory->create();
		$ran = array(1,2,3);
		$k = array_rand($ran);
		$v = $ran[$k];
		$color_val = '';
		if($v == 1) {
			$color = '#66cc80';
		} else if($v == 2) {
			$color = '#e6b366';
		} else {
			$color = '#e66666';
			$color_val = '<p style="color:'.$color.'font-size: 15px;font-weight: 600;">Based on your Credit check we would like to offer you prepaid phone</p>';
		}
		
		$colorcode = '<div style="float: left;width: 20px;height: 20px;margin: 5px;border: 1px solid rgba(0, 0, 0, .2);background: '.$color.';" ></div>';
	

		 if(isset($product_id) && $product_id != ''){
		 	$product = $this->productRepository->getById($product_id);
		    $name = $product->getName();

		    $now = new \DateTime();
			$handsetData = $this->handsetFactory->create()->getCollection()->addFieldToFilter('valid_to',array(array('lteq' => $now->format('Y-m-d H:i:s')),array('valid_to', 'null'=>'')))->addFieldToFilter('phone_sku',$product->getSku())->getFirstItem();

			if((isset($this->_catalogSession->getPort()) && ($this->_catalogSession->getPort() == 'yes')) {
		    	$plan_price = $handsetData['postpaid_phone_price_with_port_in'];
		    }
		    else {
		    	$plan_price = $handsetData['postpaid_phone_price'];
		    }		    
		    $total = $price+$plan_price;

	    	if($color == '#e6b366' && $handsetData['down_payment_amount']) {
	        	$down_payment_amount = $handsetData['down_payment_amount'];
	        }
	        else {
	        	$down_payment_amount ='';
	        }

	        if($color == '#66cc80' && $handsetData['monthly_service_price']) {
	        	$monthly_service_price = $handsetData['monthly_service_price'];
	        	$monthly_service_price_html = 'Monthly Service Price (Green):           $  '.$handsetData['monthly_service_price'].'<br>';
	        }
	        else if($color == '#e6b366' && $handsetData['monthly_service_price']){
	        	$monthly_service_price = $handsetData['monthly_service_price'];
	        	$monthly_service_price_html = 'Monthly Service Price (Yellow):           $  '.$handsetData['monthly_service_price'].'<br>';
	        }
	        else {
	        	$monthly_service_price = '';
	        	$monthly_service_price_html = '';
	        }

	        if($color == '#66cc80' && $handsetData['monthly_handset_price']) {
	        	$monthly_handset_price = $handsetData['monthly_handset_price'];
	        	$monthly_handset_price_html = 'Monthly Hand set Price (Green):           $  '.$handsetData['monthly_handset_price'].'<br>';
	        }
	        else if($color == '#e6b366' && $handsetData['monthly_handset_price']){
	        	$monthly_handset_price = $handsetData['monthly_handset_price'];
	        	$monthly_handset_price_html = 'Monthly Hand set Price (Yellow):           $  '.$handsetData['monthly_handset_price'].'<br>';
	        }
	        else {
	        	$monthly_handset_price = '';
	        	$monthly_handset_price_html = '';
	        }

	        if($color == '#e6b366' && $down_payment_amount != '') {
	        	$down_payment_html = 'Down Payment Amount:           $  '.$down_payment_amount.'<br>';
	        }
	        else {
	        	$down_payment_html = '';
	        }

	        $custom_prices = $down_payment_amount + $monthly_handset_price + $monthly_service_price;
	        $total = $total + $custom_prices;
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
				        </div></div></li></ol><br>
						'.$colorcode.'- Color Code <br>'.$color_val.'<br>
				            <input type="hidden" value="'.$product_id.'" name="upsell_id" id="upsell_id">
							Plan + SIM:        $   '.$plan_price.' <br>'
							.$monthly_service_price_html.$monthly_handset_price_html.$down_payment_html.'
							<p style="color:'.$color.';font-size: 25px;font-weight: 600;">Final price:  $'.$total.'</p><br>';
				        }


		 $data = array(
			'result' => 55,
			'upsel_data' => $upsel
			);
    return $resultJson->setData($data);



		
		
    }
}
