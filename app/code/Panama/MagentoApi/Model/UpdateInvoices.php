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
			$invoicesData->setresultId('0');
			$invoicesData->setResultMessage('Order id is mandatory');
		} else if(!$invoiceUri) {
			$invoicesData->setresultId('0');
			$invoicesData->setResultMessage('invoice Uri is mandatory');
		} else {
			$invoicesData->setresultId('1');
			$invoicesData->setResultMessage('Invoice of an order has been updated.');
		}
		
		if($invoicesData->getresultId() == 1) {
			$order = $objectManager->create('\Magento\Sales\Model\Order')->loadByIncrementId($orderId);
			if($order->getId()) {
				$order->setInvoiceUrl($invoiceUri);
				$order->save();
			} else {
				$invoicesData->setresultId('0');
				$invoicesData->setResultMessage('Invalid order id or invalid DeliveryStatusId');
			}
		}
		return $invoicesData; die;
    }
}