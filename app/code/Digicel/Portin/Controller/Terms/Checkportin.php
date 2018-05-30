<?php

namespace Digicel\Portin\Controller\Terms;
use Magento\Framework\App\RequestInterface; 
use \Magento\Framework\View\Result\PageFactory;
use \Magento\Framework\App\Action\Context;
use \Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Controller\ResultFactory;
class Checkportin extends \Magento\Framework\App\Action\Action
{
    protected $product;
    protected $cart;
	protected $jsonHelper;
public function __construct(
    	Context $context, 
    	PageFactory $pageFactory, 
    	\Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\View\Result\PageFactory $resultFactory,
		 \Magento\Catalog\Model\Product $product,
		 \Magento\Checkout\Model\Cart $cart) 
    {
        $this->pageFactory = $pageFactory;
        $this->jsonHelper = $jsonHelper;
		$this->resultFactory = $resultFactory;
		$this->cart = $cart;
        $this->product = $product;
		
        return parent::__construct($context);

    }
   
 public function execute() { 

 
  $portin = 0;
			$items = $this->cart->getQuote()->getAllVisibleItems();
			 foreach($items as $item){
			  $portin = $item->getIsPortable();
			  $currentService = $item->getCurrentService();
			  if($portin == 'yes'){
				  $portin = 1;
				  break;
			  }
			}
		
		$response = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        $response->setHeader('Content-type', 'text/plain');        
		$response->setContents($this->jsonHelper->jsonEncode(['portin'=> $portin,'currentService'=>$currentService,'status'=>'success']));
		return $response;

  } 
}
