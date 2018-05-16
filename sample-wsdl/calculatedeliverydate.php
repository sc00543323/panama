<?php
       //next example will insert new conversation
	   
	   ini_set('display_errors', 1);

$curl_post_data = array (
  'OrderHead' => 
  array (
    0 => 
    array (
      'DeliveryCity' => '14',
      'DeliveryNeighborhood' => 'Venue',
      'DeliveryAddress' => 'Le meridien Panama',
      'DeliveryAddressInstructions' => 'close to hilton',
      'DeliveryAddressLongitude' => '-79.468296',
      'DeliveryAddressLatitude' => '9.009827',
      'SaleChannel' => '1',
      'StoreId' => 'VI000017',
      'DeliveryDate' => '2018-05-12',
      'Schedule' => '',
      'OrderDetail' => 
      array (
        0 => 
        array (
          'Sku' => 'SIMAVATAR',
          'Quantity' => '1',
        ),
      ),
    ),
  ),
);	
$url = 'https://webapps02.logytechmobile.com/NotusCEM.RestService/api/CalculateDeliveryDate';
$ch = curl_init( $url );
# Setup request to send json via POST.
$payload = json_encode( $curl_post_data );
curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
# Return response instead of printing.
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
# Send request.
$result = curl_exec($ch);
curl_close($ch);
# Print response.
echo "<pre>";
$response = json_decode($result);
print_r($response);
echo "</pre>";
//echo "<pre>$result</pre>";
    ?>