<?php
session_start();

//set appointment
Function SetAppointment($PatientID,$Reason,$start,$end,$doctor_name,$doctor_id)
{
	$url="http://localhost:80/doctor-main/BackEnd/Calendar/SetAppointment.php?PatientID=".$PatientID."&Reason=".$Reason."&startDate=".$start."&endDate=".$end."&doctorname=".$doctor_name."&doctorid=".$doctor_id;
	$data=file_get_contents($url);
	$result=json_decode($data, true);
	return $result;
}
if (isset($_POST['PatientID'])) {

    //collect data
    $error      = null;
    $PatientID  = $_POST['PatientID'];
	$Reason     = $_POST['Reason'];
    $start      = $_POST['startDate'];
    $end        = $_POST['endDate'];

    //validation
    if ($PatientID == '') {
        $error['PatientID'] = 'PatientID is required';
    }

    if ($start == '') {
        $error['start'] = 'Start date is required';
    }

    if ($end == '') {
        $error['end'] = 'End date is required';
    }

    //if there are no errors, carry on
    if (! isset($error)) {

        //format date
        $start = date('Y-m-d H:i:s', strtotime($start));
        $end = date('Y-m-d H:i:s', strtotime($end));

		$start=str_replace(' ','T',$start);
		$end=str_replace(' ','T',$end);
		$start=$start.'-03:00';
		$end=$end.'-03:00';

		$doctor_name=$_SESSION["DoctorName"];
		$doctor_name=str_replace(" ","",$doctor_name);
		$doctor_id=$_SESSION["Doctor_id"];
		$result=SetAppointment($PatientID,$Reason,$start,$end,$doctor_name,$doctor_id);
		if ($result=="0")
		{
			$data['success'] = true;
			$data['message'] = 'Success!';
		}else{
			$data['success'] = false;
			$data['errors'] = $result;
		}

    } else {

        $data['success'] = false;
        $data['errors'] = $error;
    }

    echo json_encode($data);
}
