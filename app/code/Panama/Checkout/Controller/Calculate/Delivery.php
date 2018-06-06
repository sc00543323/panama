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
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
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
			/*$connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION'); 
			$result = $connection->fetchAll("SELECT district_id FROM panama_address where district_name= '".$city."' limit 1");
			if(isset($result[0]['district_id']) && $result[0]['district_id']) {
				$cityId = $result[0]['district_id'];
			} else {
				$cityId = 14;
			}*/
			$cityId = 14;
			$allItems = $this->_cart->getQuote()->getAllVisibleItems();
			$i=0;
			foreach($allItems as $item) {
				//$orderDetail[$i]['sku'] = $item->getSku();
				$orderDetail[$i]['sku'] = 'SIMAVATAR';
				$orderDetail[$i]['Quantity'] = $item->getQty();
				$i++;
			}
			
			$curl_post_data = array (
                'OrderHead' => 
                array (
					0 => 
						array (
						'DeliveryCity' => $cityId,
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
			$response = json_decode($result);

			if(isset($response[0]->ResultId) && isset($response[0]->ResultMessage)) {
				$responseData = array();
				$responseData['ResultId'] = $response[0]->ResultId;
				$responseData['ResultMessage'] = $response[0]->ResultMessage;
				$responseData['DeliveryDateRangeList'] = array();
				$i=0;
				$date ='';
				if(isset($response[0]->DeliveryDateRangeList)) {
					$deliveryDateRangeList = $response[0]->DeliveryDateRangeList;
					foreach ($deliveryDateRangeList as $value) {
						if($i>0) {
							$newDate = explode(" ",$value->_horaFinEntrega);
							$date ='';
							if($_horaFinEntrega[0] != $newDate[0]) {
								$date =$newDate[0];
							}
						}
						$_horaFinEntrega = explode(" ",$value->_horaFinEntrega);
						if($i == 0) {
							$date =$_horaFinEntrega[0];
						}
						$id = strtotime($_horaFinEntrega[0]);
						$responseData['DeliveryDateRangeList'][$i]['_disponibilidad'] = $value->_disponibilidad;
						$responseData['DeliveryDateRangeList'][$i]['_jornada'] = $value->_jornada;
						$responseData['DeliveryDateRangeList'][$i]['_scheduleId'] = $value->_scheduleId;
						$responseData['DeliveryDateRangeList'][$i]['date'] = $date;
						$responseData['DeliveryDateRangeList'][$i]['id'] = $id;
						$i++;
					}
				}
				$resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
				$resultJson->setData($responseData);
				return $resultJson; die;
			} else {
				$responseData['ResultId'] = '0';
				$responseData['ResultMessage'] = 'something went wrong';
				$responseData['DeliveryDateRangeList'] = array();
				$resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
				$resultJson->setData($responseData);
				return $resultJson; die;
			}
		} else {
            $model = __('This is Not An Ajax Call');
			$resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
			$resultJson->setData($model);
			return $resultJson; die;
        }
    }
}