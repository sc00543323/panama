<?php 

error_reporting(E_ALL);
ini_set('display_errors', '1');

function getTokenRequest($input){
 $tokenRequest='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:dig="http://digicelpanama.com/">
   <soapenv:Header>
      <dig:AuthHeader>
         <!--Optional:-->
         <dig:Username>'.$input["username"].'</dig:Username>
         <!--Optional:-->
         <dig:Password>'.$input["password"].'</dig:Password>
      </dig:AuthHeader>
   </soapenv:Header>
   <soapenv:Body>
      <dig:SOLICITAR_TOKEN/>
   </soapenv:Body>
</soapenv:Envelope>';  
return $tokenRequest;
}
 
function getHandsetPriceRequest($input){
   $handsetPriceRequest ='<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <AuthHeader xmlns="http://digicelpanama.com/">
      <Token>'.$input["token"].'</Token>
    </AuthHeader>
  </soap:Header>
  <soap:Body>
    <HANDSET_PRICE xmlns="http://digicelpanama.com/">
      <PlanID>98768</PlanID>
      <Trans_Type>0</Trans_Type>
      <SKU_ID>HIP10GQ(L)-BK</SKU_ID>
    </HANDSET_PRICE>
  </soap:Body>
</soap:Envelope>';
return $handsetPriceRequest;
}

function getHeader($request){
 $header = array(
    "Content-type: text/xml;charset=\"utf-8\"",
    "Accept: text/xml",
    "Cache-Control: no-cache",
    "Pragma: no-cache",
    "Content-length: ".strlen($request),
  );
  return $header;
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
	 curl_close($soap_do);
  } else {	 

	return $content;
	  curl_close($soap_do);
	
  }
 }
 //Get Response Close
 
  function parseResponse($content){
	  $result=array();
	//  $clean_xml = str_ireplace(['S:', 'SOAP:','env:'], '', $content);
      $xml = simplexml_load_string($content);
	 // $xml = simplexml_load_string($clean_xml, "SimpleXMLElement", LIBXML_NOCDATA);
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
 /*   echo "here<pre>";
  //$response=getTokenResponse($content);
  $tokenApiUrl = "http://appservpa01.digicelpanama.com/WS_token/TOKEN.asmx";  
  $auth = array("username"=>"avatar","password"=>"avatar2018");
  $tokenRequest = getTokenRequest($auth);
  
  $tokenHeader = getHeader($tokenRequest);  
  $tokenResponse = getResponse($tokenApiUrl, $tokenRequest, $tokenHeader);
  $token = getTokenResponse($tokenResponse);

  $params['token'] = $token['Resultado']; */
  $params['token'] = "dR0eTnbRFoe5FrD3B2j/WA==";
  $digicelApiUrl = "http://appservpa01.digicelpanama.com/WS_eCommerce/eCommerce.asmx";
  $handsetPriceRequest = getHandsetPriceRequest($params);
  $handsetPriceHeader = getHeader($handsetPriceRequest);
  $handsetPrice = getResponse($digicelApiUrl,$handsetPriceRequest,$handsetPriceHeader);
  //$creditScore = parseResponse($creditScore);

   echo "Token => ".trim($params['token'])."</br>";
   print_r($handsetPrice);
   echo "</pre>";
?>