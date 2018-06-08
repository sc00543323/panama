<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Panama\Handset\Model\Total;


class PortTax extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
   /**
     * Collect grand total address amount
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this
     */ 

    public function __construct(\Magento\Checkout\Model\Cart $cart,\Panama\Handset\Model\Handset $handset,\Magento\Tax\Model\Calculation\Rate $rate)
    {
        $this->cart = $cart;
        $this->handset = $handset;
        $this->rate = $rate;
    }

    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        $items = $shippingAssignment->getItems();
        if (!count($items)) {
            return $this;
        }
        parent::collect($quote, $shippingAssignment, $total);
        $is_portable = false;
        foreach($items as $item) {
            if($item->getIsPortable()) {
                $is_portable = true;
            }
        }
        if($is_portable) {
            $taxCalculation = $this->rate->load('port_tax','code');
            $porttax = $taxCalculation->getRate();
            $total->addTotalAmount('porttax', $porttax);
        }
        return $this;
    }

    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        $is_portable = false;
        foreach($quote->getItems() as $item) {
            if($item->getIsPortable()) {
                $is_portable = true;
            }
        }
        if($is_portable) {
            $taxCalculation = $this->rate->load('port_tax','code');
            $porttax = $taxCalculation->getRate();
            return [
                'code' => 'porttax',
                'title' => 'Port Tax',
                'value' => $porttax
            ];
        }
    }

    /**
     * Get Subtotal label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('Port Tax');
    }
}