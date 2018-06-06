<?php
/**
 * Copyright 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Panama\MagentoApi\Model;

use Panama\MagentoApi\Api\Data\InvoicesInterface;
use Magento\Framework\Model\AbstractExtensibleModel;
/**
 * Defines a data structure representing a point, to demonstrating passing
 * more complex types in and out of a function call.
 */
class Invoices extends AbstractExtensibleModel implements InvoicesInterface
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
    public function getInvoiceUri()
    {
        return $this->getData(self::invoice_uri);
    }

    /**
     * {@inheritdoc}
     */
    public function setInvoiceUri($invoiceUri)
    {
        return $this->setData(self::invoice_uri, $invoiceUri);
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