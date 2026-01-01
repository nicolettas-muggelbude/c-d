<?php
/**
 * Wartungsmodus-Verwaltung
 * PC-Wittfoot UG
 *
 * Admin-Interface zum Aktivieren/Deaktivieren des Wartungsmodus
 */

require_once __DIR__ . '/../core/config.php';
require_admin();

$page_title = 'Wartungsmodus verwalten - Admin';
$current_page = 'admin';

// Wartungsmodus-Datei
$maintenanceFile = dirname(__DIR__) . '/MAINTENANCE';
$isMaintenanceActive = file_exists($maintenanceFile);

// Aktuelle Wartungsmeldung laden
$currentMessage = '';
$currentEstimatedEnd = '';
if ($isMaintenanceActive) {
    $content = file_get_contents($maintenanceFile);
    $lines = explode("\n", $content, 2);
    $currentMessage = $lines[0] ?? '';
    $currentEstimatedEnd = isset($lines[1]) ? trim($lines[1]) : '';
}

// Formular verarbeiten
$success = false;
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'enable') {
        // Wartungsmodus aktivieren
        $message = trim($_POST['message'] ?? '');
        $estimatedEnd = trim($_POST['estimated_end'] ?? '');

        if (empty($message)) {
            $message = "Wir führen gerade Wartungsarbeiten durch.\nBitte versuchen Sie es in wenigen Minuten erneut.";
        }

        $content = $message;
        if (!empty($estimatedEnd)) {
            $content .= "\n" . $estimatedEnd;
        }

        if (file_put_contents($maintenanceFile, $content) !== false) {
            $success = 'Wartungsmodus wurde aktiviert. Die Website ist jetzt für normale Besucher nicht erreichbar.';
            $isMaintenanceActive = true;
            $currentMessage = $message;
            $currentEstimatedEnd = $estimatedEnd;
        } else {
            $error = 'Fehler beim Aktivieren des Wartungsmodus. Bitte Dateiberechtigungen prüfen.';
        }

    } elseif ($action === 'disable') {
        // Wartungsmodus deaktivieren
        if (file_exists($maintenanceFile)) {
            if (unlink($maintenanceFile)) {
                $success = 'Wartungsmodus wurde deaktiviert. Die Website ist wieder für alle erreichbar.';
                $isMaintenanceActive = false;
                $currentMessage = '';
                $currentEstimatedEnd = '';
            } else {
                $error = 'Fehler beim Deaktivieren des Wartungsmodus. Bitte Dateiberechtigungen prüfen.';
            }
        }

    } elseif ($action === 'update') {
        // Wartungsmeldung aktualisieren
        $message = trim($_POST['message'] ?? '');
        $estimatedEnd = trim($_POST['estimated_end'] ?? '');

        if (empty($message)) {
            $message = "Wir führen gerade Wartungsarbeiten durch.\nBitte versuchen Sie es in wenigen Minuten erneut.";
        }

        $content = $message;
        if (!empty($estimatedEnd)) {
            $content .= "\n" . $estimatedEnd;
        }

        if (file_put_contents($maintenanceFile, $content) !== false) {
            $success = 'Wartungsmeldung wurde aktualisiert.';
            $currentMessage = $message;
            $currentEstimatedEnd = $estimatedEnd;
        } else {
            $error = 'Fehler beim Aktualisieren der Wartungsmeldung.';
        }
    }
}

include __DIR__ . '/../templates/header.php';
?>

