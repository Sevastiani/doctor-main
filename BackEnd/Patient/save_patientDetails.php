<?php
//Get token
$token_url="http://localhost:80/doctor-main/BackEnd/Keyckloak/GetToken.php";
$token=file_get_contents($token_url);

//read data
$patientID=$_POST['patientID'];
$name=$_POST['name'];
$surname=$_POST['surname'];
$email=$_POST['email'];
$birth=$_POST['birth'];
$phone=$_POST['phone'];
$gender=$_POST['gender'];
$Address=$_POST['Address'];
$City=$_POST['City'];
$PostalCode=$_POST['PostalCode'];

//fhir request
$url='http://localhost:8080/fhir/Patient?identifier='.$patientID;

  $body='{
    "resourceType":"Patient",
    "identifier":[
       {
          "value":"'.$patientID.'"
       }
    ],
    "active":true,
    "name":[
       {
          "use":"usual",
          "family":"'.$surname.'",
          "given":[
             "'.$name.'"
          ]
       }
    ],
    "telecom":[
       {
          "system":"phone",
          "value":"'.$phone.'"
       },
       {
          "system":"email",
          "value":"'.$email.'"
       }
    ],
    "gender":"'.$gender.'",
    "birthDate":"'.$birth.'",
    "address":[
       {
          "city":"'.$City.'",
          "district":"'.$Address.'",
          "postalCode":"'.$PostalCode.'"
       }
    ]
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