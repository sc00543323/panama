<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Digicel\Customeraccount\Controller\Account;

use Magento\Customer\Model\Account\Redirect as AccountRedirect;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Helper\Address;
use Magento\Framework\UrlFactory;
use Magento\Customer\Model\Metadata\FormFactory;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Customer\Api\Data\RegionInterfaceFactory;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Customer\Model\Registration;
use Magento\Framework\Escaper;
use Magento\Customer\Model\CustomerExtractor;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\InputException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CreatePost extends \Magento\Customer\Controller\AbstractAccount
{
    /** @var AccountManagementInterface */
    protected $accountManagement;

    /** @var Address */
    protected $addressHelper;

    /** @var FormFactory */
    protected $formFactory;

    /** @var SubscriberFactory */
    protected $subscriberFactory;

    /** @var RegionInterfaceFactory */
    protected $regionDataFactory;

    /** @var AddressInterfaceFactory */
    protected $addressDataFactory;

    /** @var Registration */
    protected $registration;

    /** @var CustomerInterfaceFactory */
    protected $customerDataFactory;

    /** @var CustomerUrl */
    protected $customerUrl;

    /** @var Escaper */
    protected $escaper;

    /** @var CustomerExtractor */
    protected $customerExtractor;

    /** @var \Magento\Framework\UrlInterface */
    protected $urlModel;

    /** @var DataObjectHelper  */
    protected $dataObjectHelper;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var AccountRedirect
     */
    private $accountRedirect;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    private $cookieMetadataFactory;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\PhpCookieManager
     */
    private $cookieMetadataManager;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param AccountManagementInterface $accountManagement
     * @param Address $addressHelper
     * @param UrlFactory $urlFactory
     * @param FormFactory $formFactory
     * @param SubscriberFactory $subscriberFactory
     * @param RegionInterfaceFactory $regionDataFactory
     * @param AddressInterfaceFactory $addressDataFactory
     * @param CustomerInterfaceFactory $customerDataFactory
     * @param CustomerUrl $customerUrl
     * @param Registration $registration
     * @param Escaper $escaper
     * @param CustomerExtractor $customerExtractor
     * @param DataObjectHelper $dataObjectHelper
     * @param AccountRedirect $accountRedirect
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        AccountManagementInterface $accountManagement,
        Address $addressHelper,
        UrlFactory $urlFactory,
        FormFactory $formFactory,
        SubscriberFactory $subscriberFactory,
        RegionInterfaceFactory $regionDataFactory,
        AddressInterfaceFactory $addressDataFactory,
        CustomerInterfaceFactory $customerDataFactory,
        CustomerUrl $customerUrl,
        Registration $registration,
        Escaper $escaper,
        CustomerExtractor $customerExtractor,
        DataObjectHelper $dataObjectHelper,
        AccountRedirect $accountRedirect
    ) {
        $this->session = $customerSession;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->accountManagement = $accountManagement;
        $this->addressHelper = $addressHelper;
        $this->formFactory = $formFactory;
        $this->subscriberFactory = $subscriberFactory;
        $this->regionDataFactory = $regionDataFactory;
        $this->addressDataFactory = $addressDataFactory;
        $this->customerDataFactory = $customerDataFactory;
        $this->customerUrl = $customerUrl;
        $this->registration = $registration;
        $this->escaper = $escaper;
        $this->customerExtractor = $customerExtractor;
        $this->urlModel = $urlFactory->create();
        $this->dataObjectHelper = $dataObjectHelper;
        $this->accountRedirect = $accountRedirect;
        parent::__construct($context);
    }

    /**
     * Retrieve cookie manager
     *
     * @deprecated
     * @return \Magento\Framework\Stdlib\Cookie\PhpCookieManager
     */
    private function getCookieManager()
    {
        if (!$this->cookieMetadataManager) {
            $this->cookieMetadataManager = \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Framework\Stdlib\Cookie\PhpCookieManager::class
            );
        }
        return $this->cookieMetadataManager;
    }

    /**
     * Retrieve cookie metadata factory
     *
     * @deprecated
     * @return \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    private function getCookieMetadataFactory()
    {
        if (!$this->cookieMetadataFactory) {
            $this->cookieMetadataFactory = \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory::class
            );
        }
        return $this->cookieMetadataFactory;
    }

    /**
     * Add address to customer during create account
     *
     * @return AddressInterface|null
     */
    protected function extractAddress()
    {
        if (!$this->getRequest()->getPost('create_address')) {
            return null;
        }

        $addressForm = $this->formFactory->create('customer_address', 'customer_register_address');
        $allowedAttributes = $addressForm->getAllowedAttributes();

        $addressData = [];

        $regionDataObject = $this->regionDataFactory->create();
        foreach ($allowedAttributes as $attribute) {
            $attributeCode = $attribute->getAttributeCode();
            $value = $this->getRequest()->getParam($attributeCode);
            if ($value === null) {
                continue;
            }
            switch ($attributeCode) {
                case 'region_id':
                    $regionDataObject->setRegionId($value);
                    break;
                case 'region':
                    $regionDataObject->setRegion($value);
                    break;
                default:
                    $addressData[$attributeCode] = $value;
            }
        }
        $addressDataObject = $this->addressDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $addressDataObject,
            $addressData,
            '\Magento\Customer\Api\Data\AddressInterface'
        );
        $addressDataObject->setRegion($regionDataObject);

        $addressDataObject->setIsDefaultBilling(
            $this->getRequest()->getParam('default_billing', false)
        )->setIsDefaultShipping(
            $this->getRequest()->getParam('default_shipping', false)
        );
        return $addressDataObject;
    }
	  public function logCreate($fileName, $data) {
        $writer = new \Zend\Log\Writer\Stream(BP . "$fileName");
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info($data);
    }

    /**
     * Create customer account action
     *
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
		
	 //$data = $this->getRequest()->getParams();
	// print_r($data);exit;
	 $mob = $this->getRequest()->getParam('mobile_number');
	 $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	 $resultRedirect = $this->resultRedirectFactory->create();
	 
	  /*Logic for internal blacklist check*/
	  //Email internal blacklist check
		/*$emailCollection = $objectManager->create('\Techm\Blacklist\Model\ResourceModel\Customer\Collection');
		$emailCollection->addFieldToFilter('Email', ['eq' => $this->getRequest()->getParam('email')]);
		if(count($emailCollection) > 0){
			$this->messageManager->addError(__('You\'re email address in blacklist'));  
			//$url = $this->urlModel->getUrl('create', ['_secure' => true]);
            $resultRedirect->setUrl($this->_redirect->error($url));
			return $resultRedirect;
			
		}*/
		//Fn+Ln+Dob internal blacklist check
		//$data = $this->getRequest()->getParams();
		//print_r($data);exit;
		/*
		$todayDate = date("Y-m-d");
		$nameExplode = explode('/',$this->getRequest()->getParam('dob'));		
		$nameDob = '%'.$nameExplode[2].'-'.$nameExplode[1].'-'.$nameExplode[0].'%';
		$nameCollection = $objectManager->create('\Techm\Blacklist\Model\ResourceModel\Customer\Collection');
		$nameCollection->addFieldToFilter('firstname', ['eq' => $this->getRequest()->getParam('firstname')])
									 ->addFieldToFilter('lastname', ['eq' => $this->getRequest()->getParam('lastname')])
									 ->addFieldToFilter('dob', array('like' => $nameDob))
									 ->addFieldToFilter('removed_from_blacklist', ['gteq' => $todayDate]);
		if(count($nameCollection) > 0){
			$this->messageManager->addError(__('You\'re firstname ,lastname and dob in blacklist'));  
			$url = $this->urlModel->getUrl(**create', ['_secure' => true]);
            $resultRedirect->setUrl($this->_redirect->error($url));
			return $resultRedirect;
			
		}
		$IpCollection = $objectManager->create('\Techm\Blacklist\Model\ResourceModel\Customer\Collection');
		$IpCollection->addFieldToFilter('ipaddress', ['eq' => $this->getIp()])
					 ->addFieldToFilter('removed_from_blacklist', ['gteq' => $todayDate]);
		if(count($IpCollection) > 0){
			$this->messageManager->addError(__('You\'re ip address in blacklist'));  
			$url = $this->urlModel->getUrl('*create', ['_secure' => true]);
            $resultRedirect->setUrl($this->_redirect->error($url));
			return $resultRedirect;
		}   		
	  /*endLogic for internal blacklist check*/
	 
	 //partner Subscription*/
	  
	  
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect *
		if($this->getRequest()->getParam('dob') == '')
			 {
				$sand = $this->getRequest()->getPostValue();				
				$this->session->setCustomerFormData($this->getRequest()->getPostValue());		
				$this->messageManager->addError(__('The Date of Birth is required.'));  
				$this->_redirect('customer/account/create',['_secure' => true]);
                return;
			 }
			 
			 //code
			 $dobval = $this->getRequest()->getParam('dob');
			 $dobval = str_replace('/', '-', $dobval);
		     $dobval=date('Y-m-d',strtotime($dobval));
			 $diff = (date('Y') - date('Y',strtotime($dobval)));
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
			$this->_scopeConfig = $objectManager->create('\Magento\Framework\App\Config\ScopeConfigInterface');
            $dobconfigvalue = $this->_scopeConfig->getValue('digicel_account/dobsettings/dobvalue', $storeScope);		
			
			if($diff <= $dobconfigvalue)
			 {
				 
				   $cust = $this->getRequest()->getPostValue();		 
				   $this->session->setCustomerFormData($this->getRequest()->getPostValue());
				 
				  $message = __('Your DateOfBirth must be greater than %1 years.',$dobconfigvalue); 
				
				$this->messageManager->addError($message);  
				$this->_redirect('customer/account/create',['_secure' => true]);
                return;				
			 }
			
			//code*/
			 
			 
        if ($this->session->isLoggedIn() || !$this->registration->isAllowed()) {
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }

        if (!$this->getRequest()->isPost()) {
            $url = $this->urlModel->getUrl('*/*/create', ['_secure' => true]);
            $resultRedirect->setUrl($this->_redirect->error($url));
            return $resultRedirect;
        }

        $this->session->regenerateId();
		
		
        try {
			
            $address = $this->extractAddress();
            $addresses = $address === null ? [] : [$address];
            $customer = $this->customerExtractor->extract('customer_account_create', $this->_request);
			$cedulla = $this->getRequest()->getParam('cedulla');
			$passport = $this->getRequest()->getParam('passport');
			$mob = $this->getRequest()->getParam('mobile_number');
			$todayDate = date("Y-m-d");
			$dobValue = $this->getRequest()->getParam('dob');
			//print_r($dobExplode);exit;
            //$customer->setDob($dobval);
			$customer->setData('cedulla',$cedulla);
			$customer->setData('passport',$passport);
			$customer->setData('mobile_number',$mob);
			$customer->setData('dob',$dobValue);
			$password = $this->getRequest()->getParam('password');
			
            $confirmation = $this->getRequest()->getParam('password_confirmation');
            $redirectUrl = $this->session->getBeforeAuthUrl();
			
            $this->checkPasswordConfirmation($password, $confirmation);

            $customer = $this->accountManagement
                ->createAccount($customer, $password, $cedulla, $passport, $mob, $dobValue, $redirectUrl);
				
				//print_r($customer);exit;

           /*  if ($this->getRequest()->getParam('is_subscribed', false)) {
                $this->subscriberFactory->create()->subscribeCustomerById($customer->getId());
            }
			if ($this->getRequest()->getParam('is_partner_subscribed', false)) {
                $this->partnersubscriberFactory->create()->subscribeCustomerById($customer->getId());
            }
			if ($this->getRequest()->getParam('is_contracts_subscribed', false)) {
                $this->contractssubscriberFactory->create()->subscribeCustomerById($customer->getId());
            } */

            $this->_eventManager->dispatch(
                'customer_register_success',
                ['account_controller' => $this, 'customer' => $customer]
            );

            $confirmationStatus = $this->accountManagement->getConfirmationStatus($customer->getId());
            if ($confirmationStatus === AccountManagementInterface::ACCOUNT_CONFIRMATION_REQUIRED) {
                $email = $this->customerUrl->getEmailConfirmationUrl($customer->getEmail());
                // @codingStandardsIgnoreStart
                $this->messageManager->addSuccess(
                    __(
                        'You must confirm your account. Please check your email for the confirmation link or <a href="%1">click here</a> for a new link.',
                        $email
                    )
                );
                // @codingStandardsIgnoreEnd
                $url = $this->urlModel->getUrl('*/*/index', ['_secure' => true]);
                $resultRedirect->setUrl($this->_redirect->success($url));
            } else {
                $this->session->setCustomerDataAsLoggedIn($customer);
                $this->messageManager->addSuccess($this->getSuccessMessage());
                
                //redirect to checkout page if successful
                  $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

                 $checkouturl = $objectManager->get('Magento\Checkout\Model\Session')->getBeforeAuthUrl();
                
                
                if($checkouturl){
                    $resultRedirect = $this->resultRedirectFactory->create();
                    $resultRedirect->setUrl($this->_redirect->success($checkouturl));
                    return $resultRedirect;
                }else{
                     $requestedRedirect = $this->accountRedirect->getRedirectCookie();
                        if (!$this->scopeConfig->getValue('customer/startup/redirect_dashboard') && $requestedRedirect) {
                            $resultRedirect->setUrl($this->_redirect->success($requestedRedirect));
                            $this->accountRedirect->clearRedirectCookie();
                            return $resultRedirect;
                        }
                    $resultRedirect = $this->accountRedirect->getRedirect();
                }
            }
            if ($this->getCookieManager()->getCookie('mage-cache-sessid')) {
                $metadata = $this->getCookieMetadataFactory()->createCookieMetadata();
                $metadata->setPath('/');
                $this->getCookieManager()->deleteCookie('mage-cache-sessid', $metadata);
            }

            return $resultRedirect;
        } catch (StateException $e) {
			
            $url = $this->urlModel->getUrl('customer/account/forgotpassword');
			
			 
            // @codingStandardsIgnoreStart
            $message = __(
                'There is already an account with this email address. If you are sure that it is your email address, <a href="%1">click here</a> to get your password and access your account.',
                $url
            );
            // @codingStandardsIgnoreEnd
            $this->messageManager->addError($message);
        } catch (InputException $e) {
            $this->messageManager->addError($this->escaper->escapeHtml($e->getMessage()));
            foreach ($e->getErrors() as $error) {
                $this->messageManager->addError($this->escaper->escapeHtml($error->getMessage()));
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addError($this->escaper->escapeHtml($e->getMessage()));
        } catch (\Exception $e) {            
            $this->messageManager->addException($e, __('We can\'t save the customer.'));
        }

		$cust = $this->getRequest()->getPostValue();		
			
				$cust['lastname'] = $cust['lastname'];
                $cust['firstname'] = $cust['firstname'];
				$cust['cedulla'] = $cust['cedulla'];
				$cust['passport'] = $cust['passport'];
				$cust['mobile_number'] = $cust['mobile_number'];
				//$cust['dob'] = $cust['dob'];
				
				//$time = strtotime($cust['dob']);
				//$newformat = date('d/m/Y',$time);
				//$cust['dob'] = $newformat;
				$this->session->setCustomerFormData($cust); 
		
        $defaultUrl = $this->urlModel->getUrl('*/*/create', ['_secure' => true]);
        $resultRedirect->setUrl($this->_redirect->error($defaultUrl));
        return $resultRedirect;
    }

    /**
     * Make sure that password and password confirmation matched
     *
     * @param string $password
     * @param string $confirmation
     * @return void
     * @throws InputException
     */
    protected function checkPasswordConfirmation($password, $confirmation)
    {
        if ($password != $confirmation) {
            throw new InputException(__('Please make sure your passwords match.'));
        }
    }

    /**
     * Retrieve success message
     *
     * @return string
     */
    protected function getSuccessMessage()
    {
        if ($this->addressHelper->isVatValidationEnabled()) {
            if ($this->addressHelper->getTaxCalculationAddressType() == Address::TYPE_SHIPPING) {
                // @codingStandardsIgnoreStart
                $message = __(
                    'If you are a registered VAT customer, please <a href="%1">click here</a> to enter your shipping address for proper VAT calculation.',
                    $this->urlModel->getUrl('customer/address/edit')
                );
                // @codingStandardsIgnoreEnd
            } else {
                // @codingStandardsIgnoreStart
                $message = __(
                    'If you are a registered VAT customer, please <a href="%1">click here</a> to enter your billing address for proper VAT calculation.',
                    $this->urlModel->getUrl('customer/address/edit')
                );
                // @codingStandardsIgnoreEnd
            }
        } else {
            $message = __('Thank you for registering with %1.', $this->storeManager->getStore()->getFrontendName());
        }
        return $message;
    }
	public function getIp(){
        $ip = $_SERVER['REMOTE_ADDR'];     
        if($ip){
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            return $ip;
        }
        // There might not be any data
        return false;
    }
}