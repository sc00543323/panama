<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Panama\Handset\Model\Total;


class Downpayment extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
   /**
     * Collect grand total address amount
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this
     */ 

    public function __construct(\Magento\Checkout\Model\Cart $cart,\Panama\Handset\Model\Handset $handset)
    {
        $this->cart = $cart;
        $this->handset = $handset;
    }

    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        $itemsVisible = $this->cart->getQuote()->getAllVisibleItems();

        $downpayment_amt = 0;
        foreach($itemsVisible as $item) {
            $handsetData = $this->handset->load($item->getSku(),'phone_sku');
            if($handsetData) {
                $downpayment_amt = $downpayment_amt + $handsetData['down_payment_amount'];
            }        
        }
        return [
            'code' => 'downpayment',
            'title' => 'Downpayment',
            'value' => $downpayment_amt
        ];
    }

    /**
     * Get Subtotal label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('Downpayment');
    }
}