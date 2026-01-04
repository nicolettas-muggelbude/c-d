<?php
/**
 * Admin: Termin-Kalender mit Modal-Editor
 * PC-Wittfoot UG
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();

// Admin-Rechte prüfen
require_admin();

$db = Database::getInstance();

// AJAX-Handler für Termin erstellen/bearbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_action'])) {
    header('Content-Type: application/json');

    if ($_POST['ajax_action'] === 'save_booking') {
        $bookingId = $_POST['booking_id'] ?? null;
        $bookingType = $_POST['booking_type'] ?? 'fixed';
        $serviceType = $_POST['service_type'] ?? 'sonstiges';
        $bookingDate = $_POST['booking_date'] ?? '';
        $bookingTime = $_POST['booking_time'] ?? null;
        $bookingEndTime = $_POST['booking_end_time'] ?? null;
        $customerFirstname = $_POST['customer_firstname'] ?? '';
        $customerLastname = $_POST['customer_lastname'] ?? '';
        $customerEmail = $_POST['customer_email'] ?? '';
        $customerPhone = $_POST['customer_phone'] ?? '';
        $customerNotes = $_POST['customer_notes'] ?? '';
        $adminNotes = $_POST['admin_notes'] ?? '';
        $status = $_POST['status'] ?? 'confirmed';

        try {
            if ($bookingId) {
                // Update
                $sql = "UPDATE bookings SET
                        booking_type = :type,
                        service_type = :service,
                        booking_date = :date,
                        booking_time = :time,
                        booking_end_time = :end_time,
                        customer_firstname = :firstname,
                        customer_lastname = :lastname,
                        customer_email = :email,
                        customer_phone_mobile = :phone,
                        customer_notes = :customer_notes,
                        admin_notes = :admin_notes,
                        status = :status,
                        updated_at = NOW()
                        WHERE id = :id";

                $db->update($sql, [
                    ':type' => $bookingType,
                    ':service' => $serviceType,
                    ':date' => $bookingDate,
                    ':time' => $bookingTime,
                    ':end_time' => $bookingEndTime,
                    ':firstname' => $customerFirstname,
                    ':lastname' => $customerLastname,
                    ':email' => $customerEmail,
                    ':phone' => $customerPhone,
                    ':customer_notes' => $customerNotes,
                    ':admin_notes' => $adminNotes,
                    ':status' => $status,
                    ':id' => $bookingId
                ]);

                echo json_encode(['success' => true, 'message' => 'Termin aktualisiert']);
            } else {
                // Insert
                $sql = "INSERT INTO bookings (
                        booking_type, service_type, booking_date, booking_time, booking_end_time,
                        customer_firstname, customer_lastname, customer_email,
                        customer_phone_country, customer_phone_mobile,
                        customer_notes, admin_notes, status, created_at
                        ) VALUES (
                        :type, :service, :date, :time, :end_time,
                        :firstname, :lastname, :email,
                        '+49', :phone,
                        :customer_notes, :admin_notes, :status, NOW()
                        )";

                $bookingId = $db->insert($sql, [
                    ':type' => $bookingType,
                    ':service' => $serviceType,
                    ':date' => $bookingDate,
                    ':time' => $bookingTime,
                    ':end_time' => $bookingEndTime,
                    ':firstname' => $customerFirstname,
                    ':lastname' => $customerLastname,
                    ':email' => $customerEmail,
                    ':phone' => $customerPhone,
                    ':customer_notes' => $customerNotes,
                    ':admin_notes' => $adminNotes,
                    ':status' => $status
                ]);

                // Email-Bestätigung senden (nur bei Kundenterminen mit Email)
                if ($bookingId && !empty($customerEmail) && in_array($bookingType, ['fixed', 'walkin'])) {
                    $emailService = new EmailService();
                    $emailService->sendBookingEmail($bookingId, 'confirmation');
                }

                echo json_encode(['success' => true, 'message' => 'Termin erstellt', 'id' => $bookingId]);
            }
        } catch (Exception $e) {
            error_log('Booking save error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Fehler beim Speichern']);
        }
        exit;
    }

    if ($_POST['ajax_action'] === 'delete_booking') {
        $bookingId = $_POST['booking_id'] ?? null;
        if ($bookingId) {
            try {
                $db->delete("DELETE FROM bookings WHERE id = :id", [':id' => $bookingId]);
                echo json_encode(['success' => true, 'message' => 'Termin gelöscht']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'Fehler beim Löschen']);
            }
        }
        exit;
    }

    if ($_POST['ajax_action'] === 'get_booking') {
        $bookingId = $_POST['booking_id'] ?? null;
        if ($bookingId) {
            $booking = $db->querySingle("SELECT * FROM bookings WHERE id = :id", [':id' => $bookingId]);
            echo json_encode(['success' => true, 'booking' => $booking]);
        }
        exit;
    }
}

// Monat und Jahr
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('n');
$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

if ($month < 1 || $month > 12) $month = (int)date('n');
if ($year < 2020 || $year > 2030) $year = (int)date('Y');

$firstDay = new DateTime("$year-$month-01");
$lastDay = (clone $firstDay)->modify('last day of this month');
$prevMonth = (clone $firstDay)->modify('-1 month');
$nextMonth = (clone $firstDay)->modify('+1 month');

// Alle Buchungen für diesen Monat
$sql = "SELECT * FROM bookings
        WHERE booking_date >= :start_date
        AND booking_date <= :end_date
        ORDER BY booking_date, booking_time";

$bookings = $db->query($sql, [
    ':start_date' => $firstDay->format('Y-m-d'),
    ':end_date' => $lastDay->format('Y-m-d')
]);

$bookingsByDate = [];
foreach ($bookings as $booking) {
    $date = $booking['booking_date'];
    if (!isset($bookingsByDate[$date])) {
        $bookingsByDate[$date] = [];
    }
    $bookingsByDate[$date][] = $booking;
}

// Walk-ins nach Datum gruppieren (für spezielle Darstellung)
$walkinsByDate = [];
foreach ($bookingsByDate as $date => $dayBookings) {
    $walkins = array_filter($dayBookings, function($b) {
        return $b['booking_type'] === 'walkin';
    });
    if (!empty($walkins)) {
        $walkinsByDate[$date] = array_values($walkins);
    }
}

$page_title = 'Termin-Kalender | Admin | PC-Wittfoot UG';
$page_description = 'Kalender-Übersicht';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container" style="max-width: 1600px;">
        <div class="mb-lg" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
            <div style="display: flex; gap: 0.5rem;">
                <a href="<?= BASE_URL ?>/admin" class="btn btn-outline btn-sm">← Dashboard</a>
                <a href="<?= BASE_URL ?>/admin/booking-week" class="btn btn-outline btn-sm">📆 Wochen-Ansicht</a>
                <a href="<?= BASE_URL ?>/admin/bookings" class="btn btn-outline btn-sm">📋 Listen-Ansicht</a>
            </div>
        </div>

        <!-- Kalender-Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
            <h1 style="margin: 0;">
                <?php
                $monthNames = ['', 'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
                              'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
                echo $monthNames[(int)$firstDay->format('n')] . ' ' . $firstDay->format('Y');
                ?>
            </h1>

            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button onclick="openCreateModal()" class="btn btn-primary">
                    ➕ Neuer Termin
                </button>
                <button onclick="openBlockModal()" class="btn btn-outline">
                    🚫 Zeit sperren
                </button>
                <a href="?month=<?= $prevMonth->format('n') ?>&year=<?= $prevMonth->format('Y') ?>"
                   class="btn btn-outline btn-sm">←</a>
                <a href="?month=<?= date('n') ?>&year=<?= date('Y') ?>"
                   class="btn btn-outline btn-sm">Heute</a>
                <a href="?month=<?= $nextMonth->format('n') ?>&year=<?= $nextMonth->format('Y') ?>"
                   class="btn btn-outline btn-sm">→</a>
            </div>
        </div>

        <!-- Legende -->
        <div class="card mb-lg" style="padding: 1rem;">
            <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; align-items: center; font-size: 0.9rem;">
                <strong>Legende:</strong>
                <span><span class="legend-box" style="background: #28a745;"></span> Bestätigt</span>
                <span><span class="legend-box" style="background: #ffc107;"></span> Ausstehend</span>
                <span><span class="legend-box" style="background: #6c757d;"></span> Abgeschlossen</span>
                <span><span class="legend-box" style="background: #dc3545;"></span> Gesperrt</span>
                <span><span class="legend-box" style="background: #17a2b8;"></span> Intern</span>
            </div>
        </div>

        <!-- Kalender -->
        <div class="calendar-grid">
            <div class="calendar-header">Mo</div>
            <div class="calendar-header">Di</div>
            <div class="calendar-header">Mi</div>
            <div class="calendar-header">Do</div>
            <div class="calendar-header">Fr</div>
            <div class="calendar-header">Sa</div>
            <div class="calendar-header">So</div>

            <?php
            $current = clone $firstDay;
            $dayOfWeek = (int)$current->format('N');
            if ($dayOfWeek > 1) {
                $current->modify('-' . ($dayOfWeek - 1) . ' days');
            }

            for ($week = 0; $week < 6; $week++) {
                for ($day = 1; $day <= 7; $day++) {
                    $dateStr = $current->format('Y-m-d');
                    $dayNum = $current->format('j');
                    $isCurrentMonth = $current->format('n') == $month;
                    $isToday = $dateStr === date('Y-m-d');
                    $dayBookings = $bookingsByDate[$dateStr] ?? [];

                    $cellClass = 'calendar-day';
                    if (!$isCurrentMonth) $cellClass .= ' other-month';
                    if ($isToday) $cellClass .= ' today';
                    ?>

                    <div class="<?= $cellClass ?>" onclick="openCreateModalWithDate('<?= $dateStr ?>')">
                        <div class="day-number"><?= $dayNum ?></div>

                        <?php if (!empty($dayBookings)): ?>
                            <div class="bookings-list" onclick="event.stopPropagation()">
                                <?php
                                // Walk-ins gruppiert anzeigen
                                if (isset($walkinsByDate[$dateStr]) && !empty($walkinsByDate[$dateStr])):
                                    $walkins = $walkinsByDate[$dateStr];
                                    $walkinCount = count($walkins);
                                ?>
                                    <div class="booking-item"
                                         style="background-color: #6c757d; cursor: pointer;"
                                         onclick="showWalkinDetails('<?= $dateStr ?>')"
                                         title="<?= $walkinCount ?> Walk-in Termine">
                                        <strong>14:00-17:00</strong>
                                        <span>🚶 Ich komme vorbei (<?= $walkinCount ?>)</span>
                                    </div>
                                <?php endif; ?>

                                <?php
                                // Andere Buchungen (fixed, blocked, internal)
                                foreach ($dayBookings as $booking):
                                    if ($booking['booking_type'] === 'walkin') continue; // Walk-ins werden gruppiert angezeigt

                                    $bgColor = '#28a745'; // confirmed
                                    if ($booking['status'] === 'pending') $bgColor = '#ffc107';
                                    if ($booking['status'] === 'completed') $bgColor = '#6c757d';
                                    if ($booking['booking_type'] === 'blocked') $bgColor = '#dc3545';
                                    if ($booking['booking_type'] === 'internal') $bgColor = '#17a2b8';
                                    ?>
                                    <div class="booking-item"
                                         style="background-color: <?= $bgColor ?>;"
                                         onclick="openEditModal(<?= $booking['id'] ?>)">
                                        <?php if ($booking['booking_type'] === 'fixed' && $booking['booking_time']): ?>
                                            <strong><?= substr($booking['booking_time'], 0, 5) ?></strong>
                                        <?php endif; ?>
                                        <?php if ($booking['booking_type'] === 'blocked'): ?>
                                            <span>🚫 Gesperrt</span>
                                        <?php elseif ($booking['booking_type'] === 'internal'): ?>
                                            <span>📝 <?= e($booking['admin_notes'] ?: 'Interne Notiz') ?></span>
                                        <?php else: ?>
                                            <span><?= e($booking['customer_firstname'] . ' ' . substr($booking['customer_lastname'], 0, 1)) ?>.</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php
                    $current->modify('+1 day');
                }
            }
            ?>
        </div>
    </div>
</section>

<!-- Modal für Termin erstellen/bearbeiten -->
<div id="bookingModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Termin erstellen</h2>
            <span class="modal-close" onclick="closeModal()">&times;</span>
        </div>
        <form id="bookingForm" onsubmit="saveBooking(event)">
            <input type="hidden" id="booking_id" name="booking_id">

            <div class="form-row">
                <div class="form-group">
                    <label>Terminart</label>
                    <select id="booking_type" name="booking_type" class="form-control" onchange="updateFormFields()">
                        <option value="fixed">Fester Termin</option>
                        <option value="walkin">Ich komme vorbei</option>
                        <option value="internal">Interne Notiz</option>
                        <option value="blocked">Gesperrt</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="confirmed">Bestätigt</option>
                        <option value="pending">Ausstehend</option>
                        <option value="completed">Abgeschlossen</option>
                        <option value="cancelled">Storniert</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Datum *</label>
                    <input type="date" id="booking_date" name="booking_date" class="form-control" required>
                </div>

                <div class="form-group" id="time_field">
                    <label>Uhrzeit</label>
                    <input type="time" id="booking_time" name="booking_time" class="form-control">
                </div>
            </div>

            <div id="customer_fields">
                <!-- HelloCash Kundensuche -->
                <div class="form-group" style="position: relative;">
                    <label>Kunde aus HelloCash suchen</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="text" id="hellocash_search" class="form-control"
                               placeholder="Name, Email oder Telefon eingeben...">
                        <button type="button" class="btn btn-outline" onclick="searchHelloCash()">Suchen</button>
                    </div>
                    <input type="hidden" id="hellocash_user_id" name="hellocash_user_id">
                    <!-- Suchergebnisse Dropdown -->
                    <div id="hellocash_results" class="search-results-dropdown" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label>Dienstleistung</label>
                    <select id="service_type" name="service_type" class="form-control">
                        <option value="pc-reparatur">PC-Reparatur</option>
                        <option value="notebook-reparatur">Notebook-Reparatur</option>
                        <option value="beratung">Beratung</option>
                        <option value="software">Software-Installation</option>
                        <option value="datenrettung">Datenrettung</option>
                        <option value="virus-entfernung">Virus-Entfernung</option>
                        <option value="upgrade">Hardware-Upgrade</option>
                        <option value="sonstiges">Sonstiges</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Vorname</label>
                        <input type="text" id="customer_firstname" name="customer_firstname" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Nachname</label>
                        <input type="text" id="customer_lastname" name="customer_lastname" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>E-Mail</label>
                        <input type="email" id="customer_email" name="customer_email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Telefon</label>
                        <input type="tel" id="customer_phone" name="customer_phone" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label>Kundenanmerkungen</label>
                    <textarea id="customer_notes" name="customer_notes" class="form-control" rows="2"></textarea>
                </div>
            </div>

            <div class="form-group">
                <label><strong>Admin-Notizen (intern)</strong></label>
                <textarea id="admin_notes" name="admin_notes" class="form-control" rows="3" placeholder="Nur für Admin-Team sichtbar"></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" id="deleteBtn" class="btn btn-outline" style="color: #dc3545; margin-right: auto;" onclick="deleteBooking()">Löschen</button>
                <button type="button" class="btn btn-outline" onclick="closeModal()">Abbrechen</button>
                <button type="submit" class="btn btn-primary">Speichern</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Existing calendar styles */
