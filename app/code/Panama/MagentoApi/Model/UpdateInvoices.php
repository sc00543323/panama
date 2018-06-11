<?php
namespace Panama\MagentoApi\Model;
use Panama\MagentoApi\Api\UpdateInvoicesInterface;
use Magento\Sales\Model\Order;

class UpdateInvoices implements UpdateInvoicesInterface
{
    public function updateInvoices(\Panama\MagentoApi\Api\Data\InvoicesInterface $invoicesData) {
	
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$orderId = $invoicesData->getOrderId();
		$invoiceUri = $invoicesData->getInvoiceUri();
		
		if(!$orderId) {
			$invoicesData->setResultId('0');
			$invoicesData->setResultMessage('Order id is mandatory');
		} else if(!$invoiceUri) {
			$invoicesData->setResultId('0');
			$invoicesData->setResultMessage('invoice Uri is mandatory');
		} else {
			$invoicesData->setResultId('1');
			$invoicesData->setResultMessage('Invoice of an order has been updated.');
		}
		
		if($invoicesData->getResultId() == 1) {
			$order = $objectManager->create('\Magento\Sales\Model\Order')->loadByIncrementId($orderId);
			if($order->getId()) {
				$order->setInvoiceUrl($invoiceUri);
				$order->save();
			} else {
				$invoicesData->setResultId('0');
				$invoicesData->setResultMessage('Invalid order id or invalid DeliveryStatusId');
			}
		}
		
		$logRequest[] = $invoicesData->getOrderId();
		$logRequest[] = $invoicesData->getInvoiceUri();
		$logResponse[] = $invoicesData->getResultId();
		$logResponse[] = $invoicesData->getResultMessage();
		$objectManager->get('Panama\MagentoApi\Helper\Data')->logCreate('/var/log/invoiceupdate_request_response.log', "<==Request==>\n".json_encode($logRequest));
		$objectManager->get('Panama\MagentoApi\Helper\Data')->logCreate('/var/log/invoiceupdate_request_response.log', "<==Response==>\n".json_encode($logResponse)."\n\n");
		return $invoicesData; die;
    }
}