<?php
/**
 * Copyright © 2016 Digicel. All rights reserved.
 * See COPYING.txt for license details.
 */
 
namespace Digicel\Portin\Controller\Index;
 
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Digicel\Login\Model\Data;
 
/**
 * Login controller
 *
 * @method \Magento\Framework\App\RequestInterface getRequest()
 * @method \Magento\Framework\App\Response\Http getResponse()
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class saveNip extends \Magento\Framework\App\Action\Action
{
 
    /**
     * @var \Magento\Framework\Json\Helper\Data $helper
     */
    protected $helper;
 
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;
 
    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;
	protected $modelFactory;
	protected $customer;
	protected $customerSession;
	protected $_customerRepositoryInterface;
 
 
    /**
     * Initialize Login controller
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Json\Helper\Data $helper
     * @param AccountManagementInterface $customerAccountManagement
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Json\Helper\Data $helper,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
		\Magento\Customer\Model\Customer $customer,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
		\Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
		\Magento\Customer\Model\Session $customerSession,
		 Data $modelFactory
    ) {
        parent::__construct($context);
        $this->helper = $helper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultRawFactory = $resultRawFactory;
		$this->customer	= $customer;
		$this->_customerRepositoryInterface = $customerRepositoryInterface;
		$this->customerSession = $customerSession;
		$this->modelFactory = $modelFactory;
    }
    public function execute()
    {
		
		$loginData = $this->getRequest()->getPostValue();
		$nipNumber = implode('', $loginData['nipNumber']);
		$customerId = $this->customerSession->getId();
		if($customerId) {
		
			$customer = $this->_customerRepositoryInterface->getById($customerId);
			$customer->setCustomAttribute('nip_number',$nipNumber);
					
		try{
			$this->_customerRepositoryInterface->save($customer);
		}catch (\Exception $e) {
			$message = array('valid' => 0,'message'=> $e->getMessage());
		}
			$message = array('valid' => 1,'message'=> 'Account Updated successfully');
		} else {
			$message = array('valid' => 0,'message'=> 'Something went wrong');
		}
		
		$resultJson = $this->resultJsonFactory->create();
	    return $resultJson->setData($message);
    }
	public function getDistric(){
		return $sampleCollection = $this->modelFactory->getCollection();
	}
}