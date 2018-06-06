<?php

$soap_request='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:dig="http://digicelpanama.com/">
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
 
  $header = array(
    "Content-type: text/xml;charset=\"utf-8\"",
    "Accept: text/xml",
    "Cache-Control: no-cache",
    "Pragma: no-cache",
    "Content-length: ".strlen($soap_request),
  );
 
  $soap_do = curl_init();
  curl_setopt($soap_do, CURLOPT_URL, "http://appservpa01.digicelpanama.com/WS_token/TOKEN.asmx" );
  curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 10);
  curl_setopt($soap_do, CURLOPT_TIMEOUT,        10);
  curl_setopt($soap_do, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
  curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true );
  curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($soap_do, CURLOPT_POST,           true );
  curl_setopt($soap_do, CURLOPT_POSTFIELDS,     $soap_request);
  curl_setopt($soap_do, CURLOPT_HTTPHEADER,     $header);
 $content=curl_exec($soap_do);
  if($content === false) {
    $err = 'Curl error: ' . curl_error($soap_do);
    curl_close($soap_do);
    print $err;
  } else {
	  //echo '<pre>' . $content . '</pre>';
	  //echo $content;
	//$response=parseResponse($content);
	$response=getToken($content);
	echo "<pre>";
	print_r($response);
	//print_r($response->Body->SOLICITAR_TOKENResponse->SOLICITAR_TOKENResult->);
	//print_r($response['SOLICITAR_TOKENResult']['diffgr:diffgram']);
    curl_close($soap_do);
   // print 'Operation completed without any errors';
  }
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
  
  function getToken($string){
  
  $domDocument= new DOMDocument();
$domDocument->loadXML($string);
$result=array();
/*
foreach($domDocument->getElementsByTagName("Resultado") as $token)
{
    $result["Token"]=$token;
}
*/
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
?>