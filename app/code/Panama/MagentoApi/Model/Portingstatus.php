<?php
/**
 * Copyright 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Panama\MagentoApi\Model;

use Panama\MagentoApi\Api\Data\PortingstatusInterface;
use Magento\Framework\Model\AbstractExtensibleModel;
/**
 * Defines a data structure representing a point, to demonstrating passing
 * more complex types in and out of a function call.
 */
class Portingstatus extends AbstractExtensibleModel implements PortingstatusInterface
{
    /**
     * {@inheritdoc}
     */
    public function getOrderId()
    {
        return $this->getData(self::order_id);
    }

    /**
     * {@inheritdoc}
     */
    public function setOrderId($orderId)
    {
        return $this->setData(self::order_id, $orderId);
    }
	
	 /**
     * {@inheritdoc}
     */
    public function getPortingStatusId()
    {
        return $this->getData(self::porting_status_id);
    }

    /**
     * {@inheritdoc}
     */
    public function setPortingStatusId($portingStatusId)
    {
        return $this->setData(self::porting_status_id, $portingStatusId);
    }
	/**
     * {@inheritdoc}
     */
    public function getResultId()
    {
        return $this->getData(self::result_id);
    }

    /**
     * {@inheritdoc}
     */
    public function setResultId($resultId)
    {
        return $this->setData(self::result_id, $resultId);
    }
	 /**
     * {@inheritdoc}
     */
    public function getResultMessage()
    {
        return $this->getData(self::result_message);
    }

    /**
     * {@inheritdoc}
     */
    public function setResultMessage($resultMessage)
    {
        return $this->setData(self::result_message, $resultMessage);
    }
}