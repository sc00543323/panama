<?php

namespace Panama\Handset\Controller\Adminhtml\Handset;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var \Webkul\Grid\Model\GridFactory
     */
    var $handsetFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Panama\Handset\Model\HandsetFactory $handsetFactory
    ) {
        parent::__construct($context);
        $this->handsetFactory = $handsetFactory;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $handsetData = $this->handsetFactory->create();            
        $handset = $handsetData->load($this->getRequest()->getParam('id'));
        try {            
            if($handset->getId()) {
                $handsetData->load($handset->getId());
                $handsetData->delete();
            }
            $this->messageManager->addSuccess(__('Handset details has been successfully deleted.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('handset/handset/index');
    }
}
