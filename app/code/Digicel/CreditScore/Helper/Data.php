<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Digicel\CreditScore\Helper;

use \Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {

    const Hansset_Url = 'panama/digicel_handset_api_details/handset_url';

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

    public function getCreditScoreRequest($input) {
        $creditScoreRequest = '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <AuthHeader xmlns="http://digicelpanama.com/">
      <Token>' . $input["token"] . '</Token>
    </AuthHeader>
  </soap:Header>
  <soap:Body>
    <CREDIT_SCORING xmlns="http://digicelpanama.com/">
      <Customer_ID>' . $input["Customer_ID"] . '</Customer_ID>
      <DocumentType>' . $input["DocumentType"] . '</DocumentType>
      <Handset_Cost>' . $input["Handset_Cost"] . '</Handset_Cost>
      <Handset_Model>' . $input["Handset_Model"] . '</Handset_Model>
    </CREDIT_SCORING>
  </soap:Body>
</soap:Envelope>';
        return $creditScoreRequest;
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
