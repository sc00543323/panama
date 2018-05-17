<?php
namespace SR\StackExchange\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Customer\Api\CustomerRepositoryInterface;

class CustomerRegisterSuccess implements ObserverInterface
{
    /** @var CustomerRepositoryInterface */
    protected $customerRepository;

    /**
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Manages redirect
     */
    public function execute(Observer $observer)
    {
        $accountController = $observer->getAccountController();
        $customer = $observer->getCustomer();
        $request = $accountController->getRequest();
        $cedulla = $request->getParam('cedulla');
		$passport = $request->getParam('passport');
        $customer->setCustomAttribute('cedulla', $cedulla);
		$customer->setCustomAttribute('passport', $passport);
        $this->customerRepository->save($customer);
    }
}