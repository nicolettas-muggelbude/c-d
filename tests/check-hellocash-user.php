<?php
/**
 * HelloCash User-Daten abrufen und prüfen
 */

require_once __DIR__ . '/../src/core/config.php';

echo "=== HelloCash User-Daten Abfrage ===\n\n";

$client = new HelloCashClient();

if (!$client->isConfigured()) {
    echo "❌ HelloCash API nicht konfiguriert\n";
    exit(1);
}

// User-ID angeben oder als Argument übergeben
$userId = isset($argv[1]) ? (int)$argv[1] : 5;

echo "Rufe User-ID $userId ab...\n\n";

try {
    $user = $client->getUser($userId);

    if ($user) {
        echo "✅ User gefunden!\n\n";
        echo "=== Vollständige User-Daten ===\n";
        echo json_encode($user, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

        echo "=== Wichtige Felder ===\n";
        echo "user_id: " . ($user['user_id'] ?? 'N/A') . "\n";
        echo "user_firstname: " . ($user['user_firstname'] ?? 'N/A') . "\n";
        echo "user_surname: " . ($user['user_surname'] ?? 'N/A') . "\n";
        echo "user_email: " . ($user['user_email'] ?? 'N/A') . "\n";
        echo "user_phoneNumber: " . ($user['user_phoneNumber'] ?? 'N/A') . "\n";
        echo "user_country: " . ($user['user_country'] ?? 'N/A') . "\n";
        echo "user_country_code: " . ($user['user_country_code'] ?? 'N/A') . "\n";
        echo "user_company: " . ($user['user_company'] ?? 'N/A') . "\n\n";

        if (isset($user['user_custom_fields'])) {
            echo "=== Custom Fields ===\n";
            echo json_encode($user['user_custom_fields'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
        } else {
            echo "⚠️  Keine Custom Fields vorhanden\n";
        }

    } else {
        echo "❌ User nicht gefunden\n";
    }

} catch (Exception $e) {
    echo "❌ Fehler: " . $e->getMessage() . "\n";
}

echo "\n=== Abfrage abgeschlossen ===\n";
