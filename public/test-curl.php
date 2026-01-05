<?php
// Test file to check if cURL works in web (PHP-FPM) context
echo "<h1>PHP cURL Test</h1>";

echo "<h2>1. Extension Check</h2>";
echo "cURL extension loaded: " . (extension_loaded('curl') ? '<span style="color:green">YES ✓</span>' : '<span style="color:red">NO ✗</span>') . "<br>";
echo "allow_url_fopen: " . (ini_get('allow_url_fopen') ? '<span style="color:green">YES ✓</span>' : '<span style="color:red">NO ✗</span>') . "<br>";

if (function_exists('curl_version')) {
    $version = curl_version();
    echo "cURL version: " . $version['version'] . "<br>";
    echo "SSL version: " . $version['ssl_version'] . "<br>";
}

echo "<h2>2. Simple cURL Test</h2>";
$ch = curl_init('https://httpbin.org/json');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$result = curl_exec($ch);
$error = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($error) {
    echo '<span style="color:red">cURL ERROR: ' . $error . '</span><br>';
} else {
    echo '<span style="color:green">cURL SUCCESS! HTTP Code: ' . $httpCode . '</span><br>';
    echo 'Response length: ' . strlen($result) . ' bytes<br>';
}

echo "<h2>3. Guzzle Test</h2>";
require_once __DIR__ . '/../vendor/autoload.php';

try {
    $client = new \GuzzleHttp\Client();
    $response = $client->get('https://httpbin.org/json');
    echo '<span style="color:green">Guzzle SUCCESS! Status: ' . $response->getStatusCode() . '</span><br>';
} catch (\Exception $e) {
    echo '<span style="color:red">Guzzle ERROR: ' . $e->getMessage() . '</span><br>';
}

echo "<h2>4. PHP Info</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "SAPI: " . php_sapi_name() . "<br>";
