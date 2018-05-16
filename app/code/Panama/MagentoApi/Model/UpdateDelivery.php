<?php
namespace Panama\MagentoApi\Model;
use Panama\MagentoApi\Api\UpdateDeliveryInterface;
use Magento\Sales\Model\Order;

class UpdateDelivery implements UpdateDeliveryInterface
{
    public function status(\Panama\MagentoApi\Api\Data\DeliveryInterface $deliveryData) {
	
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$scopeConfig = $objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface');
		$deliveryStatusConfig = $scopeConfig->getValue('magento_api/delivery_status_code_configuration/delivery_status_code', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$deliveryStatusVal = explode(",",$deliveryStatusConfig);
		$deliveryStatusArray = array();
		foreach ($deliveryStatusVal as $val) {
			$value = explode("=>",$val);
			$key = trim($value[0]);
			$deliveryStatusArray[$key] = $value[1];
		}
		$orderId = $deliveryData->getOrderId();
		$deliveryStatusId = $deliveryData->getDeliveryStatusId();
		$trackingDeliveryUrl = $deliveryData->getTrackingDeliveryUrl();
		
		if(!$orderId) {
			$deliveryData->setresultId('0');
			$deliveryData->setResultMessage('Order id is mandatory');
		} else if(!$deliveryStatusId) {
			$deliveryData->setresultId('0');
			$deliveryData->setResultMessage('Delivery status id is mandatory');
		} else {
			$deliveryData->setresultId('1');
			$deliveryData->setResultMessage('Order status has been changed');
		}
		
		if($deliveryData->getresultId() == 1) {
			$orderStatus = $deliveryStatusArray[$deliveryStatusId];
			$order = $objectManager->create('\Magento\Sales\Model\Order')->load($orderId);
			if($order->getId() && $orderStatus) {
				$order->setStatus($orderStatus);
				$order->setTrackingDeliveryUrl($trackingDeliveryUrl);
				$order->save();
			} else {
				$deliveryData->setresultId('0');
				$deliveryData->setResultMessage('Invalid order id or invalid DeliveryStatusId');
			}
		}
		return $deliveryData; die;
    }
}