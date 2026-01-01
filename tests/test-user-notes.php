<?php
/**
 * HelloCash user_notes Test
 * Testet ob Festnetznummer im notes-Feld gespeichert werden kann
 */

require_once __DIR__ . '/../src/core/config.php';

echo "=== HelloCash user_notes Test ===\n\n";

$client = new HelloCashClient();

if (!$client->isConfigured()) {
    echo "❌ HelloCash API nicht konfiguriert\n";
    exit(1);
}

echo "Test 1: Neuen User mit user_notes erstellen\n";
echo str_repeat("-", 50) . "\n\n";

// Test-Daten mit Festnetznummer in notes
$testUser = [
    'firstname' => 'Notes',
    'lastname' => 'Test',
    'email' => 'test-notes-' . time() . '@pc-wittfoot.de',
    'phone_number' => '160' . rand(1000000, 9999999),
    'country_code' => 'DE',
    'company' => 'Notes Test GmbH',
    'notes' => 'Festnetz: 030 98765432'  // Festnetznummer in notes
];

echo "Erstelle User mit user_notes...\n";
echo "  user_notes: " . $testUser['notes'] . "\n\n";

try {
    // Direkter API-Call
    $ch = curl_init();
    $url = rtrim(HELLOCASH_API_URL, '/') . "/users";

    $payload = [
        'user_firstname' => $testUser['firstname'],
        'user_surname' => $testUser['lastname'],
        'user_email' => $testUser['email'],
        'user_phoneNumber' => $testUser['phone_number'],
        'user_country_code' => $testUser['country_code'],
        'user_company' => $testUser['company'],
        'user_notes' => $testUser['notes']
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

    echo "Request Payload:\n";
    echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);

    curl_close($ch);

    if ($error) {
        echo "❌ cURL Error: $error\n";
    } else {
        $data = json_decode($response, true);

        if ($httpCode >= 200 && $httpCode < 300) {
            echo "✅ User erfolgreich erstellt!\n\n";
            echo "Response:\n";
            echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

            $userId = $data['user_id'] ?? null;

            if ($userId) {
                echo "=== Prüfung ===\n";
                echo "User-ID: $userId\n";

                if (isset($data['user_notes']) && !empty($data['user_notes'])) {
                    echo "✅ user_notes gesetzt: " . $data['user_notes'] . "\n";
                } else {
                    echo "❌ user_notes ist leer oder nicht gesetzt\n";
                }

                // Nochmal abrufen um zu prüfen
                echo "\nRufe User nochmal ab zur Bestätigung...\n";
                $checkUser = $client->getUser($userId);

                if ($checkUser && isset($checkUser['user_notes'])) {
                    echo "✅ user_notes bestätigt: " . $checkUser['user_notes'] . "\n";
                } else {
                    echo "❌ user_notes wurde nicht gespeichert\n";
                }
            }

        } else {
            echo "❌ HTTP $httpCode - Fehler:\n";
            echo $response . "\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "Test abgeschlossen\n\n";

echo "Ergebnis:\n";
echo "- Wenn user_notes funktioniert → Festnetznummer kann dort gespeichert werden\n";
echo "- Wenn nicht → Festnetznummer bleibt nur in unserer Datenbank\n";
