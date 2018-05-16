<?php
       //next example will insert new conversation
	   error_reporting(E_ALL);
	   ini_set('display_errors', 1);

$curl_post_data = array (
  'OrderHead' => 
  array (
    0 => 
    array (
      'OrderId' => 'xcv',
      'ClientId' => '3',
      'ClientName' => 'prueba cliente creacion',
      'ClientEmail' => 'pruebaCreacion@prueba.com',
      'ClientPhone1' => '1234567890',
      'ClientPhone2' => '9876543210',
      'DateTimeCreation' => '2018-05-03',
      'DateTimeEstimatedDelivery' => '2018-05-04',
      'DeliveryType' => '2',
      'AuthorizedReceiverName' => 'prueba autorizado',
      'StoreToPickup' => 'PCEM',
      'DeliveryCity' => '14',
      'DeliveryNeighborhood' => 'barrio prueba',
      'DeliveryAddress' => 'calle falsa 123',
      'DeliveryAddressLongitude' => '73.111234123',
      'DeliveryAddressLatitude' => '23.4531312312',
      'DeliveryProvince' => 'provincia prueba',
      'DeliveryTownShip' => 'pruebas township',
      'WarehouseId' => 'name prueba',
      'schedule' => 'JOR5',
      'SaleChannel' => '1',
      'OrderDetail' => 
      array (
        0 => 
        array (
          'Sku' => 'SMJ500M(L)LTE-WH',
          'Quantity' => '1',
          'UnitPrice' => '100',
          'Taxes' => '3',
          'TotalPrice' => '103',
        ),
      ),
    ),
  ),
  'DataAccess' => 
  array (
    0 => 
    array (
      'user' => 'admin',
      'password' => '12345.LM',
    ),
  ),
);

$url = 'https://webapps02.logytechmobile.com/NotusCEM.RestService/api/OrdenFullFillment';
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