<?php
/**
 * Admin-Login
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();

// Bereits eingeloggt? -> zum Dashboard
if (is_admin()) {
    redirect(BASE_URL . '/admin');
}

$error = '';
$security = new Security();

// Login-Verarbeitung
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrf_token = $_POST['csrf_token'] ?? '';

    // CSRF-Check
    if (!csrf_verify($csrf_token)) {
        $error = 'Ungültiger Sicherheitstoken.';
    } elseif (empty($email) || empty($password)) {
        $error = 'Bitte E-Mail und Passwort eingeben.';
    } else {
        // Rate-Limiting prüfen (IP-basiert)
        $rateLimitIP = $security->checkRateLimit($security->getClientIP(), 'ip');

        if (!$rateLimitIP['allowed']) {
            $error = $rateLimitIP['message'];
        } else {
            // User aus DB laden
            $db = Database::getInstance();
            $user = $db->querySingle("
                SELECT * FROM users
                WHERE email = :email AND role = 'admin'
            ", [':email' => $email]);

            if ($user && verify_password($password, $user['password_hash'])) {
                // Login-Versuch protokollieren (ERFOLG)
                $security->logLoginAttempt($email, true);

                // Session regenerieren (gegen Session Fixation)
                $security->regenerateSession();

                // Login erfolgreich
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_name'] = $user['full_name'] ?? $user['username'];
                $_SESSION['is_admin'] = ($user['role'] === 'admin');

                // Audit-Log
                $security->logAudit('admin_login', 'user', $user['id']);

                redirect(BASE_URL . '/admin');
            } else {
                // Login-Versuch protokollieren (FEHLER)
                $security->logLoginAttempt($email, false);

                $remainingAttempts = $rateLimitIP['remaining'] - 1;
                if ($remainingAttempts > 0) {
                    $error = sprintf(
                        'Ungültige Anmeldedaten. Noch %d Versuch(e) übrig.',
                        $remainingAttempts
                    );
                } else {
                    $error = 'Ungültige Anmeldedaten. Zu viele Fehlversuche - Account vorübergehend gesperrt.';
                }
            }
        }
    }
}

$page_title = 'Admin-Login | PC-Wittfoot UG';
$page_description = 'Admin-Bereich Login';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <div class="row justify-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card">
                    <h1 class="text-center mb-lg">Admin-Login</h1>

                    <?php if ($error): ?>
                        <div class="alert alert-error mb-lg">
                            <?= e($error) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($flash_error = get_flash('error')): ?>
                        <div class="alert alert-error mb-lg">
                            <?= e($flash_error) ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                        <div class="form-group">
                            <label for="email">E-Mail</label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="<?= e($_POST['email'] ?? '') ?>"
                                   required
                                   autofocus>
                        </div>

                        <div class="form-group">
                            <label for="password">Passwort</label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            Anmelden
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
