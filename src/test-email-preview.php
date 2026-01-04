<?php
/**
 * Email-Template Vorschau (HTML + Plaintext)
 * PC-Wittfoot UG
 *
 * Zum Testen: http://localhost:8000/test-email-preview.php?type=confirmation&id=17
 */

require_once __DIR__ . '/core/config.php';
require_once __DIR__ . '/core/database.php';
require_once __DIR__ . '/core/EmailService.php';

// Parameter
$templateType = $_GET['type'] ?? 'confirmation';
$bookingId = $_GET['id'] ?? 1;

$db = Database::getInstance();
$emailService = new EmailService();

// Buchung laden
$booking = $db->querySingle("SELECT * FROM bookings WHERE id = :id", [':id' => $bookingId]);

if (!$booking) {
    die("Buchung nicht gefunden");
}

// Template laden
$template = $db->querySingle(
    "SELECT * FROM email_templates WHERE template_type = :type",
    [':type' => $templateType]
);

if (!$template) {
    die("Template nicht gefunden");
}

// Signatur laden
$sig = $db->querySingle("SELECT signature_text FROM email_signature WHERE id = 1");
$signature = $sig['signature_text'] ?? '';

// Platzhalter ersetzen (vereinfacht - nutzt EmailService intern)
$reflectionClass = new ReflectionClass($emailService);
$method = $reflectionClass->getMethod('replacePlaceholders');
$method->setAccessible(true);

$body = $method->invoke($emailService, $template['body'], $booking);
$subject = $method->invoke($emailService, $template['subject'], $booking);

// HTML-Version
$signatureHtml = nl2br($signature);
$fullBodyHtml = $body . "\n\n" . $signatureHtml;

// Plaintext-Version
$fullBodyPlain = strip_tags($body) . "\n\n" . $signature;

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email-Vorschau: <?php echo htmlspecialchars($templateType); ?></title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .header {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .panel {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            margin: 0 0 10px 0;
            color: #333;
        }
        h2 {
            margin: 0 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #8BC34A;
            color: #8BC34A;
        }
        .meta {
            color: #666;
            font-size: 14px;
        }
        .email-content {
            border: 1px solid #ddd;
            padding: 15px;
            background: #fafafa;
            border-radius: 4px;
            font-size: 14px;
            line-height: 1.6;
        }
        .plaintext {
            white-space: pre-wrap;
            font-family: 'Courier New', monospace;
        }
        .controls {
            margin-bottom: 15px;
            padding: 10px;
            background: #f0f0f0;
            border-radius: 4px;
        }
        select, input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }
        button {
            padding: 8px 16px;
            background: #8BC34A;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #7CB342;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📧 Email-Template Vorschau</h1>
        <div class="meta">
            <strong>Template:</strong> <?php echo htmlspecialchars($templateType); ?> |
            <strong>Buchung:</strong> #<?php echo htmlspecialchars($bookingId); ?> |
            <strong>Betreff:</strong> <?php echo htmlspecialchars($subject); ?>
        </div>

        <div class="controls" style="margin-top: 15px;">
            <form method="GET">
                <label>Template:</label>
                <select name="type" onchange="this.form.submit()">
                    <option value="confirmation" <?php echo $templateType === 'confirmation' ? 'selected' : ''; ?>>Bestätigung</option>
                    <option value="cancellation" <?php echo $templateType === 'cancellation' ? 'selected' : ''; ?>>Stornierung</option>
                    <option value="reschedule" <?php echo $templateType === 'reschedule' ? 'selected' : ''; ?>>Terminänderung</option>
                    <option value="booking_notification" <?php echo $templateType === 'booking_notification' ? 'selected' : ''; ?>>Admin: Neue Buchung</option>
                    <option value="admin_cancellation" <?php echo $templateType === 'admin_cancellation' ? 'selected' : ''; ?>>Admin: Stornierung</option>
                    <option value="admin_reschedule" <?php echo $templateType === 'admin_reschedule' ? 'selected' : ''; ?>>Admin: Änderung</option>
                    <option value="reminder_24h" <?php echo $templateType === 'reminder_24h' ? 'selected' : ''; ?>>Erinnerung 24h</option>
                    <option value="reminder_1h" <?php echo $templateType === 'reminder_1h' ? 'selected' : ''; ?>>Erinnerung 1h</option>
                </select>

                <label>Buchung-ID:</label>
                <input type="number" name="id" value="<?php echo htmlspecialchars($bookingId); ?>" min="1">

                <button type="submit">Aktualisieren</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="panel">
            <h2>🎨 HTML-Version</h2>
            <div class="email-content">
                <?php echo $fullBodyHtml; ?>
            </div>
        </div>

        <div class="panel">
            <h2>📄 Plaintext-Version</h2>
            <div class="email-content plaintext"><?php echo htmlspecialchars($fullBodyPlain); ?></div>
        </div>
    </div>
</body>
</html>
