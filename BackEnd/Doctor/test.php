<?php
$token_url="http://localhost:80/doctor-main/BackEnd/Keyckloak/GetToken.php";
$token=file_get_contents($token_url);

$DoctorID='99999999';
$name='Panayiotis';
$surname='Savva';
$email='savva.t.panayiotis@ucy.ac.cy';
$Prefix='DR.';
$phone='96671582';
$gender='male';
$Address='test';
$City='nicosia';
$PostalCode='1040';

$url='http://localhost:8080/fhir/Practitioner';

  $body='{
    "resourceType": "Practitioner",
    "identifier": [ {
      "value": "'.$DoctorID.'"
    } ],
    "active": true,
    "name": [ {
      "family": "'.$surname.'",
      "given": [ "'.$name.'" ],
      "prefix": [ "'.$Prefix.'" ]
    } ],
    "telecom": [ 
    {
      "system": "phone",
      "value": "'.$phone.'"
    },
    {
      "system": "email",
      "value": "'.$email.'"
    } ],
    "address": [
      {
        "city": "'.$City.'",
        "district": "'.$Address.'",
        "postalCode": "'.$PostalCode.'"
      }
    ],
    "gender": "'.$gender.'"
  }';

$curl = curl_init();
$headers = array('Content-Type: application/json','Authorization: Bearer '.$token);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLINFO_HEADER_OUT, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_URL, $url);
$result = curl_exec($curl);

if(strpos($result, "error") !== false)
{
  echo "Try Again".$result;
}
else
{
   echo "OK";
}
?>
