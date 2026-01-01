<?php
/**
 * Test: Buchungs-API testen
 */

require_once __DIR__ . '/../src/core/config.php';

echo "=== Booking API Test ===\n\n";

// Test-Daten (simuliert ein Formular-Submit)
$testData = [
    'booking_type' => 'fixed',
    'service_type' => 'pc-reparatur',
    'booking_date' => '2026-01-15',  // Mittwoch in der Zukunft
    'booking_time' => '11:00',
    'customer_firstname' => 'Max',
    'customer_lastname' => 'Mustermann',
    'customer_email' => 'max.mustermann.test@example.com',
    'customer_phone_country' => '+49',
    'customer_phone_mobile' => '170 1234567',  // Mit führender 0 und Leerzeichen
    'customer_phone_landline' => '030 98765432',
    'customer_company' => 'Test GmbH',
    'customer_notes' => 'Dies ist ein Test'
];

echo "Test-Daten:\n";
echo json_encode($testData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

// Telefonnummer-Bereinigung simulieren (wie in booking.php)
echo "=== Telefonnummer-Bereinigung ===\n";
$mobile_original = $testData['customer_phone_mobile'];
$testData['customer_phone_mobile'] = ltrim(trim($testData['customer_phone_mobile']), '0');
echo "Mobilnummer vorher: '$mobile_original'\n";
echo "Mobilnummer nachher: '{$testData['customer_phone_mobile']}'\n\n";

// HelloCash Integration testen
echo "=== HelloCash User-Suche/Erstellung ===\n";
$hellocashClient = new HelloCashClient();

if (!$hellocashClient->isConfigured()) {
    echo "❌ HelloCash API nicht konfiguriert\n";
} else {
    echo "✅ HelloCash API konfiguriert\n";

    $customerData = [
        'firstname' => trim($testData['customer_firstname']),
        'lastname' => trim($testData['customer_lastname']),
        'email' => trim($testData['customer_email']),
        'phone_country' => $testData['customer_phone_country'],
        'phone_mobile' => trim($testData['customer_phone_mobile']),
        'phone_landline' => trim($testData['customer_phone_landline']),
        'company' => trim($testData['customer_company'])
    ];

    echo "Rufe findOrCreateUser auf...\n";
    $result = $hellocashClient->findOrCreateUser($customerData);

    echo "\nErgebnis:\n";
    echo "  user_id: " . ($result['user_id'] ?? 'null') . "\n";
    echo "  is_new: " . ($result['is_new'] ? 'Ja' : 'Nein') . "\n";
    echo "  error: " . ($result['error'] ?? 'null') . "\n";

    if ($result['user_id']) {
        echo "\n✅ HelloCash User erfolgreich " . ($result['is_new'] ? 'erstellt' : 'gefunden') . "\n";
    } else {
        echo "\n❌ HelloCash Fehler: " . ($result['error'] ?? 'Unbekannter Fehler') . "\n";
    }
}

echo "\n=== Test abgeschlossen ===\n";