.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    background-color: #ddd;
    border: 1px solid #ddd;
}

.calendar-header {
    background-color: var(--color-primary);
    color: white;
    padding: 0.75rem;
    text-align: center;
    font-weight: 600;
    font-size: 0.9rem;
}

.calendar-day {
    background-color: white;
    min-height: 100px;
    padding: 0.5rem;
    cursor: pointer;
    transition: background-color 0.2s;
}

.calendar-day:hover {
    background-color: #f8f9fa;
}

.calendar-day.other-month {
    background-color: #f8f9fa;
    opacity: 0.6;
}

.calendar-day.today {
    background-color: #fff3cd;
    border: 2px solid var(--color-primary);
}

.day-number {
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.bookings-list {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.booking-item {
    padding: 0.35rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    color: white;
    cursor: pointer;
    transition: opacity 0.2s;
}

.booking-item:hover {
    opacity: 0.85;
}

.legend-box {
    display: inline-block;
    width: 16px;
    height: 16px;
    border-radius: 3px;
    vertical-align: middle;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    overflow-y: auto;
}

.modal-content {
    background-color: white;
    margin: 2rem auto;
    width: 90%;
    max-width: 700px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #ddd;
}

.modal-header h2 {
    margin: 0;
}

.modal-close {
    font-size: 2rem;
    font-weight: bold;
    cursor: pointer;
    color: #999;
}

.modal-close:hover {
    color: #333;
}

#bookingForm {
    padding: 1.5rem;
}

.modal-footer {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
    padding: 1.5rem;
    border-top: 1px solid #ddd;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

/* HelloCash Suchergebnis Dropdown */
.search-results-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
    margin-top: 0.25rem;
}

.search-result-item {
    padding: 0.75rem 1rem;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

.search-result-item:last-child {
    border-bottom: none;
}

.search-result-item:hover {
    background-color: #f8f9fa;
}

.search-result-item strong {
    display: block;
    color: #333;
}

.search-result-item small {
    color: #666;
}

.form-control {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

textarea.form-control {
    resize: vertical;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }

    .calendar-day {
        min-height: 70px;
        padding: 0.25rem;
    }
}
</style>

<script>
let currentBookingId = null;

function openModal() {
    document.getElementById('bookingModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('bookingModal').style.display = 'none';
    document.getElementById('bookingForm').reset();
    currentBookingId = null;
}

function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Neuer Termin';
    document.getElementById('deleteBtn').style.display = 'none';
    document.getElementById('booking_date').value = new Date().toISOString().split('T')[0];
    openModal();
    updateFormFields();
}

function openCreateModalWithDate(date) {
    openCreateModal();
    document.getElementById('booking_date').value = date;
}

function openBlockModal() {
    openCreateModal();
    document.getElementById('booking_type').value = 'blocked';
    document.getElementById('customer_firstname').value = 'Gesperrt';
    document.getElementById('customer_lastname').value = '';
    updateFormFields();
}

async function openEditModal(bookingId) {
    try {
        const formData = new FormData();
        formData.append('ajax_action', 'get_booking');
        formData.append('booking_id', bookingId);

        const response = await fetch('', {method: 'POST', body: formData});
        const data = await response.json();

        if (data.success) {
            const booking = data.booking;
            currentBookingId = bookingId;

            document.getElementById('modalTitle').textContent = 'Termin bearbeiten';
            document.getElementById('booking_id').value = booking.id;
            document.getElementById('booking_type').value = booking.booking_type;
            document.getElementById('service_type').value = booking.service_type;
            document.getElementById('booking_date').value = booking.booking_date;
            document.getElementById('booking_time').value = booking.booking_time || '';
            document.getElementById('customer_firstname').value = booking.customer_firstname || '';
            document.getElementById('customer_lastname').value = booking.customer_lastname || '';
            document.getElementById('customer_email').value = booking.customer_email || '';
            document.getElementById('customer_phone').value = booking.customer_phone_mobile || '';
            document.getElementById('customer_notes').value = booking.customer_notes || '';
            document.getElementById('admin_notes').value = booking.admin_notes || '';
            document.getElementById('status').value = booking.status;
            document.getElementById('deleteBtn').style.display = 'block';

            updateFormFields();
            openModal();
        }
    } catch (error) {
        alert('Fehler beim Laden des Termins');
    }
}

function updateFormFields() {
    const type = document.getElementById('booking_type').value;
    const customerFields = document.getElementById('customer_fields');
    const timeField = document.getElementById('time_field');

    if (type === 'internal' || type === 'blocked') {
        customerFields.style.display = 'none';
        timeField.style.display = type === 'blocked' ? 'block' : 'none';
    } else {
        customerFields.style.display = 'block';
        timeField.style.display = 'block';
    }
}

async function saveBooking(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    formData.append('ajax_action', 'save_booking');

    try {
        const response = await fetch('', {method: 'POST', body: formData});
        const data = await response.json();

        if (data.success) {
            closeModal();
            location.reload();
        } else {
            alert('Fehler: ' + data.error);
        }
    } catch (error) {
        alert('Fehler beim Speichern');
    }
}

async function deleteBooking() {
    if (!currentBookingId || !confirm('Termin wirklich löschen?')) return;

    const formData = new FormData();
    formData.append('ajax_action', 'delete_booking');
    formData.append('booking_id', currentBookingId);

    try {
        const response = await fetch('', {method: 'POST', body: formData});
        const data = await response.json();

        if (data.success) {
            closeModal();
            location.reload();
        }
    } catch (error) {
        alert('Fehler beim Löschen');
    }
}

// HelloCash Kundensuche
async function searchHelloCash() {
    const query = document.getElementById('hellocash_search').value.trim();
    const resultsDiv = document.getElementById('hellocash_results');

    if (query.length < 2) {
        resultsDiv.style.display = 'none';
        return;
    }

    try {
        const response = await fetch('<?= BASE_URL ?>/api/hellocash-search', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({action: 'search', query: query})
        });

        const data = await response.json();

        if (data.success && data.results.length > 0) {
            // Ergebnisse anzeigen
            resultsDiv.innerHTML = data.results.map(user => `
                <div class="search-result-item" onclick="selectHelloCashUser(${user.user_id})">
                    <strong>${user.display_name}</strong><br>
                    <small>${user.email || ''} ${user.phone || ''}</small>
                </div>
            `).join('');
            resultsDiv.style.display = 'block';
        } else {
            resultsDiv.innerHTML = '<div class="search-result-item">Keine Ergebnisse gefunden</div>';
            resultsDiv.style.display = 'block';
        }
    } catch (error) {
        console.error('Fehler bei HelloCash-Suche:', error);
        alert('Fehler bei der Suche');
    }
}

async function selectHelloCashUser(userId) {
    try {
        const response = await fetch('<?= BASE_URL ?>/api/hellocash-search', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({action: 'get_user', user_id: userId})
        });

        const data = await response.json();

        if (data.success && data.user) {
            // Kundendaten in Formular eintragen
            const user = data.user;
            document.getElementById('hellocash_user_id').value = user.user_id;
            document.getElementById('customer_firstname').value = user.firstname;
            document.getElementById('customer_lastname').value = user.lastname;
            document.getElementById('customer_email').value = user.email;
            document.getElementById('customer_phone').value = user.phone;

            // Suche zurücksetzen
            document.getElementById('hellocash_search').value = user.display_name || (user.firstname + ' ' + user.lastname);
            document.getElementById('hellocash_results').style.display = 'none';
        }
    } catch (error) {
        console.error('Fehler beim Laden der Kundendaten:', error);
        alert('Fehler beim Laden der Kundendaten');
    }
}

