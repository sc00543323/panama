<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Digicel\DigicelToken\Helper;

use \Magento\Framework\App\Helper\Context;
use \Digicel\DigicelToken\Model\DigicelTokenFactory;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {

    const Token_Url = 'panama/digicel_token_api_details/token_url';
    const Token_Username = 'panama/digicel_token_api_details/token_username';
    const Token_Password = 'panama/digicel_token_api_details/token_password';
    const Hansset_Url = 'panama/digicel_handset_api_details/handset_url';

    protected $_digicelModel;
    protected $_storeManager;

    /**
     * 
     * @param Context $context
     * @param DigicelTokenFactory $digicelfactory
     */
    public function __construct(
    Context $context, DigicelTokenFactory $digicelfactory, \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_digicelModel = $digicelfactory;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Call this function to get Token Response
     * @return string
     */
    public function getTokens() {
        $tokenCollection = $this->_digicelModel->create()->getCollection()->getFirstitem();
        if ($tokenCollection) {
            return $tokenCollection['token_response'];
        } else {
            return $this->getTokensFromApi();
        }
    }

    /*
     * Call this function if API response invalid tokens
     */

    public function getTokensFromApi() {
        $tokenApiUrl = $this->scopeConfig->getValue(self::Token_Url, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $username = $this->scopeConfig->getValue(self::Token_Username, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $password = $this->scopeConfig->getValue(self::Token_Password, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $auth = array("username" => $username, "password" => $password);
        $tokenRequest = $this->getTokenRequest($auth);
        $tokenHeader = $this->getHeader($tokenRequest);
        $tokenResponse = $this->getResponse($tokenApiUrl, $tokenRequest, $tokenHeader);
        $token = $this->getTokenResponse($tokenResponse);

        $tokenCollection = $this->_digicelModel->create()->getCollection()->getFirstItem();
        $this->_digicelModel->create()->load($tokenCollection['digiceltoken_id'])->setTokenResponse($token['Resultado'])->save();

        return $token['Resultado'];
    }

    /**
     * 
     * @param type $input
     * @return string
     */
    public function getTokenRequest($input) {
        $tokenRequest = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:dig="http://digicelpanama.com/">
   <soapenv:Header>
      <dig:AuthHeader>
         <!--Optional:-->
         <dig:Username>' . $input["username"] . '</dig:Username>
         <!--Optional:-->
         <dig:Password>' . $input["password"] . '</dig:Password>
      </dig:AuthHeader>
   </soapenv:Header>
   <soapenv:Body>
      <dig:SOLICITAR_TOKEN/>
   </soapenv:Body>
</soapenv:Envelope>';
        return $tokenRequest;
    }

    public function getHeader($request) {
        $header = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "Content-length: " . strlen($request),
        );
        return $header;
    }

    /**
     * 
     * @param type $string
     * @return array
     */
    public function getTokenResponse($string) {

        $domDocument = new DOMDocument();
        $domDocument->loadXML($string);
        $result = array();

        foreach ($domDocument->getElementsByTagName("codigo") as $codigoElement) {
            $result["codigo"] = $codigoElement->textContent;
        }
        foreach ($domDocument->getElementsByTagName("Resultado") as $resultElement) {
            $result["Resultado"] = $resultElement->textContent;
        }


        return $result;
    }

    public function getResponse($soapUrl, $request, $header) {

        $soap_do = curl_init();
        curl_setopt($soap_do, CURLOPT_URL, $soapUrl);
        curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($soap_do, CURLOPT_TIMEOUT, 0);
        curl_setopt($soap_do, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($soap_do, CURLOPT_POST, true);
        curl_setopt($soap_do, CURLOPT_POSTFIELDS, $request);
        curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header);
        $content = curl_exec($soap_do);
        if ($content === false) {
            $err = 'Curl error: ' . curl_error($soap_do);

            return $err;
            curl_close($soap_do);
        } else {

            return $content;
            curl_close($soap_do);
        }
    }

    public function getHandsetUrl() {
        return $this->scopeConfig->getValue(self::Hansset_Url, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getConfig($configPath) {
        return $this->scopeConfig->getValue(
                        $configPath, \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function logResponse($request, $filename) {
        $isEnabled = $this->getConfig('panama/log_api/save_response');
        if ($isEnabled) {
            $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/' . $filename);
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);
            $logger->info($request);
            return true;
        }
    }

}
