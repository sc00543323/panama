<?php
namespace Panama\Catalog\Controller\Product;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Upsellproduct extends \Magento\Framework\App\Action\Action {
    
	protected $resultPageFactory;
	protected $scopeConfig;
	 public function __construct(
	Context $context,
	 \Magento\Framework\View\Result\PageFactory $resultPageFactory,
	 ScopeConfigInterface $scopeConfig
	){
		$this->_resultPageFactory = $resultPageFactory;
		$this->scopeConfig = $scopeConfig;
		$this->objectManager = $context->getObjectManager();
        parent::__construct($context);
		
    }
	
	public function execute(){
		
		$data = $this->getRequest()->getPostValue();
			if(isset($data) && !empty($data)){
				$id = explode('_',$data['plan_id']);
				$id = $id[1];
				$product = $this->objectManager->create('Magento\Catalog\Model\Product')->load($id);
				$price  = round($product->getPrice());
				$response = $this->resultFactory
				->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON)
				->setData([
					'price' => $price
				]);
		return $response;
			}
    }
	
	}