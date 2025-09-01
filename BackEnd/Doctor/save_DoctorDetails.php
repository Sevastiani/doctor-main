<?php
//Get token
$token_url="http://localhost:80/doctor-main/BackEnd/Keyckloak/GetToken.php";
$token=file_get_contents($token_url);

//read data
$DoctorID=$_GET['DoctorID'];
$name=$_GET['name'];
$surname=$_GET['surname'];
$email=$_GET['email'];
$Prefix=$_GET['Prefix'];
$phone=$_GET['phone'];
$gender=$_GET['gender'];
$Address=$_GET['Address'];
$City=$_GET['City'];
$PostalCode=$_GET['PostalCode'];

//FHIR request
$url='http://localhost:8080/fhir/Practitioner?identifier='.$DoctorID;

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
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLINFO_HEADER_OUT, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_URL, $url);
$result = curl_exec($curl);

//response
if(strpos($result, "error") !== false)
{
  echo "Try Again".$result;
}
else
{
   echo "OK";
}
?>