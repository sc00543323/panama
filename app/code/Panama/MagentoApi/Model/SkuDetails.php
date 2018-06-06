<?php
/**
 * Copyright 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Panama\MagentoApi\Model;

use Panama\MagentoApi\Api\Data\SkuDetailsInterface;
use Magento\Framework\Model\AbstractExtensibleModel;
/**
 * Defines a data structure representing a point, to demonstrating passing
 * more complex types in and out of a function call.
 */
class SkuDetails extends AbstractExtensibleModel implements SkuDetailsInterface
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
    public function getQty()
    {
        return $this->getData(self::qty);
    }

    /**
     * {@inheritdoc}
     */
    public function setQty($qty)
    {
        return $this->setData(self::qty, $qty);
    }
	
	 /**
     * {@inheritdoc}
     */
    public function getStoreId()
    {
        return $this->getData(self::store_id);
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::store_id, $storeId);
    }
}