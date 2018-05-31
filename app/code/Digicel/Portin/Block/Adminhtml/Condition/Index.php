<?php
/**
 * Copyright © 2015 Orange . All rights reserved.
 */
namespace Digicel\Portin\Block\Adminhtml\Condition;
class Index extends \Magento\Framework\View\Element\Template
{
    protected $_storeManager;  
    
    public function __construct(
    \Magento\Framework\View\Element\Template\Context $context,
    \Orange\Upload\Helper\Data $helper
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
    
    public function getSaveUrl()
    {
        return $this->getUrl('seo/Order/Export');
    }
	
    public function getDownloadUrl($file)
    {
        return $this->getUrl('orderexport/export/Download/',['file' => $file]);
    }
     public function objectManagerInt() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        return $objectManager;
    }


    
    
   

}
