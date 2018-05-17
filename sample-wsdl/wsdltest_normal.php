<?php 
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 900);
ini_set('default_socket_timeout', 15);


$apiauth =array('UserName'=>'avatar','Password'=>'avatar2018');
$wsdl = 'http://52.76.91.142/digicel-panama/sample-wsdl/xml/WS_Token.xml';
$header = new SoapHeader('http://appservpa01.digicelpanama.com', 'AuthHeader', $apiauth);
$soap = new SoapClient($wsdl); 
$soap->__setSoapHeaders($header);  


	
$data = $soap->SOLICITAR_TOKEN($header); 

  echo "<pre>";
print_r($data);
  echo "</pre>";
die;