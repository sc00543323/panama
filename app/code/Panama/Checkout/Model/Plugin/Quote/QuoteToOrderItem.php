<?php
namespace Panama\Checkout\Model\Plugin\Quote;

use Closure;

class QuoteToOrderItem
{
    /**
     * @param \Magento\Quote\Model\Quote\Item\ToOrderItem $subject
     * @param callable $proceed
     * @param \Magento\Quote\Model\Quote\Item\AbstractItem $item
     * @param array $additional
     * @return \Magento\Sales\Model\Order\Item
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
  public function aroundConvert(
        \Magento\Quote\Model\Quote\Item\ToOrderItem $subject,
        Closure $proceed,
        \Magento\Quote\Model\Quote\Item\AbstractItem $item,
        $additional = []
    ) {
        /** @var $orderItem \Magento\Sales\Model\Order\Item */
        $orderItem = $proceed($item, $additional);//result of function 'convert' in class 'Magento\Quote\Model\Quote\Item\ToOrderItem' 
        $orderItem->setIsPortable($item->getIsPortable());
        $orderItem->setCurrentService($item->getCurrentService());
        $orderItem->setIsSmartphone($item->getIsSmartphone());
        $orderItem->setIsContract($item->getIsContract());
        return $orderItem;// return an object '$orderItem' which will replace result of function 'convert' in class 'Magento\Quote\Model\Quote\Item\ToOrderItem'
    }
}