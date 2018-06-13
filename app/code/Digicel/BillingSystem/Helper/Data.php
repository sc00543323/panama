<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Digicel\BillingSystem\Helper;

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

    public function getBillingSystemRequest($input) {
		$billingSystemRequest ='<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
		  <soap:Header>
			<AuthHeader xmlns="http://digicelpanama.com/">
			  <Token>'.$input["token"].'</Token>
			</AuthHeader>
		  </soap:Header>
		  <soap:Body>
		   <ACTIVACION_POSTPAGO xmlns="http://digicelpanama.com/">
			  <MobileNumber>'.$input["mobilenumber"].'</MobileNumber>
			  <CreditCategory>'.$input["creditcategory"].'</CreditCategory>
			  <CardPackageID>'.$input["cardpackageid"].'</CardPackageID>
			  <FirstName>'.$input["firstname"].'</FirstName>
			  <LastName>'.$input["lastname"].'</LastName>
			  <Address1>'.$input["address1"].'</Address1>
			  <Address2>'.$input["address2"].'</Address2>
			  <Address3>'.$input["address3"].'</Address3>
			  <Province>'.$input["province"].'</Province>
			  <EmailAddress>'.$input["email"].'</EmailAddress>
			  <DateOfBirth>'.$input["dob"].'</DateOfBirth>
			  <DocumentType>'.$input["doctype"].'</DocumentType>
			  <Document>'.$input["doc"].'</Document>
			  <PrimaryPricePlanID>'.$input["primaryplan"].'</PrimaryPricePlanID>
			  <Deposit>'.$input["deposit"].'</Deposit>
			  <Question>'.$input["question"].'</Question>
			  <Answer>'.$input["answer"].'</Answer>
			  <PlanID_CAIN>'.$input["planid"].'</PlanID_CAIN>
			  <RK_Aux_Service>'.$input["rk_aux_service"].'</RK_Aux_Service>
			  <TRANS_REF_COD>'.$input["trans_ref_cod"].'</TRANS_REF_COD>
			</ACTIVACION_POSTPAGO>
		  </soap:Body>
		</soap:Envelope>';
		return $billingSystemRequest;
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
