<?php
namespace Digicel\Customeraccount\Block;
use Magento\Framework\View\Element\Template;

class Cedulla extends Template
{
    protected $customerSession;
	
	/**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->customerSession = $customerSession;
    }

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
	public function getMyCustomMethod()
    {
       
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$customerSession = $objectManager->create("Magento\Customer\Model\Session");
		if($customerSession->isLoggedIn()){
		  $customerId = $customerSession->getCustomerId();
		}
		
		
		/*$customerData = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);
		foreach($customerData as $cust){
			echo "hi -"; echo "<br>";
			$firstname = $cust['firstname'];
			echo $firstname;
		}
		exit;*/
		//return '<b>I Am From MyCustomMethod</b>';
    }
}