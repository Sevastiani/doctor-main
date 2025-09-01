<?php
//get token
$token_url="http://localhost:80/doctor-main/BackEnd/Keyckloak/GetToken.php";
$token=file_get_contents($token_url);

//fhir request
$url='http://localhost:8080/fhir/Patient';

$options = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Authorization: Bearer ".$token
  )
);
$context=stream_context_create($options);
//response
echo $data=file_get_contents($url,false,$context);




?>