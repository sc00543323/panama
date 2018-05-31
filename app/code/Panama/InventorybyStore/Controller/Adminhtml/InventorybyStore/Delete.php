<?php

namespace Panama\InventorybyStore\Controller\Adminhtml\InventorybyStore;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var \Webkul\Grid\Model\GridFactory
     */
    var $inventoryFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Panama\InventorybyStore\Model\InventorybyStoreFactory $inventoryFactory
    ) {
        parent::__construct($context);
        $this->inventoryFactory = $inventoryFactory;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $inventoryData = $this->inventoryFactory->create();            
        $inventory = $inventoryData->load($this->getRequest()->getParam('id'));
        try {            
            if($inventory->getId()) {
                $inventoryData->load($inventory->getId());
                $inventoryData->delete();
            }
            $this->messageManager->addSuccess(__('Inventory details has been successfully deleted.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('inventorybystore/inventorybystore/index');
    }
}
