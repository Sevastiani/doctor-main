<?php
//get token
$token_url="http://localhost:80/doctor-main/BackEnd/Keyckloak/GetToken.php";
$token=file_get_contents($token_url);

$email=$_GET['email'];

//fhir end point
$url="http://localhost:8080/fhir/Practitioner?email=".$email;

$options = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Authorization: Bearer ".$token,
    'header'=>"Accept: application/fhir+json"
  )
);
$context=stream_context_create($options);

echo $data=file_get_contents($url,false,$context);

?>
