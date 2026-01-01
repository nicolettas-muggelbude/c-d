<?php
/**
 * Admin: Termin-Details
 * PC-Wittfoot UG
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();

// Admin-Rechte prüfen
require_admin();

$db = Database::getInstance();

// Booking ID
$bookingId = $_GET['id'] ?? null;

if (!$bookingId || !is_numeric($bookingId)) {
    header('Location: ' . BASE_URL . '/admin/bookings');
    exit;
}

// Status ändern oder Termin bearbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_status' && isset($_POST['status'])) {
        $newStatus = $_POST['status'];

        if (in_array($newStatus, ['pending', 'confirmed', 'completed', 'cancelled'])) {
            $sql = "UPDATE bookings SET status = :status, updated_at = NOW() WHERE id = :id";
            $db->update($sql, [':status' => $newStatus, ':id' => $bookingId]);

            $_SESSION['success_message'] = 'Status erfolgreich aktualisiert';
            header('Location: ' . BASE_URL . '/admin/booking-detail?id=' . $bookingId);
            exit;
        }
    }

    // Termin bearbeiten (Datum/Zeit)
    if ($_POST['action'] === 'update_booking' && isset($_POST['booking_date'])) {
        $newDate = $_POST['booking_date'];
        $newTime = $_POST['booking_time'] ?? null;

        $errors = [];

        // Datum validieren
        $dateObj = DateTime::createFromFormat('Y-m-d', $newDate);
        if (!$dateObj) {
            $errors[] = 'Ungültiges Datumsformat';
        } else {
            // Prüfen ob Datum in der Zukunft liegt
            $today = new DateTime();
            $today->setTime(0, 0, 0);
            if ($dateObj < $today) {
                $errors[] = 'Datum muss in der Zukunft liegen';
            }

            // Prüfen ob erlaubter Wochentag
            $dayOfWeek = $dateObj->format('N');
            $bookingType = $booking['booking_type'];

            if ($bookingType === 'fixed') {
                if ($dayOfWeek < 2 || $dayOfWeek > 5) {
                    $errors[] = 'Feste Termine sind nur Dienstag bis Freitag möglich';
                }
            } else if ($bookingType === 'walkin') {
                if ($dayOfWeek < 2 || $dayOfWeek > 6) {
                    $errors[] = 'Walk-in Termine sind nur Dienstag bis Samstag möglich';
                }
            }
        }

        // Zeit validieren (nur bei festen Terminen)
        if ($booking['booking_type'] === 'fixed') {
            if (empty($newTime)) {
                $errors[] = 'Bitte wählen Sie eine Uhrzeit';
            } else if (!preg_match('/^\d{2}:\d{2}$/', $newTime)) {
                $errors[] = 'Ungültiges Zeitformat';
            }
        }

        if (empty($errors)) {
            $sql = "UPDATE bookings SET booking_date = :date, booking_time = :time, updated_at = NOW() WHERE id = :id";
            $db->update($sql, [
                ':date' => $newDate,
                ':time' => $booking['booking_type'] === 'fixed' ? $newTime : null,
                ':id' => $bookingId
            ]);

            $_SESSION['success_message'] = 'Termin erfolgreich aktualisiert';
            header('Location: ' . BASE_URL . '/admin/booking-detail?id=' . $bookingId);
            exit;
        } else {
            $error_message = implode('<br>', $errors);
        }
    }
}

// Buchung laden
$sql = "SELECT * FROM bookings WHERE id = :id";
$booking = $db->querySingle($sql, [':id' => $bookingId]);

if (!$booking) {
    header('Location: ' . BASE_URL . '/admin/bookings');
    exit;
}

// Erfolgsmeldung
$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['success_message']);

// Status-Labels
$statusLabels = [
    'pending' => 'Ausstehend',
    'confirmed' => 'Bestätigt',
    'completed' => 'Abgeschlossen',
    'cancelled' => 'Storniert'
];

$statusColors = [
    'pending' => '#ffc107',
    'confirmed' => '#28a745',
    'completed' => '#6c757d',
    'cancelled' => '#dc3545'
];

// Service-Labels
$serviceLabels = [
    'pc-reparatur' => 'PC-Reparatur',
    'notebook-reparatur' => 'Notebook-Reparatur',
    'beratung' => 'Beratung',
    'software' => 'Software-Installation',
    'datenrettung' => 'Datenrettung',
    'virus-entfernung' => 'Virus-Entfernung',
    'upgrade' => 'Hardware-Upgrade',
    'sonstiges' => 'Sonstiges'
];

// Datum formatieren
$date = DateTime::createFromFormat('Y-m-d', $booking['booking_date']);
$dateFormatted = $date ? $date->format('d.m.Y') : $booking['booking_date'];
$dayOfWeek = $date ? $date->format('l') : '';
$dayNames = [
    'Monday' => 'Montag',
    'Tuesday' => 'Dienstag',
    'Wednesday' => 'Mittwoch',
    'Thursday' => 'Donnerstag',
    'Friday' => 'Freitag',
    'Saturday' => 'Samstag',
    'Sunday' => 'Sonntag'
];
$dayFormatted = $dayNames[$dayOfWeek] ?? $dayOfWeek;

$page_title = 'Termin #' . $booking['id'] . ' | Admin | PC-Wittfoot UG';
$page_description = 'Termin-Details';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container" style="max-width: 900px;">
        <div class="mb-lg">
            <a href="<?= BASE_URL ?>/admin/bookings" class="btn btn-outline btn-sm">← Zurück zur Übersicht</a>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
            <div>
                <h1>Termin #<?= e($booking['id']) ?></h1>
                <p class="text-muted">Erstellt am <?= date('d.m.Y H:i', strtotime($booking['created_at'])) ?> Uhr</p>
            </div>
            <div>
                <span class="badge" style="background-color: <?= $statusColors[$booking['status']] ?>; color: white; font-size: 1rem; padding: 0.5rem 1rem;">
                    <?= e($statusLabels[$booking['status']] ?? $booking['status']) ?>
                </span>
            </div>
        </div>

        <?php if ($success_message): ?>
            <div class="alert alert-success mb-lg">
                <?= e($success_message) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-error mb-lg">
                <?= $error_message ?>
            </div>
        <?php endif; ?>

        <!-- Termindetails -->
        <div class="card mb-lg">
            <h2 class="mb-lg">Termindetails</h2>

            <div class="detail-grid">
                <div class="detail-item">
                    <label>Terminart</label>
                    <div>
                        <?php if ($booking['booking_type'] === 'fixed'): ?>
                            <span class="badge badge-info">Fester Termin</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Ich komme vorbei (Walk-in)</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="detail-item">
                    <label>Dienstleistung</label>
                    <div><?= e($serviceLabels[$booking['service_type']] ?? $booking['service_type']) ?></div>
                </div>

                <div class="detail-item">
                    <label>Datum</label>
                    <div><strong><?= e($dateFormatted) ?></strong> (<?= e($dayFormatted) ?>)</div>
                </div>

                <div class="detail-item">
                    <label>Uhrzeit</label>
                    <div>
                        <?php if ($booking['booking_type'] === 'fixed' && $booking['booking_time']): ?>
                            <strong><?= e($booking['booking_time']) ?> Uhr</strong>
                        <?php else: ?>
                            <span class="text-muted">Walk-in (ab 14:00 Uhr)</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="detail-item" style="grid-column: 1 / -1;">
                    <label>Kundenanmerkungen</label>
                    <div class="note-box">
                        <?php if (!empty($booking['customer_notes'])): ?>
                            <?= nl2br(e($booking['customer_notes'])) ?>
                        <?php else: ?>
                            <span class="text-muted">Keine Anmerkungen</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kundendaten -->
        <div class="card mb-lg">
            <h2 class="mb-lg">Kundendaten</h2>

            <div class="detail-grid">
                <div class="detail-item">
                    <label>Vorname</label>
                    <div><?= e($booking['customer_firstname']) ?></div>
                </div>

                <div class="detail-item">
                    <label>Nachname</label>
                    <div><?= e($booking['customer_lastname']) ?></div>
                </div>

                <?php if ($booking['customer_company']): ?>
                    <div class="detail-item" style="grid-column: 1 / -1;">
                        <label>Firma</label>
                        <div><?= e($booking['customer_company']) ?></div>
                    </div>
                <?php endif; ?>

                <div class="detail-item">
                    <label>E-Mail</label>
                    <div>
                        <a href="mailto:<?= e($booking['customer_email']) ?>"><?= e($booking['customer_email']) ?></a>
                    </div>
                </div>

                <div class="detail-item">
                    <label>Mobilnummer</label>
                    <div>
                        <a href="tel:<?= e($booking['customer_phone_country'] . $booking['customer_phone_mobile']) ?>">
                            <?= e($booking['customer_phone_country']) ?> <?= e($booking['customer_phone_mobile']) ?>
                        </a>
                    </div>
                </div>

                <?php if ($booking['customer_phone_landline']): ?>
                    <div class="detail-item">
                        <label>Festnetz</label>
                        <div>
                            <a href="tel:<?= e($booking['customer_phone_country'] . $booking['customer_phone_landline']) ?>">
                                <?= e($booking['customer_phone_country']) ?> <?= e($booking['customer_phone_landline']) ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="detail-item" style="grid-column: 1 / -1;">
                    <label>Adresse</label>
                    <div>
                        <?= e($booking['customer_street'] ?? '') ?> <?= e($booking['customer_house_number'] ?? '') ?><br>
                        <?= e($booking['customer_postal_code'] ?? '') ?> <?= e($booking['customer_city'] ?? '') ?>
                    </div>
                </div>

                <?php if ($booking['hellocash_customer_id']): ?>
                    <div class="detail-item" style="grid-column: 1 / -1;">
                        <label>HelloCash Kunden-ID</label>
                        <div>
                            <code><?= e($booking['hellocash_customer_id']) ?></code>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Termin bearbeiten -->
        <div class="card mb-lg">
            <h2 class="mb-lg">Termin bearbeiten</h2>

            <form method="POST" action="">
                <input type="hidden" name="action" value="update_booking">

                <div class="form-group">
                    <label for="booking_date">Datum</label>
                    <input type="date"
                           id="booking_date"
                           name="booking_date"
                           value="<?= e($booking['booking_date']) ?>"
                           class="form-control"
                           required>
                    <small class="text-muted">
                        <?php if ($booking['booking_type'] === 'fixed'): ?>
                            Feste Termine: Dienstag bis Freitag
                        <?php else: ?>
                            Walk-in: Dienstag bis Samstag
                        <?php endif; ?>
                    </small>
                </div>

                <?php if ($booking['booking_type'] === 'fixed'): ?>
                    <div class="form-group">
                        <label for="booking_time">Uhrzeit</label>
                        <input type="time"
                               id="booking_time"
                               name="booking_time"
                               value="<?= e($booking['booking_time']) ?>"
                               class="form-control"
                               required>
                        <small class="text-muted">Verfügbare Zeiten: 11:00 und 12:00 Uhr</small>
                    </div>
                <?php endif; ?>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Termin aktualisieren</button>
                </div>
            </form>
        </div>

        <!-- Status ändern -->
        <div class="card">
            <h2 class="mb-lg">Status ändern</h2>

            <form method="POST" action="">
                <input type="hidden" name="action" value="update_status">

                <div class="form-group mb-lg">
                    <label for="status">Neuer Status</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="pending" <?= $booking['status'] === 'pending' ? 'selected' : '' ?>>
                            Ausstehend
                        </option>
                        <option value="confirmed" <?= $booking['status'] === 'confirmed' ? 'selected' : '' ?>>
                            Bestätigt
                        </option>
                        <option value="completed" <?= $booking['status'] === 'completed' ? 'selected' : '' ?>>
                            Abgeschlossen
                        </option>
                        <option value="cancelled" <?= $booking['status'] === 'cancelled' ? 'selected' : '' ?>>
                            Storniert
                        </option>
                    </select>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Status aktualisieren</button>
                    <a href="<?= BASE_URL ?>/admin/bookings" class="btn btn-outline">Zurück zur Übersicht</a>
                </div>
            </form>
        </div>
    </div>
</section>

<style>
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

.badge {
    display: inline-block;
    padding: 0.35rem 0.65rem;
    font-size: 0.8rem;
    border-radius: 4px;
    font-weight: 500;
}

.badge-info {
    background-color: #17a2b8;
    color: white;
}

.badge-secondary {
    background-color: #6c757d;
    color: white;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.detail-item label {
    display: block;
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-item div {
    font-size: 1rem;
    color: #212529;
}

.note-box {
    padding: 1rem;
    background-color: #f8f9fa;
    border-left: 3px solid var(--color-primary);
    border-radius: 4px;
}

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

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

code {
    padding: 0.25rem 0.5rem;
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 3px;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .detail-grid {
        grid-template-columns: 1fr;
    }

    .detail-item {
        grid-column: 1 / -1 !important;
    }
}
</style>

<?php include __DIR__ . '/../templates/footer.php'; ?>
