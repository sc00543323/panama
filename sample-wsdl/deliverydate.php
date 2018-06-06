<?php

$url= 'https://webapps02.logytechmobile.com/NotusCEM.RestService/api/CalculateDeliveryDate?data={"OrderHead"=[{"DeliveryCity":"1","DeliveryNeighborhood":"Zona Franca","DeliveryAddress":"Cra 13","DeliveryAddressInstructions":"Frente a Exito","DeliveryAddressLongitude":"9.234234234234"			,"DeliveryAddressLatitude":"-1.989273283475","SaleChannel":"1","StoreId":"PCEM","DeliveryDate":"2017-09-27","Schedule":"","OrderDetail":[{"Sku":"SMJ500M(L)LTE-BK","Quantity":"1"}]}]}';


//$url = "https://appsdemo.logytechmobile.com/NotusCemPanamaRestService/api/CalculateDeliveryDate";
$ch = curl_init();
# Setup request to send json via POST.
//$payload = json_encode( array($deliveryRequest) );
//print_r($payload);
//curl_setopt( $ch, CURLOPT_POSTFIELDS, $deliveryRequest );
curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($ch,CURLOPT_HTTPHEADER, array('Cookie:langcookie=en; currentcurr=USD',));
curl_setopt($ch, CURLOPT_COOKIEFILE,'');
# Return response instead of printing.
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
# Send request.
$result = curl_exec($ch);

curl_close($ch);
# Print response.
//echo "result";
//$responseArray = json_decode($result,true);
var_dump($result);
//print_r($result);

echo "</pre>";