<?php

namespace Panama\MagentoApi\Api\Data;

/**
 * Defines a data structure representing a point, to demonstrating passing
 * more complex types in and out of a function call.
 */
interface OrderDetailsInterface
{

	const sku = 'sku';
	const serial_id = 'serial_id';
	const msisdn = 'msisdn';
	
    /**
     * Get sku
     *
     * @return string
     */
    public function getSku();

    /**
     * Set sku
     *
     * @param string $sku
     * @return $this
     */
    public function setSku($sku);

    /**
     * Get SerialId
     *
     * @return string
     */
    public function getSerialId();

    /**
     * Set SerialId
     *
     * @param string $serialId
     * @return $this
     */
    public function setSerialId($serialId);

    /**
     * Get Msisdn
     *
     * @return string
     */
    public function getMsisdn();

    /**
     * Set Msisdn
     *
     * @param string $Msisdn
     * @return $this
     */
    public function setMsisdn($Msisdn);
}
