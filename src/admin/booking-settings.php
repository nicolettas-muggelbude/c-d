<?php
/**
 * Admin: Termineinstellungen
 * PC-Wittfoot UG
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();

// Admin-Rechte prüfen
require_admin();

$db = Database::getInstance();

// Erfolgsmeldung
$success_message = null;
$error_message = null;

// Formular verarbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startTime = $_POST['booking_start_time'] ?? '';
    $endTime = $_POST['booking_end_time'] ?? '';
    $intervalMinutes = $_POST['booking_interval_minutes'] ?? '';
    $maxBookingsPerSlot = $_POST['max_bookings_per_slot'] ?? '';

    // Validierung
    $errors = [];

    // Zeitformat validieren (HH:MM)
    if (!preg_match('/^\d{2}:\d{2}$/', $startTime)) {
        $errors[] = 'Startzeit muss im Format HH:MM sein';
    }
    if (!preg_match('/^\d{2}:\d{2}$/', $endTime)) {
        $errors[] = 'Endzeit muss im Format HH:MM sein';
    }

    // Prüfen ob Endzeit nach Startzeit liegt
    if (empty($errors)) {
        $start = DateTime::createFromFormat('H:i', $startTime);
        $end = DateTime::createFromFormat('H:i', $endTime);
        if ($end <= $start) {
            $errors[] = 'Endzeit muss nach der Startzeit liegen';
        }
    }

    // Intervall validieren
    if (!is_numeric($intervalMinutes) || $intervalMinutes < 15 || $intervalMinutes > 240) {
        $errors[] = 'Intervall muss zwischen 15 und 240 Minuten liegen';
    }

    // Max. Buchungen validieren
    if (!is_numeric($maxBookingsPerSlot) || $maxBookingsPerSlot < 1 || $maxBookingsPerSlot > 10) {
        $errors[] = 'Max. Buchungen pro Slot muss zwischen 1 und 10 liegen';
    }

    if (empty($errors)) {
        try {
            // Einstellungen aktualisieren
            $sql = "UPDATE booking_settings SET setting_value = :value, updated_at = NOW()
                    WHERE setting_key = :key";

            $db->execute($sql, [':value' => $startTime, ':key' => 'booking_start_time']);
            $db->execute($sql, [':value' => $endTime, ':key' => 'booking_end_time']);
            $db->execute($sql, [':value' => $intervalMinutes, ':key' => 'booking_interval_minutes']);
            $db->execute($sql, [':value' => $maxBookingsPerSlot, ':key' => 'max_bookings_per_slot']);

            $success_message = 'Einstellungen erfolgreich gespeichert';

        } catch (Exception $e) {
            error_log('Booking Settings Update Error: ' . $e->getMessage());
            $error_message = 'Fehler beim Speichern der Einstellungen';
        }
    } else {
        $error_message = implode('<br>', $errors);
    }
}

// Aktuelle Einstellungen laden
$settings = [];
$sql = "SELECT setting_key, setting_value, description FROM booking_settings";
$result = $db->query($sql);

foreach ($result as $row) {
    $settings[$row['setting_key']] = [
        'value' => $row['setting_value'],
        'description' => $row['description']
    ];
}

$page_title = 'Termineinstellungen | Admin | PC-Wittfoot UG';
$page_description = 'Termineinstellungen verwalten';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container" style="max-width: 800px;">
        <div class="mb-lg">
            <a href="<?= BASE_URL ?>/admin" class="btn btn-outline btn-sm">← Zurück zum Dashboard</a>
        </div>

        <h1>Termineinstellungen</h1>
        <p class="lead mb-xl">Buchungszeiten und Kapazität für feste Termine konfigurieren</p>

        <?php if ($success_message): ?>
            <div class="alert alert-success mb-lg">
                <?= e($success_message) ?>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-error mb-lg">
                <?= $error_message ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <form method="POST" action="">
                <div class="mb-lg">
                    <h3 class="mb-md">Buchungszeiten</h3>
                    <p class="text-muted mb-md" style="font-size: 0.9rem;">
                        Diese Einstellungen gelten für <strong>feste Termine</strong> (Dienstag bis Freitag).
                    </p>

                    <div class="form-group">
                        <label for="booking_start_time">Erste verfügbare Zeit</label>
                        <input
                            type="time"
                            id="booking_start_time"
                            name="booking_start_time"
                            value="<?= e($settings['booking_start_time']['value'] ?? '11:00') ?>"
                            required
                            class="form-control"
                        >
                        <small class="text-muted">
                            <?= e($settings['booking_start_time']['description'] ?? '') ?>
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="booking_end_time">Letzte verfügbare Zeit</label>
                        <input
                            type="time"
                            id="booking_end_time"
                            name="booking_end_time"
                            value="<?= e($settings['booking_end_time']['value'] ?? '13:00') ?>"
                            required
                            class="form-control"
                        >
                        <small class="text-muted">
                            <?= e($settings['booking_end_time']['description'] ?? '') ?>
                        </small>
                    </div>
                </div>

                <div class="mb-lg">
                    <h3 class="mb-md">Zeitabstand</h3>

                    <div class="form-group">
                        <label for="booking_interval_minutes">Intervall in Minuten</label>
                        <input
                            type="number"
                            id="booking_interval_minutes"
                            name="booking_interval_minutes"
                            value="<?= e($settings['booking_interval_minutes']['value'] ?? '60') ?>"
                            min="15"
                            max="240"
                            step="15"
                            required
                            class="form-control"
                        >
                        <small class="text-muted">
                            <?= e($settings['booking_interval_minutes']['description'] ?? '') ?>
                            (Empfohlen: 15, 30, 45, 60, 90 oder 120 Minuten)
                        </small>
                    </div>
                </div>

                <div class="mb-xl">
                    <h3 class="mb-md">Kapazität</h3>

                    <div class="form-group">
                        <label for="max_bookings_per_slot">Max. Buchungen pro Zeitslot</label>
                        <input
                            type="number"
                            id="max_bookings_per_slot"
                            name="max_bookings_per_slot"
                            value="<?= e($settings['max_bookings_per_slot']['value'] ?? '1') ?>"
                            min="1"
                            max="10"
                            required
                            class="form-control"
                        >
                        <small class="text-muted">
                            <?= e($settings['max_bookings_per_slot']['description'] ?? '') ?>
                        </small>
                    </div>
                </div>

                <div class="alert alert-info mb-lg" style="font-size: 0.9rem;">
                    <strong>Hinweis:</strong> Änderungen gelten sofort für alle zukünftigen Buchungen.
                    Bestehende Buchungen bleiben unverändert.
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <a href="<?= BASE_URL ?>/admin" class="btn btn-outline">Abbrechen</a>
                    <button type="submit" class="btn btn-primary">Einstellungen speichern</button>
                </div>
            </form>
        </div>

        <!-- Vorschau der generierten Zeitslots -->
        <div class="card mt-lg">
            <h3 class="mb-md">Vorschau: Generierte Zeitslots</h3>
            <p class="text-muted mb-md" style="font-size: 0.9rem;">
                Basierend auf den aktuellen Einstellungen werden folgende Zeitslots generiert:
            </p>

            <div id="slots-preview" class="p-md" style="background-color: #f8f9fa; border-radius: 4px;">
                <?php
                $startTime = $settings['booking_start_time']['value'] ?? '11:00';
                $endTime = $settings['booking_end_time']['value'] ?? '13:00';
                $intervalMinutes = (int)($settings['booking_interval_minutes']['value'] ?? 60);

                $currentTime = DateTime::createFromFormat('H:i', $startTime);
                $endTimeObj = DateTime::createFromFormat('H:i', $endTime);

                $slots = [];
                while ($currentTime < $endTimeObj) {
                    $slots[] = $currentTime->format('H:i');
                    $currentTime->modify("+{$intervalMinutes} minutes");
                }

                if (empty($slots)) {
                    echo '<p class="text-muted">Keine Zeitslots verfügbar (Endzeit muss nach Startzeit liegen)</p>';
                } else {
                    echo '<div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">';
                    foreach ($slots as $slot) {
                        echo '<span class="badge" style="padding: 0.5rem 1rem; background-color: var(--color-primary); color: white;">' . e($slot) . ' Uhr</span>';
                    }
                    echo '</div>';
                    echo '<p class="text-muted mt-md" style="font-size: 0.85rem;">Gesamt: ' . count($slots) . ' Zeitslots</p>';
                }
                ?>
            </div>
        </div>
    </div>
</section>

<style>
.form-control {
    width: 100%;
    padding: 0.75rem;
    font-size: 1rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.form-group small {
    display: block;
    margin-top: 0.5rem;
    font-size: 0.875rem;
}

.alert {
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 1rem;
}

.alert-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.alert-error {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.alert-info {
    background-color: #d1ecf1;
    border: 1px solid #bee5eb;
    color: #0c5460;
}

.badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 4px;
}
</style>

<?php include __DIR__ . '/../templates/footer.php'; ?>
