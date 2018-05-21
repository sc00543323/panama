<?php

namespace Digicel\Portin\Controller\Terms;
use Magento\Framework\View\Result\PageFactory;


class ViewFile extends \Magento\Framework\App\Action\Action {

	protected $_storeManager;
	protected $resultPageFactory;
	protected $_scopeConfig;
	
	
    public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		PageFactory $resultPageFactory
	){
		parent::__construct($context);
		$this->_storeManager = $storeManager;
		$this->resultPageFactory = $resultPageFactory;
		$this->_scopeConfig = $scopeConfig;
	}

    public function execute() { 
		if($this->getRequest()->isAjax()) {
			$type = $this->getRequest()->getParam('type');
			if($type == 'billing') {
				$portin =  $this->_scopeConfig->getValue('terms_and_condition/file_upload/installation_billing', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);	
			}else if($type == 'general') {
				$portin =  $this->_scopeConfig->getValue('terms_and_condition/file_upload/general', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);	
			}else{
				$portin =  $this->_scopeConfig->getValue('terms_and_condition/file_upload/portin', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			}
			
		}
		echo $filename = 'pub/media/terms_and_condition/'.$portin;
			die;
    }
}
