<?php
	session_start();
	//Get apointment
	Function GetAppointments($practitionerID,$token)
	{
		$url="http://localhost:8080/fhir/Appointment?practitioner=".$practitionerID."&status=pending";
		$options = array(
		  'http'=>array(
			'method'=>"GET",
			'header'=>"Authorization: Bearer ".$token
		  )
		);
		$context=stream_context_create($options);
		$data=file_get_contents($url,false,$context);
		$appointments=json_decode($data, true);
		return $appointments;
	} 

	
	//read practitioner details by id
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
	
	$data = [];
	$token_url="http://localhost:80/doctor-main/BackEnd/Keyckloak/GetToken.php";
	$token=file_get_contents($token_url);
	
	$doctor_id=$_SESSION['Doctor_id'];
	$practitionerID=GetFHIRPractitionerID($doctor_id);
	$appointments=GetAppointments($practitionerID,$token);
	
	$total=$appointments['total'];
	
	For($i=0;$i<$total;$i++)
	{
		$FHIRPatientID=NULL;
		$appointment_id=$appointments['entry'][$i]['resource']['id'];
		$start_date=$appointments['entry'][$i]['resource']['start'];
		$end_date=$appointments['entry'][$i]['resource']['end'];
		$participant=$appointments['entry'][$i]['resource']['participant'];
		$start_date=str_replace("T"," ",$start_date);
		$end_date=str_replace("T"," ",$end_date);
		$start_date=str_replace("-03:00","",$start_date);
		$end_date=str_replace("-03:00","",$end_date);
		
		for($j=0;$j<sizeof($participant);$j++)
		{
			$member=$participant[$j]['actor']['reference'];
			
			if (strpos($member, 'Patient/') !== false) 
			{
				$FHIRPatientID=str_replace('Patient/','',$member);
				$title=$participant[$j]['actor']['display'];
			}
		}
		$data[] = [
			'id'              => $appointment_id,
			'title'           => $appointment_id.' '.$title,//$appointment_id.' '.$FullName.' '.$Phone,
			'start'           => $start_date,
			'end'             => $end_date,
			'backgroundColor' => '#6453e9',
			'textColor'       => '#ffffff'
		];
	}
	

echo json_encode($data);
