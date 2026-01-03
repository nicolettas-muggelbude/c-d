<?php
/**
 * API-Endpoint: Vollständig ausgebuchte Tage
 * PC-Wittfoot UG
 *
 * GET /api/fully-booked-dates?weeks=4
 * Gibt alle Tage zurück, an denen ALLE Zeitslots ausgebucht sind
 */

header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/../core/config.php';

// Nur GET erlauben
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

try {
    $db = Database::getInstance();

    // Einstellungen laden
    $settings = [];
    $sql = "SELECT setting_key, setting_value FROM booking_settings
            WHERE setting_key IN ('booking_start_time', 'booking_end_time', 'booking_interval_minutes', 'max_bookings_per_slot')";
    $result = $db->query($sql);

    foreach ($result as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }

    $startTime = $settings['booking_start_time'] ?? '11:00';
    $endTime = $settings['booking_end_time'] ?? '17:00';
    $intervalMinutes = (int)($settings['booking_interval_minutes'] ?? 60);
    $maxBookingsPerSlot = (int)($settings['max_bookings_per_slot'] ?? 1);

    // Anzahl verfügbarer Slots pro Tag berechnen
    $currentTime = DateTime::createFromFormat('H:i', $startTime);
    $endTimeObj = DateTime::createFromFormat('H:i', $endTime);
    $slotsPerDay = 0;
    while ($currentTime < $endTimeObj) {
        $slotsPerDay++;
        $currentTime->modify("+{$intervalMinutes} minutes");
    }

    // Max. Buchungen pro Tag
    $maxBookingsPerDay = $slotsPerDay * $maxBookingsPerSlot;

    // Zeitraum festlegen (nächste X Wochen)
    $weeks = (int)($_GET['weeks'] ?? 4);
    $weeks = min(max($weeks, 1), 12); // Zwischen 1 und 12 Wochen

    $today = new DateTime();
    $today->setTime(0, 0, 0);
    $endDate = clone $today;
    $endDate->modify("+{$weeks} weeks");

    // Ausgebuchte Tage finden
    // Ein Tag ist ausgebucht wenn: Anzahl Buchungen >= maxBookingsPerDay
    $sql = "SELECT booking_date, COUNT(*) as booking_count
            FROM bookings
            WHERE booking_date >= :start_date
            AND booking_date < :end_date
            AND booking_type = 'fixed'
            AND status != 'cancelled'
            GROUP BY booking_date
            HAVING booking_count >= :max_bookings";

    $result = $db->query($sql, [
        ':start_date' => $today->format('Y-m-d'),
        ':end_date' => $endDate->format('Y-m-d'),
        ':max_bookings' => $maxBookingsPerDay
    ]);

    $fullyBookedDates = [];
    foreach ($result as $row) {
        $fullyBookedDates[] = $row['booking_date'];
    }

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'fully_booked_dates' => $fullyBookedDates,
        'slots_per_day' => $slotsPerDay,
        'max_bookings_per_day' => $maxBookingsPerDay
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Fehler beim Abrufen der ausgebuchten Tage'
    ]);

    error_log('Fully Booked Dates API Error: ' . $e->getMessage());
}
