<?php
session_start();
function get_string_between($string, $start, $end){
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
}
if (isset($_SESSION["token"])==false)
{
	$comm='curl -H "Content-Type: application/x-www-form-urlencoded" -d "client_id=fhir-client" -d "client_secret=NSkpyxiVbqqyDEjj4WZ299n3CHta5qJR" -d "username=sevi" -d "password=Fhir1234" -d "grant_type=password" -X POST http://localhost:8888/realms/medapp/protocol/openid-connect/token';

	$output= shell_exec("$comm 2>&1; echo $?");
	$token=get_string_between($output, '"access_token":"','","expires_in"');
	$Refreshtoken=get_string_between($output, '"refresh_token":"','",');
	$_SESSION["token"]=$token;
	$_SESSION["Refreshtoken"]=$Refreshtoken;
}
else
{
	//check if token expired
	$token_is_active=Check_Token($_SESSION["token"]);

	if($token_is_active==false)
	{
		$comm='curl -H "Content-Type: application/x-www-form-urlencoded" -d "client_id=fhir-client" -d "client_secret=NSkpyxiVbqqyDEjj4WZ299n3CHta5qJR" -d refresh_token="'.$_SESSION["Refreshtoken"].'" -d "username=sevi" -d "password=Fhir1234" -d "grant_type=password" -X POST http://localhost:8888/realms/medapp/protocol/openid-connect/token';

		$output= shell_exec("$comm 2>&1; echo $?");
		$token=get_string_between($output, '"access_token":"','","expires_in"');
		$Refreshtoken=get_string_between($output, '"refresh_token":"','",');
		$_SESSION["token"]=$token;
		$_SESSION["Refreshtoken"]=$Refreshtoken;
	}
}
echo $_SESSION["Refreshtoken"];


Function Check_Token($token)
{
	$url="http://localhost:8080/fhir/Patient";

	$options = array(
		'http'=>array(
		'method'=>"GET",
		'header'=>"Authorization: Bearer ".$token
		)
	);
	$context=stream_context_create($options);
	$data=file_get_contents($url,false,$context);
	if ($data ==NULL)
	{
		return false;
	}else{
		return true;
	}
}

?>
