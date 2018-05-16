<?php 
echo "<pre>";
$deliveryCity =  14;
$deliveryRequest = array("OrderHead" =>
	array(
			"DeliveryCity"=>14
			,"DeliveryNeighborhood"=>"Venue"
			,"DeliveryAddress"=>"Le meridien Panama"
			,"DeliveryAddressInstructions"=>"close to hilton"
			,"DeliveryAddressLongitude"=>"-79.468296"
			,"DeliveryAddressLatitude"=>"9.009827"
			,"SaleChannel"=>1
			,"StoreId"=>"VI000017"
			,"DeliveryDate"=>"2017-03-30"
			,"Schedule"=>""
			,"OrderDetail" =>
				array(
					
						"Sku"=>"SMJ500M(L)LTE-BK",
						"Quantity"=>1
					)		
						
		)
	);	
	

	
	var_dump($deliveryRequest);
	
	$data = 'data={"OrderHead"=[{"DeliveryCity":"1","DeliveryNeighborhood":"Zona Franca","DeliveryAddress":"Cra 13","DeliveryAddressInstructions":"Frente a Exito","DeliveryAddressLongitude":"9.234234234234"			,"DeliveryAddressLatitude":"-1.989273283475","SaleChannel":"1","StoreId":"PCEM","DeliveryDate":"2017-09-27","Schedule":"","OrderDetail":[{"Sku":"SMJ500M(L)LTE-BK","Quantity":"1"}]}]}';

$url = "https://appsdemo.logytechmobile.com/NotusCemPanamaRestService/api/CalculateDeliveryDate";
$ch = curl_init( $url );
# Setup request to send json via POST.
//$payload = json_encode( array($deliveryRequest) );
//print_r($payload);
curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );

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