<?php
//get token
$token_url="http://localhost:80/doctor-main/BackEnd/Keyckloak/GetToken.php";
$token=file_get_contents($token_url);

//read data
$doctor_id=$_GET['doctor_id'];

//fhri request

$url='http://localhost:8080/fhir/Practitioner?identifier='.$doctor_id;

$options = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Authorization: Bearer ".$token
  )
);
$context=stream_context_create($options);

echo $data=file_get_contents($url,false,$context);

?>