<?php
/**
 * API-Endpoint: Email-Vorschau
 * PC-Wittfoot UG
 *
 * GET /api/email-preview?type=confirmation&id=17
 */

header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../core/database.php';
require_once __DIR__ . '/../core/EmailService.php';

// Nur GET erlauben
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$templateType = $_GET['type'] ?? '';
$bookingId = (int)($_GET['id'] ?? 1);

if (empty($templateType)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Template-Typ erforderlich']);
    exit;
}

try {
    $db = Database::getInstance();

    // Buchung laden
    $booking = $db->querySingle("SELECT * FROM bookings WHERE id = :id", [':id' => $bookingId]);

    if (!$booking) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Buchung nicht gefunden']);
        exit;
    }

    // Template laden
    $template = $db->querySingle(
        "SELECT * FROM email_templates WHERE template_type = :type",
        [':type' => $templateType]
    );

    if (!$template) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Template nicht gefunden']);
        exit;
    }

    // Signatur laden
    $sig = $db->querySingle("SELECT signature_text FROM email_signature WHERE id = 1");
    $signature = $sig['signature_text'] ?? '';

    // EmailService nutzen für Platzhalter-Ersetzung
    $emailService = new EmailService();
    $reflectionClass = new ReflectionClass($emailService);
    $method = $reflectionClass->getMethod('replacePlaceholders');
    $method->setAccessible(true);

    // Platzhalter ersetzen
    $body = $method->invoke($emailService, $template['body'], $booking);
    $subject = $method->invoke($emailService, $template['subject'], $booking);

    // HTML-Version mit Signatur
    $signatureHtml = nl2br($signature);
    $fullBodyHtml = $body . "\n\n" . $signatureHtml;

    // Plaintext-Version mit Signatur
    $fullBodyPlain = strip_tags($body) . "\n\n" . $signature;

    // Erfolg
    echo json_encode([
        'success' => true,
        'subject' => $subject,
        'html' => $fullBodyHtml,
        'plain' => $fullBodyPlain
    ]);

} catch (Exception $e) {
    error_log('Email preview error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Fehler beim Generieren der Vorschau'
    ]);
}
