<?php

namespace Digicel\Portin\Controller\Terms;
use Magento\Framework\View\Result\PageFactory;


class Downloadgeneral extends \Magento\Framework\App\Action\Action {

	protected $_storeManager;
	protected $resultPageFactory;
	protected $_scopeConfig;
	protected $_directory;
	
    public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\Magento\Framework\Filesystem\DirectoryList $directory,
		PageFactory $resultPageFactory
	){
		parent::__construct($context);
		$this->_storeManager = $storeManager;
		$this->resultPageFactory = $resultPageFactory;
		$this->directory = $directory;
		$this->_scopeConfig = $scopeConfig;
	}

    public function execute() {
    
        $fileName =  $this->_scopeConfig->getValue('terms_and_condition/file_upload/general', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);	
		if($fileName){
			$file = $this->directory->getPath("media")."/terms_and_condition/".$fileName; 
			header('Pragma: public');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Cache-Control: private', false); // required for certain browsers 
			header('Content-Type: application/force-download');
			header('Content-Disposition: attachment; filename="'. basename($file) . '";');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: ' . filesize($file));
			readfile($file);
			echo 'success'; die;
		}else{
			echo 'file not present';die;
		}

		
		
    }
}
