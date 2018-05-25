<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Digicel\Portin\Helper;

use \Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {
    
    protected $_storeManager;

	
    public function __construct(
    Context $context, 
            \Magento\Store\Model\StoreManagerInterface $storeManager
            
    ) {
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }
	
    public function getConfig($configPath)
    {
        return $this->scopeConfig->getValue(
            $configPath,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    public function getPdfUrl($configPath) {
        return $this->getMediaUrl() . 'terms_and_condition/' . $this->getConfig($configPath);
    }

    public function getMediaUrl() {
        $mediaUrl = $this->_storeManager
                ->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl;
    }

}
