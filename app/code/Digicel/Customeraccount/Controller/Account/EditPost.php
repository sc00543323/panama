<?php
/**
 *
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Digicel\Customeraccount\Controller\Account;

use Magento\Customer\Model\AuthenticationInterface;
use Magento\Customer\Model\Customer\Mapper;
use Magento\Customer\Model\EmailNotificationInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerExtractor;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\InvalidEmailOrPasswordException;
use Magento\Framework\Exception\State\UserLockedException;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Newsletter\Model\Subscriber;
/**
 * Class EditPost
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class EditPost extends \Magento\Customer\Controller\AbstractAccount
{
    /**
     * Form code for data extractor
     */
    const FORM_DATA_EXTRACTOR_CODE = 'customer_account_edit';

    /**
     * @var AccountManagementInterface
     */
    protected $customerAccountManagement;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var Validator
     */
    protected $formKeyValidator;

    /**
     * @var CustomerExtractor
     */
    protected $customerExtractor;

    /**
     * @var Session
     */
    protected $session;

    /** @var EmailNotificationInterface */
    private $emailNotification;

    /**
     * @var AuthenticationInterface
     */
    private $authentication;

    /**
     * @var Mapper
     */
    private $customerMapper;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param AccountManagementInterface $customerAccountManagement
     * @param CustomerRepositoryInterface $customerRepository
     * @param Validator $formKeyValidator
     * @param CustomerExtractor $customerExtractor
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        AccountManagementInterface $customerAccountManagement,
        CustomerRepositoryInterface $customerRepository,
        Validator $formKeyValidator,
		SubscriberFactory $subscriberFactory,
		Subscriber $subscriber,
		\Magento\Sales\Model\Order $orderModel,
		ScopeConfigInterface $scopeConfig,
		\Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection $optionCollection,		
        CustomerExtractor $customerExtractor		
    ) {
        parent::__construct($context);
        $this->session = $customerSession;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->customerRepository = $customerRepository;
        $this->formKeyValidator = $formKeyValidator;
		$this->subscriberFactory = $subscriberFactory;
		$this->subscriber = $subscriber;
		$this->scopeConfig = $scopeConfig;
		$this->_orderModel = $orderModel;
		$this->optionCollection = $optionCollection;
        $this->customerExtractor = $customerExtractor;
    }

    /**
     * Get authentication
     *
     * @return AuthenticationInterface
     */
    private function getAuthentication()
    {

        if (!($this->authentication instanceof AuthenticationInterface)) {
            return ObjectManager::getInstance()->get(
                \Magento\Customer\Model\AuthenticationInterface::class
            );
        } else {
            return $this->authentication;
        }
    }

    /**
     * Get email notification
     *
     * @return EmailNotificationInterface
     * @deprecated
     */
    private function getEmailNotification()
    {
        if (!($this->emailNotification instanceof EmailNotificationInterface)) {
            return ObjectManager::getInstance()->get(
                EmailNotificationInterface::class
            );
        } else {
            return $this->emailNotification;
        }
    }

    /**
     * Change customer email or password action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
		
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
		$data = $this->getRequest()->getParams();
        $resultRedirect = $this->resultRedirectFactory->create();
        $validFormKey = $this->formKeyValidator->validate($this->getRequest());
		/** added optIn/optOut functionality started */
		$countorders  = $this->getCustomerOrders();
		$mobile_number = $this->getMobileNumber();
		
        if ($validFormKey && $this->getRequest()->isPost()) {
            $currentCustomerDataObject = $this->getCustomerDataObject($this->session->getCustomerId());
            $customerCandidateDataObject = $this->populateNewCustomerDataObject(
                $this->_request,
                $currentCustomerDataObject
            );
		
		if($countorders == 0){
			/** added optIn/optOut functionality ended*/	
			
				if( $this->getRequest()->getParam('dob') == '')
				{
				
					$this->session->setCustomerFormData($this->getRequest()->getPostValue());
					$this->messageManager->addError('Date of Birth is Required');  
					$this->_redirect('*/*/edit',['_secure' => true]);
					return;
				}
				$dobval = $this->getRequest()->getParam('dob');
				$dobval = str_replace('/', '-', $dobval);
				$dobval = date('m-d-Y',strtotime($dobval));
				$diff = (date('Y') - date('Y',strtotime($dobval)));		
				$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;		
				$dobconfigvalue = $this->scopeConfig->getValue('digicel_account/dobsettings/dobvalue', $storeScope);
				if($diff <= $dobconfigvalue)
				{
						$cust = $this->getRequest()->getPostValue();
						$this->session->setCustomerFormData($this->getRequest()->getPostValue());
						$message = __(
						'Your DateOfBirth must be greater than %1 years.',
						$dobconfigvalue
					); 
						
						$this->messageManager->addError($message);  
						$this->_redirect('*/*/edit',['_secure' => true]);
						return;				
				}
			}
			else{
				$dobval = $currentCustomerDataObject->getDob();
			}
             
            try {
                // whether a customer enabled change email option
                $this->processChangeEmailRequest($currentCustomerDataObject);
				
				$cedulla = $this->getRequest()->getParam('cedulla');
				$passport = $this->getRequest()->getParam('passport');
				$mobile = $this->getRequest()->getParam('mobile_number');
				if($dobval !=''){
                $customerCandidateDataObject->setDob($dobval);
				}
				if($cedulla !=''){
                $customerCandidateDataObject->setData('cedulla',$cedulla);
				}
				if($passport !=''){
                $customerCandidateDataObject->setData('passport',$passport);
				}
				if($mobile !=''){
                $customerCandidateDataObject->setData('mobile_number',$mobile);
				}
                // whether a customer enabled change password option
                $isPasswordChanged = $this->changeCustomerPassword($currentCustomerDataObject->getEmail());
				$this->processChangeParamsRequest($currentCustomerDataObject->getId(),$isPasswordChanged);
               // $customerCandidateDataObject
                $this->customerRepository->save($customerCandidateDataObject);
                $this->getEmailNotification()->credentialsChanged(
                    $customerCandidateDataObject,
                    $currentCustomerDataObject->getEmail(),
                    $isPasswordChanged
                );
                $this->dispatchSuccessEvent($customerCandidateDataObject);
                $this->messageManager->addSuccess(__('You saved the account information.'));
                return $resultRedirect->setPath('customer/account');
            } catch (InvalidEmailOrPasswordException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (UserLockedException $e) {
                $message = __(
                    'Invalid login or password.'
                );
                $this->session->logout();
                $this->session->start();
                $this->messageManager->addError($message);
                return $resultRedirect->setPath('customer/account/login');
            } catch (InputException $e) {
                $this->messageManager->addError($e->getMessage());
                foreach ($e->getErrors() as $error) {
                    $this->messageManager->addError($error->getMessage());
                }
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {			
                $this->messageManager->addException($e, __('We can\'t save the customer.'));
            }

            $this->session->setCustomerFormData($this->getRequest()->getPostValue());
        }

        return $resultRedirect->setPath('*/*/edit');
    }
	
	public function getCustomerOrders()
	{
		$customer_id = $this->session->getCustomerId();
		$orders = $this->_orderModel->getCollection()->addAttributeToFilter('customer_id', $customer_id);
		return count($orders);
	}
	
	public function getMobileNumber()
	{
		$customer_id = $this->session->getCustomerId();
		$dataCollection = $this->_orderModel->getCollection()->addAttributeToFilter('customer_id', $customer_id);
		foreach($dataCollection as $data){
			$mobile = $data['mobile_number'];
		}
		return $mobile;
	}

    /**
     * Account editing action completed successfully event
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customerCandidateDataObject
     * @return void
     */
    private function dispatchSuccessEvent(\Magento\Customer\Api\Data\CustomerInterface $customerCandidateDataObject)
    {
        $this->_eventManager->dispatch(
            'customer_account_edited',
            ['email' => $customerCandidateDataObject->getEmail()]
        );
    }

    /**
     * Get customer data object
     *
     * @param int $customerId
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    private function getCustomerDataObject($customerId)
    {
        return $this->customerRepository->getById($customerId);
    }

    /**
     * Create Data Transfer Object of customer candidate
     *
     * @param \Magento\Framework\App\RequestInterface $inputData
     * @param \Magento\Customer\Api\Data\CustomerInterface $currentCustomerData
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    private function populateNewCustomerDataObject(
        \Magento\Framework\App\RequestInterface $inputData,
        \Magento\Customer\Api\Data\CustomerInterface $currentCustomerData
    ) {
        $attributeValues = $this->getCustomerMapper()->toFlatArray($currentCustomerData);
        $customerDto = $this->customerExtractor->extract(
            self::FORM_DATA_EXTRACTOR_CODE,
            $inputData,
            $attributeValues
        );
        $customerDto->setId($currentCustomerData->getId());
        if (!$customerDto->getAddresses()) {
            $customerDto->setAddresses($currentCustomerData->getAddresses());
        }
        if (!$inputData->getParam('change_email')) {
            $customerDto->setEmail($currentCustomerData->getEmail());
        }

        return $customerDto;
    }

    /**
     * Change customer password
     *
     * @param string $email
     * @return boolean
     * @throws InvalidEmailOrPasswordException|InputException
     */
    protected function changeCustomerPassword($email)
    {
        $isPasswordChanged = false;
        if ($this->getRequest()->getParam('change_password')) {
            $currPass = $this->getRequest()->getPost('current_password');
            $newPass = $this->getRequest()->getPost('password');
            $confPass = $this->getRequest()->getPost('password_confirmation');
            if ($newPass != $confPass) {
                throw new InputException(__('Password confirmation doesn\'t match entered password.'));
            }

            $isPasswordChanged = $this->customerAccountManagement->changePassword($email, $currPass, $newPass);
        }

        return $isPasswordChanged;
    }

    /**
     * Process change email request
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $currentCustomerDataObject
     * @return void
     * @throws InvalidEmailOrPasswordException
     * @throws UserLockedException
     */
    private function processChangeEmailRequest(\Magento\Customer\Api\Data\CustomerInterface $currentCustomerDataObject)
    {
        if ($this->getRequest()->getParam('change_email')) {
            // authenticate user for changing email
            try {
                $this->getAuthentication()->authenticate(
                    $currentCustomerDataObject->getId(),
                    $this->getRequest()->getPost('current_password')
                );
            } catch (InvalidEmailOrPasswordException $e) {
                throw new InvalidEmailOrPasswordException(__('The password doesn\'t match this account.'));
            }
        }
    }
	
	 private function processChangeParamsRequest($customerId,$isPasswordChanged)
    {
		$customer = $this->getCustomerDataObject($customerId);
		$model = $this->optionCollection ;
		$prefixoptval = $customer->getCustomAttribute('mobile_prefix')->getValue();
		$prefixchanged = 'no';
		if( $prefixoptval != $this->getRequest()->getParam('mobile_prefix')){
				 $prefixchanged = 'yes';
				}else{
					$prefixchanged = 'no';
				}
		$model->setIdFilter($prefixoptval)
            ->setStoreFilter();
		$valuearray = $model->toOptionArray();
		$prefixchanged = 'no';
		if(!empty($valuearray)){
			$customermobileprefix = $valuearray[0]['label'];
				if( $customermobileprefix != $this->getRequest()->getParam('mobile_prefix')){
				 $prefixchanged = 'yes';
				}else{
					$prefixchanged = 'no';
				}
		}else{
			$mobprefix  = substr($this->getRequest()->getParam('mobile_prefix'), 1);
			if( $prefixoptval !=  $mobprefix){
			$prefixchanged = 'yes';
			}else{
			$prefixchanged = 'no';
			}
		}	
		$diff =0;		
		if($this->getRequest()->getParam('dob')){
			$diff = abs(strtotime($this->getRequest()->getParam('dob')) - strtotime($customer->getDob()));
		}		
		if(($this->getRequest()->getParam('prefix') && ($this->getRequest()->getParam('prefix') != $customer->getPrefix() ))||
		($this->getRequest()->getParam('firstname') && ($customer->getFirstname() != $this->getRequest()->getParam('firstname'))) || 
		($this->getRequest()->getParam('lastname') && ($customer->getLastname() != $this->getRequest()->getParam('lastname')))	||
		($diff != 0) || $prefixchanged == 'yes' || 
		($customer->getCustomAttribute('mobile_number')->getValue() != $this->getRequest()->getParam('mobile_number')) || 
		($isPasswordChanged == 1) || 
		($this->getRequest()->getParam('email') && ($customer->getEmail() != $this->getRequest()->getParam('email')))
		)
		{ 			
			 //$this->getEmailNotification()->customerParamsChanged($customer);       
		   }
	   return;
	  
	   
        
    }

    /**
     * Get Customer Mapper instance
     *
     * @return Mapper
     *
     * @deprecated
     */
    private function getCustomerMapper()
    {
        if ($this->customerMapper === null) {
            $this->customerMapper = ObjectManager::getInstance()->get(\Magento\Customer\Model\Customer\Mapper::class);
        }
        return $this->customerMapper;
    }
	
	private function SubscribeNewsletter($subscribestatus)
	{
	
		$customer = $this->getCustomerDataObject($this->session->getCustomerId());
		$email = $customer->getEmail();	  
		$checkSubscriber = $this->subscriber->loadByEmail($email);
		
		if(($checkSubscriber->isSubscribed()))
		{
			if((boolean)$subscribestatus === false)
			{
				$checkSubscriber->unsubscribe();
			}
		 
		}
		else
		{			
			if ((boolean)$subscribestatus === true) 
			{
                $this->subscriberFactory->create()->subscribe($email);
            }
		
		}
		return;
	}
	private function SubscribepartnerNewsletter($subscribestatus)
	{
		$customer = $this->getCustomerDataObject($this->session->getCustomerId());
		$email = $customer->getEmail();	  
		$checkSubscriber = $this->partnersubscriber->loadByEmail($email);		
		if(($checkSubscriber->isSubscribed()))
		{
			if((boolean)$subscribestatus === false)
			{
			    
				$checkSubscriber->unsubscribe();
			}
		 
		}
		else
		{			
			if ((boolean)$subscribestatus === true) 
			{
                $this->partnersubscriberFactory->create()->subscribe($email);
            }
		
		}
		return;
	}
		private function SubscribecontractsNewsletter($subscribestatus)
	{
		$customer = $this->getCustomerDataObject($this->session->getCustomerId());
		$email = $customer->getEmail();  
		$checkSubscriber = $this->contractssubscriber->loadByEmail($email);	
		$checkSubscriber->isSubscribed();
		
		if(($checkSubscriber->isSubscribed()))
		{
			if((boolean)$subscribestatus === false)
			{
			    
				$checkSubscriber->unsubscribe();
				
			}
		 
		}
		else
		{			
			if ((boolean)$subscribestatus === true) 
			{
                $this->contractssubscriberFactory->create()->subscribe($email);
				
            }
		
		}
		return;
	}
}