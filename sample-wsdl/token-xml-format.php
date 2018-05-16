<?php $soapUrl = "http://panesbweb000/WS_TOKEN/Token.asmx";
 //$soapUrl = "http://52.76.91.142/digicel-panama/sample-wsdl/xml/WS_Token.xml";

		$xml_post_string = '<?xml version="1.0" encoding="utf-8"?>';
		$xml_post_string .= '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:dig="http://digicelpanama.com/">';
		$xml_post_string .= ' <soapenv:Header>';
		$xml_post_string .= '<dig:AuthHeader>';
		$xml_post_string .= '<!--Optional:-->';
		$xml_post_string .= '<dig:Username>avatar</dig:Username>';
		$xml_post_string .= '<!--Optional:-->';
		$xml_post_string .= '<dig:Password>avatar2018</dig:Password>';
		$xml_post_string .= '</dig:AuthHeader>';
		$xml_post_string .= '</soapenv:Header>';
		$xml_post_string .= '<soapenv:Body>';
		$xml_post_string .= '<dig:SOLICITAR_TOKEN/>';
		$xml_post_string .= '</soapenv:Body>';
		$xml_post_string .= '</soapenv:Envelope>';

$headers = array(
"POST /package/package_1.3/packageservices.asmx HTTP/1.1",
"Host: http://appservpa01.digicelpanama.com ",
"Content-Type: application/soap+xml; charset=utf-8",
"Content-Length: ".strlen($xml_post_string)
); 

$url = $soapUrl;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($ch); 
curl_close($ch);

var_dump($response);
$response1 = str_replace("<soap:Body>","",$response);
$response2 = str_replace("</soap:Body>","",$response1);

$parser = simplexml_load_string($response2);

$json = json_encode($parser);
$array = json_decode($json,TRUE);
var_dump($array);exit;
?>