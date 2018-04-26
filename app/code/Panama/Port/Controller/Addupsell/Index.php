<?php

namespace Panama\Port\Controller\Addupsell;
use Magento\Framework\View\Result\PageFactory;


class Index extends \Magento\Framework\App\Action\Action {

	protected $_storeManager;
	protected $_categoryRepository;
	protected $_url;
	protected $_checkoutSession;
	protected $formKey;
	protected $resultPageFactory;
	protected $_cart;
	protected $productRepository;
	
    public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
		\Magento\Framework\UrlInterface $url,
		\Magento\Catalog\Model\Session $checkoutSession,
		\Magento\Framework\Data\Form\FormKey $formKey,
		PageFactory $resultPageFactory,
		\Magento\Checkout\Model\Cart $cart,
    	\Magento\Catalog\Api\ProductRepositoryInterface $productRepository
	){
		parent::__construct($context);
		$this->_storeManager = $storeManager;
        $this->_categoryRepository = $categoryRepository;
		$this->_url = $url;
		$this->_checkoutSession = $checkoutSession;
		$this->formKey = $formKey;
		$this->resultPageFactory = $resultPageFactory;
		$this->_cart = $cart;
    	$this->productRepository = $productRepository;
	}

    public function execute() {
		$resultPage = $this->resultPageFactory->create();
	    //check portabality in session data
		
		$portSessionVal           = $this->_checkoutSession->getPort();
		$currentServiceSessionVal = $this->_checkoutSession->getCurrentService();
		$buySmartphoneSessionVal  = $this->_checkoutSession->getBuySmartphone();
		
		if(isset($portSessionVal) && $portSessionVal != ''){
			$isPortable      = $portSessionVal;
			$currentService  = $currentServiceSessionVal;
			$isSmartphone    = $buySmartphoneSessionVal;
			$isContract      = '';
		}else{
			$isPortable     = $this->_checkoutSession->setPort($this->getRequest()->getParam('portable'));
			$currentService = $this->_checkoutSession->setCurrentService($this->getRequest()->getParam('service'));
			$isContract     = $this->_checkoutSession->setBuySmartphone($this->getRequest()->getParam('agree_condition'));
			$isSmartphone   = '';
		}
		if($this->getRequest()->getParam('port_remove') == 'no') {
			$isPortable = $this->_checkoutSession->setPort('no');
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
      
		/*$allItems = $this->_cart->getQuote()->getAllVisibleItems();
			foreach ($allItems as $item) {
				if($bundleId ==  $item->getProductId() && !$item->getIsPortable() && !$item->getCurrentService() && !$item->getIsSmartphone()) {
					$item->setIsPortable($isPortable);
					$item->setCurrentService($currentService);
					$item->setIsSmartphone($isSmartphone);
					$item->save();
				}
			}*/
        return $resultPage;
    }
}
