<?php
/**
 * Admin: Wochen-Ansicht mit Stunden-Unterteilung
 * PC-Wittfoot UG
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();

// Admin-Rechte prüfen
require_admin();

$db = Database::getInstance();

// Woche bestimmen (ISO-Woche)
$week = isset($_GET['week']) ? (int)$_GET['week'] : (int)date('W');
$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

// Validierung
if ($week < 1 || $week > 53) $week = (int)date('W');
if ($year < 2020 || $year > 2030) $year = (int)date('Y');

// Montag der Woche berechnen
$monday = new DateTime();
$monday->setISODate($year, $week);
$sunday = (clone $monday)->modify('+6 days');

// Vorige/Nächste Woche
$prevWeek = (clone $monday)->modify('-7 days');
$nextWeek = (clone $monday)->modify('+7 days');

// Alle Buchungen für diese Woche laden
$sql = "SELECT * FROM bookings
        WHERE booking_date >= :start_date
        AND booking_date <= :end_date
        ORDER BY booking_date, booking_time";

$bookings = $db->query($sql, [
    ':start_date' => $monday->format('Y-m-d'),
    ':end_date' => $sunday->format('Y-m-d')
]);

// Buchungen nach Datum gruppieren und Zeitbereiche berechnen
$bookingsByDate = [];
foreach ($bookings as $booking) {
    $date = $booking['booking_date'];
    if (!isset($bookingsByDate[$date])) {
        $bookingsByDate[$date] = [];
    }

    // Berechne Start- und Endstunde
    $startTime = $booking['booking_time'] ? substr($booking['booking_time'], 0, 5) : '14:00';
    $endTime = $booking['booking_end_time'] ? substr($booking['booking_end_time'], 0, 5) : null;

    // Wenn keine Endzeit, dann +1 Stunde
    if (!$endTime) {
        $startHour = (int)substr($startTime, 0, 2);
        $endTime = sprintf('%02d:00', $startHour + 1);
    }

    $booking['_display_start'] = $startTime;
    $booking['_display_end'] = $endTime;
    $booking['_start_hour'] = (int)substr($startTime, 0, 2);
    $booking['_end_hour'] = (int)substr($endTime, 0, 2);
    $booking['_duration_hours'] = $booking['_end_hour'] - $booking['_start_hour'];

    $bookingsByDate[$date][] = $booking;
}

// Zeitraster (8:00 - 18:00)
$startHour = 8;
$endHour = 18;
$timeSlots = [];
for ($hour = $startHour; $hour <= $endHour; $hour++) {
    $timeSlots[] = sprintf('%02d:00', $hour);
}

$page_title = 'Wochen-Ansicht | Admin | PC-Wittfoot UG';
$page_description = 'Wochenkalender';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container-fluid" style="max-width: 1800px;">
        <div class="mb-lg" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <a href="<?= BASE_URL ?>/admin" class="btn btn-outline btn-sm">← Dashboard</a>
                <a href="<?= BASE_URL ?>/admin/booking-calendar" class="btn btn-outline btn-sm">📅 Monats-Ansicht</a>
            </div>
        </div>

        <!-- Wochen-Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
            <h1 style="margin: 0;">
                KW <?= $week ?> / <?= $year ?>
                <small style="color: #666; font-size: 0.6em; font-weight: normal;">
                    (<?= $monday->format('d.m.') ?> - <?= $sunday->format('d.m.Y') ?>)
                </small>
            </h1>

            <div style="display: flex; gap: 0.5rem;">
                <button onclick="openCreateModal()" class="btn btn-primary">➕ Neuer Termin</button>
                <a href="?week=<?= $prevWeek->format('W') ?>&year=<?= $prevWeek->format('Y') ?>"
                   class="btn btn-outline btn-sm">←</a>
                <a href="?week=<?= date('W') ?>&year=<?= date('Y') ?>"
                   class="btn btn-outline btn-sm">Heute</a>
                <a href="?week=<?= $nextWeek->format('W') ?>&year=<?= $nextWeek->format('Y') ?>"
                   class="btn btn-outline btn-sm">→</a>
            </div>
        </div>

        <!-- Legende -->
        <div class="card mb-lg" style="padding: 0.75rem;">
            <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; align-items: center; font-size: 0.85rem;">
                <strong>Legende:</strong>
                <span><span class="legend-box" style="background: #28a745;"></span> Bestätigt</span>
                <span><span class="legend-box" style="background: #ffc107;"></span> Ausstehend</span>
                <span><span class="legend-box" style="background: #6c757d;"></span> Abgeschlossen</span>
                <span><span class="legend-box" style="background: #dc3545;"></span> Gesperrt</span>
                <span><span class="legend-box" style="background: #17a2b8;"></span> Intern</span>
            </div>
        </div>

        <!-- Wochen-Grid -->
        <div class="week-grid">
            <!-- Header mit Wochentagen -->
            <div class="week-header week-time-column">Zeit</div>
            <?php
            $current = clone $monday;
            $dayNames = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];
            for ($i = 0; $i < 7; $i++) {
                $isToday = $current->format('Y-m-d') === date('Y-m-d');
                $dayClass = $isToday ? 'week-header today' : 'week-header';
                ?>
                <div class="<?= $dayClass ?>">
                    <div style="font-weight: bold;"><?= $dayNames[$i] ?></div>
                    <div style="font-size: 0.9em;"><?= $current->format('d.m.') ?></div>
                </div>
                <?php
                $current->modify('+1 day');
            }
            ?>

            <!-- Zeit-Slots mit Buchungen -->
            <?php
            $slotIndex = 0;
            foreach ($timeSlots as $timeSlot):
                $currentHour = (int)substr($timeSlot, 0, 2);
            ?>
                <!-- Zeit-Spalte -->
                <div class="week-time-cell">
                    <strong><?= $timeSlot ?></strong>
                </div>

                <!-- Tages-Spalten -->
                <?php
                $current = clone $monday;
                for ($day = 0; $day < 7; $day++) {
                    $dateStr = $current->format('Y-m-d');
                    $dayBookings = $bookingsByDate[$dateStr] ?? [];
                    $isToday = $dateStr === date('Y-m-d');
                    $cellClass = 'week-cell';
                    if ($isToday) $cellClass .= ' today';
                    ?>
                    <div class="<?= $cellClass ?>"
                         data-date="<?= $dateStr ?>"
                         data-hour="<?= $currentHour ?>"
                         onclick="openCreateModalWithDateTime('<?= $dateStr ?>', '<?= $timeSlot ?>')">

                        <?php
                        // Zeige nur Buchungen, die in dieser Stunde BEGINNEN
                        foreach ($dayBookings as $booking):
                            if ($booking['_start_hour'] !== $currentHour) continue;

                            $bgColor = '#28a745'; // confirmed
                            if ($booking['status'] === 'pending') $bgColor = '#ffc107';
                            if ($booking['status'] === 'completed') $bgColor = '#6c757d';
                            if ($booking['booking_type'] === 'blocked') $bgColor = '#dc3545';
                            if ($booking['booking_type'] === 'internal') $bgColor = '#17a2b8';

                            $durationHours = $booking['_duration_hours'];
                            $heightPixels = ($durationHours * 60) - 1; // 60px pro Stunde minus 1px für Border
                            ?>
                            <div class="week-booking week-booking-multi"
                                 style="background-color: <?= $bgColor ?>; height: <?= $heightPixels ?>px; position: absolute; left: 1px; right: 1px; top: 1px; z-index: 10;"
                                 onclick="event.stopPropagation(); openEditModal(<?= $booking['id'] ?>)"
                                 title="<?= $booking['_display_start'] ?> - <?= $booking['_display_end'] ?>">

                                <div style="padding: 0.35rem 0.5rem;">
                                    <?php if ($booking['booking_type'] === 'blocked'): ?>
                                        <strong>🚫 Gesperrt</strong>
                                        <div style="font-size: 0.75em; margin-top: 0.2rem; opacity: 0.9;">
                                            <?= $booking['_display_start'] ?> - <?= $booking['_display_end'] ?>
                                        </div>
                                    <?php elseif ($booking['booking_type'] === 'internal'): ?>
                                        <strong>📝 Intern</strong>
                                        <div style="font-size: 0.75em; margin-top: 0.2rem; opacity: 0.9;">
                                            <?= $booking['_display_start'] ?> - <?= $booking['_display_end'] ?>
                                        </div>
                                        <?php if ($booking['admin_notes']): ?>
                                            <div style="font-size: 0.8em; margin-top: 0.3rem;">
                                                <?= e(mb_substr($booking['admin_notes'], 0, 50)) ?><?= mb_strlen($booking['admin_notes']) > 50 ? '...' : '' ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <strong><?= e($booking['customer_firstname'] . ' ' . substr($booking['customer_lastname'], 0, 1)) ?>.</strong>
                                        <div style="font-size: 0.75em; margin-top: 0.2rem; opacity: 0.9;">
                                            <?= $booking['_display_start'] ?> - <?= $booking['_display_end'] ?>
                                        </div>
                                        <div style="font-size: 0.8em; margin-top: 0.3rem;">
                                            <?php
                                            $services = [
                                                'pc-reparatur' => 'PC-Reparatur',
                                                'notebook-reparatur' => 'Notebook-Reparatur',
                                                'beratung' => 'Beratung',
                                                'software' => 'Software',
                                                'datenrettung' => 'Datenrettung',
                                                'virus-entfernung' => 'Virus-Entfernung',
                                                'upgrade' => 'Hardware-Upgrade',
                                                'sonstiges' => 'Sonstiges'
                                            ];
                                            echo $services[$booking['service_type']] ?? $booking['service_type'];
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php
                    $current->modify('+1 day');
                }
                $slotIndex++;
                ?>
            <?php endforeach; ?>
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
                        <option value="walkin">Walk-in</option>
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

            <div class="form-group">
                <label>Datum *</label>
                <input type="date" id="booking_date" name="booking_date" class="form-control" required>
            </div>

            <div class="form-row" id="time_fields">
                <div class="form-group">
                    <label>Von (Uhrzeit)</label>
                    <input type="time" id="booking_time" name="booking_time" class="form-control">
                </div>

                <div class="form-group">
                    <label>Bis (Uhrzeit)</label>
                    <input type="time" id="booking_end_time" name="booking_end_time" class="form-control">
                    <small class="text-muted">Optional - Standard: +1 Stunde</small>
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
.week-grid {
    display: grid;
    grid-template-columns: 80px repeat(7, 1fr);
    gap: 1px;
    background-color: #ddd;
    border: 1px solid #ddd;
    overflow-x: auto;
}

.week-header {
    background-color: var(--color-primary);
    color: white;
    padding: 0.75rem;
    text-align: center;
    font-weight: 600;
    font-size: 0.9rem;
}

.week-header.today {
    background-color: #0056b3;
}

.week-time-column {
    grid-column: 1;
}

.week-time-cell {
    background-color: #f8f9fa;
    padding: 0.5rem;
    text-align: right;
    font-size: 0.85rem;
    border-right: 2px solid #ddd;
}

.week-cell {
    background-color: white;
    height: 60px;
    min-height: 60px;
    padding: 0;
    cursor: pointer;
    transition: background-color 0.2s;
    position: relative;
    overflow: visible;
}

.week-cell:hover {
    background-color: #f8f9fa;
}

.week-cell.today {
    background-color: #fff3cd;
}

.week-booking {
    padding: 0.35rem 0.5rem;
    border-radius: 4px;
    margin-bottom: 0.25rem;
    font-size: 0.75rem;
    color: white;
    cursor: pointer;
    transition: opacity 0.2s;
}

.week-booking:hover {
    opacity: 0.85;
}

.legend-box {
    display: inline-block;
    width: 16px;
    height: 16px;
    border-radius: 3px;
    vertical-align: middle;
}

/* Modal (same as calendar) */
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

