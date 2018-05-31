<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Panama\Mylines\Block\Order;
/**
 * Sales order history block
 */
class History extends \Magento\Sales\Block\Order\History
{
	/**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;
	
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
	 * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Sales\Model\Order\Config $orderConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
		\Magento\Sales\Model\OrderFactory $orderFactory,
		\Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
		\Magento\Framework\Registry $registry,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\Order\Config $orderConfig,
		\Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Framework\Stdlib\DateTime\DateTime $date,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
		$this->_objectManager = $objectManager;
		$this->orderFactory = $orderFactory;
		$this->orderCollectionFactory = $orderCollectionFactory;
		$this->registry = $registry;
		$this->scopeConfig = $scopeConfig;
		$this->date = $date;
        parent::__construct($context, $orderCollectionFactory, $customerSession, $orderConfig, $data);
    }
    
	public function getCurrentDate(){
		$current_date = $this->date->date('Y-m-d H:i:s');
		return $current_date;
	}
	
	public function getCurrentOrder()
	{
		$orders = $this->getOrders();

		$currentOrder = $orders->getFirstItem();
		
		return $currentOrder;
	}
	
	public function setCurrentOrder($order)
	{
		$this->registry->register('current_order', $order);
	}
	
	public function getMsisdnNumber(){
		$order = $this->getCurrentOrder();
		$msisdnData = $this->orderCollectionFactory->create()->addFieldToFilter('entity_id', ['eq' => $order->getIncrementId()])->getData('msisdn');
		foreach ($msisdnData as $msisdn)
		{
			$msisdnNumber = $msisdn['msisdn'];
		}
		return $msisdnNumber;
	}
	
	public function getMsisdnStatus(){
		$order = $this->getCurrentOrder();
		$msisdnStatusData = $this->orderCollectionFactory->create()->addFieldToFilter('entity_id', ['eq' => $order->getIncrementId()])->getData('msisdn_status');
		foreach ($msisdnStatusData as $msisdn)
		{
			$msisdnStatus = $msisdn['msisdn_status'];
		}
		return $msisdnStatus;
	}
	
}