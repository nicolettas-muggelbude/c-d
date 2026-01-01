<?php
/**
 * Test: Booking API direkt aufrufen
 */

// Simuliere einen API-Request
$_SERVER['REQUEST_METHOD'] = 'POST';

// Test-Daten (wie vom Frontend gesendet)
$testData = [
    'booking_type' => 'fixed',
    'service_type' => 'pc-reparatur',
    'booking_date' => '2026-01-15',  // Mittwoch
    'booking_time' => '11:00',
    'customer_firstname' => 'Test',
    'customer_lastname' => 'User',
    'customer_email' => 'test@example.com',
    'customer_phone_country' => '+49',
    'customer_phone_mobile' => '170 9876543',
    'customer_phone_landline' => '030 12345678',
    'customer_company' => '',
    'customer_notes' => 'Test-Buchung'
];

echo "=== Booking API Direkttest ===\n";
echo "Test-Daten:\n";
echo json_encode($testData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

// JSON-Input simulieren
file_put_contents('php://stdin', json_encode($testData));

// Output buffering starten, um die API-Response zu fangen
ob_start();

// API-Endpoint einbinden
include __DIR__ . '/../src/api/booking.php';

// Response abrufen
$response = ob_get_clean();

echo "=== API Response ===\n";
echo $response . "\n";

// Response analysieren
$responseData = json_decode($response, true);
if ($responseData) {
    echo "\n=== Analyse ===\n";
    echo "Success: " . ($responseData['success'] ? 'JA ✅' : 'NEIN ❌') . "\n";

    if (!$responseData['success']) {
        echo "Error: " . ($responseData['error'] ?? 'Unbekannt') . "\n";
        if (isset($responseData['errors'])) {
            echo "Errors:\n";
            foreach ($responseData['errors'] as $error) {
                echo "  - $error\n";
            }
        }
    } else {
        echo "Booking ID: " . ($responseData['booking_id'] ?? 'N/A') . "\n";
    }
}

echo "\n=== Test abgeschlossen ===\n";
