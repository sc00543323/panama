<?php

namespace Panama\Port\Controller\Index;
use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Framework\App\Action\Action {

	protected $_storeManager;
	protected $_categoryRepository;
	protected $_url;
	protected $_checkoutSession;
	
    public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
		\Magento\Framework\UrlInterface $url,
		\Magento\Checkout\Model\Session $checkoutSession
	){
		parent::__construct($context);
		$this->_storeManager = $storeManager;
        $this->_categoryRepository = $categoryRepository;
		$this->_url = $url;
		$this->_checkoutSession = $checkoutSession;
	}

    public function execute() {
        if ($this->getRequest()->isAjax()) {
			$data['product_id'] = $this->getRequest()->getParam('product_id');
			$data['port'] = $this->getRequest()->getParam('port');
			$data['current_service'] = $this->getRequest()->getParam('current_service');
			$data['buy_smartphone'] = $this->getRequest()->getParam('buy_smartphone');
			$this->_checkoutSession->setPort($data['port']);
			$this->_checkoutSession->setCurrentService($data['current_service']);
			$this->_checkoutSession->setBuySmartphone($data['buy_smartphone']);
			$resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
			if($data['current_service'] == 'postpaid') {
				$categoryId = 7;
			} else if($data['current_service'] == 'prepaid') {
				$categoryId = 6;
			} else {
				$categoryId = '';
			}
			if($data['port'] == 'no') {
				$resultJson->setData($this->_url->getUrl('customcatalog/cart/add/', array('id' => $data['product_id'])));
				
			} else if($data['current_service'] == 'postpaid' || $data['current_service'] == 'prepaid') {
				$category = $this->_categoryRepository->get($categoryId, $this->_storeManager->getStore()->getId());
				$resultJson->setData($category->getUrl());
			} else if($data['buy_smartphone'] == 'no') {
				$resultJson->setData($this->_url->getUrl('customcatalog/cart/add/', array('id' => $data['product_id'])));
			} else if($data['buy_smartphone'] == 'yes') {
				$categoryId = 4;
				$category = $this->_categoryRepository->get($categoryId, $this->_storeManager->getStore()->getId());
				$resultJson->setData($category->getUrl());
			} else {
				$resultJson->setData($data);
			}
			return $resultJson; die;
        } else {
            $model = __('This is Not An Ajax Call');
			$resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
			$resultJson->setData($model);
			return $resultJson;
            header("Location: http://".$_SERVER['SERVER_NAME']);
            die();      
        }
    }
}
