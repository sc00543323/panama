<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Digicel\NipAgain\Helper;

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

    public function getNipAgainRequest($input) {
		$nipAgainRequest ='<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
		  <soap:Header>
			<AuthHeader xmlns="http://digicelpanama.com/">
			  <Token>'.$input["token"].'</Token>
			</AuthHeader>
		  </soap:Header>
		  <soap:Body> 
			 <LNP_ASEP_NIP_REQUEST_AGAIN xmlns="http://digicelpanama.com/">
			  <OperadorDonador>160</OperadorDonador>
			  <Numero_a_Portar>66642409</Numero_a_Portar>
			</LNP_ASEP_NIP_REQUEST_AGAIN>
		  </soap:Body>
		</soap:Envelope>';
		return $nipAgainRequest;
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
