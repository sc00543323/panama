<?php
/**
 * This is API module for Confirm Offline Payment
 * Copyright (C) 2017 Digicle-Panama@2018.All rights reserved
 * 
 * This file is part of Panama/Offlinepayment.
 * 
 * Panama/Offlinepayment is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Panama\Offlinepayment\Model;

use Panama\Offlinepayment\Api\OfflinepaymentManagementInterface;
use Magento\Sales\Model\Order;
 
class OfflinepaymentManagement implements OfflinepaymentManagementInterface
{

    /**
     * 
     * POST for Offlinepayment api
     * @param int $orderId
	 * @param int $orderPaymentConfirm
	 * @param string $ConfirmationNumber
	 * @param string $PaymentType
	 * @param string $paidOn
	 * @return int $resultId
     */

    public function ConfirmOfflinePayment($orderId,$orderPaymentConfirm,$ConfirmationNumber,$PaymentType,$paidOn)
    {
       
		if($orderPaymentConfirm == 1) {
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$order = $objectManager->create('\Magento\Sales\Model\Order')->loadByIncrementId($orderId);
			if($order->getId()) {
				$order->setOrderId($orderId);
				$order->setOrderPaymentConfirm($orderPaymentConfirm);
				$order->setConfirmationNumber($ConfirmationNumber);
				$order->setPaymentType($PaymentType);
				$order->setPaidOn($paidOn);
				$order->setResultId('1');
				$order->setResultMessage('Order Offine Payment is confirmed.');
				$order->save();
			} else {
				$order->setResultId('0');
				$order->setResultMessage('Invalid order id or invalid ConfirmationId');
			}
			$logRequest[] = $orderId;
			$logRequest[] = $orderPaymentConfirm;
			$logRequest[] = $ConfirmationNumber;
			$logRequest[] = $PaymentType;
			$logRequest[] = $paidOn;
			$logResponse[] = $order->getResultId();
			$logResponse[] = $order->getResultMessage();
			$objectManager->get('Panama\MagentoApi\Helper\Data')->logCreate('/var/log/offlinepaymentupdate_request_response.log', "<==Request==>\n".json_encode($logRequest));
			$objectManager->get('Panama\MagentoApi\Helper\Data')->logCreate('/var/log/offlinepaymentupdate_request_response.log', "<==Response==>\n".json_encode($logResponse)."\n\n");
		}
		
		return $resultId = $order->getResultId();
    }
}
