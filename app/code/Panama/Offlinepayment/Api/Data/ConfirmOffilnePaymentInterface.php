<?php
/**
 * Copyright 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Panama\Offlinepayment\Api\Data;
/**
 * Defines a data structure representing a point, to demonstrating passing
 * more complex types in and out of a function call.
 */
interface ConfirmOffilnePaymentInterface
{
	
	const order_id = 'order_id';
	const order_payment_confirm = 'order_payment_confirm';
	const confirmation_number = 'confirmation_number';
	const payment_type = 'payment_type';
	const paid_on = 'paid_on';
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
     * @param int $orderPaymentConfirm
     * @return $this
     */
    public function setOrderPaymentConfirm($orderPaymentConfirm);
	
	/**
     * Returns orderPaymentConfirm
     *
     * @return int
     */
    public function getOrderPaymentConfirm();
	
	/**
     * Set $ConfirmationNumber
     *
     * @param string $ConfirmationNumber
     * @return $this
     */
    public function setConfirmationNumber($ConfirmationNumber);
	
	
	/**
     * Returns  $ConfirmationNumber
     *
     * @return string
     */
    public function getConfirmationNumber();
	
	/**
     * Set $PaymentType
     *
     * @param string $PaymentType
     * @return $this
     */
    public function setPaymentType($PaymentType);
	
	/**
     * Returns  $PaymentType
     *
     * @return string
     */
    public function getPaymentType();
	
	/**
     * Set $paidOn
     *
     * @param string $paidOn
     * @return $this
     */
    public function setPaidOn($paidOn);
	
	/**
     * Returns  $paidOn
     *
     * @return string
     */
    public function getPaidOn();
	
	/**
     * Set result id
     *
     * @param int $resultId
     * @return $this
     */
	public function setResultId($resultId);
	
	
	/**
     * Returns $resultId
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
     * Returns $resultMessage
     *
     * @return string
     */
    public function getResultMessage();
}