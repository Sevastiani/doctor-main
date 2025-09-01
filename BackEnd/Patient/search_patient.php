<?php
// get token
$token_url = "http://localhost/doctor-main/BackEnd/Keyckloak/GetToken.php";
$token = @file_get_contents($token_url);

header('Content-Type: application/json; charset=utf-8');

$patientID = isset($_GET['patientID']) ? trim($_GET['patientID']) : '';
$Phone     = isset($_GET['Phone']) ? trim($_GET['Phone']) : '';

if ($token === false || $token === '') {
    http_response_code(500);
    echo json_encode(['error' => 'Unable to obtain auth token']);
    exit;
}

if ($Phone !== '') {
    $url = "http://localhost:8080/fhir/Patient?phone=" . rawurlencode($Phone);
} elseif ($patientID !== '') {
    $url = "http://localhost:8080/fhir/Patient?identifier=" . rawurlencode($patientID);
} else {
    echo json_encode(['total' => 0, 'entry' => []]);
    exit;
}

$options = [
  'http' => [
    'method' => "GET",
    'header' => "Authorization: Bearer " . $token . "\r\n"
  ]
];
$context = stream_context_create($options);

$data = @file_get_contents($url, false, $context);
if ($data === false) {
    http_response_code(502);
    echo json_encode(['error' => 'FHIR endpoint unavailable']);
    exit;
}

echo $data;
