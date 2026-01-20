<?php
/**
 * Admin - Passwort vergessen
 * E-Mail-Adresse eingeben, um Reset-Link zu erhalten
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();

// Bereits eingeloggt? -> zum Dashboard
if (is_admin()) {
    redirect(BASE_URL . '/admin');
}

$error = '';
$success = '';
$security = new Security();

// Formular-Verarbeitung
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $csrf_token = $_POST['csrf_token'] ?? '';

    // CSRF-Check
    if (!csrf_verify($csrf_token)) {
        $error = 'Ungültiger Sicherheitstoken.';
    } elseif (empty($email)) {
        $error = 'Bitte E-Mail-Adresse eingeben.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Ungültige E-Mail-Adresse.';
    } else {
        // Rate-Limiting prüfen (IP-basiert) - max 3 Anfragen pro Stunde
        $rateLimitIP = $security->checkRateLimit($security->getClientIP() . '_password_reset', 'ip', 3, 3600);

        if (!$rateLimitIP['allowed']) {
            $error = 'Zu viele Anfragen. Bitte versuchen Sie es später erneut.';
        } else {
            // User aus DB laden
            $db = Database::getInstance();
            $user = $db->querySingle("
                SELECT * FROM users
                WHERE email = :email AND role = 'admin'
            ", [':email' => $email]);

            if ($user) {
                // Token generieren (kryptografisch sicher)
                $token = bin2hex(random_bytes(32)); // 64 Zeichen
                $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1 Stunde gültig

                // Alte Tokens für diesen User löschen
                $db->delete("
                    DELETE FROM password_reset_tokens
                    WHERE user_id = :user_id
                ", [':user_id' => $user['id']]);

                // Neuen Token speichern
                $db->insert("
                    INSERT INTO password_reset_tokens (user_id, token, expires_at, ip_address, user_agent)
                    VALUES (:user_id, :token, :expires_at, :ip, :user_agent)
                ", [
                    ':user_id' => $user['id'],
                    ':token' => $token,
                    ':expires_at' => $expiresAt,
                    ':ip' => $security->getClientIP(),
                    ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
                ]);

                // Reset-Link erstellen
                $resetLink = BASE_URL . '/admin/reset-password?token=' . $token;

                // E-Mail versenden
                try {
                    $emailSent = send_email(
                        $user['email'],
                        'Passwort zurücksetzen - PC-Wittfoot UG',
                        "Hallo " . e($user['full_name'] ?? $user['username']) . ",\n\n" .
                        "Sie haben eine Passwort-Zurücksetzung angefordert.\n\n" .
                        "Klicken Sie auf den folgenden Link, um Ihr Passwort zurückzusetzen:\n" .
                        $resetLink . "\n\n" .
                        "Dieser Link ist 1 Stunde gültig.\n\n" .
                        "Falls Sie diese Anfrage nicht gestellt haben, ignorieren Sie diese E-Mail.\n\n" .
                        "Mit freundlichen Grüßen,\n" .
                        "PC-Wittfoot UG"
                    );

                    if ($emailSent) {
                        // Audit-Log
                        $security->logAudit('password_reset_requested', 'user', $user['id']);

                        $success = 'Ein Link zum Zurücksetzen Ihres Passworts wurde an Ihre E-Mail-Adresse gesendet.';
                    } else {
                        $error = 'E-Mail konnte nicht versendet werden. Bitte kontaktieren Sie den Administrator.';
                    }
                } catch (Exception $e) {
                    error_log('Password reset email failed: ' . $e->getMessage());
                    $error = 'E-Mail konnte nicht versendet werden. Bitte versuchen Sie es später erneut.';
                }
            } else {
                // Aus Sicherheitsgründen IMMER Erfolg melden (auch wenn User nicht existiert)
                // Verhindert E-Mail-Enumeration
                $success = 'Falls diese E-Mail-Adresse registriert ist, wurde ein Link zum Zurücksetzen des Passworts versendet.';
            }
        }
    }
}

$page_title = 'Passwort vergessen | PC-Wittfoot UG';
$current_page = '';
include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <div class="row justify-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card">
                    <div style="text-align: center; font-size: 3em; margin-bottom: var(--space-md);">🔑</div>
                    <h1 class="text-center mb-lg">Passwort vergessen?</h1>

                    <?php if ($error): ?>
                        <div class="alert alert-error mb-lg"><?= e($error) ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success mb-lg"><?= e($success) ?></div>
                        <div class="text-center">
                            <a href="<?= BASE_URL ?>/admin/login" class="btn btn-primary">Zurück zum Login</a>
                        </div>
                    <?php else: ?>
                        <p class="text-center mb-lg">
                            Geben Sie Ihre E-Mail-Adresse ein. Wir senden Ihnen einen Link zum Zurücksetzen Ihres Passworts.
                        </p>

                        <form method="post">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                            <div class="form-group">
                                <label for="email">E-Mail-Adresse</label>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       value="<?= e($_POST['email'] ?? '') ?>"
                                       required
                                       autofocus>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block btn-lg">
                                Link anfordern
                            </button>
                        </form>

                        <hr style="margin: var(--space-lg) 0;">

                        <div class="text-center">
                            <a href="<?= BASE_URL ?>/admin/login" class="text-muted" style="font-size: 0.9em;">
                                ← Zurück zum Login
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
