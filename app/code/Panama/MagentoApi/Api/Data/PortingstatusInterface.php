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
interface PortingstatusInterface
{
	
	const order_id = 'order_id';
	const porting_status_id = 'porting_status_id';
	const result_id = 'result_id';
	const result_message = 'result_message';
	/**
     * Set orderId
     *
     * @param string $orderId
     * @return $this
     */
    public function setOrderId($orderId);
	
	/**
     * Returns orderId
     *
     * @return string
     */
    public function getOrderId();
	/**
     * Set portingStatusId
     *
     * @param string $portingStatusId
     * @return $this
     */
    public function setPortingStatusId($portingStatusId);
	
	/**
     * Returns  portingStatusId
     *
     * @return string
     */
    public function getPortingStatusId();
	/**
     * Set result id
     *
     * @param int $resultId
     * @return $this
     */
	public function setResultId($resultId);
	
	/**
     * Returns result id
     *
     * @return int
     */
    public function getResultId();
	/**
     * Set resultMessage
     *
     * @param string $resultMessage
     * @return $this
     */
    public function setResultMessage($resultMessage);
	
	/**
     * Returns resultMessage
     *
     * @return string
     */
    public function getResultMessage();
}