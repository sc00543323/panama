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
 
  function getCreditScoreRequest($input){
   $creditScoreRequest ='<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <AuthHeader xmlns="http://digicelpanama.com/">
      <Token>'.$input["token"].'</Token>
    </AuthHeader>
  </soap:Header>
  <soap:Body>
    <CREDIT_SCORING xmlns="http://digicelpanama.com/">
      <Customer_ID>AP730177</Customer_ID>
      <DocumentType>3</DocumentType>
      <Handset_Cost>257.58</Handset_Cost>
      <Handset_Model>SIMAVATAR</Handset_Model>
    </CREDIT_SCORING>
  </soap:Body>
</soap:Envelope>';
return $creditScoreRequest;
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

  
 } //Get Response Close
  function parseResponse($content){
	 
	$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $content);
		$xml = simplexml_load_string($xml);
		$json = json_encode($xml);
		$responseArray = json_decode($json,true);
		return $responseArray;
  }
 
  function getResponseCode($string, $responseParams){
  
	$domDocument= new DOMDocument();
	$domDocument->loadXML($string);
	$result=array();
	
foreach($domDocument->getElementsByTagName($responseParams["code"]) as $codigoElement)
{
    $result["codigo"]=$codigoElement->textContent;
}
foreach($domDocument->getElementsByTagName($responseParams["description"]) as $resultElement)
{
    $result["Resultado"]=$resultElement->textContent;
}


return $result;
  } 
    echo "<pre>";
  //$response=getTokenResponse($content);
  $tokenApiUrl = "http://appservpa01.digicelpanama.com/WS_token/TOKEN.asmx";  
  $auth = array("username"=>"avatar","password"=>"avatar2018");
  $tokenRequest = getTokenRequest($auth);
  
  $tokenHeader = getHeader($tokenRequest);  
  $tokenResponse = getResponse($tokenApiUrl, $tokenRequest, $tokenHeader);
  $responseParams = array("code"=>"codigo","description"=>"Resultado");
  $token = getResponseCode($tokenResponse,$responseParams);

  //print_r($tokenResponse);
  //print_r($token);

  $params['token'] = $token['Resultado'];
  //$params['token'] = str_replace("==","",$token['Resultado']);
  //$params['token'] = strstr($token['Resultado'],'/');
  $digicelApiUrl = "http://appservpa01.digicelpanama.com/WS_eCommerce/eCommerce.asmx";
  $creditScoreRequest = getCreditScoreRequest($params);
  $creditScoreHeader = getHeader($creditScoreRequest);
  $creditScore = getResponse($digicelApiUrl,$creditScoreRequest,$creditScoreHeader);
 // $responseParams = array("code"=>"Codigo","description"=>"Descripcion",);
 //$token = getResponseCode($creditScore,$responseParams);

 // echo "Token => ".trim($params['token'])."</br>";
 $creditScore = parseResponse($creditScore);
   print_r($creditScore);
   echo "</pre>";
?>