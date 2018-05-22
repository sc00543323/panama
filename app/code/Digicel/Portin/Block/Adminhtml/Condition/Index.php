<?php
/**
 * Copyright © 2015 Panama . All rights reserved.
 */
namespace Digicel\Portin\Block\Adminhtml\Condition;
class Index extends \Magento\Framework\View\Element\Template
{
    protected $_storeManager;  
    
    public function __construct(
    \Magento\Framework\View\Element\Template\Context $context,
    ) {
          $this->_storeManager = $context->getStoreManager();      
        parent::__construct($context);
    }

    protected function _toHtml() {

        return parent::_toHtml();
    }
    
    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }
    
}
