<?php

namespace Panama\MagentoApi\Api\Data;

/**
 * Defines a data structure representing a point, to demonstrating passing
 * more complex types in and out of a function call.
 */
interface SkuDetailsInterface
{

	const sku = 'sku';
	const qty = 'qty';
	const store_id = 'store_id';
	
	/**
     * Set sku
     *
     * @param string $sku
     * @return $this
     */
    public function setSku($sku);
	
	/**
     * Returns sku
     *
     * @return string
     */
    public function getSku();
	/**
     * Set qty
     *
     * @param int $qty
     * @return $this
     */
    public function setQty($qty);
	
	/**
     * Returns  qty
     *
     * @return int
     */
    public function getQty();
	
	/**
     * Set storeId
     *
     * @param string $storeId
     * @return $this
     */
    public function setStoreId($storeId);
	
	/**
     * Returns storeId
     *
     * @return string
     */
    public function getStoreId();
}
