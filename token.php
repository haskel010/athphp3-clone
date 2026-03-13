<?php
// token.php — receives id, calls real API, logs if needed, and responds cleanly

header('Content-Type: application/json');

// Step 1: Read JSON input from frontend
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!isset($data['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing ID']);
    exit;
}

$id = $data['id'];

// Step 2: Call the external token API
$externalUrl = 'http://localhost:1773/get/token';

$ch = curl_init($externalUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['id' => $id]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);



$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

// 👇 Add this to debug the raw response
file_put_contents('debug_response.log', $response); // log to a file
// or for dev:
var_dump($response); // for debugging only, comment out later

curl_close($ch);


// Step 3: Process API response
$data = json_decode($response, true);

if (isset($data['errors'])) {
    $errorMessage = $data['errors'][0]['message'];

    if ($errorMessage === "5") {
        // Invalid ID: Show toast
        echo json_encode(['status' => 'invalid']);
        exit;
    }

    // Step 4: Log ID to Google Sheets via Apps Script
    $googleScriptURL = "https://script.google.com/macros/s/XXXXXXXXXXXXXXX/exec"; // 🔁 Replace with your actual URL

    $postFields = http_build_query([
        'Pateint-id' => $id
    ]);

    $logReq = curl_init($googleScriptURL);
    curl_setopt($logReq, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($logReq, CURLOPT_POST, true);
    curl_setopt($logReq, CURLOPT_POSTFIELDS, $postFields);
    curl_exec($logReq);
    curl_close($logReq);

    // Respond to frontend
    echo json_encode(['status' => 'pending']);
    exit;
}

// Step 5: Success — return redirect URL
echo json_encode([
    'status' => 'ok',
    'redirect' => '/summary.php/?param1=' . urlencode($id)
]);
