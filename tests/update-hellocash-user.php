<?php
/**
 * HelloCash User aktualisieren (Custom Fields setzen)
 */

require_once __DIR__ . '/../src/core/config.php';

echo "=== HelloCash User Update Test ===\n\n";

$client = new HelloCashClient();

if (!$client->isConfigured()) {
    echo "❌ HelloCash API nicht konfiguriert\n";
    exit(1);
}

$userId = 5;

echo "Aktualisiere User-ID $userId mit Custom Field 'Festnetz'...\n\n";

try {
    $updateData = [
        'phone_landline' => '030 12345678'  // Wird zu Custom Field
    ];

    // Direkt ein Update-Request mit Custom Field testen
    $ch = curl_init();
    $url = HELLOCASH_API_URL . "/users/{$userId}";

    $payload = [
        'user_custom_fields' => [
            'Festnetz' => '030 12345678'
        ]
    ];

    $headers = [
        'Authorization: Bearer ' . HELLOCASH_API_KEY,
        'Content-Type: application/json',
        'Accept: application/json'
    ];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    echo "Request URL: $url\n";
    echo "Request Body:\n";
    echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);

    curl_close($ch);

    echo "HTTP Code: $httpCode\n";

    if ($error) {
        echo "❌ cURL Error: $error\n";
    } else {
        $data = json_decode($response, true);

        if ($httpCode >= 200 && $httpCode < 300) {
            echo "✅ Update erfolgreich!\n\n";
            echo "Response:\n";
            echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

            if (isset($data['user_custom_fields'])) {
                echo "✅ Custom Fields gesetzt:\n";
                echo json_encode($data['user_custom_fields'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
            } else {
                echo "⚠️  Custom Fields noch immer null\n";
            }
        } else {
            echo "❌ Fehler:\n";
            echo $response . "\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
}

echo "\n=== Test abgeschlossen ===\n";
echo "\nℹ️  Führen Sie jetzt 'php tests/check-hellocash-user.php' aus um zu prüfen ob das Custom Field gesetzt wurde\n";
