<?php
/**
 * Bestehende "Ich komme vorbei" Termine mit Zeitslots versehen
 * PC-Wittfoot UG
 */

require_once __DIR__ . '/../src/core/config.php';

$db = Database::getInstance();

// Alle Walk-in Termine ohne Zeit finden
$walkinsWithoutTime = $db->query("
    SELECT id, booking_date
    FROM bookings
    WHERE booking_type = 'walkin'
    AND (booking_time IS NULL OR booking_time = '00:00:00')
    AND status != 'cancelled'
    ORDER BY booking_date, id
");

echo "Gefunden: " . count($walkinsWithoutTime) . " Walk-in Termine ohne Zeit\n\n";

// Nach Datum gruppieren
$byDate = [];
foreach ($walkinsWithoutTime as $booking) {
    $date = $booking['booking_date'];
    if (!isset($byDate[$date])) {
        $byDate[$date] = [];
    }
    $byDate[$date][] = $booking;
}

// Slots zuweisen
$slots = ['14:00:00', '15:00:00', '16:00:00'];
$updated = 0;

foreach ($byDate as $date => $bookings) {
    echo "Datum: $date (" . count($bookings) . " Termine)\n";

    foreach ($bookings as $index => $booking) {
        $assignedSlot = $slots[$index % 3];

        $db->update(
            "UPDATE bookings SET booking_time = :time WHERE id = :id",
            [':time' => $assignedSlot, ':id' => $booking['id']]
        );

        echo "  ✓ Buchung #{$booking['id']}: $assignedSlot\n";
        $updated++;
    }
    echo "\n";
}

echo "✅ Insgesamt $updated Termine aktualisiert!\n";
