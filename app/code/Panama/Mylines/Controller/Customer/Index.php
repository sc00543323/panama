<?php 
namespace Panama\Mylines\Controller\Customer;  
class Index extends \Magento\Framework\App\Action\Action {
/**
 * @param \Magento\Framework\App\Action\Context $context
 */
protected $customerSession;
 public function __construct(
 \Magento\Framework\App\Action\Context $context,
 \Magento\Customer\Model\Session $customerSession,
 array $data = []) 
 {       
		$this->_customerSession =$customerSession;
		parent::__construct($context);
    } 
   
 public function execute() { 
 
 $customerSession = $this->_customerSession;
  if ($customerSession->isLoggedIn()) {
    $this->_view->loadLayout(); 
    $this->_view->renderLayout();
   }
	else{
	  $this->_redirect('customer/account/login');
	}  
  } 
  
} 