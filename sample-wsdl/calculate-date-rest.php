<?php

$postData = array("OrderHead" =>
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

// Setup cURL
$ch = curl_init('https://webapps02.logytechmobile.com/NotusCEM.RestService/api/CalculateDeliveryDate');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_HTTPHEADER => array(       
        'Content-Type: application/json'
    ),
    CURLOPT_POSTFIELDS => json_encode($postData)
));

// Send the request
$response = curl_exec($ch);

// Check for errors
if($response === FALSE){
    die(curl_error($ch));
}

// Decode the response
$responseData = json_decode($response, TRUE);

// Print the date from the response
//echo $responseData['published'];
print_r($responseData);
exit;