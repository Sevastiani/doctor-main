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

if (isset($_POST['id'])) 
{
    //collect data
    $error      = null;
    $id         = $_POST['id'];
    $start      = $_POST['start'];
    $end        = $_POST['end'];
	$result     = $_POST['result'];

    //validation
    if ($start == '') {
        $error['start'] = 'Start date is required';
    }

    if ($end == '') {
        $error['end'] = 'End date is required';
    }

    //if there are no errors, carry on
    if (!isset($error)) 
	{
        //reformat date
        $start = date('Y-m-d H:i:s', strtotime($start));
        $end = date('Y-m-d H:i:s', strtotime($end));
        $start=str_replace(' ','T',$start);
		$end=str_replace(' ','T',$end);
		$start=$start.'-03:00';
		$end=$end.'-03:00';
		
		$token_url="http://localhost:80/doctor-main/BackEnd/Keyckloak/GetToken.php";
		$token=file_get_contents($token_url);
		$appointmentDetails=GetAppointmentDetails($id,$token);
		
		$description=$appointmentDetails['entry'][0]['resource']['description'];
		$participant=$appointmentDetails['entry'][0]['resource']['participant'];

		for($j=0;$j<sizeof($participant);$j++)
		{
			$member=$participant[$j]['actor']['reference'];
			if (strpos($member, 'Patient/') !== false) 
			{
				$FHIRPatientID=str_replace('Patient/','',$member);
				$title=$participant[$j]['actor']['display'];
			}
			elseif (strpos($member, 'Practitioner/') !== false) 
			{
				$fhirPractitionerID=str_replace('Practitioner/','',$member);
				$doctor_name=$participant[$j]['actor']['display'];	
			}
		}
		
		
		$url='http://localhost:8080/fhir/Appointment/'.$id;
		$status='pending';
		if ($result!='' and $result!=NULL)
		{
			$description=$description.":".$result;
			$status='proposed';
		}

		$body='{
			"resourceType": "Appointment",
			"id": "'.$id.'",
			"status": "'.$status.'",
			"description": "'.$description.'",
			"start": "'.$start.'",
			"end": "'.$end.'",
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
					"reference": "Patient/'.$FHIRPatientID.'",
					"display": "'.$title.'"
				},
				"status": "accepted"
			}
			]
		}';
		
		//update appointment
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
			$data['success'] = false;
			$data['errors'] = $error;
		}
		else
		{
			 $data['success'] = true;
			$data['message'] = 'Success!';
		}
    } else {

        $data['success'] = false;
        $data['errors'] = $error;
    }

   echo json_encode($data);
}