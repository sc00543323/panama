<?php 
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 900);
ini_set('default_socket_timeout', 15);


//$params = array('param1'=>$param1);


$wsdl = 'http://52.76.91.142/digicel-panama/sample-wsdl/xml/WS_Token.xml';
/*
$params = array(
		'Username'=>'avatar',
		'Password'=>'avatar2018',		
	);
try {
	$soap = new SoapClient($wsdl, $params);
	$data = $soap->SOLICITAR_TOKEN();
}
catch(Exception $e) {
	die($e->getMessage());
}
*/
$username = 'avatar';
$password = 'avatar2018';
$process = curl_init($wsdl);
curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
curl_setopt($process, CURLOPT_HEADER, 1);
curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
curl_setopt($process, CURLOPT_TIMEOUT, 30);
curl_setopt($process, CURLOPT_POST, 1);
//curl_setopt($process, CURLOPT_POSTFIELDS, $payloadName);
curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
$return = curl_exec($process);
curl_close($process);

//$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $return);
		$xml = simplexml_load_string($return);
		$json = json_encode($xml);
		$responseArray = json_decode($json,true);
		//echo "<pre>"; print_r($responseArray); exit;
echo "<pre>";
var_dump($responseArray);
  echo "</pre>";
die;