<?php

namespace Panama\InventorybyStore\Controller\Adminhtml\InventorybyStore;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Webkul\Grid\Model\GridFactory
     */
    var $inventoryFactory;

    var $authSession;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Panama\InventorybyStore\Model\InventorybyStoreFactory $inventoryFactory,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ) {
        parent::__construct($context);
        $this->inventoryFactory = $inventoryFactory;
        $this->authSession = $authSession;
        $this->date = $date;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if (!$data) {
            $this->_redirect('inventorybystore/inventorybystore/import');
            return;
        }
        try {
            $inventoryData = $this->inventoryFactory->create();
            $inventoryData->setData($data);            
            if(isset($data['id'])) {
                $inventoryData->setId($data['id']);
            }
            $inventoryData->save();            
            $this->messageManager->addSuccess(__('Inventory details has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('inventorybystore/inventorybystore/index');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_Grid::save');
    }
}
