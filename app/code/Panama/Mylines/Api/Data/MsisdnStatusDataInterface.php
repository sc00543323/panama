<?php
/**
 * Copyright 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Panama\Mylines\Api\Data;

interface MsisdnStatusDataInterface
{
	
	const order_id = 'order_id';
	const msisdn = 'msisdn';
	const msisdn_status = 'msisdn_status';
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
     * Set msisdn_status_id
     *
     * @param int $msisdn_status_id
     * @return $this
     */
    public function setMsisdnStatusId($msisdn_status_id);
	
	/**
     * Returns $msisdn_status_id
     *
     * @return int
     */
    public function getMsisdnStatusId();
	
   /**
     * Set msisdn_status
     *
     * @param string $msisdn_status

     * @return $this
     */
    public function setMsisdnStatus($msisdn_status);
	
	/**
     * Returns $msisdn_status
     *
     */
    public function getMsisdnStatus();
	
	/**
     * Set $msisdn
     *
     * @return $this
     */
    public function setMsisdn($msisdn);
	
	
	/**
     * Returns  $msisdn
     *
     */
    public function getMsisdn();
	
	
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