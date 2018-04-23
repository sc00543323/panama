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
      $params = array(
                'form_key' => $this->formKey->getFormKey(),
                'product' =>$this->getRequest()->getParam('product'),
                'qty'   =>1,
                'super_attribute' => $this->getRequest()->getParam('super_attribute'),
            );
$_product = $this->productRepository->getById($this->getRequest()->getParam('product'));
$this->_cart->addProduct($_product,$params);
$this->_cart->save();
//add Upsell Product
$this->_redirect('customcatalog/cart/add/', array('id' => $this->getRequest()->getParam('upsell_id')));



          
        return $resultPage;

    }
}
