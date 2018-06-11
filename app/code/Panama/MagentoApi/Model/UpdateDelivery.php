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
			$deliveryData->setResultId('0');
			$deliveryData->setResultMessage('Order id is mandatory');
		} else if(!$deliveryStatusId) {
			$deliveryData->setResultId('0');
			$deliveryData->setResultMessage('Delivery status id is mandatory');
		} else {
			$deliveryData->setResultId('1');
			$deliveryData->setResultMessage('Order status has been changed');
		}
		
		if($deliveryData->getResultId() == 1) {
			if(isset($deliveryStatusArray[$deliveryStatusId])) {
				$orderStatus = $deliveryStatusArray[$deliveryStatusId];
			} else {
				$orderStatus = '';
			}
			$order = $objectManager->create('\Magento\Sales\Model\Order')->loadByIncrementId($orderId);
			if($order->getId() && $orderStatus) {
				$order->setStatus($orderStatus);
				$order->setTrackingDeliveryUrl($trackingDeliveryUrl);
				$order->save();
			} else {
				$deliveryData->setResultId('0');
				$deliveryData->setResultMessage('Invalid order id or invalid DeliveryStatusId');
			}
		}
		$logRequest[] = $orderId;
		$logRequest[] = $deliveryStatusId;
		$logRequest[] = $trackingDeliveryUrl;
		$logResponse[] = $deliveryData->getResultId();
		$logResponse[] = $deliveryData->getResultMessage();
		$objectManager->get('Panama\MagentoApi\Helper\Data')->logCreate('/var/log/deliveryupdate_request_response.log', "<==Request==>\n".json_encode($logRequest));
		$objectManager->get('Panama\MagentoApi\Helper\Data')->logCreate('/var/log/deliveryupdate_request_response.log', "<==Response==>\n".json_encode($logResponse)."\n\n");
		return $deliveryData; die;
    }
}