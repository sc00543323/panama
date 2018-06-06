<?php
namespace Panama\Handset\Controller\Adminhtml\Handset;

class Index extends \Magento\Backend\App\Action
{
    private $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Panama_Handset::parent');
        $resultPage->getConfig()->getTitle()->prepend(__('Handset List'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Panama_Handset::parent');
    }
}
