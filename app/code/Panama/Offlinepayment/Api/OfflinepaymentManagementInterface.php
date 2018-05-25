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

namespace Panama\Offlinepayment\Api;
use Panama\Offlinepayment\Api\Data\ConfirmOfflinePaymentInterface;

interface OfflinepaymentManagementInterface
{


    /**
     * POST for Offlinepayment api
     * @param int $orderId
	 * @param int $orderPaymentConfirm
	 * @param string $ConfirmationNumber
	 * @param string $PaymentType
	 * @param string $paidOn
     * @return int $resultId
     */
    public function ConfirmOfflinePayment($orderId,$orderPaymentConfirm,$ConfirmationNumber,$PaymentType,$paidOn);
}