<style>
    .maintenance-status {
        background: white;
        border-radius: 8px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .status-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .status-active {
        background: #ff9800;
        color: white;
    }

    .status-inactive {
        background: #4CAF50;
        color: white;
    }

    .maintenance-form {
        background: white;
        border-radius: 8px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #333;
    }

    .form-group input[type="text"],
    .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
        font-family: inherit;
    }

    .form-group textarea {
        min-height: 120px;
        resize: vertical;
    }

    .form-help {
        font-size: 0.875rem;
        color: #666;
        margin-top: 0.25rem;
    }

    .button-group {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-primary {
        background: #667eea;
        color: white;
    }

    .btn-primary:hover {
        background: #5568d3;
    }

    .btn-success {
        background: #4CAF50;
        color: white;
    }

    .btn-success:hover {
        background: #45a049;
    }

    .btn-warning {
        background: #ff9800;
        color: white;
    }

    .btn-warning:hover {
        background: #e68900;
    }

    .btn-danger {
        background: #f44336;
        color: white;
    }

    .btn-danger:hover {
        background: #da190b;
    }

    .alert {
        padding: 1rem;
        border-radius: 4px;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .info-box {
        background: #e3f2fd;
        border-left: 4px solid #2196F3;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 4px;
    }

    .info-box h3 {
        margin-top: 0;
        color: #1976D2;
    }

    .preview-box {
        background: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .preview-box h4 {
        margin-top: 0;
        color: #666;
    }

    .preview-content {
        white-space: pre-line;
        color: #333;
    }

    /* Dark Mode Styles */
    [data-theme="dark"] .maintenance-status,
    [data-theme="dark"] .maintenance-form {
        background: #2d2d2d;
        color: #e0e0e0;
    }

    [data-theme="dark"] .form-group input[type="text"],
    [data-theme="dark"] .form-group textarea {
        background: #1a1a1a;
        border-color: #404040;
        color: #e0e0e0;
    }

    [data-theme="dark"] .info-box {
        background: #1a3a52;
        border-left-color: #2196F3;
    }

    [data-theme="dark"] .info-box h3 {
        color: #64b5f6;
    }

    [data-theme="dark"] .preview-box {
        background: #1a1a1a;
        border-color: #404040;
    }

    [data-theme="dark"] .preview-box h4 {
        color: #b0b0b0;
    }

    [data-theme="dark"] .preview-content {
        color: #e0e0e0;
    }

    [data-theme="dark"] .alert-success {
        background: #1b4d3e;
        color: #a5d6a7;
        border-color: #2e7d32;
    }

    [data-theme="dark"] .alert-error {
        background: #4d1b1b;
        color: #ef9a9a;
        border-color: #c62828;
    }

    [data-theme="dark"] .form-help {
        color: #b0b0b0;
    }

    [data-theme="dark"] .form-group label {
        color: #e0e0e0;
    }
</style>

<section class="section">
    <div class="container">
        <h1>Wartungsmodus verwalten</h1>

<?php if ($success): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div class="maintenance-status">
    <h2>Aktueller Status</h2>

    <div class="status-indicator <?= $isMaintenanceActive ? 'status-active' : 'status-inactive' ?>">
        <?php if ($isMaintenanceActive): ?>
            🔧 Wartungsmodus AKTIV
        <?php else: ?>
            ✅ Website ONLINE
        <?php endif; ?>
    </div>

    <?php if ($isMaintenanceActive): ?>
        <div class="info-box">
            <h3>Wartungsmodus ist aktiv</h3>
            <p>
                <strong>Wichtig:</strong> Die Website ist derzeit für normale Besucher nicht erreichbar.
                Sie sehen eine Wartungsseite. Als Administrator können Sie weiterhin auf alle Bereiche zugreifen.
            </p>
            <?php if (!empty($currentMessage)): ?>
                <div class="preview-box">
                    <h4>Aktuelle Nachricht:</h4>
                    <div class="preview-content"><?= htmlspecialchars($currentMessage) ?></div>
                    <?php if (!empty($currentEstimatedEnd)): ?>
                        <p style="margin-top: 0.5rem;"><strong>Voraussichtliches Ende:</strong> <?= htmlspecialchars($currentEstimatedEnd) ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <form method="post" style="margin-top: 1rem;">
            <input type="hidden" name="action" value="disable">
            <button type="submit" class="btn btn-success" onclick="return confirm('Wartungsmodus wirklich deaktivieren? Die Website wird dann wieder für alle zugänglich.')">
                ✅ Wartungsmodus deaktivieren
            </button>
        </form>
    <?php endif; ?>
</div>

<?php if ($isMaintenanceActive): ?>
    <!-- Wartungsmeldung bearbeiten -->
    <div class="maintenance-form">
        <h2>Wartungsmeldung bearbeiten</h2>
        <form method="post">
            <input type="hidden" name="action" value="update">

            <div class="form-group">
                <label for="message">Nachricht an Besucher</label>
                <textarea name="message" id="message" required><?= htmlspecialchars($currentMessage) ?></textarea>
                <div class="form-help">
                    Diese Nachricht wird den Besuchern auf der Wartungsseite angezeigt.
                </div>
            </div>

            <div class="form-group">
                <label for="estimated_end">Voraussichtliches Ende (optional)</label>
                <input type="text" name="estimated_end" id="estimated_end" value="<?= htmlspecialchars($currentEstimatedEnd) ?>"
                       placeholder="z.B. Heute um 18:00 Uhr">
                <div class="form-help">
                    Diese Information wird den Besuchern angezeigt, falls angegeben.
                </div>
            </div>

            <button type="submit" class="btn btn-primary">💾 Meldung aktualisieren</button>
        </form>
    </div>
<?php else: ?>
    <!-- Wartungsmodus aktivieren -->
    <div class="maintenance-form">
        <h2>Wartungsmodus aktivieren</h2>

        <div class="info-box">
            <h3>Was passiert beim Aktivieren?</h3>
            <p>
                • Die Website wird für normale Besucher gesperrt<br>
                • Besucher sehen eine professionelle Wartungsseite<br>
                • Als Administrator können Sie weiterhin auf alle Bereiche zugreifen<br>
                • Sie sehen oben eine orange Warnleiste als Erinnerung
            </p>
        </div>

        <form method="post">
            <input type="hidden" name="action" value="enable">

            <div class="form-group">
                <label for="message">Nachricht an Besucher</label>
                <textarea name="message" id="message" required>Wir führen gerade Wartungsarbeiten durch.
Bitte versuchen Sie es in wenigen Minuten erneut.</textarea>
                <div class="form-help">
                    Diese Nachricht wird den Besuchern auf der Wartungsseite angezeigt.
                </div>
            </div>

            <div class="form-group">
                <label for="estimated_end">Voraussichtliches Ende (optional)</label>
                <input type="text" name="estimated_end" id="estimated_end"
                       placeholder="z.B. Heute um 18:00 Uhr">
                <div class="form-help">
                    Diese Information wird den Besuchern angezeigt, falls angegeben.
                </div>
            </div>

            <button type="submit" class="btn btn-warning" onclick="return confirm('Wartungsmodus wirklich aktivieren? Die Website wird dann für normale Besucher gesperrt.')">
                🔧 Wartungsmodus aktivieren
            </button>
        </form>
    </div>
<?php endif; ?>

<!-- Zusätzliche Informationen -->
<div class="maintenance-form">
    <h2>Nützliche Informationen</h2>

    <h3>Health-Check Endpoint</h3>
    <p>
        Sie können den Systemstatus über den Health-Check Endpoint überprüfen:<br>
        <code><a href="<?= BASE_URL ?>/api/health-check" target="_blank"><?= BASE_URL ?>/api/health-check</a></code>
    </p>
    <p>
        Dieser Endpoint zeigt an:
    </p>
    <ul>
        <li>Datenbankverbindung</li>
        <li>EmailService-Status</li>
        <li>Composer/Vendor-Verzeichnis</li>
        <li>Logs- und Uploads-Verzeichnisse</li>
        <li>Speicherplatz</li>
        <li>PHP-Version</li>
        <li>Wartungsmodus-Status</li>
    </ul>

    <h3>Deployment-Workflow</h3>
    <p>Empfohlene Schritte für Updates:</p>
    <ol>
        <li>Wartungsmodus aktivieren</li>
        <li>Backup erstellen (Dateien & Datenbank)</li>
        <li>Neue Dateien per FTP hochladen</li>
        <li>Datenbank-Migrationen durchführen (falls nötig)</li>
        <li>Health-Check prüfen</li>
        <li>Wartungsmodus deaktivieren</li>
    </ol>
</div>

    </div><!-- .container -->
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