@media (max-width: 1200px) {
    .week-grid {
        font-size: 0.85rem;
    }

    .week-time-cell {
        padding: 0.35rem;
    }

    .week-cell {
        min-height: 50px;
    }
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
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
    document.getElementById('booking_time').value = '11:00';
    openModal();
    updateFormFields();
}

function openCreateModalWithDateTime(date, time) {
    openCreateModal();
    document.getElementById('booking_date').value = date;
    document.getElementById('booking_time').value = time;
}

async function openEditModal(bookingId) {
    try {
        const formData = new FormData();
        formData.append('ajax_action', 'get_booking');
        formData.append('booking_id', bookingId);

        const response = await fetch('<?= BASE_URL ?>/admin/booking-calendar', {method: 'POST', body: formData});
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
            document.getElementById('booking_end_time').value = booking.booking_end_time || '';
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
    const timeFields = document.getElementById('time_fields');

    if (type === 'internal') {
        customerFields.style.display = 'none';
        timeFields.style.display = 'none';
    } else if (type === 'blocked') {
        customerFields.style.display = 'none';
        timeFields.style.display = 'grid';
    } else {
        customerFields.style.display = 'block';
        timeFields.style.display = 'grid';
    }
}

async function saveBooking(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    formData.append('ajax_action', 'save_booking');

    try {
        const response = await fetch('<?= BASE_URL ?>/admin/booking-calendar', {method: 'POST', body: formData});
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
        const response = await fetch('<?= BASE_URL ?>/admin/booking-calendar', {method: 'POST', body: formData});
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

window.onclick = function(event) {
    const modal = document.getElementById('bookingModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
