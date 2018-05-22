<?php

namespace Panama\Port\Controller\Addupsell;
use Magento\Framework\View\Result\PageFactory;


class Index extends \Magento\Framework\App\Action\Action {

	protected $_storeManager;
	protected $_categoryRepository;
	protected $_url;
	protected $_catalogSession;
	protected $formKey;
	protected $resultPageFactory;
	protected $_cart;
	protected $productRepository;
	
    public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
		\Magento\Framework\UrlInterface $url,
		\Magento\Catalog\Model\Session $catalogSession,
		\Magento\Framework\Data\Form\FormKey $formKey,
		PageFactory $resultPageFactory,
		\Magento\Checkout\Model\Cart $cart,
    	\Magento\Catalog\Api\ProductRepositoryInterface $productRepository
	){
		parent::__construct($context);
		$this->_storeManager = $storeManager;
        $this->_categoryRepository = $categoryRepository;
		$this->_url = $url;
		$this->_catalogSession = $catalogSession;
		$this->formKey = $formKey;
		$this->resultPageFactory = $resultPageFactory;
		$this->_cart = $cart;
    	$this->productRepository = $productRepository;
	}

    public function execute() {
		$resultPage = $this->resultPageFactory->create();
	    //check portabality in session data
		
		$portSessionVal           = $this->_catalogSession->getPort();
		$currentServiceSessionVal = $this->_catalogSession->getCurrentService();
		$buySmartphoneSessionVal  = $this->_catalogSession->getBuySmartphone();
		
		if(isset($portSessionVal) && $portSessionVal != ''){
			$isPortable      = $portSessionVal;
			$currentService  = $currentServiceSessionVal;
			$isSmartphone    = $buySmartphoneSessionVal;
			$isContract      = '';
		}else{
			$isPortable     = $this->_catalogSession->setPort($this->getRequest()->getParam('portable'));
			$currentService = $this->_catalogSession->setCurrentService($this->getRequest()->getParam('service'));
			if($this->getRequest()->getParam('agree_condition') == 'contract') {
				$isContract     = $this->_catalogSession->setContract('yes');
			} else {
				$isContract     = $this->_catalogSession->setContract('no');
			}			
		}
		if($this->getRequest()->getParam('port_remove') == 'no') {
			$isPortable = $this->_catalogSession->setPort('no');
		}
		if($this->getRequest()->getParam('contract_remove') == 'no') {
			$isContract = $this->_catalogSession->setContract('no');
		}
		//add Smartphone Device	  
		$params = array(
			'form_key' => $this->formKey->getFormKey(),
			'product'  =>$this->getRequest()->getParam('product'),
			'qty'      =>1,
			'super_attribute' => $this->getRequest()->getParam('super_attribute'),
            );
		$_product = $this->productRepository->getById($this->getRequest()->getParam('product'));
		$this->_cart->addProduct($_product,$params);
		$this->_cart->save();
		
		//add Upsell Product
		$bundleId = $this->getRequest()->getParam('upsell_id');
		$this->_redirect('customcatalog/cart/add/', array('id' => $bundleId));
        return $resultPage;
    }
}
