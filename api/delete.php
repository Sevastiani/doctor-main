<?php

if (isset($_POST["id"])) {
   $appid=$_POST['id'];
}else{
  $appid='1234';
}
   //get token
   $token_url="http://localhost:80/doctor-main/BackEnd/Keyckloak/GetToken.php";
   echo $token=file_get_contents($token_url);
   //end point
   $url="http://localhost:8080/fhir/Appointment/".$appid;
   //send request
	$headers = array('Content-Type: application/json','Authorization: Bearer '.$token);
	$ch = curl_init();
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_URL, $url);
  $result = curl_exec($ch);
if ($result === false) {
    echo "cURL Error: " . curl_error($ch);
}
  echo $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
