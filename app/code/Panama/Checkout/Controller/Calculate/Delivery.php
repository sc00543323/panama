<?php

namespace Panama\Checkout\Controller\Calculate;
use Magento\Framework\Controller\ResultFactory;

class Delivery extends \Magento\Framework\App\Action\Action {

	protected $_storeManager;
	protected $_url;
	protected $_checkoutSession;
	protected $_cart;
	
    public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Store\Model\StoreManagerInterface $storeManager,		
		\Magento\Framework\UrlInterface $url,
		\Magento\Checkout\Model\Session $checkoutSession,
		\Magento\Checkout\Model\Cart $cart
	){
		parent::__construct($context);
		$this->_storeManager = $storeManager;        
		$this->_url = $url;
		$this->_checkoutSession = $checkoutSession;
		$this->_cart = $cart;
	}

    public function execute() {
        //calculate deivery time and date
		//if($this->getRequest()->isAjax()) {
			//$city = $this->getRequest()->getParam('city');
			//$address = $this->getRequest()->getParam('address');
			$city = '14';
			$address = 'Le meridien Panama';
			$deliveryNeighborhood = 'Venue';
			$deliveryAddressInstructions = 'close to hilton';
			$latitude = 8.975926;
			$longitude = -79.522287;
			$storeId = 'VI000017';
			$deliveryDate = date('Y-m-d');
			$schedule = '';
			$saleChannel = '1';
			
			$curl_post_data = array (
                'OrderHead' => 
                array (
					0 => 
						array (
						'DeliveryCity' => '14',
						'DeliveryNeighborhood' => 'Venue',
						'DeliveryAddress' => 'Le meridien Panama',
						'DeliveryAddressInstructions' => 'close to hilton',
						'DeliveryAddressLongitude' => '-79.468296',
						'DeliveryAddressLatitude' => '9.009827',
						'SaleChannel' => '1',
						'StoreId' => 'VI000017',
						'DeliveryDate' => '2018-05-12',
						'Schedule' => '',
						'OrderDetail' => 
							array (
							0 => 
								array (
								'Sku' => 'SIMAVATAR',
								'Quantity' => '1',
								),
							),
						),
				),
			);
			
			$url = 'https://webapps02.logytechmobile.com/NotusCEM.RestService/api/CalculateDeliveryDate';
			$ch = curl_init($url);
			# Setup request to send json via POST.
			$postData = json_encode($curl_post_data);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
			# Return response instead of printing.
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			# Send request.
			$result = curl_exec($ch);
			curl_close($ch);
			# Print response.
			$response = json_decode($result);
			//$resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
			//$resultJson->setData($result);
			//echo "<pre>";print_r($response);echo "</pre>";
			//return $resultJson; die;
			//echo "<pre>$result</pre>";
			//echo $response;die;
			$resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
			$resultJson->setData(true);
			return $resultJson; die;
		/*} else {
            $model = __('This is Not An Ajax Call');
			$resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
			$resultJson->setData($model);
			return $resultJson; die;
        }*/
    }
}
