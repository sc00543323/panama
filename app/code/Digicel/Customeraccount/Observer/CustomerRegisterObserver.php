<?php
namespace Digicel\Customeraccount\Observer;
use Magento\Customer\Model\CustomerFactory;

class CustomerRegisterObserver implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $_customerFactory;

    function __construct(\Magento\Customer\Model\CustomerFactory $customerFactory)
    {
        $this->_customerFactory = $customerFactory;

    }


    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customerData = $observer->getCustomer();
        if($_POST['cedulla'] && $_POST['passport']) {
            $customer = $this->_customerFactory->create()->load($customerData->getId());
            $customer->setData('cedulla', $_POST['cedulla']);
			$customer->setData('passport', $_POST['passport']);
            $customer->save();
        }
    }
}

?>