<?php
/**
 * Copyright © 2016 Digicel. All rights reserved.
 * See COPYING.txt for license details.
 */
 
namespace Digicel\Login\Controller\Login;
 
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Digicel\Login\Model\Data;
 
/**
 * Login controller
 *
 * @method \Magento\Framework\App\RequestInterface getRequest()
 * @method \Magento\Framework\App\Response\Http getResponse()
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Checkoutaddress extends \Magento\Framework\App\Action\Action
{
 
    /**
     * @var \Magento\Framework\Json\Helper\Data $helper
     */
    protected $helper;
 
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;
 
    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;
	protected $modelFactory;
	
 
 
    /**
     * Initialize Login controller
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Json\Helper\Data $helper
     * @param AccountManagementInterface $customerAccountManagement
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Json\Helper\Data $helper,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
		 Data $modelFactory
    ) {
        parent::__construct($context);
        $this->helper = $helper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultRawFactory = $resultRawFactory;
		$this->modelFactory = $modelFactory;
    }
    public function execute()
    {
		$layout = $this->_view->getLayout();
		
        $html = $layout->createBlock(\Magento\Customer\Block\Form\Register::class)->setTemplate('Magento_Customer::form/checkoutaddress.phtml')->toHtml();
		$resultJson = $this->resultJsonFactory->create();
		//return $resultJson->setData($data);
		return $resultJson->setData(['html' => $html]);
    }
	public function getDistric(){
		return $sampleCollection = $this->modelFactory->getCollection();
	}
}