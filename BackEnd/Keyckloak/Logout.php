<?php
session_start();
$token_url="http://localhost:80/doctor-main/BackEnd/Keyckloak/GetRefreshToken.php";
$token=file_get_contents($token_url);

$comm='curl -H "Content-Type: application/x-www-form-urlencoded" -d "client_id=fhir-client" -d "client_secret=NSkpyxiVbqqyDEjj4WZ299n3CHta5qJR" -d "username=sevi" -d "password=Fhir1234" -d "grant_type=password" -d "refresh_token='.$token.'" -X POST http://localhost:8888/realms/medapp/protocol/openid-connect/logout?client_id=fhir-client&post_logout_redirect_uri=encodedRedirectUri';

$output= shell_exec("$comm 2>&1; echo $?");

session_unset ();
session_destroy();
echo "logout";

?>
