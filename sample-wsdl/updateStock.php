<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$adminUrl = 'http://52.76.91.142/digicel-panama/rest/V1/integration/admin/token/';
$ch = curl_init();
$data = array("username" => "admin", "password" => "admin@2018");

$data_string = json_encode($data);
$ch = curl_init($adminUrl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string))
);
$token = curl_exec($ch);
echo $token=  json_decode($token);

//Use above token into header
$headers = array("Authorization: Bearer $token","Content-Type: application/json");
 
//$headers = array("Content-Type: application/json");
$skus = array(
  'sim1' => 10,
  'Prepaid sim' => 5
);
$sku = 'Prepaid%20sim';
$stock = 5;
//foreach ($skus as $sku => $stock) {
  $requestUrl='http://52.76.91.142/digicel-panama/rest/V1/products/' . $sku . '/stockItems/1';

  $sampleProductData = array(
          "qty" => $stock
  );
  $productData = json_encode(array('stockItem' => $sampleProductData));

  $ch = curl_init();
  curl_setopt($ch,CURLOPT_URL, $requestUrl);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $productData);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  curl_close($ch);
  var_dump($response);

  unset($productData);
  unset($sampleProductData);
//}
