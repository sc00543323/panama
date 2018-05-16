<?php

function getTokenRequest($input){
return $tokenRequest='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:dig="http://digicelpanama.com/">
   <soapenv:Header>
      <dig:AuthHeader>
         <!--Optional:-->
         <dig:Username>avatar</dig:Username>
         <!--Optional:-->
         <dig:Password>avatar2018</dig:Password>
      </dig:AuthHeader>
   </soapenv:Header>
   <soapenv:Body>
      <dig:SOLICITAR_TOKEN/>
   </soapenv:Body>
</soapenv:Envelope>'; 
 }
  function getcreditScoreRequest($input){
  return $creditScoreRequest ='<![CDATA[<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:dig="http://digicelpanama.com/">\r
   <soap:Header>\r
      <dig:AuthHeader>\r
         <!--Optional:-->\r
         <dig:Token>?</dig:Token>\r
      </dig:AuthHeader>\r
   </soap:Header>\r
   <soap:Body>\r
      <dig:CREDIT_SCORING>\r
         <!--Optional:-->\r
         <dig:Customer_ID>?</dig:Customer_ID>\r
         <!--Optional:-->\r
         <dig:DocumentType>?</dig:DocumentType>\r
         <!--Optional:-->\r
         <dig:Handset_Cost>?</dig:Handset_Cost>\r
         <!--Optional:-->\r
         <dig:Handset_Model>?</dig:Handset_Model>\r
      </dig:CREDIT_SCORING>\r
   </soap:Body>\r
</soap:Envelope>]]>';
}
function getHeader($request){
 return $header = array(
    "Content-type: text/xml;charset=\"utf-8\"",
    "Accept: text/xml",
    "Cache-Control: no-cache",
    "Pragma: no-cache",
    "Content-length: ".strlen($request),
  );
}

 function getResponse($soapUrl,$request,$header){
 

 
  $soap_do = curl_init();
  curl_setopt($soap_do, CURLOPT_URL, $soapUrl );
  curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 10);
  curl_setopt($soap_do, CURLOPT_TIMEOUT,        10);
  curl_setopt($soap_do, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
  curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true );
  curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($soap_do, CURLOPT_POST,           true );
  curl_setopt($soap_do, CURLOPT_POSTFIELDS,     $request);
  curl_setopt($soap_do, CURLOPT_HTTPHEADER,     $header);
 $content=curl_exec($soap_do);
  if($content === false) {
    $err = 'Curl error: ' . curl_error($soap_do);    
    return $err;
  } else {	 

	return $content;
	
	
  }
  curl_close($soap_do);
  
 } //Get Response Close
  function parseResponse($content){
	  $result=array();
	  $clean_xml = str_ireplace(['S:', 'SOAP:','env:'], '', $content);
      $xml = simplexml_load_string($clean_xml);
	  $xml = simplexml_load_string($clean_xml, "SimpleXMLElement", LIBXML_NOCDATA);
		$json = json_encode($xml);
		$array = json_decode($json,TRUE);
	  return $array;
	  //PRINT_R($result);
  }
  
  function getTokenResponse($string){
  
	$domDocument= new DOMDocument();
	$domDocument->loadXML($string);
	$result=array();
	
foreach($domDocument->getElementsByTagName("codigo") as $codigoElement)
{
    $result["codigo"]=$codigoElement->textContent;
}
foreach($domDocument->getElementsByTagName("Resultado") as $resultElement)
{
    $result["Resultado"]=$resultElement->textContent;
}


return $result;
  } 
  //$response=getTokenResponse($content);
  $tokenApiUrl = "http://appservpa01.digicelpanama.com/WS_token/TOKEN.asmx";
  $digicelApiUrl = "http://appservpa01.digicelpanama.com/WS_eCommerce/eCommerce.asmx";
  $auth = array("username"=>"avatar","password"=>"avatar2018");
  $tokenRequest = getTokenRequest($auth);
  $tokenHeader = getHeader($tokenRequest);  
  $token = getResponse($tokenApiUrl, $tokenRequest, $tokenHeader);
  
  $creditScoreHeader = getHeader($creditScoreRequest);
  $creditScore = getResponse($digicelApiUrl,$creditScoreRequest,$creditScoreHeader);
 
?>