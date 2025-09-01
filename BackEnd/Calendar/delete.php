<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Cancel Appointment</title>
</head>

<body>

<?php
$token_url="http://localhost:80/doctor-main/BackEnd/Keyckloak/GetToken.php";
$token=file_get_contents($token_url);
$Appid=$_POST['id'];
$doctor_name= $_SESSION['DoctorName'];
$doctor_id=$_SESSION['Doctor_id'];
$fhirPractitionerID=GetFHIRPractitionerID($doctor_id);
$url='http://localhost:8080/fhir/Appointment/'.$Appid;

$body='{
	"resourceType": "Appointment",
	"id": "'.$Appid.'",
	"status": "cancelled",
	"participant": [
		{
			"actor": {
				"reference": "Practitioner/'.$fhirPractitionerID.'",
				"display": "'.$doctor_name.'"
			},
			"status": "accepted"
		}
	]
  }';
$curl = curl_init();
$headers = array('Content-Type: application/json','Authorization: Bearer '.$token,'Cache-Control: no-store','max-results=20');
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLINFO_HEADER_OUT, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_URL, $url);
$result = curl_exec($curl);

if(strpos($result, "error") !== false)
{
	echo "Error!Please try again.".$result;
}
else
{
	echo "<h3>Booking deleted.</h3>";
}


Function GetFHIRPractitionerID($doctor_id)
{
	$FHIRID	=NULL;
	$url="http://localhost:80/doctor-main/BackEnd/Practitioner/search_Practitioner.php?doctor_id=".$doctor_id;
    $data=file_get_contents($url);
	$Practitioner=json_decode($data, true);
    $total=$Practitioner['total'];
    
    if ($total>0)
    {
   		$FHIRID=$Practitioner['entry'][0]['resource']['id'];
	}
	return $FHIRID;
}			
?>

<a href="http://localhost:80/doctor-main/Calendar.php"><p>Back to the calendar</p></a>

</body>

</html>
