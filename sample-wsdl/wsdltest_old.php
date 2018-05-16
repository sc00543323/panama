<?php 
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 900);
ini_set('default_socket_timeout', 15);


//$params = array('param1'=>$param1);


$wsdl = 'http://52.76.91.142/digicel-panama/sample-wsdl/xml/WS_Token.xml';

$params = array(
		'Username'=>'avatar',
		'Password'=>'avatar2018'
	);
try {
	$soap = new SoapClient($wsdl, $params);
	$data = $soap->SOLICITAR_TOKEN();
}
catch(Exception $e) {
	die($e->getMessage());
}
  echo "<pre>";
//var_dump($data);
print_r($data);
  echo "</pre>";
die;
