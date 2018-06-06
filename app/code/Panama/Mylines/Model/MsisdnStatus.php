<?php
/**
 * This is API module for Confirm Offline Payment
 * Copyright (C) 2017 Digicle-Panama@2018.All rights reserved
 * 
 * This file is part of Panama/Mylines.
 * 
 * Panama/Mylines is free software: you can redistribute it and/or modify
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

namespace Panama\Mylines\Model;

use Panama\Mylines\Api\MsisdnStatusInterface;
use Magento\Sales\Model\Order;
 
class MsisdnStatus implements MsisdnStatusInterface
{

    /**
     * 
     * POST for Mylines api
     * @param int $orderId
	 * @param int $msisdn
	 * @param int $msisdn_status_id
	 * @param string $msisdn_status
	 * @return int $resultId
     */

    public function msisdnStatus($orderId,$msisdn,$msisdn_status_id,$msisdn_status)
    {
       
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$order = $objectManager->create('\Magento\Sales\Model\Order')->loadByIncrementId($orderId);
			if($msisdn_status_id == 1) {
				$order->setOrderId($orderId);
				$order->setMsisdn($msisdn);
				$order->setMsisdnStatusId($msisdn_status_id);

				$order->setMsisdnStatus($msisdn_status);
				$order->setResultId('1');
				$order->setResultMessage('MSISDN Status is Active.');
				$order->save();

			} 
			 else if($msisdn_status_id == 0){
				$order->setMsisdn($msisdn);
				$order->setMsisdnStatusId($msisdn_status_id);
				$order->setMsisdnStatus($msisdn_status);
				$order->setResultId('0');
				$order->setResultMessage('MSISDN Status is Inactive');
				$order->save();
			}

		
		$resultId = $order->getResultId();
		$resultMessage = $order->getResultMessage();
		$response = array("result_id"=>$resultId,"result_message"=>$resultMessage);
		return json_encode($response);
    }
}
