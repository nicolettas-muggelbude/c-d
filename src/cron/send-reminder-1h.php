<?php
/**
 * Cron-Job: 1-Stunden Erinnerungen versenden
 * PC-Wittfoot UG
 *
 * Läuft stündlich (z.B. jede volle Stunde)
 * Versendet Erinnerungs-Emails für Termine in ca. 1 Stunde
 */

require_once __DIR__ . '/../core/config.php';

// Sicherstellen dass nur CLI-Zugriff erlaubt ist
if (php_sapi_name() !== 'cli') {
    // Alternative: IP-Check für Webserver-Cron
    // if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1') {
    //     http_response_code(403);
    //     exit('Access denied');
    // }
}

$emailService = new EmailService();

// Buchungen für 1h-Erinnerung holen
$bookings = $emailService->getBookingsForReminder1h();

$sent = 0;
$failed = 0;

foreach ($bookings as $booking) {
    $success = $emailService->sendBookingEmail($booking['id'], 'reminder_1h');

    if ($success) {
        $sent++;
    } else {
        $failed++;
    }
}

$message = date('Y-m-d H:i:s') . " - 1h-Reminders: $sent sent, $failed failed\n";
echo $message;
error_log($message);

// Exit-Code für Cron-Monitoring
exit($failed > 0 ? 1 : 0);
