<?php
/**
 * Admin - Passwort zurücksetzen
 * Neues Passwort mit Token setzen
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
$db = Database::getInstance();

// Token aus URL holen
$token = $_GET['token'] ?? '';

// Token validieren
$resetToken = null;
if (!empty($token)) {
    $resetToken = $db->querySingle("
        SELECT rt.*, u.email, u.full_name, u.username
        FROM password_reset_tokens rt
        JOIN users u ON rt.user_id = u.id
        WHERE rt.token = :token
          AND rt.used = FALSE
          AND rt.expires_at > NOW()
    ", [':token' => $token]);

    if (!$resetToken) {
        $error = 'Ungültiger oder abgelaufener Token. Bitte fordern Sie einen neuen Link an.';
    }
}

// Passwort zurücksetzen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $resetToken) {
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    $csrf_token = $_POST['csrf_token'] ?? '';

    // CSRF-Check
    if (!csrf_verify($csrf_token)) {
        $error = 'Ungültiger Sicherheitstoken.';
    } elseif (empty($password)) {
        $error = 'Bitte neues Passwort eingeben.';
    } elseif (strlen($password) < 8) {
        $error = 'Passwort muss mindestens 8 Zeichen lang sein.';
    } elseif ($password !== $passwordConfirm) {
        $error = 'Passwörter stimmen nicht überein.';
    } else {
        // Passwort hashen
        $passwordHash = hash_password($password);

        // Passwort in DB aktualisieren
        $db->update("
            UPDATE users
            SET password_hash = :password_hash
            WHERE id = :user_id
        ", [
            ':password_hash' => $passwordHash,
            ':user_id' => $resetToken['user_id']
        ]);

        // Token als verwendet markieren
        $db->update("
            UPDATE password_reset_tokens
            SET used = TRUE, used_at = NOW()
            WHERE id = :token_id
        ", [':token_id' => $resetToken['id']]);

        // Audit-Log
        $security->logAudit('password_reset_completed', 'user', $resetToken['user_id']);

        $success = 'Ihr Passwort wurde erfolgreich zurückgesetzt. Sie können sich jetzt anmelden.';
        $resetToken = null; // Formular ausblenden
    }
}

$page_title = 'Passwort zurücksetzen | PC-Wittfoot UG';
$current_page = '';
include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <div class="row justify-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card">
                    <div style="text-align: center; font-size: 3em; margin-bottom: var(--space-md);">🔐</div>
                    <h1 class="text-center mb-lg">Passwort zurücksetzen</h1>

                    <?php if ($error): ?>
                        <div class="alert alert-error mb-lg"><?= e($error) ?></div>
                        <?php if (!$resetToken): ?>
                            <div class="text-center">
                                <a href="<?= BASE_URL ?>/admin/forgot-password" class="btn btn-primary">
                                    Neuen Link anfordern
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success mb-lg"><?= e($success) ?></div>
                        <div class="text-center">
                            <a href="<?= BASE_URL ?>/admin/login" class="btn btn-primary">Zum Login</a>
                        </div>
                    <?php elseif ($resetToken): ?>
                        <p class="text-center mb-lg">
                            Hallo <?= e($resetToken['full_name'] ?? $resetToken['username']) ?>!<br>
                            Geben Sie Ihr neues Passwort ein.
                        </p>

                        <form method="post">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                            <div class="form-group">
                                <label for="password">Neues Passwort</label>
                                <input type="password"
                                       id="password"
                                       name="password"
                                       minlength="8"
                                       required
                                       autofocus>
                                <small class="text-muted">Mindestens 8 Zeichen</small>
                            </div>

                            <div class="form-group">
                                <label for="password_confirm">Passwort bestätigen</label>
                                <input type="password"
                                       id="password_confirm"
                                       name="password_confirm"
                                       minlength="8"
                                       required>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block btn-lg">
                                Passwort zurücksetzen
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

<script>
// Passwort-Bestätigung live validieren
const password = document.getElementById('password');
const passwordConfirm = document.getElementById('password_confirm');

if (password && passwordConfirm) {
    passwordConfirm.addEventListener('input', function() {
        if (this.value && this.value !== password.value) {
            this.setCustomValidity('Passwörter stimmen nicht überein');
        } else {
            this.setCustomValidity('');
        }
    });
}
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
