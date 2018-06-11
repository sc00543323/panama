<?php
namespace Panama\MagentoApi\Model;
use Panama\MagentoApi\Api\UpdatePortingstatusInterface;
use Magento\Sales\Model\Order;

class UpdatePortingstatus implements UpdatePortingstatusInterface
{
    public function updatePortingStatus(\Panama\MagentoApi\Api\Data\PortingstatusInterface $portingstatusData) {
	
	
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$scopeConfig = $objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface');
		$portingStatusConfig = $scopeConfig->getValue('magento_api/porting_status_id_configuration/porting_status_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$portingStatusVal = explode(",",$portingStatusConfig);
		$portingStatusArray = array();
		foreach ($portingStatusVal as $val) {
			$value = explode("=>",$val);
			$key = trim($value[0]);
			$portingStatusArray[$key] = $value[1];
		}
		$orderId = $portingstatusData->getOrderId();
		$portingStatusId = $portingstatusData->getPortingStatusId();
		
		if(!$orderId) {
			$portingstatusData->setResultId('0');
			$portingstatusData->setResultMessage('Order id is mandatory');
		} else if(!$portingStatusId) {
			$portingstatusData->setResultId('0');
			$portingstatusData->setResultMessage('Porting status id is mandatory');
		} else {
			$portingstatusData->setResultId('1');
			$portingstatusData->setResultMessage('Porting status has been updated.');
		}
		
		if($portingstatusData->getResultId() == 1) {
			$portingStatus = $portingStatusArray[$portingStatusId];
			$order = $objectManager->create('\Magento\Sales\Model\Order')->loadByIncrementId($orderId);
			if($order->getId() && $portingStatus) {
				$order->setPortingStatus($portingStatus);
				$order->save();
			} else {
				$portingstatusData->setResultId('0');
				$portingstatusData->setResultMessage('Invalid order id or invalid porting status id');
			}
		}
		$logRequest[] = $portingstatusData->getOrderId();
		$logRequest[] = $portingstatusData->getPortingStatusId();
		$logResponse[] = $portingstatusData->getResultId();
		$logResponse[] = $portingstatusData->getResultMessage();
		$objectManager->get('Panama\MagentoApi\Helper\Data')->logCreate('/var/log/portingstatusupdate_request_response.log', "<==Request==>\n".json_encode($logRequest));
		$objectManager->get('Panama\MagentoApi\Helper\Data')->logCreate('/var/log/portingstatusupdate_request_response.log', "<==Response==>\n".json_encode($logResponse)."\n\n");
		return $portingstatusData; die;
    }
}