// Suche auch bei Enter-Taste
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('hellocash_search');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchHelloCash();
            }
        });

        // Dropdown schließen bei Klick außerhalb
        document.addEventListener('click', function(e) {
            const resultsDiv = document.getElementById('hellocash_results');
            if (!e.target.closest('.form-group')) {
                resultsDiv.style.display = 'none';
            }
        });
    }
});

// Walk-in Details anzeigen
const walkinData = <?= json_encode($walkinsByDate) ?>;

function showWalkinDetails(dateStr) {
    const walkins = walkinData[dateStr] || [];
    if (walkins.length === 0) return;

    if (walkins.length === 1) {
        // Bei nur einem Walk-in direkt Edit-Modal öffnen
        openEditModal(walkins[0].id);
        return;
    }

    // Bei mehreren Walk-ins: Liste anzeigen
    let html = `<div style="background: white; border: 2px solid #6c757d; border-radius: 8px; padding: 1.5rem; max-width: 600px; max-height: 80vh; overflow-y: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">`;
    html += `<h3 style="margin: 0 0 1rem 0; color: #333; font-size: 1.3rem;">🚶 Ich komme vorbei (${walkins.length})</h3>`;
    html += `<div style="font-size: 1rem; color: #666; margin-bottom: 1.5rem;">14:00-17:00 Uhr</div>`;
    html += `<div style="display: flex; flex-direction: column; gap: 0.75rem;">`;

    const serviceLabels = {
        'beratung': 'Beratung',
        'verkauf': 'Verkauf',
        'fernwartung': 'Fernwartung',
        'hausbesuch': 'Hausbesuch',
        'installation': 'Installation',
        'diagnose': 'Diagnose',
        'reparatur': 'Reparatur',
        'sonstiges': 'Sonstiges'
    };

    walkins.forEach(w => {
        const time = w.booking_time ? w.booking_time.substring(0, 5) : '—';
        const service = serviceLabels[w.service_type] || w.service_type;
        const notes = w.customer_notes ? w.customer_notes.substring(0, 100) : '';

        html += `<div onclick="openEditModal(${w.id}); closeWalkinPopup();" style="cursor: pointer; padding: 1rem; background: #f8f9fa; border-radius: 6px; border-left: 4px solid #6c757d; transition: all 0.2s;" onmouseover="this.style.background='#e9ecef'" onmouseout="this.style.background='#f8f9fa'">`;
        html += `<div style="font-size: 1.1rem; font-weight: bold; margin-bottom: 0.3rem;">${w.customer_firstname} ${w.customer_lastname}</div>`;
        html += `<div style="font-size: 0.95rem; color: #666; margin-bottom: 0.3rem;">⏰ Empfohlung: ${time} Uhr</div>`;
        html += `<div style="font-size: 0.95rem; color: #666; margin-bottom: 0.3rem;">📋 Anliegen: ${service}</div>`;
        if (notes) {
            html += `<div style="font-size: 0.9rem; color: #555; font-style: italic; margin-top: 0.5rem; padding-top: 0.5rem; border-top: 1px solid #dee2e6;">💬 ${notes}${w.customer_notes.length > 100 ? '...' : ''}</div>`;
        }
        html += `</div>`;
    });

    html += `</div>`;
    html += `<div style="margin-top: 1.5rem; text-align: right;"><button onclick="closeWalkinPopup()" class="btn btn-sm btn-outline">Schließen</button></div>`;
    html += `</div>`;

    // Popup erstellen
    const popup = document.createElement('div');
    popup.id = 'walkinPopup';
    popup.style.cssText = 'position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10000;';
    popup.innerHTML = html;

    const overlay = document.createElement('div');
    overlay.id = 'walkinOverlay';
    overlay.style.cssText = 'position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999;';
    overlay.onclick = closeWalkinPopup;

    document.body.appendChild(overlay);
    document.body.appendChild(popup);
}

function closeWalkinPopup() {
    const popup = document.getElementById('walkinPopup');
    const overlay = document.getElementById('walkinOverlay');
    if (popup) popup.remove();
    if (overlay) overlay.remove();
}

// Modal schließen bei Klick außerhalb
window.onclick = function(event) {
    const modal = document.getElementById('bookingModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
