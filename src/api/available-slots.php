<?php
/**
 * API-Endpoint: Verfügbare Zeitslots
 * PC-Wittfoot UG
 *
 * GET /api/available-slots?date=2026-01-15
 */

header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/../core/config.php';

// Nur GET erlauben
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Datum validieren
$date = $_GET['date'] ?? '';

if (empty($date)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datum erforderlich']);
    exit;
}

$dateObj = DateTime::createFromFormat('Y-m-d', $date);
if (!$dateObj) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Ungültiges Datumsformat']);
    exit;
}

// Prüfen ob Datum in der Zukunft liegt
$today = new DateTime();
$today->setTime(0, 0, 0);
if ($dateObj < $today) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datum muss in der Zukunft liegen']);
    exit;
}

// Prüfen ob erlaubter Wochentag (Di-Fr für feste Termine)
// Samstag wird bei Walk-ins im Frontend geprüft
$dayOfWeek = $dateObj->format('N');
if ($dayOfWeek < 2 || $dayOfWeek > 6) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Termine sind nur Dienstag bis Samstag möglich']);
    exit;
}

// Für feste Termine nur Di-Fr erlauben (Samstag hat keine Zeitslots für fixed)
if ($dayOfWeek === 6) {
    // Samstag: Keine Zeitslots für feste Termine
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'date' => $date,
        'slots' => []
    ]);
    exit;
}

try {
    $db = Database::getInstance();

    // Buchungs-Einstellungen abrufen
    $settings = [];
    $sql = "SELECT setting_key, setting_value FROM booking_settings
            WHERE setting_key IN ('booking_start_time', 'booking_end_time', 'booking_interval_minutes', 'max_bookings_per_slot')";
    $result = $db->query($sql);

    foreach ($result as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }

    // Standard-Werte falls keine Einstellungen vorhanden
    $startTime = $settings['booking_start_time'] ?? '11:00';
    $endTime = $settings['booking_end_time'] ?? '17:00';
    $intervalMinutes = (int)($settings['booking_interval_minutes'] ?? 60);
    $maxBookingsPerSlot = (int)($settings['max_bookings_per_slot'] ?? 1);

    // Alle möglichen Zeitslots generieren
    $slots = [];
    $currentTime = DateTime::createFromFormat('Y-m-d H:i', $date . ' ' . $startTime);
    $endTimeObj = DateTime::createFromFormat('Y-m-d H:i', $date . ' ' . $endTime);

    while ($currentTime < $endTimeObj) {
        $slots[] = $currentTime->format('H:i');
        $currentTime->modify("+{$intervalMinutes} minutes");
    }

    // Gebuchte Slots zählen
    $sql = "SELECT booking_time, COUNT(*) as count
            FROM bookings
            WHERE booking_date = :date
            AND booking_type = 'fixed'
            AND status != 'cancelled'
            GROUP BY booking_time";

    $bookedSlots = [];
    $result = $db->query($sql, [':date' => $date]);

    foreach ($result as $row) {
        $bookedSlots[$row['booking_time']] = (int)$row['count'];
    }

    // Verfügbare Slots filtern
    $availableSlots = [];
    foreach ($slots as $slot) {
        $bookedCount = $bookedSlots[$slot] ?? 0;
        $available = $bookedCount < $maxBookingsPerSlot;

        $availableSlots[] = [
            'time' => $slot,
            'available' => $available,
            'booked' => $bookedCount,
            'max' => $maxBookingsPerSlot
        ];
    }

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'date' => $date,
        'slots' => $availableSlots
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Fehler beim Abrufen der verfügbaren Zeitslots'
    ]);

    error_log('Available Slots API Error: ' . $e->getMessage());
}
