<?php
/**
 * HelloCash API Debug Test
 * Testet verschiedene Endpoints um die API-Struktur zu verstehen
 */

require_once __DIR__ . '/../src/core/config.php';

echo "=== HelloCash API Debug Test ===\n\n";

$apiUrl = rtrim(HELLOCASH_API_URL, '/');
$apiKey = HELLOCASH_API_KEY;

/**
 * Test einen Endpoint
 */
function testEndpoint($url, $apiKey, $method = 'GET') {
    $ch = curl_init();

    $headers = [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json',
        'Accept: application/json'
    ];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);

    curl_close($ch);

    return [
        'http_code' => $httpCode,
        'response' => $response,
        'error' => $error
    ];
}

// Verschiedene mögliche Endpoints testen
$endpoints = [
    '/' => 'Root/Home',
    '/api' => 'API Root',
    '/v1' => 'API v1',
    '/customers' => 'Customers',
    '/api/customers' => 'API Customers',
    '/v1/customers' => 'V1 Customers',
    '/client' => 'Client',
    '/api/client' => 'API Client',
    '/users' => 'Users',
    '/api/users' => 'API Users',
];

echo "Teste verschiedene Endpoints:\n";
echo str_repeat("-", 60) . "\n";

foreach ($endpoints as $path => $label) {
    $url = $apiUrl . $path;
    echo sprintf("%-30s", $label . " ($path):");

    $result = testEndpoint($url, $apiKey);

    if (!empty($result['error'])) {
        echo " ❌ cURL Error: " . $result['error'] . "\n";
        continue;
    }

    echo " HTTP " . $result['http_code'];

    if ($result['http_code'] == 200) {
        echo " ✅ OK";
        $data = json_decode($result['response'], true);
        if ($data) {
            echo " - Keys: " . implode(', ', array_keys($data));
        }
    } elseif ($result['http_code'] == 401) {
        echo " 🔒 Unauthorized (API-Key ungültig?)";
    } elseif ($result['http_code'] == 403) {
        echo " 🚫 Forbidden (Keine Berechtigung)";
    } elseif ($result['http_code'] == 404) {
        echo " ❌ Not Found";
    } else {
        echo " ⚠️  " . $result['response'];
    }

    echo "\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "Tipp: Prüfen Sie die Swagger-Dokumentation:\n";
echo "https://api.hellocash.net/docs/\n\n";
