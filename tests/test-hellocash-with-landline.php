<?php
/**
 * HelloCash API Test mit Festnetznummer
 * Testet die Custom Field Integration
 */

require_once __DIR__ . '/../src/core/config.php';

echo "=== HelloCash Custom Field Test (Festnetz) ===\n\n";

$client = new HelloCashClient();

if (!$client->isConfigured()) {
    echo "❌ HelloCash API nicht konfiguriert\n";
    exit(1);
}

echo "Erstelle Test-User mit Festnetznummer...\n\n";

// Test-Daten mit Festnetznummer
$customerData = [
    'firstname' => 'Max',
    'lastname' => 'Mustermann',
    'email' => 'test-festnetz-' . time() . '@pc-wittfoot.de',
    'phone_country' => '+49',
    'phone_mobile' => '170 9876543',
    'phone_landline' => '030 12345678',  // Festnetznummer
    'company' => 'Test GmbH'
];

echo "Daten:\n";
echo "  Vorname: " . $customerData['firstname'] . "\n";
echo "  Nachname: " . $customerData['lastname'] . "\n";
echo "  E-Mail: " . $customerData['email'] . "\n";
echo "  Mobilnummer: " . $customerData['phone_country'] . " " . $customerData['phone_mobile'] . "\n";
echo "  Festnetz: " . $customerData['phone_landline'] . "\n";
echo "  Firma: " . $customerData['company'] . "\n\n";

try {
    $result = $client->findOrCreateUser($customerData);

    if ($result['user_id']) {
        echo "✅ User erfolgreich erstellt!\n\n";
        echo "User-ID: " . $result['user_id'] . "\n";
        echo "Status: " . ($result['is_new'] ? 'Neu erstellt' : 'Bereits vorhanden') . "\n\n";

        echo "Übermittelte Felder:\n";
        echo "  user_firstname: Max\n";
        echo "  user_surname: Mustermann\n";
        echo "  user_email: " . $customerData['email'] . "\n";
        echo "  user_phoneNumber: 170 9876543 (ohne +49)\n";
        echo "  user_country_code: DE\n";
        echo "  user_company: Test GmbH\n";
        echo "  user_custom_fields:\n";
        echo "    Festnetz: 030 12345678\n\n";

        echo "ℹ️  Bitte im HelloCash Portal prüfen:\n";
        echo "1. Gehen Sie zu Kunden/Users\n";
        echo "2. Suchen Sie User-ID: " . $result['user_id'] . "\n";
        echo "3. Prüfen Sie ob das Custom Field 'Festnetz' die Nummer '030 12345678' enthält\n\n";
    } else {
        echo "❌ Fehler: " . ($result['error'] ?? 'Unbekannter Fehler') . "\n";
    }

} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
}

echo "\n=== Test abgeschlossen ===\n";
