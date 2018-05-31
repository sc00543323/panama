<?php
namespace Panama\InventorybyStore\Controller\Adminhtml\InventorybyStore;

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
        $resultPage->setActiveMenu('Panama_InventorybyStore::parent');
        $resultPage->getConfig()->getTitle()->prepend(__('InventorybyStore List'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Panama_InventorybyStore::parent');
    }
}
