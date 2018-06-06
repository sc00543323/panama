<?php
namespace Panama\MagentoApi\Model;
use Panama\MagentoApi\Api\UpdateConfirmserialidsInterface;
use Magento\Sales\Model\Order;

class UpdateConfirmserialids implements UpdateConfirmserialidsInterface
{
    public function updateConfirmserialids(\Panama\MagentoApi\Api\Data\ConfirmserialidsInterface $confirmserialidsData) {
	
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$orderDetailsArray = $confirmserialidsData->getSkuSerialIdList();
		$orderId = $confirmserialidsData->getOrderId();
		$order = $objectManager->create('\Magento\Sales\Model\Order')->loadByIncrementId($orderId);
		$allItems = $order->getAllItems();
		
		if(!$orderId) {
			$confirmserialidsData->setresultId('0');
			$confirmserialidsData->setResultMessage('Order id is mandatory');
		} else if(!$order->getId()) {
			$confirmserialidsData->setresultId('0');
			$confirmserialidsData->setResultMessage('Given Order id is not exist');
		} else {
			$confirmserialidsData->setresultId('1');
			$confirmserialidsData->setResultMessage('Order status has been changed');
		}
		
		if($confirmserialidsData->getresultId() == 1) {
			foreach($orderDetailsArray as $orderDetails) {
				$sku = $orderDetails->getSku();
				$serialId = $orderDetails->getSerialId();
				$msisdn = $orderDetails->getMsisdn();
				foreach($allItems as $item) {
					if($sku == $item->getSku()) {						
						$item->setSerialId($serialId);
						$item->setMsisdn($msisdn);
						$item->save();
					}
				}
			}
		}
		return $confirmserialidsData; die;
    }
}