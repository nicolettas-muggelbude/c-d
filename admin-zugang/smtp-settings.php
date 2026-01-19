<?php
/**
 * Admin: SMTP-Einstellungen
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

// POST-Handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enabled = isset($_POST['smtp_enabled']) ? 1 : 0;
    $host = trim($_POST['smtp_host']);
    $port = (int)$_POST['smtp_port'];
    $encryption = $_POST['smtp_encryption'];
    $username = trim($_POST['smtp_username']);
    $password = trim($_POST['smtp_password']);
    $debug = (int)$_POST['smtp_debug'];

    // Validierung
    if (empty($host)) {
        $error = "SMTP Host ist erforderlich";
    } elseif ($port < 1 || $port > 65535) {
        $error = "Port muss zwischen 1 und 65535 liegen";
    } elseif (!in_array($encryption, ['tls', 'ssl', 'none'])) {
        $error = "Ungültiger Verschlüsselungstyp";
    } else {
        $sql = "UPDATE smtp_settings SET
                smtp_enabled = :enabled,
                smtp_host = :host,
                smtp_port = :port,
                smtp_encryption = :encryption,
                smtp_username = :username,
                smtp_debug = :debug,
                updated_at = NOW()
                WHERE id = 1";

        $params = [
            ':enabled' => $enabled,
            ':host' => $host,
            ':port' => $port,
            ':encryption' => $encryption,
            ':username' => $username,
            ':debug' => $debug
        ];

        // Nur Passwort aktualisieren wenn ein neues eingegeben wurde
        if (!empty($password)) {
            $sql = "UPDATE smtp_settings SET
                    smtp_enabled = :enabled,
                    smtp_host = :host,
                    smtp_port = :port,
                    smtp_encryption = :encryption,
                    smtp_username = :username,
                    smtp_password = :password,
                    smtp_debug = :debug,
                    updated_at = NOW()
                    WHERE id = 1";
            $params[':password'] = $password;
        }

        $db->update($sql, $params);
        $success = "SMTP-Einstellungen erfolgreich gespeichert!";
    }
}

// Aktuelle Einstellungen laden
$settings = $db->querySingle("SELECT * FROM smtp_settings WHERE id = 1");

$page_title = 'SMTP-Einstellungen | Admin | PC-Wittfoot UG';
$page_description = 'SMTP-Konfiguration';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container" style="max-width: 800px;">
        <div class="mb-lg">
            <a href="<?= BASE_URL ?>/admin" class="btn btn-outline btn-sm">← Dashboard</a>
        </div>

        <h1>SMTP-Einstellungen</h1>
        <p class="lead mb-xl">Konfiguriere den Email-Versand mit SMTP oder PHP mail()</p>

        <?php if ($success): ?>
            <div class="alert alert-success mb-lg"><?= e($success) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error mb-lg"><?= e($error) ?></div>
        <?php endif; ?>

        <!-- Info-Box -->
        <div class="card mb-lg" style="background-color: #fff3cd; border-left: 4px solid #ffc107;">
            <h3>ℹ️ Hinweise</h3>
            <ul style="margin: 0; padding-left: 1.5rem;">
                <li><strong>PHP mail()</strong> - Verwendet den Server-integrierten Email-Versand (einfacher, aber ggf. Spam-Probleme)</li>
                <li><strong>SMTP</strong> - Professioneller Email-Versand über externen SMTP-Server (empfohlen für Produktion)</li>
                <li><strong>Gmail:</strong> smtp.gmail.com, Port 587, TLS, App-Passwort erforderlich</li>
                <li><strong>Office365:</strong> smtp.office365.com, Port 587, TLS</li>
                <li><strong>Passwort:</strong> Wird nur geändert wenn ein neues eingegeben wird</li>
            </ul>
        </div>

        <!-- Formular -->
        <form method="POST" class="card">
            <!-- SMTP aktivieren -->
            <div class="form-group">
                <label>
                    <input type="checkbox" name="smtp_enabled" value="1" <?= $settings['smtp_enabled'] ? 'checked' : '' ?>>
                    <strong>SMTP verwenden</strong> (sonst PHP mail())
                </label>
            </div>

            <hr>

            <!-- SMTP Host -->
            <div class="form-group">
                <label for="smtp_host"><strong>SMTP Server</strong></label>
                <input type="text" id="smtp_host" name="smtp_host" class="form-control"
                       value="<?= e($settings['smtp_host']) ?>"
                       placeholder="smtp.gmail.com" required>
            </div>

            <!-- Port und Verschlüsselung -->
            <div class="form-row">
                <div class="form-group">
                    <label for="smtp_port"><strong>Port</strong></label>
                    <input type="number" id="smtp_port" name="smtp_port" class="form-control"
                           value="<?= e($settings['smtp_port']) ?>"
                           min="1" max="65535" required>
                </div>

                <div class="form-group">
                    <label for="smtp_encryption"><strong>Verschlüsselung</strong></label>
                    <select id="smtp_encryption" name="smtp_encryption" class="form-control">
                        <option value="tls" <?= $settings['smtp_encryption'] === 'tls' ? 'selected' : '' ?>>TLS (empfohlen)</option>
                        <option value="ssl" <?= $settings['smtp_encryption'] === 'ssl' ? 'selected' : '' ?>>SSL</option>
                        <option value="none" <?= $settings['smtp_encryption'] === 'none' ? 'selected' : '' ?>>Keine</option>
                    </select>
                </div>
            </div>

            <!-- Benutzername -->
            <div class="form-group">
                <label for="smtp_username"><strong>Benutzername</strong></label>
                <input type="text" id="smtp_username" name="smtp_username" class="form-control"
                       value="<?= e($settings['smtp_username']) ?>"
                       placeholder="deine-email@gmail.com">
            </div>

            <!-- Passwort -->
            <div class="form-group">
                <label for="smtp_password"><strong>Passwort</strong></label>
                <input type="password" id="smtp_password" name="smtp_password" class="form-control"
                       placeholder="Nur ausfüllen zum Ändern">
                <small class="text-muted">Aktuelles Passwort bleibt erhalten wenn leer gelassen</small>
            </div>

            <!-- Debug-Level -->
            <div class="form-group">
                <label for="smtp_debug"><strong>Debug-Level</strong></label>
                <select id="smtp_debug" name="smtp_debug" class="form-control">
                    <option value="0" <?= $settings['smtp_debug'] === 0 ? 'selected' : '' ?>>Aus (Produktion)</option>
                    <option value="1" <?= $settings['smtp_debug'] === 1 ? 'selected' : '' ?>>Nur Fehler</option>
                    <option value="2" <?= $settings['smtp_debug'] === 2 ? 'selected' : '' ?>>Verbose (Entwicklung)</option>
                </select>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 1.5rem;">
                <a href="<?= BASE_URL ?>/admin/smtp-test" class="btn btn-outline">🧪 Test-Email senden</a>
                <button type="submit" class="btn btn-primary">💾 Speichern</button>
            </div>
        </form>

        <!-- Aktuelle Konfiguration -->
        <div class="card mt-lg">
            <h3>Aktuelle Konfiguration</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 0.5rem; border-bottom: 1px solid #ddd;"><strong>Modus:</strong></td>
                    <td style="padding: 0.5rem; border-bottom: 1px solid #ddd;">
                        <?= $settings['smtp_enabled'] ? '✅ SMTP' : '📧 PHP mail()' ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem; border-bottom: 1px solid #ddd;"><strong>Server:</strong></td>
                    <td style="padding: 0.5rem; border-bottom: 1px solid #ddd;"><?= e($settings['smtp_host']) ?>:<?= e($settings['smtp_port']) ?></td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem; border-bottom: 1px solid #ddd;"><strong>Verschlüsselung:</strong></td>
                    <td style="padding: 0.5rem; border-bottom: 1px solid #ddd;"><?= strtoupper($settings['smtp_encryption']) ?></td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem; border-bottom: 1px solid #ddd;"><strong>Benutzername:</strong></td>
                    <td style="padding: 0.5rem; border-bottom: 1px solid #ddd;"><?= e($settings['smtp_username']) ?: '-' ?></td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem;"><strong>Zuletzt geändert:</strong></td>
                    <td style="padding: 0.5rem;"><?= date('d.m.Y H:i', strtotime($settings['updated_at'])) ?> Uhr</td>
                </tr>
            </table>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
