<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Registration</title>
</head>

<body>

<?php
    $Prefix=$_POST['Prefix'];
    $Name=$_POST['Name'];
    $Surname=$_POST['Surname'];
    $id=$_POST['id'];
    $Phone=$_POST['Phone'];
    $Gender=$_POST['Gender'];
    $City=$_POST['City'];
    $Address=$_POST['Address'];
    $PostalCode=$_POST['PostalCode'];
    $Email=$_POST['Email'];
    $Password=$_POST['Password'];

    include 'config.php';
    session_start();
    $conn = mysqli_connect($servername, $username, $password,  $dbname ,$db_port);

    // Check connection
    if (!$conn) {
        echo "Connection failed: " . mysqli_connect_error();
        exit();
    }

    $sql = "INSERT INTO users (email, Password) VALUES ('".$Email."', '".$Password."')";

    if ($conn->query($sql) === TRUE) 
    {
        $url='http://localhost:8080/baseDstu3/Practitioner';

        $body='{
            "resourceType": "Practitioner",
            "identifier": [ {
            "value": "'.$id.'"
            } ],
            "active": true,
            "name": [ {
            "family": "'.$Surname.'",
            "given": [ "'.$Name.'" ],
            "prefix": [ "'.$Prefix.'" ]
            } ],
            "telecom": [ 
            {
            "system": "phone",
            "value": "'.$Phone.'"
            },
            {
            "system": "email",
            "value": "'.$Email.'"
            } ],
            "address": [
            {
                "city": "'.$City.'",
                "district": "'.$Address.'",
                "postalCode": "'.$PostalCode.'"
            }
            ],
            "gender": "'.$Gender.'"
        }';
        $curl = curl_init();
        $headers = array('Content-Type: application/json');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_URL, $url);
        $result = curl_exec($curl);

        if(strpos($result, "Successfully") !== false)
        {
            echo "<h3>Doctor registered successfully.</h3>";
        }
        else
        {
            echo "Try Again";
        }
    }
    else
    {
        echo "Try Again";
    }

?>
<a href="../../index.php"><p>Back to LogIn Page...</p></a>

</body>

</html>