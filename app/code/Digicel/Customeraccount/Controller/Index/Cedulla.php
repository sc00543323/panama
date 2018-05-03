<?php 
namespace Digicel\Customeraccount\Controller\Index;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
 
class Cedulla extends \Magento\Framework\App\Action\Action { 
/**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;
 
    /**
     * [__construct]
     * @param Context                          $context
     * @param PageFactory                      $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct(
            $context
        );
    }
 
    /**
     * loads custom layout
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
      
	   $resultPage = $this->_resultPageFactory->create();
	   /*$block = $resultPage->getLayout()
							->getBlock('Digicel\Customeraccount\Block\Cedulla')
							->setTemplate('Digicel_Customeraccount::mycedulla.phtml')
							->toHtml();*
	   
	   $block = $resultPage->getLayout()->getBlock('Digicel\Customeraccount\Block\Cedulla');
	   //print_r($block);exit;
	   /*$block = $resultPage->getLayout()
                ->createBlock('Digicel\Customeraccount\Block\Cedulla')
                ->setTemplate('Digicel_Customeraccount::mycedulla.phtml')
                ->toHtml();*/
				
        //$this->getResponse()->setBody($block);
        return $resultPage;
    }
  
}
?> 