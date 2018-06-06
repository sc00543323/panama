<?php
/**
 * Copyright 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Panama\MagentoApi\Api\Data;

/**
 * Defines a data structure representing a point, to demonstrating passing
 * more complex types in and out of a function call.
 */
interface InventoryInterface
{
	const skuDetails = 'sku_details';
	
	/**
     * Set skuDetails
     *
     * @param Panama\MagentoApi\Api\Data\SkuDetailsInterface[] $skuDetails
     * @return $this
     */
	 public function setSkuDetails($skuDetails);
	
	 /**
     * Get the skuDetails
     *
     * @return Panama\MagentoApi\Api\Data\SkuDetailsInterface[]|null
     */
    public function getSkuDetails();
}