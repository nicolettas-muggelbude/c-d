#!/usr/bin/env php
<?php
/**
 * Cronjob: HelloCash Synchronisation
 * PC-Wittfoot UG
 *
 * Synchronisiert neue Buchungen mit HelloCash
 * Läuft alle 5 Minuten
 *
 * Cronjob Setup:
 * Alle 5 Minuten: /usr/bin/php /pfad/zu/cronjobs/sync-hellocash.php >> /pfad/zu/logs/cronjob.log 2>&1
 */

require_once __DIR__ . '/../src/core/config.php';
require_once __DIR__ . '/../src/core/database.php';
require_once __DIR__ . '/../src/core/HelloCashClient.php';

echo "[" . date('Y-m-d H:i:s') . "] HelloCash Sync gestartet\n";

$db = Database::getInstance();
$hellocashClient = new HelloCashClient();

if (!$hellocashClient->isConfigured()) {
    echo "[ERROR] HelloCash API nicht konfiguriert\n";
    exit(1);
}

// Finde alle Buchungen ohne HelloCash-ID
$bookings = $db->query("
    SELECT * FROM bookings
    WHERE hellocash_customer_id IS NULL
    AND status != 'cancelled'
    ORDER BY created_at DESC
    LIMIT 50
");

if (empty($bookings)) {
    echo "[INFO] Keine Buchungen zu synchronisieren\n";
    exit(0);
}

echo "[INFO] " . count($bookings) . " Buchung(en) gefunden\n";

$synced = 0;
$errors = 0;

foreach ($bookings as $booking) {
    echo "[SYNC] Buchung #{$booking['id']} ({$booking['customer_firstname']} {$booking['customer_lastname']})... ";

    // Nachname und Firma validieren (HelloCash benötigt mindestens eins davon)
    $lastname = trim($booking['customer_lastname'] ?? '');
    $company = !empty($booking['customer_company']) ? trim($booking['customer_company']) : null;

    // Fallback: Wenn weder Nachname noch Firma vorhanden, Platzhalter setzen
    if (empty($lastname) && empty($company)) {
        $lastname = '.';  // Minimal-Platzhalter für HelloCash
    }

    $customerData = [
        'firstname' => trim($booking['customer_firstname']),
        'lastname' => $lastname,
        'email' => trim($booking['customer_email']),
        'phone_country' => $booking['customer_phone_country'] ?? '+49',
        'phone_mobile' => trim($booking['customer_phone_mobile']),
        'phone_landline' => !empty($booking['customer_phone_landline']) ? trim($booking['customer_phone_landline']) : null,
        'company' => $company,
        'street' => trim($booking['customer_street']),
        'house_number' => trim($booking['customer_house_number']),
        'postal_code' => trim($booking['customer_postal_code']),
        'city' => trim($booking['customer_city'])
    ];

    try {
        $result = $hellocashClient->findOrCreateUser($customerData);

        if ($result['user_id']) {
            // HelloCash-ID in Buchung speichern
            $db->update("UPDATE bookings SET hellocash_customer_id = :hc_id WHERE id = :id", [
                ':hc_id' => $result['user_id'],
                ':id' => $booking['id']
            ]);

            echo "OK (ID: {$result['user_id']}, " . ($result['is_new'] ? 'neu erstellt' : 'gefunden') . ")\n";
            $synced++;
        } else {
            echo "FEHLER: " . ($result['error'] ?? 'Unbekannter Fehler') . "\n";
            $errors++;
        }
    } catch (Exception $e) {
        echo "FEHLER: " . $e->getMessage() . "\n";
        $errors++;
    }

    // Kleine Pause um API nicht zu überlasten
    usleep(200000); // 0.2 Sekunden
}

echo "\n[" . date('Y-m-d H:i:s') . "] HelloCash Sync beendet\n";
echo "[RESULT] Erfolgreich: $synced, Fehler: $errors\n";

exit($errors > 0 ? 1 : 0);
