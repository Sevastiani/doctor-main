<?php
session_start();
$logout=$_REQUEST['logout'];
if ($logout!=NULL)
{
	session_unset ();
	session_destroy();
	echo "logout";
}
else
{
   session_unset () ;
   $email=$_REQUEST['email'];
	if ($email!=NULL)
	{
		$url= "http://localhost/doctor-main/BackEnd/Practitioner/search_Practitioner_byemail.php?email=".$email;
		$PractitionerDeatail=file_get_contents($url);
		$Practitioner=json_decode($PractitionerDeatail, true);
		$total=$Practitioner['total'];
		if ($total>0)
		{
		 	$Doctor_Name=$Practitioner['entry'][0]['resource']['name'][0]['given'][0];
			$Doctor_Surname=$Practitioner['entry'][0]['resource']['name'][0]['family'];
			$Doctor_prefix=$Practitioner['entry'][0]['resource']['name'][0]['prefix'][0];
			$Doctor_FullName=$Doctor_prefix.' '.$Doctor_Name.' '.$Doctor_Surname;
		 $Doctor_id=$Practitioner['entry'][0]['resource']['identifier'][0]['value'];
		 $_SESSION["DoctorName"]=$Doctor_FullName;
			$_SESSION['Doctor_id']=$Doctor_id;
			echo "OK";
		}
		else
		{
			echo "NotOK..";
		}
	}
	else
	{
		echo "NotOK..";
	}
}
?>
