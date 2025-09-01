<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Appointment</title>
</head>

<body>

<?php


Function GetAppointmentDetails($Appid,$token)
{
	$url="http://localhost:8080/fhir/Appointment?_id=".$Appid;
    $options = array(
	  'http'=>array(
		'method'=>"GET",
		'header'=>"Authorization: Bearer ".$token
	  )
	);
	$context=stream_context_create($options);

	$data=file_get_contents($url,false,$context);
	$appointment=json_decode($data, true);
	return $appointment;
}
$token_url="http://localhost:80/doctor-main/BackEnd/Keyckloak/GetToken.php";
$token=file_get_contents($token_url);

$Appid=$_POST['appid'];
$FinalResults=$_POST['results'];

$appointmentDetails= GetAppointmentDetails($Appid,$token);
$total=$appointmentDetails['total'];
if ($total>0)
{
	$json_StartDate=$appointmentDetails['entry'][0]['resource']['start'];
	$json_EndDate=$appointmentDetails['entry'][0]['resource']['end'];
	$description=$appointmentDetails['entry'][0]['resource']['description'];
	$participant=$appointmentDetails['entry'][0]['resource']['participant'];

	$FinalResults=$description.": ".$FinalResults;

	for($j=0;$j<sizeof($participant);$j++)
	{
		$member=$participant[$j]['actor']['reference'];
		if (strpos($member, 'Patient/') !== false) 
		{
			$FHIRPatientID=str_replace('Patient/','',$member);
		}elseif (strpos($member, 'Practitioner/') !== false) 
		{
			$fhirPractitionerID=str_replace('Practitioner/','',$member);
			$doctor_name=$participant[$j]['actor']['display'];
			
		}
	}

	if($Appid!=NULL or $FinalResults!=NULL )
	{
		$url='http://localhost:8080/fhir/Appointment/'.$Appid;

		$body='{
			"resourceType": "Appointment",
			"id": "'.$Appid.'",
			"status": "proposed",
			"description": "'.$FinalResults.'",
			"start": "'.$json_StartDate.'",
			"end": "'.$json_EndDate.'",
			"participant": [
			{
				"actor": {
					"reference": "Practitioner/'.$fhirPractitionerID.'",
					"display": "'.$doctor_name.'"
				},
				"status": "accepted"
			},
			{
				"actor": {
				"reference": "Patient/'.$FHIRPatientID.'"
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
			echo "Error!Please try again..";
		}
		else
		{
			echo "<h3>Appointment Updated.</h3>";
		}
	}
	else
	{
		echo "Error!Please try again..";
	}
}else{
	echo "No Appointment fount with this id:".$Appid;
}



			
?>

<a href="http://localhost:80/doctor-main/DoctorCalendar.php"><p>Back to the calendar</p></a>

</body>

</html>
