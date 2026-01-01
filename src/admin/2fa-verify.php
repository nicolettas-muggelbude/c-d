<?php
/**
 * Admin - 2FA Verification
 * Zwei-Faktor-Code-Eingabe nach erfolgreichem Login
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();

// Bereits vollständig eingeloggt? -> Dashboard
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    redirect(BASE_URL . '/admin');
}

// Keine 2FA-Pending Session? -> Zurück zum Login
if (!isset($_SESSION['2fa_user_id']) || !isset($_SESSION['2fa_pending'])) {
    redirect(BASE_URL . '/admin/login');
}

$db = Database::getInstance();
$userId = $_SESSION['2fa_user_id'];
$error = '';

// 2FA-Daten laden
$twofa = $db->querySingle("SELECT * FROM user_2fa WHERE user_id = :user_id AND enabled = TRUE", [
    ':user_id' => $userId
]);

if (!$twofa) {
    // Kein 2FA konfiguriert, aber Session sagt 2FA required? Fehler.
    unset($_SESSION['2fa_pending'], $_SESSION['2fa_user_id']);
    redirect(BASE_URL . '/admin/login');
}

// Verification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = preg_replace('/[^0-9]/', '', $_POST['code'] ?? '');
    $useBackup = !empty($_POST['use_backup']) && $_POST['use_backup'] === '1';
    $trustDevice = isset($_POST['trust_device']);

    if (empty($code)) {
        $error = 'Bitte geben Sie einen Code ein.';
    } elseif ($useBackup) {
        // Backup-Code verwenden
        $backupCodes = json_decode($twofa['backup_codes'], true) ?? [];
        $formattedCode = substr($code, 0, 4) . '-' . substr($code, 4, 4); // XXXX-XXXX Format

        if (in_array($formattedCode, $backupCodes, true)) {
            // Backup-Code entfernen (einmalig verwendbar)
            $backupCodes = array_diff($backupCodes, [$formattedCode]);
            $db->update("
                UPDATE user_2fa SET backup_codes = :codes WHERE user_id = :user_id
            ", [
                ':user_id' => $userId,
                ':codes' => json_encode(array_values($backupCodes))
            ]);

            // Login abschließen
            completeLogin($userId, $trustDevice);
        } else {
            $error = 'Ungültiger Backup-Code.';
        }
    } else {
        // TOTP-Code verifizieren
        if (TOTP::verify($twofa['secret'], $code, 1)) {
            // Login abschließen
            completeLogin($userId, $trustDevice);
        } else {
            $error = 'Ungültiger Code. Bitte versuchen Sie es erneut.';
        }
    }
}

function completeLogin($userId, $trustDevice = false) {
    global $db;

    $user = $db->querySingle("SELECT * FROM users WHERE id = :id", [':id' => $userId]);

    if ($user) {
        // 2FA-Session aufräumen
        unset($_SESSION['2fa_pending'], $_SESSION['2fa_user_id']);

        // Admin-Session setzen
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['full_name'] ?? $user['username'];
        $_SESSION['is_admin'] = ($user['role'] === 'admin');

        // Gerät als vertrauenswürdig markieren, falls gewünscht
        if ($trustDevice) {
            DeviceFingerprint::trust($user['id'], 30); // 30 Tage gültig
        }

        // Audit-Log
        $security = new Security();
        $security->logAudit('admin_login_2fa', 'user', $user['id'], [
            'trusted_device' => $trustDevice
        ]);

        redirect(BASE_URL . '/admin');
    }
}

$page_title = '2FA Verification | PC-Wittfoot UG';
$current_page = '';
include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <div class="row justify-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card">
                    <div style="text-align: center; font-size: 3em; margin-bottom: var(--space-md);">🔐</div>
                    <h1 class="text-center mb-lg">Zwei-Faktor-Authentifizierung</h1>

                    <?php if ($error): ?>
                        <div class="alert alert-error mb-lg"><?= e($error) ?></div>
                    <?php endif; ?>

                    <p class="text-center mb-lg">
                        Geben Sie den 6-stelligen Code aus Ihrer Authenticator-App ein.
                    </p>

                    <form method="post" id="2fa-form">
                        <div class="form-group">
                            <label for="code">Verifizierungscode</label>
                            <input type="text"
                                   id="code"
                                   name="code"
                                   placeholder="000000"
                                   maxlength="8"
                                   required
                                   autofocus
                                   autocomplete="off"
                                   style="font-size: 1.5em; letter-spacing: 0.3em; text-align: center; font-family: monospace;">
                            <small class="text-muted">6 Ziffern aus der App oder 8 Ziffern Backup-Code</small>
                        </div>

                        <input type="hidden" name="use_backup" id="use_backup" value="0">

                        <div class="form-group" style="margin-top: var(--space-md);">
                            <label class="form-check">
                                <input type="checkbox" name="trust_device" id="trust_device">
                                <span>Dieses Gerät für 30 Tage merken</span>
                            </label>
                            <small class="text-muted" style="display: block; margin-top: var(--space-xs);">
                                Sie müssen auf diesem Gerät für 30 Tage keinen 2FA-Code mehr eingeben.
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            Verifizieren
                        </button>
                    </form>

                    <hr style="margin: var(--space-lg) 0;">

                    <div class="text-center">
                        <button type="button"
                                class="btn btn-outline btn-sm"
                                onclick="toggleBackupMode()">
                            Backup-Code verwenden
                        </button>
                    </div>

                    <div class="text-center mt-md">
                        <a href="<?= BASE_URL ?>/admin/logout" class="text-muted" style="font-size: 0.9em;">
                            Abbrechen & Ausloggen
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
const codeInput = document.getElementById('code');
const useBackupInput = document.getElementById('use_backup');
let backupMode = false;

// Auto-submit bei 6 oder 8 Ziffern
codeInput.addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');

    const len = this.value.length;
    if ((len === 6 && !backupMode) || (len === 8 && backupMode)) {
        document.getElementById('2fa-form').submit();
    }
});

function toggleBackupMode() {
    backupMode = !backupMode;
    useBackupInput.value = backupMode ? '1' : '0';

    if (backupMode) {
        codeInput.placeholder = '00000000';
        codeInput.setAttribute('maxlength', '8');
        document.querySelector('label[for="code"]').textContent = 'Backup-Code';
    } else {
        codeInput.placeholder = '000000';
        codeInput.setAttribute('maxlength', '6');
        document.querySelector('label[for="code"]').textContent = 'Verifizierungscode';
    }

    codeInput.value = '';
    codeInput.focus();
}
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
