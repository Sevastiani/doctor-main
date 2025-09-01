<?php
//Get appointment details by id
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

//get token
$token_url="http://localhost:80/doctor-main/BackEnd/Keyckloak/GetToken.php";
$token=file_get_contents($token_url);

if (isset($_POST['id'])) {
	$Appid=$_POST['id'];

	$appointmentDetails=GetAppointmentDetails($Appid,$token);

	//parse data
	$json_StartDate=$appointmentDetails['entry'][0]['resource']['start'];
	$json_StartDate=str_replace("-03:00","",$json_StartDate);
	$json_StartDate=str_replace("T"," ",$json_StartDate);

	$json_EndDate=$appointmentDetails['entry'][0]['resource']['end'];
	$json_EndDate=str_replace("-03:00","",$json_EndDate);
	$json_EndDate=str_replace("T"," ",$json_EndDate);

	$participant=$appointmentDetails['entry'][0]['resource']['participant'];

	for($j=0;$j<sizeof($participant);$j++)
	{
		$member=$participant[$j]['actor']['reference'];
		if (strpos($member, 'Patient/') !== false)
		{
			$title=$participant[$j]['actor']['display'];
		}
	}

    $data = [
        'id'        => $Appid,
        'title'     => $title,
        'start'     => date('d-m-Y H:i:s', strtotime($json_StartDate)),
        'end'       => date('d-m-Y H:i:s', strtotime($json_EndDate)),
        'color'     => '#6453e9',
        'textColor' => '#ffffff'
    ];



}
 echo json_encode($data);
