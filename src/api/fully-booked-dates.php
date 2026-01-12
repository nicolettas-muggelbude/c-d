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

    // Alle Buchungen für den Zeitraum holen (inkl. Zeitspannen)
    $sql = "SELECT booking_date, booking_type, booking_time, booking_end_time
            FROM bookings
            WHERE booking_date >= :start_date
            AND booking_date < :end_date
            AND booking_type IN ('fixed', 'blocked')
            AND status != 'cancelled'";

    $bookings = $db->query($sql, [
        ':start_date' => $today->format('Y-m-d'),
        ':end_date' => $endDate->format('Y-m-d')
    ]);

    // Buchungen nach Datum gruppieren
    $bookingsByDate = [];
    foreach ($bookings as $booking) {
        $date = $booking['booking_date'];
        if (!isset($bookingsByDate[$date])) {
            $bookingsByDate[$date] = [];
        }
        $bookingsByDate[$date][] = $booking;
    }

    // Alle verfügbaren Slots generieren
    $allSlots = [];
    $currentTime = DateTime::createFromFormat('H:i', $startTime);
    $endTimeObj = DateTime::createFromFormat('H:i', $endTime);
    while ($currentTime < $endTimeObj) {
        $allSlots[] = $currentTime->format('H:i');
        $currentTime->modify("+{$intervalMinutes} minutes");
    }

    // Für jeden Tag prüfen ob ausgebucht
    $fullyBookedDates = [];
    foreach ($bookingsByDate as $date => $dayBookings) {
        $bookedSlotsCount = 0;

        // Für jeden Slot prüfen ob er belegt ist
        foreach ($allSlots as $slot) {
            $slotBookedCount = 0;

            foreach ($dayBookings as $booking) {
                if ($booking['booking_type'] === 'fixed') {
                    // Feste Termine: Exact match
                    $bookingTime = substr($booking['booking_time'], 0, 5);
                    if ($bookingTime === $slot) {
                        $slotBookedCount++;
                    }
                } else if ($booking['booking_type'] === 'blocked') {
                    // Blockierungen: Zeitspannen-Check
                    $blockStart = substr($booking['booking_time'], 0, 5);
                    $blockEnd = $booking['booking_end_time'] ? substr($booking['booking_end_time'], 0, 5) : null;

                    if ($slot >= $blockStart && ($blockEnd === null || $slot < $blockEnd)) {
                        // Slot ist geblockt - als voll zählen
                        $slotBookedCount = $maxBookingsPerSlot;
                        break;
                    }
                }
            }

            // Wenn Slot voll ist, als belegt zählen
            if ($slotBookedCount >= $maxBookingsPerSlot) {
                $bookedSlotsCount++;
            }
        }

        // Tag ist ausgebucht wenn alle Slots voll sind
        if ($bookedSlotsCount >= $slotsPerDay) {
            $fullyBookedDates[] = $date;
        }
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
