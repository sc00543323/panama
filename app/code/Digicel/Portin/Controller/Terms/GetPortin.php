<?php

namespace Digicel\Portin\Controller\Terms;
use Magento\Framework\View\Result\PageFactory;


class GetPortin extends \Magento\Framework\App\Action\Action {

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
    
		$portin =  $this->_scopeConfig->getValue('terms_and_condition/file_upload/portin', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);	
		echo $filename = 'pub/media/test/'.$portin;
		die;
		
    }
}
