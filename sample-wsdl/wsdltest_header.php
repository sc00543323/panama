<?php 
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 900);
ini_set('default_socket_timeout', 15);
ini_set('display_errors', 1);

 
class ChannelAdvisorAuth 
{ 
    
    public $Username; 
    public $Password; 

    public function __construct($username, $pass) 
    { 
        $this->Username = $username; 
        $this->Password = $pass; 
    } 
} 

$user        = "avatar"; 
$password    = "avatar2018"; 


// Create the SoapClient instance 
$url         = "http://52.76.91.142/digicel-panama/sample-wsdl/xml/WS_Token.xml"; 
$client     = new SoapClient($url, array("trace" => 1, "exception" => 0)); 

// Create the header 
$auth         = new ChannelAdvisorAuth($user, $password); 
$header     = new SoapHeader("http://appservpa01.digicelpanama.com", "APICredentials", $auth, false); 

// Call wsdl function 
$result = $client->__soapCall("SOLICITAR_TOKEN",array(),NULL, $header); 

// Echo the result 
echo "<pre>".print_r($result, true)."</pre>"; 

?>