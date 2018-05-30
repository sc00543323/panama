<?php

namespace Panama\MagentoApi\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterfac
     */
    protected $_scopeConfig;
    protected $_request;

    public function __construct(
    \Magento\Framework\App\Helper\Context $context, \Magento\Framework\App\Request\Http $request
    ) {
        parent::__construct($context);
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_request = $request;
    }

    public function logCreate($fileName, $data) {
        //$log_mode = $this->_scopeConfig->getValue('custom_modulelog/modulelog__configuration/setting_mode', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        //if(isset($log_mode) && $log_mode==1){
            $writer = new \Zend\Log\Writer\Stream(BP . "$fileName");
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);
            //$log_mode = $this->_scopeConfig->getValue('custom_modulelog/modulelog__configuration/setting_mode', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            //if(isset($log_mode) && $log_mode==1){
                $logger->info($data);
            //}
        //}
    }
}