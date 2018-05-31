<?php

namespace Panama\Handset\Controller\Adminhtml\Handset;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Webkul\Grid\Model\GridFactory
     */
    var $handsetFactory;

    var $authSession;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Panama\Handset\Model\HandsetFactory $handsetFactory,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ) {
        parent::__construct($context);
        $this->handsetFactory = $handsetFactory;
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
        $data['created_by'] = $this->authSession->getUser()->getUsername();        
        if (!$data) {
            $this->_redirect('handset/handset/import');
            return;
        }
        if(!isset($data['id'])) {
            $data['created_date'] = $this->date->gmtDate();
        }
        $_handsetData = $this->handsetFactory->create()->getCollection()->addFieldToFilter('phone_sku',$data['phone_sku']);
        if(count($_handsetData) && (!isset($data['id']))) {
            $this->messageManager->addError(__('Phone sku already exist'));
            $this->_redirect('handset/handset/index');
            return;
        }
        try {
            $handsetData = $this->handsetFactory->create();
            $handsetData->setData($data);            
            if(isset($data['id'])) {
                $handsetData->setId($data['id']);
            }
            $handsetData->save();            
            $this->messageManager->addSuccess(__('Handset details has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('handset/handset/index');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_Grid::save');
    }
}
