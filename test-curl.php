<?php

// Test cURL availability
echo "Testing cURL availability...\n\n";

echo "1. cURL extension loaded: " . (extension_loaded('curl') ? 'YES' : 'NO') . "\n";
echo "2. allow_url_fopen: " . (ini_get('allow_url_fopen') ? 'YES' : 'NO') . "\n";

if (function_exists('curl_version')) {
    $version = curl_version();
    echo "3. cURL version: " . $version['version'] . "\n";
    echo "4. SSL version: " . $version['ssl_version'] . "\n";
} else {
    echo "3. curl_version() function not available\n";
}

// Test simple HTTP request
echo "\n5. Testing HTTP request with Guzzle...\n";

try {
    $client = new \GuzzleHttp\Client();
    $response = $client->get('https://httpbin.org/get');
    echo "   Success! Status: " . $response->getStatusCode() . "\n";
} catch (\Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\nAll tests completed.\n";
