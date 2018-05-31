<?php
namespace Digicel\Login\Controller\Login;
 class Saveaddress extends \Magento\Framework\App\Action\Action {

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
	
	protected $addressRepository;
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
		\Magento\Customer\Model\Session $customersession,
		\Magento\Customer\Model\Address $addressRepository
		
    ) {
        $this->storeManager     = $storeManager;
        $this->customerFactory  = $customerFactory;
		$this->resultJsonFactory = $resultJsonFactory;
		$this->customersession = $customersession;
		$this->addressRepository = $addressRepository;
        parent::__construct($context);

    }
    public function execute()
    {
		$loginData = $this->getRequest()->getPostValue();
		// Get Website ID
        $websiteId  = $this->storeManager->getWebsite()->getWebsiteId();
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $message = '';
		$customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load($this->customersession->getId());
		$address_data = $customerObj->getAddresses();
		foreach ($address_data as $address)
		{
			$addressData = $address->toArray();
			$addressId = $addressData['entity_id'];
		}


if( count($address_data) !=0){
	
	$address = $this->addressRepository->load($addressId);
	
       $address ->setFirstname($loginData['firstname'])
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
                ->setIsDefaultShipping('1');
				try{
                $this->addressRepository->save($address);
					$message = array('valid' => 1,'message'=> 'Address Updated successfully');
                }catch (\Exception $e) {
                        $message = array('valid' => 0,'message'=> $e->getMessage());
						print_r($message);
					}
	
		}else{
		if($loginData){
		$addresCollection = $objectManager->get('\Magento\Customer\Model\AddressFactory');
		$address = $addresCollection->create();

				$address->setCustomerId($this->customersession->getId())
					->setFirstname($loginData['firstname'])
					->setLastname($loginData['lastname'])
					->setCountryId('PA')
					->setRegion($loginData['Province'])
					->setPostcode($loginData['Zone_Barrio'])
					->setCity($loginData['District'])
					->setTelephone($loginData['mobile_number'])
					->setDirectionIndications($loginData['Direction_indications'])
					->setAlternateMobileNumber($loginData['alt_mobile'])
					->setStreet($loginData['Address'])
					->setIsDefaultBilling('1')
					->setIsDefaultShipping('1')
					->setSaveInAddressBook('1');

				try{
					$address->save();
						$message = array('valid' => 1,'message'=> 'Address Created successfully');
					}catch (\Exception $e) {
							$message = array('valid' => 0,'message'=> $e->getMessage());
							print_r($message);
						}
						
			}
		}
		$resultJson = $this->resultJsonFactory->create();
	    return $resultJson->setData($message);
    }
}