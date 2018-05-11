<?php

/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Digicel\Login\Controller\Login;

use Magento\Customer\Model\Account\Redirect as AccountRedirect;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Framework\Exception\EmailNotConfirmedException;
use Magento\Framework\Exception\AuthenticationException;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\State\UserLockedException;
use Magento\Framework\App\Config\ScopeConfigInterface;
//use Techm\Loginrestrict\Helper\Data as CustomCooike;

//use Magento\Framework\Controller\ResultFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class LoginPost extends \Magento\Framework\App\Action\Action {

    /** @var AccountManagementInterface */
    protected $customerAccountManagement;

    /** @var Validator */
    protected $formKeyValidator;

    /**
     * @var AccountRedirect
     */
    protected $accountRedirect;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    private $cookieMetadataFactory;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\PhpCookieManager
     */
    private $cookieMetadataManager;
    protected $resultFactory;
	
	/**
     * @var \Techm\Loginrestrict\Helper\Data
     */
    //private $getCookiedata;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param AccountManagementInterface $customerAccountManagement
     * @param CustomerUrl $customerHelperData
     * @param Validator $formKeyValidator
     * @param AccountRedirect $accountRedirect
     */
    public function __construct(
		Context $context,
		Session $customerSession,
		AccountManagementInterface $customerAccountManagement,
		CustomerUrl $customerHelperData,
		Validator $formKeyValidator,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
		\Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
		//CustomCooike $getCookiedata,
		AccountRedirect $accountRedirect
    ) {
        $this->session = $customerSession;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->customerUrl = $customerHelperData;
        $this->formKeyValidator = $formKeyValidator;
        $this->accountRedirect = $accountRedirect;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultRawFactory = $resultRawFactory;
		//$this->getCookiedata = $getCookiedata;
        parent::__construct($context);
    }

    /**
     * Login post action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute() {

		$credentials = null;
        $httpBadRequestCode = 400;

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        try {
            $credentials = $this->getRequest()->getParams();
        } catch (\Exception $e) {
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }
        if (!$credentials || $this->getRequest()->getMethod() !== 'POST' || !$this->getRequest()->isXmlHttpRequest()) {
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }

        $login = $credentials;
        if (!empty($login['username']) && !empty($login['password'])) {
			 
            try {
                $customer = $this->customerAccountManagement->authenticate($login['username'], $login['password']);
                $this->session->setCustomerDataAsLoggedIn($customer);
                $this->session->regenerateId();               
                $response = [
                    'errors' => false,
                    'message' => __('Login successful.')
                ];
				
               
            } catch (EmailNotConfirmedException $e) {
                $value = $this->customerUrl->getEmailConfirmationUrl($login['username']);
                $message = __(
                        'This account is not confirmed. <a href="%1">Click here</a> to resend confirmation email.', $value
                );
                $response = [
                    'errors' => true,
                    'message' => $message
                ];
                $this->session->setUsername($login['username']);
            } catch (UserLockedException $e) {
                $message = __(
                        'The account is locked. Please wait and try again or contact %1.', $this->getScopeConfig()->getValue('contact/email/recipient_email')
                );
                $response = [
                    'errors' => true,
                    'message' => $message
                ];
                $this->session->setUsername($login['username']);
            } catch (AuthenticationException $e) {
                $message = __('Invalid login or password.');
                $response = [
                    'errors' => true,
                    'message' => $message
                ];
                $this->session->setUsername($login['username']);
            } catch (LocalizedException $e) {
                $message = $e->getMessage();
                $response = [
                    'errors' => true,
                    'message' => $message
                ];
                $this->session->setUsername($login['username']);
            } catch (\Exception $e) {
                 // PA DSS violation: throwing or logging an exception here can disclose customer password
                 $response = [
                    'errors' => true,
                    'message' => __('An unspecified error occurred. Please contact us for assistance.')
                ];
            }
        } else {
			$response = [
                    'errors' => true,
                    'message' => __('A login and a password are required.')
                ];
        }

        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);
    }

}
