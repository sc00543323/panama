<?php

namespace Panama\Port\Controller\Index;
use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Framework\App\Action\Action {

	protected $_storeManager;
	protected $_categoryRepository;
	protected $_url;
	protected $_catalogSession;
	protected $_cart;
	
    public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
		\Magento\Framework\UrlInterface $url,
		\Magento\Catalog\Model\Session $catalogSession,
		\Magento\Checkout\Model\Cart $cart,
		\Panama\Handset\Model\HandsetFactory $handsetFactory
	){
		parent::__construct($context);
		$this->_storeManager = $storeManager;
        $this->_categoryRepository = $categoryRepository;
		$this->_url = $url;
		$this->_catalogSession = $catalogSession;
		$this->_cart = $cart;
		$this->handsetFactory = $handsetFactory;
	}

    public function execute() {
        //prepaid and postpaid addtocart popup ajax calling
        if($this->getRequest()->isAjax()) {
			$data['product_id'] = $this->getRequest()->getParam('product_id');
			$data['port'] = $this->getRequest()->getParam('port');
			$data['current_service'] = $this->getRequest()->getParam('current_service');
			$data['buy_smartphone'] = $this->getRequest()->getParam('buy_smartphone');
			$data['port_remove'] = $this->getRequest()->getParam('port_remove');
			$data['contract'] = $this->getRequest()->getParam('contract');
			$data['contract_remove'] = $this->getRequest()->getParam('contract_remove');
			$data['productsku'] = $this->getRequest()->getParam('productsku');
			if($data['port_remove'] == 'no') {
				$data['port'] = 'no';
			}
			if($data['contract_remove'] == 'no') {
				$data['contract'] = 'no';
			}
			
			//set portable data in session for prepaid/postpaid with smartphone journey
			$this->_catalogSession->setPortProductId($data['product_id']);
			$this->_catalogSession->setPort($data['port']);
			$this->_catalogSession->setContract($data['contract']);
			$this->_catalogSession->setCurrentService($data['current_service']);
			$this->_catalogSession->setBuySmartphone($data['buy_smartphone']);
			$resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
			$now = new \DateTime();
			$handsetData = $this->handsetFactory->create()->getCollection()->addFieldToFilter('valid_to',['gteq' => $now->format('Y-m-d H:i:s')])->addFieldToFilter('phone_sku',$data['productsku'])->getFirstItem();
			if($data['port'] == 'yes' && !$data['current_service'] && !$data['buy_smartphone'] && !$data['contract']) {
				if($handsetData['prepaid_phone_price_with_port_in']) {
		        	$price = $handsetData['prepaid_phone_price_with_port_in'];
		        }
		        else {
		        	$price = '';
		        }	
				$itemCount = $this->_cart->getQuote()->getItemsCount();
				if ($itemCount == 0 ) {
					$result=array
		            (
		                'result' => true,
		                'price' => $price,
		                'test' => 1
		            );
					$resultJson->setData($result);
				} else if($itemCount > 0) {
					$allItems = $this->_cart->getQuote()->getAllItems();
					$portability = true;
					foreach($allItems as $item) {
						if($item->getIsPortable() == 'yes') {
							$portability = false;
							break;
						}
					}
					$result=array
		            (
		                'result' => $portability,
		                'price' => $price,
		                'test' => 2
		            );
					$resultJson->setData($result);
				}
			} else if($data['contract'] == 'yes') {
				if($handsetData['postpaid_phone_price_with_port_in']) {
		        	$price = $handsetData['postpaid_phone_price_with_port_in'];
		        }
		        else {
		        	$price = '';
		        }	
				$itemCount = $this->_cart->getQuote()->getItemsCount();
				if ($itemCount == 0 ) {
					$result=array
		            (
		                'result' => true,
		                'price' => $price,
		                'test' => 3
		            );
					$resultJson->setData($result);
				} else if($itemCount > 0) {
					$allItems = $this->_cart->getQuote()->getAllItems();
					$contract = true;
					foreach($allItems as $item) {
						if($item->getIsContract() == 'yes') {
							$contract = false;
							break;
						}
					}
					$result=array
		            (
		                'result' => $contract,
		                'price' => $price,
		                'test' => 4
		            );
					$resultJson->setData($result);
				}
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
			return $resultJson; die;
        }
    }
}
