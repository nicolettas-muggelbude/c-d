<?php
/**
 * API-Endpoint: Terminbuchung
 * PC-Wittfoot UG
 *
 * POST /api/booking - Neuen Termin erstellen
 */

header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/../core/config.php';

// Nur POST erlauben
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// JSON-Daten lesen
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Debug-Logging
error_log('=== Booking Request Start ===');
error_log('Raw Input: ' . $input);
error_log('Decoded Data: ' . json_encode($data));

// Validierung
$errors = [];

// Terminart validieren
if (empty($data['booking_type']) || !in_array($data['booking_type'], ['fixed', 'walkin'])) {
    $errors[] = 'Ungültige Terminart';
}

// Dienstleistung validieren
if (empty($data['service_type'])) {
    $errors[] = 'Bitte wählen Sie eine Dienstleistung';
}

// Datum validieren
if (empty($data['booking_date'])) {
    $errors[] = 'Bitte wählen Sie ein Datum';
} else {
    $date = DateTime::createFromFormat('Y-m-d', $data['booking_date']);
    if (!$date) {
        $errors[] = 'Ungültiges Datumsformat';
    } else {
        // Prüfen ob Datum in der Zukunft liegt
        $today = new DateTime();
        $today->setTime(0, 0, 0);
        if ($date < $today) {
            $errors[] = 'Datum muss in der Zukunft liegen';
        }

        // Prüfen ob erlaubter Wochentag
        $dayOfWeek = $date->format('N');
        $bookingType = $data['booking_type'] ?? '';

        if ($bookingType === 'fixed') {
            // Feste Termine: Dienstag bis Freitag (2-5)
            if ($dayOfWeek < 2 || $dayOfWeek > 5) {
                $errors[] = 'Feste Termine sind nur Dienstag bis Freitag möglich';
            }
        } else if ($bookingType === 'walkin') {
            // Walk-in: Dienstag bis Samstag (2-6)
            if ($dayOfWeek < 2 || $dayOfWeek > 6) {
                $errors[] = 'Walk-in Termine sind nur Dienstag bis Samstag möglich';
            }
        }
    }
}

// Zeit validieren (nur bei festen Terminen)
if ($data['booking_type'] === 'fixed') {
    if (empty($data['booking_time'])) {
        $errors[] = 'Bitte wählen Sie eine Uhrzeit';
    } else {
        // Zeitformat validieren (HH:MM)
        if (!preg_match('/^\d{2}:\d{2}$/', $data['booking_time'])) {
            $errors[] = 'Ungültiges Zeitformat';
        }

        // Optional: Prüfen ob Zeit verfügbar ist
        // (Wird durch Frontend bereits geprüft, aber zusätzliche Sicherheit)
    }
}

// Kundendaten validieren
if (empty($data['customer_firstname']) || strlen(trim($data['customer_firstname'])) < 2) {
    $errors[] = 'Bitte geben Sie Ihren Vornamen ein';
}

if (empty($data['customer_lastname']) || strlen(trim($data['customer_lastname'])) < 2) {
    $errors[] = 'Bitte geben Sie Ihren Nachnamen ein';
}

if (empty($data['customer_email']) || !filter_var($data['customer_email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Bitte geben Sie eine gültige E-Mail-Adresse ein';
}

// Mobilnummer bereinigen (führende 0 entfernen)
if (!empty($data['customer_phone_mobile'])) {
    $data['customer_phone_mobile'] = ltrim(trim($data['customer_phone_mobile']), '0');
}

// Festnetz bereinigen (führende 0 entfernen)
if (!empty($data['customer_phone_landline'])) {
    $data['customer_phone_landline'] = ltrim(trim($data['customer_phone_landline']), '0');
}

if (empty($data['customer_phone_mobile'])) {
    $errors[] = 'Bitte geben Sie Ihre Mobilnummer ein';
}

// Adresse validieren
if (empty($data['customer_street']) || strlen(trim($data['customer_street'])) < 2) {
    $errors[] = 'Bitte geben Sie Ihre Straße ein';
}

if (empty($data['customer_house_number'])) {
    $errors[] = 'Bitte geben Sie Ihre Hausnummer ein';
}

if (empty($data['customer_postal_code']) || !preg_match('/^\d{5}$/', $data['customer_postal_code'])) {
    $errors[] = 'Bitte geben Sie eine gültige PLZ ein (5 Ziffern)';
}

if (empty($data['customer_city']) || strlen(trim($data['customer_city'])) < 2) {
    $errors[] = 'Bitte geben Sie Ihren Ort ein';
}

// Fehler zurückgeben
if (!empty($errors)) {
    error_log('Validation Errors: ' . json_encode($errors));
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => implode(', ', $errors),
        'errors' => $errors
    ]);
    exit;
}

error_log('Validation passed - checking availability');

// Doppelbuchung verhindern (nur bei festen Terminen)
if ($data['booking_type'] === 'fixed') {
    $db = Database::getInstance();

    // Einstellungen laden
    $settingRow = $db->querySingle("SELECT setting_value FROM booking_settings WHERE setting_key = 'max_bookings_per_slot'");
    $maxBookingsPerSlot = (int)($settingRow['setting_value'] ?? 1);

    // Aktuelle Buchungen für diesen Slot zählen
    $sql = "SELECT COUNT(*) as count
            FROM bookings
            WHERE booking_date = :date
            AND TIME_FORMAT(booking_time, '%H:%i') = :time
            AND booking_type = 'fixed'
            AND status != 'cancelled'";

    $result = $db->querySingle($sql, [
        ':date' => $data['booking_date'],
        ':time' => $data['booking_time']
    ]);

    $currentBookings = (int)($result['count'] ?? 0);

    if ($currentBookings >= $maxBookingsPerSlot) {
        error_log("Booking rejected - slot full ({$currentBookings}/{$maxBookingsPerSlot})");
        http_response_code(409); // Conflict
        echo json_encode([
            'success' => false,
            'error' => 'Dieser Zeitslot ist leider bereits ausgebucht. Bitte wählen Sie einen anderen Termin.',
            'error_code' => 'SLOT_FULL'
        ]);
        exit;
    }

    error_log("Slot available ({$currentBookings}/{$maxBookingsPerSlot})");
}

