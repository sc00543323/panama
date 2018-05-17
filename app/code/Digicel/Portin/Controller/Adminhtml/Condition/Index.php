<?php
/**
 *
 * Copyright © 2015 Orangecommerce. All rights reserved.
 */
namespace Digicel\Portin\Controller\Adminhtml\Condition;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
class Index extends \Magento\Backend\App\Action
{

	/**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
		//echo 'asdasd'; die;
		$resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }
}
