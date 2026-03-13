<?php
// Step 1: Get the query parameter
$param = isset($_GET['param1']) ? $_GET['param1'] : null;

if ($param) {
    // Step 2: Make an API request
    $apiUrl = "https://paypostman.com/summary?param1=" . urlencode($param) . "&out=1";
    $ch = curl_init();
    
    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    // Execute cURL request
    $htmlContent = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Check for errors
    if (curl_errno($ch)) {
        echo "cURL Error: " . curl_error($ch);
    } elseif ($httpCode !== 200) {
        echo "Error: API returned status code " . $httpCode;
    } else {
        // Step 3: Render the HTML
        echo $htmlContent;
    }

    // Close cURL session
    curl_close($ch);
} else {
    echo "Error: Missing 'param' in the URL.";
}
?>