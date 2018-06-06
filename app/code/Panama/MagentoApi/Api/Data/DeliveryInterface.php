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
interface DeliveryInterface
{
	
	const order_id = 'order_id';
	const delivery_status_id = 'delivery_status_id';
	const tracking_delivery_url = 'tracking_delivery_url';
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
     * Set orderId
     *
     * @param int $deliveryStatusId
     * @return $this
     */
    public function setDeliveryStatusId($deliveryStatusId);
	
	/**
     * Returns deliveryStatusId
     *
     * @return int
     */
    public function getDeliveryStatusId();
	/**
     * Set trackingDeliveryUrl
     *
     * @param string $trackingDeliveryUrl
     * @return $this
     */
    public function setTrackingDeliveryUrl($trackingDeliveryUrl);
	
	/**
     * Returns  trackingDeliveryUrl
     *
     * @return string
     */
    public function getTrackingDeliveryUrl();
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