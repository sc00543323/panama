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
		$loginData = $this->getRequest()->getPostValue();
		
        // Get Website ID
        $websiteId  = $this->storeManager->getWebsite()->getWebsiteId();
		$customerexist = $this->customer->loadByEmail($loginData['email']);
		$customerexist->setWebsiteId($websiteId);
		
		//Fn+Ln+Dob internal blacklist check
        $formId = 'guest_checkout';
        $captchaModel = $this->_helper->getCaptcha($formId);
        $data = $this->captchaStringResolver->resolve($this->getRequest(), $formId);        
         if (!$captchaModel->isCorrect($this->captchaStringResolver->resolve($this->getRequest(), $formId))){
            $message = array('valid' => 0,'message'=> 'Incorrect CAPTCHA');
        }else{
            $todayDate = date('Y-m-d');
            $nameExplode = explode('/',$loginData['dob']);		
            $nameDob = '%'.$nameExplode[2].'-'.$nameExplode[1].'-'.$nameExplode[0].'%';
            
          /*endLogic for internal blacklist check*/
            if ($customerexist->getId()) {
                $message = array('valid' => 0,'message'=> 'Le compte existe déjà!!');
            /*}elseif(count($emailCollection) > 0){
                $message = array('valid' => 0,'message'=> 'You\'re email address in blacklist');*/
            }elseif(count($nameCollection) > 0){
                $message = array('valid' => 0,'message'=> 'You\'re firstname ,lastname and dob in blacklist');
            }elseif(count($IpCollection) > 0){
                $message = array('valid' => 0,'message'=> 'You\'re ip address in blacklist');
            } else {
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                $this->_scopeConfig = $objectManager->create('\Magento\Framework\App\Config\ScopeConfigInterface');
                $dobconfigvalue = $this->_scopeConfig->getValue('digicel_account/dobsettings/dobvalue', $storeScope);
                $birthday = $nameExplode[2].'-'.$nameExplode[1].'-'.$nameExplode[0];
                // $then will first be a string-date
                $then = strtotime($birthday);
                //The age to be over, over +18
                $min = strtotime('+'.$dobconfigvalue.' years', $then);                   
                if(time() < $min){
                    $message = array('valid' => 0,'message'=> 'Your DateOfBirth must be greater than %1 years');
                }else{            
                    // Instantiate object (this is the most important part)
                    $customer = $this->customerFactory->create();
                    $attribute = $this->eavConfig->getAttribute('customer', 'mobile_prefix');
                        $options = $attribute->getSource()->getAllOptions();
                        $label = '';
                        foreach ($options as $_option){
                            //print_r($_option);
                            if($_option['label'] == $loginData['mobile_prefix']){
                                $mobVal = $_option['value'];
                                break;
                            }
                        }
                        
                    $customer->setWebsiteId($websiteId);
                    $customer->setEmail($loginData['email']);
                    $customer->setFirstname($loginData['firstname']);
                    $customer->setLastname($loginData['lastname']);
                    $customer->setPassword($loginData['password']);
                    $customer->setPrefix($loginData['prefix']);
                    $customer->setDob(date("Y-m-d", strtotime($loginData['dob'])));
                    //$customer->setMobilePrefix($loginData['mobile_prefix']);
                    $customer->setMobilePrefix($mobVal);
                    $customer->setMobileNumber($loginData['mobile_number']);

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
                        $customercreate = $this->customer->load($customer->getId()); //2 is Customer ID
                        $customercreate->setWebsiteId($websiteId);				
                        // Load customer session				
                        $this->customersession->setCustomerAsLoggedIn($customercreate);
                        if ($this->getRequest()->getParam('is_subscribed', false)) {
                        $this->subscriberFactory->create()->subscribeCustomerById($customer->getId());
                        }
                       
                        $message = array('valid' => 1,'message'=> 'Account created successfully');
                    }else{
                        $message = array('valid' => 0,'message'=> 'Something went wrong');
                    }
                } 
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