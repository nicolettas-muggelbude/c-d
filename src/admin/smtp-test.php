<?php
/**
 * Admin: SMTP Test
 * PC-Wittfoot UG
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();

// Admin-Rechte prüfen
require_admin();

$db = Database::getInstance();

// Erfolgsmeldung
$success = null;
$error = null;
$debug_output = '';

// POST-Handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $test_email = trim($_POST['test_email']);

    // Validierung
    if (!filter_var($test_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Bitte geben Sie eine gültige E-Mail-Adresse ein";
    } else {
        // Test-Email senden
        $emailService = new EmailService();

        // SMTP-Einstellungen laden für Debug-Info
        $smtp = $db->querySingle("SELECT * FROM smtp_settings WHERE id = 1");

        // Aktuelles Datum/Zeit für Test
        $datetime = date('d.m.Y H:i:s');

        $subject = "Test-Email von PC-Wittfoot UG";
        $body = "Dies ist eine Test-Email vom Terminbuchungs-System.\n\n";
        $body .= "Versandt am: {$datetime}\n";
        $body .= "Methode: " . ($smtp['smtp_enabled'] ? "SMTP" : "PHP mail()") . "\n";

        if ($smtp['smtp_enabled']) {
            $body .= "SMTP-Server: {$smtp['smtp_host']}:{$smtp['smtp_port']}\n";
            $body .= "Verschlüsselung: " . strtoupper($smtp['smtp_encryption']) . "\n";
            $body .= "Benutzername: {$smtp['smtp_username']}\n";
        }

        $body .= "\nWenn Sie diese Email erhalten haben, funktioniert der Email-Versand korrekt!\n\n";
        $body .= "---\n";
        $body .= "PC-Wittfoot UG\n";
        $body .= "IT-Service & Reparatur";

        // Test mit direktem PHPMailer-Call für besseres Debugging
        try {
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

            // Debug-Ausgabe aktivieren
            $mail->SMTPDebug = 2;
            $mail->Debugoutput = function($str, $level) use (&$debug_output) {
                $debug_output .= htmlspecialchars($str) . "<br>";
            };

            // SMTP-Einstellungen
            if ($smtp && $smtp['smtp_enabled']) {
                $mail->isSMTP();
                $mail->Host       = $smtp['smtp_host'];
                $mail->SMTPAuth   = !empty($smtp['smtp_username']);
                $mail->Username   = $smtp['smtp_username'];
                $mail->Password   = $smtp['smtp_password'];
                $mail->SMTPSecure = $smtp['smtp_encryption'] !== 'none' ? $smtp['smtp_encryption'] : '';
                $mail->Port       = $smtp['smtp_port'];
            } else {
                $mail->isMail();
            }

            // Email-Konfiguration
            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress($test_email);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body = $body;

            // Versenden
            $sent = $mail->send();

            if ($sent) {
                $success = "✅ Test-Email erfolgreich an {$test_email} versendet!";
            }

        } catch (\PHPMailer\PHPMailer\Exception $e) {
            $error = "❌ Fehler beim Versenden: " . $mail->ErrorInfo;
        } catch (\Exception $e) {
            $error = "❌ Fehler: " . $e->getMessage();
        }
    }
}

$page_title = 'SMTP Test | Admin | PC-Wittfoot UG';
$page_description = 'SMTP-Test';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container" style="max-width: 800px;">
        <div class="mb-lg">
            <a href="<?= BASE_URL ?>/admin/smtp-settings" class="btn btn-outline btn-sm">← SMTP-Einstellungen</a>
        </div>

        <h1>🧪 SMTP Test</h1>
        <p class="lead mb-xl">Sende eine Test-Email um die SMTP-Konfiguration zu überprüfen</p>

        <?php if ($success): ?>
            <div class="alert alert-success mb-lg"><?= $success ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error mb-lg"><?= $error ?></div>
        <?php endif; ?>

        <!-- Formular -->
        <form method="POST" class="card">
            <div class="form-group">
                <label for="test_email"><strong>Test-Email-Adresse</strong></label>
                <input type="email" id="test_email" name="test_email" class="form-control"
                       value="<?= e($_POST['test_email'] ?? MAIL_ADMIN) ?>"
                       placeholder="admin@pc-wittfoot.de" required>
                <small class="text-muted">Email-Adresse an die die Test-Email gesendet wird</small>
            </div>

            <button type="submit" class="btn btn-primary">📧 Test-Email senden</button>
        </form>

        <?php if (!empty($debug_output)): ?>
            <!-- Debug-Ausgabe -->
            <div class="card mt-lg" style="background: #f8f9fa;">
                <h3>Debug-Ausgabe</h3>
                <div style="font-family: monospace; font-size: 0.85rem; background: white; padding: 1rem; border-radius: 4px; overflow-x: auto; max-height: 400px; overflow-y: auto;">
                    <?= $debug_output ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Hinweise -->
        <div class="card mt-lg" style="background-color: #e7f3ff; border-left: 4px solid #007bff;">
            <h3>💡 Hinweise</h3>
            <ul style="margin: 0; padding-left: 1.5rem;">
                <li>Die Test-Email wird mit den aktuellen SMTP-Einstellungen versendet</li>
                <li>Bei Problemen: Debug-Level in den SMTP-Einstellungen auf "Verbose" stellen</li>
                <li><strong>Gmail:</strong> Verwenden Sie ein App-Passwort (nicht Ihr normales Passwort)</li>
                <li><strong>Office365:</strong> Möglicherweise muss "weniger sichere Apps" aktiviert werden</li>
                <li>Prüfen Sie Ihren Spam-Ordner falls die Email nicht ankommt</li>
            </ul>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
