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
			$confirmserialidsData->setResultId('0');
			$confirmserialidsData->setResultMessage('Order id is mandatory');
		} else if(!$order->getId()) {
			$confirmserialidsData->setResultId('0');
			$confirmserialidsData->setResultMessage('Given Order id is not exist');
		} else {
			$confirmserialidsData->setResultId('1');
			$confirmserialidsData->setResultMessage('Order status has been changed');
		}
		
		$logRequest[] = $orderId;
		if($confirmserialidsData->getResultId() == 1) {
			foreach($orderDetailsArray as $orderDetails) {
				$sku = $orderDetails->getSku();
				$serialId = $orderDetails->getSerialId();
				$msisdn = $orderDetails->getMsisdn();
				$logRequest[] = $sku;
				$logRequest[] = $serialId;
				$logRequest[] = $msisdn;
				foreach($allItems as $item) {
					if($sku == $item->getSku()) {						
						$item->setSerialId($serialId);
						$item->setMsisdn($msisdn);
						$item->save();
					}
				}
			}
		}
		$logResponse[] = $confirmserialidsData->getResultId();
		$logResponse[] = $confirmserialidsData->getResultMessage();
		$objectManager->get('Panama\MagentoApi\Helper\Data')->logCreate('/var/log/confirmsearialidupdate_request_response.log', "<==Request==>\n".json_encode($logRequest));
		$objectManager->get('Panama\MagentoApi\Helper\Data')->logCreate('/var/log/confirmsearialidupdate_request_response.log', "<==Response==>\n".json_encode($logResponse)."\n\n");
		
		return $confirmserialidsData; die;
    }
}