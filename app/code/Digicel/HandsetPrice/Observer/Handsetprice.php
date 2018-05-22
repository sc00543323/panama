<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Digicel\HandsetPrice\Observer;

use Magento\Framework\Event\ObserverInterface;

class Handsetprice implements ObserverInterface {

    protected $_request;/**     * @param \Magento\Framework\App\RequestInterface $request */

    public function __construct(\Magento\Framework\App\RequestInterface $request) {
        $this->_request = $request;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {
        $reqeustParams = $this->_request->getParams();
        if ($price = @$reqeustParams['handset_api_price']) {

            $item = $observer->getEvent()->getData('quote_item');
            if ($item->getProductId() == $reqeustParams['product']) {
                $item->setCustomPrice($price);
                $item->setOriginalCustomPrice($price);
                $item->getProduct()->setIsSuperMode(true);
            }
        }
    }

}
