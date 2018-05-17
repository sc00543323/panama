<?php

namespace Digicel\Portin\Controller\Terms;
use Magento\Framework\View\Result\PageFactory;


class Getname extends \Magento\Framework\App\Action\Action {

	protected $_storeManager;
	protected $resultPageFactory;
	
    public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		PageFactory $resultPageFactory
	){
		parent::__construct($context);
		$this->_storeManager = $storeManager;
		$this->resultPageFactory = $resultPageFactory;
	}

    public function execute() {
		
    return 'Hi';



		
		
    }
}
