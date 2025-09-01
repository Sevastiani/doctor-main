<?php
session_start();
Function GetPatientDetails($fhirPatientID,$token)
{
	$url="http://localhost:8080/fhir/Patient?_id=".$fhirPatientID;
	$options = array(
	  'http'=>array(
		'method'=>"GET",
		'header'=>"Authorization: Bearer ".$token
	  )
	);
	$context=stream_context_create($options);
	$data=file_get_contents($url,false,$context);
	$fhirpatient=json_decode($data, true);
	return $fhirpatient;
} 

$token_url="http://localhost:80/doctor-main/BackEnd/Keyckloak/GetToken.php";
$token=file_get_contents($token_url);

$json_StartDate=$_GET["startDate"];
$json_EndDate=$_GET["endDate"];
$reason=$_GET["Reason"];
$patient_id=$_GET["PatientID"];
$doctor_name=$_GET["doctorname"];
$doctor_id=$_GET["doctorid"];

$fhirPatientID= GetFHIRPatientID($patient_id);
$fhirPractitionerID=GetFHIRPractitionerID($doctor_id);
if ($fhirPatientID!=NULL)
{
	$fhirPatient=GetPatientDetails($fhirPatientID,$token);

	$Surname=$fhirPatient['entry'][0]['resource']['name'][0]['family'];
	$Name=$fhirPatient['entry'][0]['resource']['name'][0]['given'][0];
	$FullName=$Name." ".$Surname;
	for ($z=0;$z<sizeof($fhirPatient['entry'][0]['resource']['telecom']);$z++)
	{  
		if ($fhirPatient['entry'][0]['resource']['telecom'][$z]['system']=="phone")
		{
			$Phone=$fhirPatient['entry'][0]['resource']['telecom'][$z]['value'];
		}
	}
	$title=$FullName.' '.$Phone;
}


if($fhirPatientID!=NULL && $fhirPractitionerID!=NULL )
{
	$url='http://localhost:8080/fhir/Appointment';

	$body='{
		"resourceType": "Appointment",
		"status": "pending",
		"description": "'.$reason.'",
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
				"reference": "Patient/'.$fhirPatientID.'",
				"display": "'.$title.'"
			},
			"status": "accepted"
		}
		]
	}';
	$curl = curl_init();
	$headers = array('Content-Type: application/json','Authorization: Bearer '.$token,'Cache-Control: no-store','max-results=20');
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLINFO_HEADER_OUT, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_URL, $url);
	$result = curl_exec($curl);

	if(strpos($result, "error") !== false)
	{
		echo "1";	
	}
	else
	{
		echo "0";
	}
}
else{
	echo "1";
}

Function GetFHIRPatientID($PatientID)
{
	$FHIRID	=NULL;
	$url="http://localhost:80/doctor-main/BackEnd/Patient/search_patient.php?patientID=".$PatientID;
    $data=file_get_contents($url);
	$patient=json_decode($data, true);
    $total=$patient['total'];
    
    if ($total>0)
    {
   		$FHIRID=$patient['entry'][0]['resource']['id'];
	}
	return $FHIRID;
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


