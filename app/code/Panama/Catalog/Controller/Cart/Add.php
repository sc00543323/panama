<?php
namespace Panama\Catalog\Controller\Cart;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Data\Form\FormKey;
use Magento\Catalog\Model\ProductFactory;
use Magento\Checkout\Model\Cart;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Controller\ResultFactory; 
use Magento\Framework\UrlInterface;

class Add extends \Magento\Framework\App\Action\Action
{
	/**
     * @var Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;
	/**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Magento\Framework\Data\Form\FormKey
     */
    protected $formKey;
    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
	protected $_cart;
	protected $_productRepository;
	protected $_storeManager;
	protected $_catalogSession;
	
    public function __construct(
		Context $context,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Catalog\Model\Session $catalogSession,
		FormKey $formKey,
		ProductFactory $productFactory,
		Cart $cart,
		ProductRepository $productRepository,
		PageFactory $resultPageFactory
	){
		parent::__construct($context);
		$this->formKey = $formKey;
		$this->resultPageFactory = $resultPageFactory;
		$this->_productFactory = $productFactory;
		$this->_cart = $cart;
		$this->_productRepository = $productRepository;
		$this->_storeManager = $storeManager;
		$this->_catalogSession = $catalogSession;
	}
 
    /**
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
		try {
			$productId = $this->getRequest()->getParam('id');//Bundle product id
			if(!$productId) {
				$productId = $this->_productFactory->create()->getIdBySku($this->getRequest()->getParam('sku'));
				if(!$productId) {
					throw new \Magento\Framework\Exception\LocalizedException(__('Invalid Product.'));
				}
			}
			$product = $this->_productRepository->getById($productId);
			
			$portPrdSessionVal =$this->_catalogSession->getPortProductId();
			$portSessionVal = $this->_catalogSession->getPort();
			$currentServiceSessionVal = $this->_catalogSession->getCurrentService();
			$buySmartphoneSessionVal = $this->_catalogSession->getBuySmartphone();
			
			if($product->getTypeId() == 'bundle'){
				$bundledOptions = $this->getBundleProductOptionsData($productId); // All option of the bundled product
				$resultPage = $this->resultPageFactory->create();
				$params = array(
					'form_key' => $this->formKey->getFormKey(),
					'product' => $productId,//product Id
					'related_product' => null,
					'qty'   =>1,//quantity of product
					'bundle_option'=> $bundledOptions,
				);
			}else{
				$params = array(
					'form_key' => $this->formKey->getFormKey(),
					'product' => $productId,//product Id
					'related_product' => null,
					'qty'   =>1,//quantity of product
				);
			}				
			$this->_cart->addProduct($product, $params);
			$this->_cart->save();
                    
			$allItems = $this->_cart->getQuote()->getAllItems();
			foreach ($allItems as $item) {
				if($productId ==  $item->getProductId() && !$item->getIsPortable() && !$item->getCurrentService() && !$item->getIsSmartphone()) {
					$item->setIsPortable($portSessionVal);
					$item->setCurrentService($currentServiceSessionVal);
					$item->setIsSmartphone($buySmartphoneSessionVal);
					$item->save();
				}
			}
			$this->_catalogSession->unsPortProductId();
			$this->_catalogSession->unsPort();
			$this->_catalogSession->unsCurrentService();
			$this->_catalogSession->unsBuySmartphone();
			//$this->_redirect("checkout/cart");
			if (!$this->_cart->getQuote()->getHasError()) {
                          $cart = $this->_cart->getQuote()->getAllVisibleItems();
                          $cartCount = count($cart);
				$message = __(
					'You added %1 to your shopping cart.',
					$product->getName()
				);
				$this->messageManager->addSuccessMessage($message);
			}	
          $this->_eventManager->dispatch(
                'checkout_cart_add_product_complete',
                ['product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse()]
            );			
			$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
			//$resultRedirect->setUrl($this->_redirect->getRefererUrl()); //Redirect to current page
			$resultRedirect->setPath('checkout/cart');           
			return $resultRedirect;
		}
		catch (\Magento\Framework\Exception\LocalizedException $e) {
	
            $this->messageManager->addException($e, __('We can\'t add this item to your shopping cart right now.'));            
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
			$resultRedirect->setUrl($this->_redirect->getRefererUrl());
			return $resultRedirect;
        }        
    }
	
	/**
     * Get all products of the bundled product
     * @return \Magento\Framework\View\Result\Page
     */	
	private function getBundleProductOptionsData($productId)
    {
        $product = $this->_productFactory->create()->load($productId); // Bundle product
        //get all the selection products used in bundle product.
        $selectionCollection = $product->getTypeInstance(true)
            ->getSelectionsCollection(
                $product->getTypeInstance(true)->getOptionsIds($product),
                $product
            );
 
        foreach ($selectionCollection as $proselection) {
            $selectionArray = [];
            $selectionArray['selection_product_name'] = $proselection->getName();
            $selectionArray['selection_product_quantity'] = $proselection->getPrice();
            $selectionArray['selection_product_price'] = $proselection->getSelectionQty();
            $selectionArray['selection_product_id'] = $proselection->getProductId();
            //$productsArray[$proselection->getOptionId()][$proselection->getSelectionId()] = $selectionArray;
			$bundlecartArray[$proselection->getOptionId()][$proselection->getProductId()] = $proselection->getSelectionId(); //Store all options in array
        }		
		return $bundlecartArray;
    }
}