<?php
/**
 * Admin - 2FA Setup
 * Zwei-Faktor-Authentifizierung einrichten
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();
require_admin();

$db = Database::getInstance();
$userId = $_SESSION['user_id'];

// 2FA-Status laden
$twofa = $db->querySingle("SELECT * FROM user_2fa WHERE user_id = :user_id", [
    ':user_id' => $userId
]);

$step = $_GET['step'] ?? ($twofa && $twofa['enabled'] ? 'manage' : 'setup');
$error = '';
$success = '';

// Debug-Logging
error_log("2FA Setup: Current step = " . $step);
error_log("2FA Setup: twofa exists = " . ($twofa ? 'YES' : 'NO'));
error_log("2FA Setup: twofa enabled = " . ($twofa && $twofa['enabled'] ? 'TRUE' : 'FALSE'));
error_log("2FA Setup: POST method = " . $_SERVER['REQUEST_METHOD']);

// Step 1: Secret generieren und QR-Code anzeigen
if ($step === 'setup') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'generate') {
        // Neues Secret generieren
        $secret = TOTP::generateSecret();
        $backupCodes = TOTP::generateBackupCodes();

        // In DB speichern (noch nicht aktiviert)
        $db->insert("
            INSERT INTO user_2fa (user_id, secret, enabled, backup_codes)
            VALUES (:user_id, :secret, FALSE, :backup_codes)
            ON DUPLICATE KEY UPDATE secret = :secret2, backup_codes = :backup_codes2
        ", [
            ':user_id' => $userId,
            ':secret' => $secret,
            ':backup_codes' => json_encode($backupCodes),
            ':secret2' => $secret,
            ':backup_codes2' => json_encode($backupCodes)
        ]);

        // Neu laden
        $twofa = $db->querySingle("SELECT * FROM user_2fa WHERE user_id = :user_id", [
            ':user_id' => $userId
        ]);

        $step = 'verify';
    }
}

// Step 2: Code verifizieren und aktivieren
if ($step === 'verify' && $twofa && !$twofa['enabled']) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code'])) {
        $code = $_POST['code'];

        // Debug-Logging
        error_log("2FA Verify: Code eingegeben: " . $code);
        error_log("2FA Verify: Secret: " . $twofa['secret']);

        if (TOTP::verify($twofa['secret'], $code)) {
            error_log("2FA Verify: Code ist GÜLTIG - aktiviere 2FA");

            // 2FA aktivieren
            $db->update("
                UPDATE user_2fa SET enabled = TRUE WHERE user_id = :user_id
            ", [':user_id' => $userId]);

            $success = '2FA wurde erfolgreich aktiviert!';
            $step = 'manage';

            // Neu laden
            $twofa = $db->querySingle("SELECT * FROM user_2fa WHERE user_id = :user_id", [
                ':user_id' => $userId
            ]);

            error_log("2FA Verify: Enabled nach Update: " . ($twofa['enabled'] ? 'TRUE' : 'FALSE'));
        } else {
            error_log("2FA Verify: Code ist UNGÜLTIG");
            $error = 'Ungültiger Code. Bitte versuchen Sie es erneut.';
        }
    }
}

// 2FA deaktivieren
if ($step === 'manage' && isset($_POST['action']) && $_POST['action'] === 'disable') {
    if (csrf_verify($_POST['csrf_token'] ?? '')) {
        $db->delete("DELETE FROM user_2fa WHERE user_id = :user_id", [':user_id' => $userId]);
        $success = '2FA wurde deaktiviert.';
        $twofa = null;
        $step = 'setup';
    }
}

// Backup-Codes neu generieren
if ($step === 'manage' && isset($_POST['action']) && $_POST['action'] === 'regenerate_codes') {
    if (csrf_verify($_POST['csrf_token'] ?? '')) {
        $backupCodes = TOTP::generateBackupCodes();
        $db->update("
            UPDATE user_2fa SET backup_codes = :codes WHERE user_id = :user_id
        ", [
            ':user_id' => $userId,
            ':codes' => json_encode($backupCodes)
        ]);

        $twofa['backup_codes'] = json_encode($backupCodes);
        $success = 'Backup-Codes wurden neu generiert.';
    }
}

$page_title = '2FA Einstellungen | Admin | PC-Wittfoot UG';
$current_page = '';
include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <div class="d-flex justify-between align-center mb-lg" style="flex-wrap: wrap; gap: var(--space-md);">
            <h1 class="mb-0">Zwei-Faktor-Authentifizierung</h1>
            <a href="<?= BASE_URL ?>/admin" class="btn btn-outline">← Dashboard</a>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error mb-lg"><?= e($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success mb-lg"><?= e($success) ?></div>
        <?php endif; ?>

        <?php if ($step === 'setup'): ?>
            <!-- Schritt 1: Setup starten -->
            <div class="card">
                <h2>2FA einrichten</h2>
                <p>Erhöhen Sie die Sicherheit Ihres Accounts durch Zwei-Faktor-Authentifizierung.</p>

                <div class="info-box" style="background: #e3f2fd; padding: var(--space-md); border-radius: var(--border-radius-md); margin: var(--space-lg) 0;">
                    <h3 style="margin-top: 0;">Was ist 2FA?</h3>
                    <p>Mit 2FA benötigen Sie neben Ihrem Passwort einen zusätzlichen Code aus einer Authenticator-App:</p>
                    <ul>
                        <li>Google Authenticator</li>
                        <li>Authy</li>
                        <li>Microsoft Authenticator</li>
                        <li>Oder eine andere TOTP-kompatible App</li>
                    </ul>
                </div>

                <form method="post">
                    <input type="hidden" name="action" value="generate">
                    <button type="submit" class="btn btn-primary">2FA jetzt einrichten</button>
                </form>
            </div>

        <?php elseif ($step === 'verify' && $twofa): ?>
            <!-- Schritt 2: QR-Code scannen und verifizieren -->
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="card mb-lg">
                        <h2>Schritt 1: App einrichten</h2>
                        <p>Scannen Sie diesen QR-Code mit Ihrer Authenticator-App:</p>

                        <?php
                        $user = $db->querySingle("SELECT email FROM users WHERE id = :id", [':id' => $userId]);
                        $otpauthUrl = TOTP::getQRCodeUrl($twofa['secret'], $user['email']);
                        ?>

                        <div id="qrcode" style="text-align: center; margin: var(--space-lg) 0; display: flex; justify-content: center;"></div>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
                        <script>
                        new QRCode(document.getElementById("qrcode"), {
                            text: "<?= e($otpauthUrl) ?>",
                            width: 200,
                            height: 200,
                            colorDark: "#000000",
                            colorLight: "#ffffff",
                            correctLevel: QRCode.CorrectLevel.M
                        });
                        </script>

                        <div style="background: #f5f5f5; padding: var(--space-md); border-radius: var(--border-radius-sm); text-align: center;">
                            <small>Manueller Schlüssel:</small><br>
                            <code style="font-size: 1.1em; letter-spacing: 2px;"><?= e(chunk_split($twofa['secret'], 4, ' ')) ?></code>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card mb-lg">
                        <h2>Schritt 2: Code verifizieren</h2>
                        <p>Geben Sie den 6-stelligen Code aus Ihrer App ein:</p>

                        <form method="post">
                            <div class="form-group">
                                <label for="code">Verifizierungscode</label>
                                <input type="text"
                                       id="code"
                                       name="code"
                                       placeholder="000000"
                                       maxlength="6"
                                       pattern="[0-9]{6}"
                                       required
                                       autofocus
                                       style="font-size: 1.5em; letter-spacing: 0.5em; text-align: center;">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Verifizieren & Aktivieren</button>
                        </form>
                    </div>

                    <div class="card" style="margin-bottom: var(--space-xl);">
                        <h3 style="margin-top: 0;">Backup-Codes</h3>
                        <p style="margin-bottom: var(--space-md);"><small>Speichern Sie diese Codes sicher! Sie können damit einloggen, falls Ihre App nicht verfügbar ist.</small></p>
                        <?php
                        $codes = json_decode($twofa['backup_codes'], true);
                        echo '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: var(--space-sm); font-family: monospace; margin-top: var(--space-md);">';
                        foreach ($codes as $code) {
                            echo '<div style="background: #f5f5f5; padding: var(--space-md); text-align: center; border-radius: 4px;">' . e($code) . '</div>';
                        }
                        echo '</div>';
                        ?>
                    </div>
                </div>
            </div>

        <?php elseif ($step === 'manage' && $twofa && $twofa['enabled']): ?>
            <!-- 2FA ist aktiviert - Verwaltung -->
            <div class="card">
                <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                    <div style="font-size: 3em;">✅</div>
                    <div>
                        <h2 style="margin: 0;">2FA ist aktiviert</h2>
                        <p style="margin: 0; color: var(--color-success);">Ihr Account ist durch Zwei-Faktor-Authentifizierung geschützt.</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <h3>Backup-Codes</h3>
                        <p><small>Verwenden Sie diese Codes, falls Ihre Authenticator-App nicht verfügbar ist.</small></p>
                        <?php
                        $codes = json_decode($twofa['backup_codes'], true);
                        echo '<div style="display: grid; grid-template-columns: 1fr; gap: var(--space-sm); font-family: monospace; margin-bottom: var(--space-md);">';
                        foreach ($codes as $code) {
                            echo '<div style="background: #f5f5f5; padding: var(--space-sm); text-align: center;">' . e($code) . '</div>';
                        }
                        echo '</div>';
                        ?>

                        <form method="post" style="display: inline;">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            <input type="hidden" name="action" value="regenerate_codes">
                            <button type="submit" class="btn btn-outline btn-sm">Codes neu generieren</button>
                        </form>
                    </div>

                    <div class="col-12 col-md-6">
                        <h3>2FA deaktivieren</h3>
                        <p><small>Warnung: Dadurch wird Ihr Account weniger sicher!</small></p>

                        <form method="post" onsubmit="return confirm('2FA wirklich deaktivieren? Dies macht Ihren Account weniger sicher.')">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            <input type="hidden" name="action" value="disable">
                            <button type="submit" class="btn btn-outline" style="color: var(--color-error); border-color: var(--color-error);">
                                2FA deaktivieren
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
// Auto-submit bei 6 Ziffern
const codeInput = document.getElementById('code');
if (codeInput) {
    codeInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length === 6) {
            this.form.submit();
        }
    });
}
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
