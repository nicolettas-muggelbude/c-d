<?php
/**
 * API Endpoint: Terminänderung
 * PC-Wittfoot UG
 *
 * Kunde kann Termin (Datum/Zeit) ändern via Magic Link Token
 * Validierung: >= 48h vor Termin
 */

require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../core/database.php';
require_once __DIR__ . '/../core/EmailService.php';

header('Content-Type: application/json');

// Nur POST-Requests erlauben
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// JSON-Input lesen
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    exit;
}

$token = $input['token'] ?? null;
$newDate = $input['new_date'] ?? null;
$newTime = $input['new_time'] ?? null;

// Validierung
if (!$token) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Token erforderlich']);
    exit;
}

if (!$newDate) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Neues Datum erforderlich']);
    exit;
}

try {
    $db = Database::getInstance();

    // Buchung anhand Token laden
    $sql = "SELECT * FROM bookings WHERE manage_token = :token LIMIT 1";
    $booking = $db->querySingle($sql, [':token' => $token]);

    if (!$booking) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Buchung nicht gefunden']);
        exit;
    }

    // Status prüfen
    if ($booking['status'] === 'cancelled') {
        http_response_code(409);
        echo json_encode(['success' => false, 'error' => 'Stornierte Buchungen können nicht geändert werden']);
        exit;
    }

    if ($booking['status'] === 'completed') {
        http_response_code(409);
        echo json_encode(['success' => false, 'error' => 'Abgeschlossene Buchungen können nicht geändert werden']);
        exit;
    }

    // Zeitlimit prüfen (nur für feste Termine: >= 48h vor Termin)
    // Walk-ins können jederzeit geändert werden
    if ($booking['booking_type'] === 'fixed') {
        $bookingDateTime = new DateTime($booking['booking_date'] . ' ' . ($booking['booking_time'] ?? '00:00:00'));
        $now = new DateTime();
        $hoursUntil = ($bookingDateTime->getTimestamp() - $now->getTimestamp()) / 3600;

        if ($hoursUntil < 48) {
            http_response_code(409);
            echo json_encode([
                'success' => false,
                'error' => 'Terminänderungen sind nur bis 48 Stunden vor dem Termin möglich. Bitte kontaktieren Sie uns telefonisch.'
            ]);
            exit;
        }
    }

    // Neuen Termintyp basierend auf Uhrzeit bestimmen
    $newBookingType = $newTime ? 'fixed' : 'walkin';

    // Bei festem Termin: Verfügbarkeit des neuen Slots prüfen
    if ($newBookingType === 'fixed') {
        // Prüfen ob der neue Slot noch verfügbar ist
        $slotCheckSql = "SELECT COUNT(*) as booking_count
                         FROM bookings
                         WHERE booking_date = :date
                         AND TIME_FORMAT(booking_time, '%H:%i') = TIME_FORMAT(:time, '%H:%i')
                         AND booking_type = 'fixed'
                         AND status IN ('pending', 'confirmed')
                         AND id != :current_booking_id";

        $slotCheck = $db->querySingle($slotCheckSql, [
            ':date' => $newDate,
            ':time' => $newTime,
            ':current_booking_id' => $booking['id']
        ]);

        $maxBookingsPerSlot = 2; // Aus config oder Settings
        if ($slotCheck['booking_count'] >= $maxBookingsPerSlot) {
            http_response_code(409);
            echo json_encode([
                'success' => false,
                'error' => 'Dieser Zeitslot ist leider bereits ausgebucht. Bitte wählen Sie einen anderen Termin.',
                'error_code' => 'SLOT_FULL'
            ]);
            exit;
        }
    }

    // Alte Werte für Email speichern und formatieren
    $oldDateRaw = $booking['booking_date'];
    $oldTimeRaw = $booking['booking_time'];

    // Datum formatieren (Deutsch)
    $dateObj = new DateTime($oldDateRaw);
    $weekdays = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
    $months = ['', 'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
               'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];

    $oldDate = $weekdays[(int)$dateObj->format('w')] . ', ' .
               $dateObj->format('d') . '. ' .
               $months[(int)$dateObj->format('n')] . ' ' .
               $dateObj->format('Y');

    // Zeit formatieren (ohne Sekunden)
    $oldTime = substr($oldTimeRaw, 0, 5) . ' Uhr';

    // Buchung aktualisieren
    $updateSql = "UPDATE bookings
                  SET booking_date = :new_date,
                      booking_time = :new_time,
                      booking_type = :new_type,
                      updated_at = NOW()
                  WHERE id = :id";

    $db->update($updateSql, [
        ':new_date' => $newDate,
        ':new_time' => $newTime,
        ':new_type' => $newBookingType,
        ':id' => $booking['id']
    ]);

    // Email-Benachrichtigung senden
    $emailService = new EmailService();

    // Email an Kunden (Terminänderung bestätigt) mit alten Werten
    $emailService->sendBookingEmail(
        $booking['id'],
        'reschedule',
        [
            'old_date' => $oldDate,
            'old_time' => $oldTime
        ],
        true // Duplikat-Prüfung überspringen (erlaubt mehrfache Terminänderungen)
    );

    // Email an Admin (Benachrichtigung über Terminänderung)
    $emailService->sendBookingNotification(
        $booking['id'],
        'admin_reschedule',
        [
            'old_date' => $oldDate,
            'old_time' => $oldTime,
            'admin_link' => BASE_URL . '/admin/booking-detail.php?id=' . $booking['id']
        ],
        true // Duplikat-Prüfung überspringen
    );

    // Erfolg
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Termin erfolgreich geändert',
        'booking_id' => $booking['id']
    ]);

} catch (Exception $e) {
    error_log('Booking reschedule error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.'
    ]);
}
