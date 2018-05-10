<?php
namespace Panama\MagentoApi\Model;
use Panama\MagentoApi\Api\UpdateDeliveryInterface;
use Magento\Sales\Model\Order;

class UpdateDelivery implements UpdateDeliveryInterface
{
    public function status(\Panama\MagentoApi\Api\Data\DeliveryInterface $deliveryData) {
	
		$deliveryStatusArray = array("101" =>"in_enlistment", "102" => "dispatched", "113" => "in_transit", "103" => "delivered", "112" => "returned", "107" => "complete", "163" => "annulled", "250" => "ready_to_pick_up_at_the_store", "251" => "canceled", "252" => "canceled");
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
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
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