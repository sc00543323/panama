<?php
namespace Digicel\Login\Controller\Login;
use Magento\Newsletter\Model\SubscriberFactory;
 class CreatePost extends \Magento\Framework\App\Action\Action {

    protected $storeManager;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;
	
	 /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;


    protected $customer;	
	
    /**
     * @param \Magento\Framework\App\Action\Context      $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\CustomerFactory    $customerFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
		\Magento\Customer\Model\Customer $customercreate,
		\Magento\Customer\Model\Session $customersession,
		\Magento\Customer\Model\Customer $customer,
		SubscriberFactory $subscriberFactory,
		\Magento\Eav\Model\Config $eavConfig,
        \Magento\Captcha\Helper\Data $helper,
        \Magento\Captcha\Observer\CaptchaStringResolver $captchaStringResolver
		
    ) {
        $this->storeManager     = $storeManager;
        $this->customerFactory  = $customerFactory;
		$this->resultJsonFactory = $resultJsonFactory;
		$this->customercreate = $customercreate;
		$this->customersession = $customersession;
		$this->customer	= $customer;
		$this->subscriberFactory = $subscriberFactory;
		$this->eavConfig = $eavConfig;
        $this->_helper = $helper;
        $this->captchaStringResolver = $captchaStringResolver;
        parent::__construct($context);

    }
    public function execute()
    {
		$loginData = $this->getRequest()->getPostValue(); //print_r($loginData); die;
		// Get Website ID        
        $websiteId  = $this->storeManager->getWebsite()->getWebsiteId();
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerexist = $objectManager->create('Magento\Customer\Model\Customer');
        $customerexist->setWebsiteId($websiteId); 
        $customerexist->loadByEmail($loginData['email']);
        /*Check Customer Already Exisst*/
            if ($customerexist->getId()) {
                $message = array('valid' => 0,'message'=> 'The account already exists !!');
            } else {
				// Instantiate object (this is the most important part)
				$customer = $this->customerFactory->create();
				
					$attribute = $this->eavConfig->getAttribute('customer', 'mobile_prefix');
					$options = $attribute->getSource()->getAllOptions();
					$label = '';
					foreach ($options as $_option){
						//print_r($_option);
						if($_option['value'] == $loginData['mobile_prefix']){
							$mobVal = $_option['value'];
							break;
						}
					}
					
			/*  Cedula Number Combine */		
			$cedula_value = $loginData['cedulaProvince']."-".$loginData['cedulaLetters']."-".$loginData['cedulaItake']."-".$loginData['cedulaSeat'];
			
                        
                    $customer->setWebsiteId($websiteId);
                    $customer->setEmail($loginData['email']);
                    $customer->setFirstname($loginData['firstname']);
                    $customer->setLastname($loginData['lastname']);
                    $customer->setPassword($loginData['password']);
                    $customer->setDob(date("Y-m-d", strtotime($loginData['dob'])));
					$customer->setMobilePrefix($mobVal);
                    $customer->setMobileNumber($loginData['mobile_number']);
					$customer->setAltMobileNumber($loginData['alt_mobile']);
					$customer->setCedulla($cedula_value);
					$customer->setPassport($loginData['cedulaPassport']);
					$customer->setMobileNumber($loginData['is_subscribed']);
					$customer->setMobileNumber($loginData['is_partner_subscribed']);
					$customer->setMobileNumber($loginData['is_contracts_subscribed']);
					
					$customer->setAddresses(null);

                    $storeId = $this->storeManager->getWebsite($websiteId)->getDefaultStore()->getId();
                    $customer->setStoreId($storeId);

                    $storeName = $this->storeManager->getStore($customer->getStoreId())->getName();
                    $customer->setCreatedIn($storeName);
                                
                    try{
                        $customer->save();		
                        $customer->sendNewAccountEmail();
                    }catch (\Exception $e) {
                        $message = array('valid' => 0,'message'=> $e->getMessage());
					}
					if($customer->getId() != ''){				
                        // Load customer
                        $customercreate = $this->customer->load($customer->getId()); 
                        $customercreate->setWebsiteId($websiteId);				
                        // Load customer session				
                        $this->customersession->setCustomerAsLoggedIn($customercreate);
                        if ($this->getRequest()->getParam('is_subscribed', false)) {
                        $this->subscriberFactory->create()->subscribeCustomerById($customer->getId());
						
						
			$addresss = $objectManager->get('\Magento\Customer\Model\AddressFactory');
            $address = $addresss->create();

            $address->setCustomerId($customer->getId())
                ->setFirstname($loginData['firstname'])
                ->setLastname($loginData['lastname'])
                ->setCountryId('PA')
				->setRegion($loginData['Province_val'])
                ->setPostcode($loginData['Zone_Barrio_val'])
                ->setCity($loginData['District_val'])
                ->setTelephone($loginData['mobile_number'])
				->setDirectionIndications($loginData['Direction_indications'])
				->setAlternateMobileNumber($loginData['alt_mobile'])
                ->setStreet(array($loginData['Address']))
				->setIsDefaultBilling('1')
                ->setIsDefaultShipping('1')
                ->setSaveInAddressBook('1');

            try{
                $address->save();		
                }catch (\Exception $e) {
                        $message = array('valid' => 0,'message'=> $e->getMessage());
						
					}	
                }
                       
                        $message = array('valid' => 1,'message'=> 'Account created successfully');
                    }else{
                        $message = array('valid' => 0,'message'=> 'Something went wrong');
                    }
                } 
           
        $resultJson = $this->resultJsonFactory->create();
	    return $resultJson->setData($message);
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