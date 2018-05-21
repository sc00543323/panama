<?php
/**
 * Copyright 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Panama\MagentoApi\Model;

use Panama\MagentoApi\Api\Data\OrderDetailsInterface;
use Magento\Framework\Model\AbstractExtensibleModel;
/**
 * Defines a data structure representing a point, to demonstrating passing
 * more complex types in and out of a function call.
 */
class OrderDetails extends AbstractExtensibleModel implements OrderDetailsInterface
{

	/**
     * {@inheritdoc}
     */
    public function getSku()
    {
        return $this->getData(self::sku);
    }

    /**
     * {@inheritdoc}
     */
    public function setSku($sku)
    {
        return $this->setData(self::sku, $sku);
    }
	
	 /**
     * {@inheritdoc}
     */
    public function getSerialId()
    {
        return $this->getData(self::serial_id);
    }

    /**
     * {@inheritdoc}
     */
    public function setSerialId($serialId)
    {
        return $this->setData(self::serial_id, $serialId);
    }
	
	/**
     * {@inheritdoc}
     */
    public function getMsisdn()
    {
        return $this->getData(self::msisdn);
    }

    /**
     * {@inheritdoc}
     */
    public function setMsisdn($msisdn)
    {
        return $this->setData(self::msisdn, $msisdn);
    }
}