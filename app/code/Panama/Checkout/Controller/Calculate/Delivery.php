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
		if($this->getRequest()->isAjax()) {
			$city = $this->getRequest()->getParam('city');
			$address = $this->getRequest()->getParam('address');
			$deliveryNeighborhood = 'Venue';
			$deliveryAddressInstructions = 'close to hilton';
			$latitude = 9.009827;
			$longitude = -79.522287;
			$storeId = 'VI000017';
			$deliveryDate = date('Y-m-d');
			$schedule = '';
			$saleChannel = '1';
			$allItems = $this->_cart->getQuote()->getAllVisibleItems();
			$i=0;
			foreach($allItems as $item) {
				$orderDetail[$i]['sku'] = $item->getSku();
				$orderDetail[$i]['Quantity'] = $item->getQty();
				$i++;
			}
			
			$curl_post_data = array (
                'OrderHead' => 
                array (
					0 => 
						array (
						'DeliveryCity' => $city,
						'DeliveryNeighborhood' => $deliveryNeighborhood,
						'DeliveryAddress' => $address,
						'DeliveryAddressInstructions' => $deliveryAddressInstructions,
						'DeliveryAddressLongitude' => $longitude,
						'DeliveryAddressLatitude' => $latitude,
						'SaleChannel' => $saleChannel,
						'StoreId' => $storeId,
						'DeliveryDate' => $deliveryDate,
						'Schedule' => $schedule,
						'OrderDetail' => $orderDetail
						),
				),
			);
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$scopeConfig = $objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface');
			$calculateDeliveryUrl = $scopeConfig->getValue('panama/calculatedelivery_api/calculatedelivery_url', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			$ch = curl_init($calculateDeliveryUrl);
			# Setup request to send json via POST.
			$postData = json_encode($curl_post_data);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
			# Return response instead of printing.
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			# Send request.
			$result = curl_exec($ch);
			curl_close($ch);
			//$response = json_decode($result);
			//echo "<pre>";print_r($response);echo "</pre>";die;
			//echo "dddd--".$result['ResultId'];
			$array[0]['ResultId'] = 1;
			$array[0]['ResultMessage'] = 'confirmeaaa';
			$array[0]['DeliveryDateRangeList'][] = array("_disponibilidad"=>100, "_jornada"=>"13:00-13:59", "_horaInicioEntrega"=>"17:00", "_horaFinEntrega"=>"18:00");
			$array[0]['DeliveryDateRangeList'][] = array("_disponibilidad"=>101, "_jornada"=>"13:11-13:59", "_horaInicioEntrega"=>"19:00", "_horaFinEntrega"=>"21:00");


			//echo "<pre>";print_r($array);echo "</pre>";
			$resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
			$resultJson->setData($array);
			return $resultJson; die;
		} else {
            $model = __('This is Not An Ajax Call');
			$resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
			$resultJson->setData($model);
			return $resultJson; die;
        }
    }
}
