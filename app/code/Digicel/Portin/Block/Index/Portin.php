<?php

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Digicel\Portin\Block\Index;

use Magento\Customer\Model\Context;
/**
 * Product View block
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Portin extends \Magento\Checkout\Block\Cart\AbstractCart
 {

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Url
     */
    protected $_catalogUrlBuilder;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * @var \Magento\Checkout\Helper\Cart
     */
    protected $_cartHelper;
	
	protected $_customerRepositoryInterface;
	
	protected $_timezoneInterface;
	
	protected $cart;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Catalog\Model\ResourceModel\Url $catalogUrlBuilder
     * @param \Magento\Checkout\Helper\Cart $cartHelper
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param array $data
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Catalog\Model\ResourceModel\Url $catalogUrlBuilder,
        \Magento\Checkout\Helper\Cart $cartHelper,
		\Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
		\Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface,
        \Magento\Framework\App\Http\Context $httpContext,
		\Magento\Checkout\Model\Cart $cart,
		
        array $data = []
    ) {
        $this->_cartHelper = $cartHelper;
        $this->_catalogUrlBuilder = $catalogUrlBuilder;
        parent::__construct($context, $customerSession, $checkoutSession, $data);
		$this->customerSession = $customerSession;
        $this->_isScopePrivate = true;
		$this->_customerRepositoryInterface = $customerRepositoryInterface;
		$this->_timezoneInterface = $timezoneInterface;
        $this->httpContext = $httpContext;
		$this->cart = $cart;
    }
	
	public function getNip(){		
		$data = 1234;
		return $data;
	}
	public function getCustomer(){		
		$customerId = $this->customerSession->getId();
		$customer = $this->_customerRepositoryInterface->getById($customerId);
		return $customer;
	}
	
	public function getCurrentService(){
		$currentService = '';
		
		$items = $this->cart->getQuote()->getAllVisibleItems();
			 foreach($items as $item){
			  $portin = $item->getIsPortable();			  
			  if($portin == 'yes'){
				  $currentService = $item->getCurrentService();
				  $portin = 1;
				  break;
			  }
			}
			return $currentService;
	}
	
	public function convertDate($date){
		$FormatedDate = $this->_timezoneInterface
                                        ->date(new \DateTime($date))
                                        ->format('m/d/Y');
		return $FormatedDate;
	}
	
}