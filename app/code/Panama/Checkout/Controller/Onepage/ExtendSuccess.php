<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Panama\Checkout\Controller\Onepage;

class ExtendSuccess extends \Magento\Checkout\Controller\Onepage
{

    /**
     * Order success action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
		$session = $this->getOnepage()->getCheckout();
		$scopeConfig = $this->_objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface');
		$orderfullfillmentUrl = $scopeConfig->getValue('panama/orderfullfillment_api/orderfullfillment_url', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$username = $scopeConfig->getValue('panama/orderfullfillment_api/orderfullfillment_username', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$password = $scopeConfig->getValue('panama/orderfullfillment_api/orderfullfillment_password', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$orderId = $session->getLastOrderId();
		//$orderId = 000000006;
		$order = $this->_objectManager->create('\Magento\Sales\Model\Order')->load($orderId);
		$orderId = $order->getIncrementId();
		$customerId = $order->getCustomerId();
		$customer = $this->_objectManager->create('Magento\Customer\Model\Customer')->load($customerId);
		$customerName = $customer->getName();
		$customerEmail = $customer->getEmail();
		$shippingAddressId = $customer->getDefaultShipping();
		$ShippingAddress = $this->_objectManager->create('Magento\Customer\Model\Address')->load($shippingAddressId);
		$customerPhone1 = $ShippingAddress->getTelephone();
		$customerPhone2 = 1111111;
		$date = date("Y-m-d");
		$city = $ShippingAddress->getCity();
		//$city = 'Aguadulce';
		$connection = $this->_objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION'); 
		$result = $connection->fetchAll("SELECT district_id FROM panama_address where district_name= '".$city."' limit 1");
		$cityId = $result[0]['district_id'];		
		$street = $ShippingAddress->getStreet();		
		$deliveryProvince = $ShippingAddress->getRegion();
		$deliveryTownship = $ShippingAddress->getPostcode();
		$deliveyNeighborhood = $ShippingAddress->getDirectionIndications();
		
		$orderItems = $order->getAllVisibleItems();
		$orderDetails = array();
		$i = 0;
		foreach($orderItems as $item) {
			$orderDetails[$i]['Sku'] = $item->getSku();			
			$orderDetails[$i]['Quantity'] = $item->getQtyOrdered();
			$orderDetails[$i]['UnitPrice'] = $item->getPrice();
			$orderDetails[$i]['Taxes'] = $item->getTaxAmount();
			$orderDetails[$i]['TotalPrice'] = $item->getRowTotal();
			$i++;
		}
		$curl_post_data = array (
		  'OrderHead' => 
		  array (
			0 => 
			array (
			  'OrderId' => $orderId,
			  'ClientId' => '3',
			  'ClientName' => $customerName,
			  'ClientEmail' => $customerEmail,
			  'ClientPhone1' => $customerPhone1,
			  'ClientPhone2' => $customerPhone2,
			  'DateTimeCreation' => $date,
			  'DateTimeEstimatedDelivery' => $date,
			  'DeliveryType' => '1',
			  'AuthorizedReceiverName' => $customerName,
			  'StoreToPickup' => 'VI000017',
			  'DeliveryCity' => $cityId,
			  'DeliveryNeighborhood' => $deliveyNeighborhood,
			  'DeliveryAddress' => $street[0],
			  'DeliveryAddressLongitude' => '-79.442813',
			  'DeliveryAddressLatitude' => '9.063453',
			  'DeliveryProvince' => $deliveryProvince,
			  'DeliveryTownShip' => $deliveryTownship,
			  'WarehouseId' => 'VI000017',
			  'schedule' => 'JOR5',
			  'SaleChannel' => '1',
			  'OrderDetail' =>
				$orderDetails
			),
		  ),
		  'DataAccess' => 
		  array (
			0 => 
			array (
			  'user' => $username,
			  'password' => $password,
			),
		  ),
		);
		
		//$url = 'https://appsdemo.logytechmobile.com/NotusCEMpanamaRestService/api/OrderFullFillment';
		$ch = curl_init();
		 curl_setopt($ch, CURLOPT_URL, $orderfullfillmentUrl);
		# Setup request to send json via POST.
		//$payload = '{"OrderHead":[{"OrderId":"20180521-001","ClientId":"431482","ClientName":"Nancy Lasso","ClientEmail":"nancy.lasso@logytechmobile.com","ClientPhone1":"62191294","ClientPhone2":"3929262","DateTimeCreation":"2018-05-21","DateTimeEstimatedDelivery":"2018-05-21","DeliveryType":"1","AuthorizedReceiverName":"Nancy Lasso","StoreToPickup":"VI000017","DeliveryCity":"14","DeliveryNeighborhood":"San Antonio","DeliveryAddress":"quinta las praderas, calle carrara casa b82 ","DeliveryAddressLongitude":"-79.442813","DeliveryAddressLatitude":"9.063453","DeliveryProvince":"Panama","DeliveryTownShip":"Rufina ALfaro","WarehouseId":"VI000017","schedule":"JOR5","SaleChannel":"1","OrderDetail":[{"Sku":"SIMAVATAR","Quantity":"1","UnitPrice":"235.00","Taxes":"0.07","TotalPrice":"235.07"}]}],"DataAccess":[{"user":"admin","password":"12345.LM"}]}';
		$payload = json_encode($curl_post_data);
		//echo "<pre>";
		//print_r($payload);echo "<br>";
		//print_r($payload1);die;
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Cookie:langcookie=en; currentcurr=USD'));

		# Return response instead of printing.
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		# Send request.
		$result = curl_exec($ch);
		curl_close($ch);
		# Print response.
		$response = json_decode($result);
		//echo "<pre>";
		//print_r($response);die;
		
		if(isset($response[0]->ResultId) && $response[0]->ResultId ==1) {
			$order->setTrackingDeliveryUrl($response[0]->TrackingDeliveryUrl);
			$order->save();
		}
		
        if (!$this->_objectManager->get(\Magento\Checkout\Model\Session\SuccessValidator::class)->isValid()) {
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }
        $session->clearQuote();
        //@todo: Refactor it to match CQRS
        $resultPage = $this->resultPageFactory->create();
        $this->_eventManager->dispatch(
            'checkout_onepage_controller_success_action',
            ['order_ids' => [$session->getLastOrderId()]]
        );
        return $resultPage;
    }
}