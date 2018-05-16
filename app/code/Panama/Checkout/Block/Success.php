<?php
namespace Panama\Checkout\Block;

class Success  extends \Magento\Framework\View\Element\Template
{
	
	protected $orderRepository;
	protected $_checkoutSession;
	protected $taxItem;
	
	public function __construct(
    \Magento\Framework\View\Element\Template\Context $context,
    \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
	\Magento\Checkout\Model\Session $checkoutSession,
	\Magento\Sales\Model\ResourceModel\Order\Tax\Item $taxItem,
    array $data = []
	){
		$this->orderRepository = $orderRepository;
		$this->_checkoutSession = $checkoutSession;
		$this->taxItem = $taxItem;
		parent::__construct($context, $data);
	}

	public function getOrder()
	{
		$order = $this->_checkoutSession->getLastRealOrder();
		$orderId = $order->getEntityId(); 
		return $this->orderRepository->get($order->getIncrementId());
	}
	public function getTax()
    {
        $order = $this->_checkoutSession->getLastRealOrder();
		$order->getIncrementId();
		return $tax_items = $this->taxItem->getTaxItemsByOrderId($order->getIncrementId());
    }
	public function getContinueUrl()
	{
		return $this->getBaseUrl();
	}
}