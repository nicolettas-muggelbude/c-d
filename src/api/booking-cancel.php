<?php
/**
 * API-Endpoint: Buchung stornieren
 * PC-Wittfoot UG
 *
 * POST /api/booking-cancel - Buchung via Token stornieren
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

// Token validieren
if (empty($data['token'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Kein Token angegeben'
    ]);
    exit;
}

$db = Database::getInstance();

// Buchung anhand Token finden
$sql = "SELECT * FROM bookings WHERE manage_token = :token LIMIT 1";
$booking = $db->querySingle($sql, [':token' => $data['token']]);

if (!$booking) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'error' => 'Buchung nicht gefunden'
    ]);
    exit;
}

// Prüfen ob bereits storniert
if ($booking['status'] === 'cancelled') {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Diese Buchung wurde bereits storniert'
    ]);
    exit;
}

// Zeitpunkt prüfen (nur >= 24h vorher erlaubt)
$bookingDateTime = new DateTime($booking['booking_date'] . ' ' . ($booking['booking_time'] ?? '00:00:00'));
$now = new DateTime();
$hoursUntil = ($bookingDateTime->getTimestamp() - $now->getTimestamp()) / 3600;

if ($hoursUntil < 24) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Stornierungen sind nur bis 24 Stunden vor dem Termin möglich. Bitte kontaktieren Sie uns telefonisch.'
    ]);
    exit;
}

// Buchung auf 'cancelled' setzen
try {
    $updateSql = "UPDATE bookings SET status = 'cancelled', updated_at = NOW() WHERE id = :id";
    $db->update($updateSql, [':id' => $booking['id']]);

    // Stornierungsbestätigung per Email senden
    $emailService = new EmailService();
    $emailService->sendBookingEmail($booking['id'], 'cancellation');

    // Admin benachrichtigen (Stornierung)
    $emailService->sendBookingNotification($booking['id'], 'admin_cancellation');

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Buchung erfolgreich storniert'
    ]);

} catch (Exception $e) {
    error_log('Booking cancellation error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Fehler beim Stornieren der Buchung'
    ]);
}
