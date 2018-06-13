<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Digicel\StatusMsisdn\Helper;

use \Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {

    protected $_storeManager;

    /**
     * 
     * @param Context $context
     */
    public function __construct(
    Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    public function getConfig($configPath) {
        return $this->scopeConfig->getValue(
                        $configPath, \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getStatusMsisdnRequest($input) {
		$statusMsisdnRequest ='<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
		  <soap:Header>
			<AuthHeader xmlns="http://digicelpanama.com/">
			  <Token>'.$input["token"].'</Token>
			</AuthHeader>
		  </soap:Header>
		  <soap:Body>
			<STATUS_MSISDN xmlns="http://digicelpanama.com/">
			  <MSISDN>50760349549</MSISDN>
			</STATUS_MSISDN>
		  </soap:Body>
		</soap:Envelope>';
		return $statusMsisdnRequest;
    }

    //Get Response Close
    public function parseResponse($content) {

        $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $content);
        $xml = simplexml_load_string($xml);
        $json = json_encode($xml);
        $responseArray = json_decode($json, true);
        return $responseArray;
    }

}