error_log('Availability check passed - preparing HelloCash integration');

// HelloCash API Integration
$hellocashUserId = null;
$hellocashClient = new HelloCashClient();

if ($hellocashClient->isConfigured()) {
    $customerData = [
        'firstname' => trim($data['customer_firstname']),
        'lastname' => trim($data['customer_lastname']),
        'email' => trim($data['customer_email']),
        'phone_country' => $data['customer_phone_country'] ?? '+49',
        'phone_mobile' => trim($data['customer_phone_mobile']),
        'phone_landline' => isset($data['customer_phone_landline']) && !empty($data['customer_phone_landline']) ? trim($data['customer_phone_landline']) : null,
        'company' => isset($data['customer_company']) && !empty($data['customer_company']) ? trim($data['customer_company']) : null,
        // Adresse
        'street' => trim($data['customer_street']),
        'house_number' => trim($data['customer_house_number']),
        'postal_code' => trim($data['customer_postal_code']),
        'city' => trim($data['customer_city'])
    ];

    $result = $hellocashClient->findOrCreateUser($customerData);

    if ($result['user_id']) {
        $hellocashUserId = $result['user_id'];
        error_log('HelloCash User: ' . ($result['is_new'] ? 'Neu erstellt' : 'Gefunden') . ' - ID: ' . $hellocashUserId);
    } else if ($result['error']) {
        error_log('HelloCash Error: ' . $result['error']);
    }
} else {
    error_log('HelloCash API not configured');
}

// In Datenbank speichern
$db = Database::getInstance();
error_log('Preparing database insert...');

try {
    $sql = "
        INSERT INTO bookings (
            booking_type,
            service_type,
            booking_date,
            booking_time,
            customer_notes,
            customer_firstname,
            customer_lastname,
            customer_company,
            customer_email,
            customer_phone_country,
            customer_phone_mobile,
            customer_phone_landline,
            customer_street,
            customer_house_number,
            customer_postal_code,
            customer_city,
            hellocash_customer_id,
            status,
            created_at
        ) VALUES (
            :booking_type,
            :service_type,
            :booking_date,
            :booking_time,
            :customer_notes,
            :customer_firstname,
            :customer_lastname,
            :customer_company,
            :customer_email,
            :customer_phone_country,
            :customer_phone_mobile,
            :customer_phone_landline,
            :customer_street,
            :customer_house_number,
            :customer_postal_code,
            :customer_city,
            :hellocash_customer_id,
            'pending',
            NOW()
        )
    ";

    $params = [
        ':booking_type' => $data['booking_type'],
        ':service_type' => $data['service_type'],
        ':booking_date' => $data['booking_date'],
        ':booking_time' => $data['booking_type'] === 'fixed' ? $data['booking_time'] : null,
        ':customer_notes' => isset($data['customer_notes']) ? trim($data['customer_notes']) : null,
        ':customer_firstname' => trim($data['customer_firstname']),
        ':customer_lastname' => trim($data['customer_lastname']),
        ':customer_company' => isset($data['customer_company']) ? trim($data['customer_company']) : null,
        ':customer_email' => trim($data['customer_email']),
        ':customer_phone_country' => $data['customer_phone_country'] ?? '+49',
        ':customer_phone_mobile' => trim($data['customer_phone_mobile']),
        ':customer_phone_landline' => isset($data['customer_phone_landline']) && !empty($data['customer_phone_landline']) ? trim($data['customer_phone_landline']) : null,
        ':customer_street' => trim($data['customer_street']),
        ':customer_house_number' => trim($data['customer_house_number']),
        ':customer_postal_code' => trim($data['customer_postal_code']),
        ':customer_city' => trim($data['customer_city']),
        ':hellocash_customer_id' => $hellocashUserId
    ];

    error_log('Insert params: ' . json_encode($params));
    $bookingId = $db->insert($sql, $params);
    error_log('Booking ID: ' . $bookingId);

    if ($bookingId) {
        // Erfolg
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'booking_id' => $bookingId,
            'message' => 'Termin erfolgreich gebucht'
        ]);

        // Email-Bestätigung an Kunden senden
        $emailService = new EmailService();
        $emailService->sendBookingEmail($bookingId, 'confirmation');

        // Admin-Benachrichtigung senden
        $emailService->sendBookingNotification($bookingId);

    } else {
        throw new Exception('Fehler beim Speichern in der Datenbank');
    }

} catch (Exception $e) {
    // Fehler loggen (detailliert)
    error_log('=== Booking API Error ===');
    error_log('Error Message: ' . $e->getMessage());
    error_log('Error Code: ' . $e->getCode());
    error_log('Error File: ' . $e->getFile() . ':' . $e->getLine());
    error_log('Stack Trace: ' . $e->getTraceAsString());

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Fehler beim Speichern der Buchung'
    ]);
}
