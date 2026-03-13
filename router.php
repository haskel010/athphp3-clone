<?php
$basePath = '/myapp'; // change this to your actual folder name
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Remove base path from URI
$cleanUri = '/' . ltrim(str_replace($basePath, '', $requestUri), '/');

// Serve existing files directly
if ($cleanUri !== '/' && file_exists(__DIR__ . $cleanUri)) {
    return false;
}

// Custom API route
if ($cleanUri === '/api/authenticate' && $requestMethod === 'POST') {
    require __DIR__ . '/token.php';
    exit;
}

// Default fallback
require __DIR__ . '/index.php';
?>