<?php

namespace Panama\Port\Controller\Index;
use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Framework\App\Action\Action {

	protected $_url; 
	protected $_checkoutSession;
	
    public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\UrlInterface $url,
		\Magento\Checkout\Model\Session $checkoutSession
	){
		parent::__construct($context);
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
			if($data['port'] == 'no') {
				$resultJson->setData($this->_url->getUrl('customcatalog/cart/add/', array('id' => $data['product_id'])));
				return $resultJson; die;
			}
			$resultJson->setData($data);
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
