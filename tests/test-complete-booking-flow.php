<?php
/**
 * Vollständiger Test: Terminbuchung mit HelloCash Integration
 */

require_once __DIR__ . '/../src/core/config.php';

echo "=== Vollständiger Terminbuchungs-Test ===\n\n";

$client = new HelloCashClient();

if (!$client->isConfigured()) {
    echo "❌ HelloCash API nicht konfiguriert\n";
    exit(1);
}

// Simuliere Kundendaten aus Terminbuchungsformular
$bookingData = [
    'firstname' => 'Anna',
    'lastname' => 'Schmidt',
    'email' => 'anna.schmidt.' . time() . '@example.com',
    'phone_country' => '+49',
    'phone_mobile' => '176 98765432',
    'phone_landline' => '089 12345678',  // München Festnetz
    'company' => 'Schmidt Consulting GmbH'
];

echo "Buchungsdaten:\n";
echo "  Name: " . $bookingData['firstname'] . " " . $bookingData['lastname'] . "\n";
echo "  Firma: " . $bookingData['company'] . "\n";
echo "  E-Mail: " . $bookingData['email'] . "\n";
echo "  Mobil: " . $bookingData['phone_country'] . " " . $bookingData['phone_mobile'] . "\n";
echo "  Festnetz: " . $bookingData['phone_landline'] . "\n\n";

echo "Schritt 1: Suche oder erstelle User in HelloCash...\n";

try {
    $result = $client->findOrCreateUser($bookingData);

    if ($result['user_id']) {
        echo "✅ " . ($result['is_new'] ? 'User neu erstellt' : 'User gefunden') . "\n";
        echo "   User-ID: " . $result['user_id'] . "\n\n";

        // User-Daten abrufen zur Prüfung
        echo "Schritt 2: User-Daten prüfen...\n";
        $user = $client->getUser($result['user_id']);

        if ($user) {
            echo "✅ User-Daten abgerufen:\n\n";
            echo "  user_firstname: " . ($user['user_firstname'] ?? 'N/A') . "\n";
            echo "  user_surname: " . ($user['user_surname'] ?? 'N/A') . "\n";
            echo "  user_company: " . ($user['user_company'] ?? 'N/A') . "\n";
            echo "  user_email: " . ($user['user_email'] ?? 'N/A') . "\n";
            echo "  user_phoneNumber: " . ($user['user_phoneNumber'] ?? 'N/A') . "\n";
            echo "  user_country: " . ($user['user_country'] ?? 'N/A') . "\n";
            echo "  user_notes: " . ($user['user_notes'] ?? 'N/A') . "\n\n";

            // Prüfung
            echo "=== Validierung ===\n";

            $checks = [
                'Mobilnummer korrekt (ohne +49)' => isset($user['user_phoneNumber']) && strpos($user['user_phoneNumber'], '176') !== false,
                'Ländercode gesetzt (DE)' => isset($user['user_country']) && $user['user_country'] === 'DE',
                'Festnetz in Notes' => isset($user['user_notes']) && strpos($user['user_notes'], '089 12345678') !== false
            ];

            foreach ($checks as $check => $passed) {
                echo ($passed ? '✅' : '❌') . " $check\n";
            }

            echo "\n";

            if (all_true($checks)) {
                echo "🎉 ALLE TESTS BESTANDEN!\n";
                echo "Die HelloCash Integration funktioniert vollständig!\n\n";
                echo "Im HelloCash Portal prüfen:\n";
                echo "- User-ID: " . $result['user_id'] . "\n";
                echo "- Notes-Feld sollte enthalten: 'Festnetz: 089 12345678'\n";
            } else {
                echo "⚠️  Einige Checks fehlgeschlagen\n";
            }

        }

    } else {
        echo "❌ Fehler: " . ($result['error'] ?? 'Unbekannt') . "\n";
    }

} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
}

echo "\n=== Test abgeschlossen ===\n";

function all_true($array) {
    foreach ($array as $value) {
        if (!$value) return false;
    }
    return true;
}
