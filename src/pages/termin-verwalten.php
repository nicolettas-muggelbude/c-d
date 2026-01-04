<?php
/**
 * Terminverwaltung für Kunden
 * PC-Wittfoot UG
 *
 * Kunden können hier ihre Buchung ansehen, ändern oder stornieren
 * Zugriff via Magic Link (Token in URL)
 */

// Token aus Query-String holen
$token = $_GET['token'] ?? null;
$error = null;
$cancelled = false;
$booking = null;
$canModify = false;
$canCancel = false;

// Token validieren und Buchung laden
if (!$token) {
    $error = 'Kein gültiger Verwaltungs-Link. Bitte verwenden Sie den Link aus Ihrer Bestätigungs-Email.';
} else {
    $db = Database::getInstance();

    // Buchung anhand Token suchen
    $sql = "SELECT * FROM bookings WHERE manage_token = :token LIMIT 1";
    $booking = $db->querySingle($sql, [':token' => $token]);

    if (!$booking) {
        $error = 'Keine Terminbuchung vorhanden. Der Link ist möglicherweise ungültig oder die Buchung wurde bereits gelöscht.';
    } elseif ($booking['status'] === 'cancelled') {
        $cancelled = true;
    } else {
        // Zeitpunkt der Buchung berechnen
        $bookingDateTime = new DateTime($booking['booking_date'] . ' ' . ($booking['booking_time'] ?? '00:00:00'));
        $now = new DateTime();
        $hoursUntil = ($bookingDateTime->getTimestamp() - $now->getTimestamp()) / 3600;

        // Änderung nur möglich wenn >= 48h vorher
        $canModify = $hoursUntil >= 48;

        // Stornierung nur möglich wenn >= 24h vorher
        $canCancel = $hoursUntil >= 24;
    }
}

// Service-Namen mapping
$serviceNames = [
    'beratung' => 'Beratung',
    'verkauf' => 'Verkauf',
    'fernwartung' => 'Fernwartung',
    'hausbesuch' => 'Hausbesuch',
    'installation' => 'Installation',
    'diagnose' => 'Diagnose',
    'reparatur' => 'Reparatur',
    'sonstiges' => 'Sonstiges'
];

$bookingTypeNames = [
    'fixed' => 'Fester Termin',
    'walkin' => 'Ich komme vorbei'
];
?>
<?php
$page_title = 'Termin verwalten | PC-Wittfoot UG';
$page_description = 'Verwalten Sie Ihre Terminbuchung - ansehen, ändern oder stornieren.';
$current_page = 'termin';
$extra_css = ['css/booking.css', 'css/flatpickr.min.css'];

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <h1>Meine Buchung verwalten</h1>

            <?php if ($cancelled): ?>
                <!-- Storniert -->
                <div class="card">
                    <div class="alert alert-info">
                        Kein Termin gebucht
                    </div>
                    <p>
                        <a href="/termin" class="btn btn-primary" onclick="saveCustomerDataToStorage()">Neuen Termin buchen</a>
                        <a href="/kontakt" class="btn btn-secondary">Kontakt aufnehmen</a>
                    </p>
                </div>

                <script>
                    // Kundendaten in sessionStorage speichern, damit sie beim erneuten Buchen verfügbar sind
                    function saveCustomerDataToStorage() {
                        const customerData = {
                            customer_firstname: <?php echo json_encode($booking['customer_firstname']); ?>,
                            customer_lastname: <?php echo json_encode($booking['customer_lastname']); ?>,
                            customer_company: <?php echo json_encode($booking['customer_company']); ?>,
                            customer_email: <?php echo json_encode($booking['customer_email']); ?>,
                            customer_phone_country: <?php echo json_encode($booking['customer_phone_country']); ?>,
                            customer_phone_mobile: <?php echo json_encode($booking['customer_phone_mobile']); ?>,
                            customer_phone_landline: <?php echo json_encode($booking['customer_phone_landline']); ?>,
                            customer_street: <?php echo json_encode($booking['customer_street']); ?>,
                            customer_house_number: <?php echo json_encode($booking['customer_house_number']); ?>,
                            customer_postal_code: <?php echo json_encode($booking['customer_postal_code']); ?>,
                            customer_city: <?php echo json_encode($booking['customer_city']); ?>,
                            customer_notes: '' // Notizen nicht übernehmen bei neuer Buchung
                        };
                        sessionStorage.setItem('booking_customer_data', JSON.stringify(customerData));
                        console.log('Kundendaten für neue Buchung in sessionStorage gespeichert');
                    }
                </script>

            <?php elseif ($error): ?>
                <!-- Fehler -->
                <div class="card">
                    <div class="alert alert-error">
                        <strong>Fehler:</strong> <?php echo htmlspecialchars($error); ?>
                    </div>
                    <p>
                        <a href="/termin" class="btn btn-primary">Neuen Termin buchen</a>
                        <a href="/kontakt" class="btn btn-secondary">Kontakt aufnehmen</a>
                    </p>
                </div>

            <?php else: ?>
                <!-- Buchungsdetails -->
                <div class="card">
                    <h2>Ihre Buchung</h2>

                    <div class="booking-summary">
                        <dl>
                            <dt>Buchungsnummer:</dt>
                            <dd><strong><?php echo str_pad($booking['id'], 6, '0', STR_PAD_LEFT); ?></strong></dd>

                            <dt>Terminart:</dt>
                            <dd><?php echo htmlspecialchars($bookingTypeNames[$booking['booking_type']] ?? $booking['booking_type']); ?></dd>

                            <dt>Dienstleistung:</dt>
                            <dd><?php echo htmlspecialchars($serviceNames[$booking['service_type']] ?? $booking['service_type']); ?></dd>

                            <dt>Datum:</dt>
                            <dd><?php
                                $date = DateTime::createFromFormat('Y-m-d', $booking['booking_date']);
                                echo $date ? $date->format('d.m.Y') : $booking['booking_date'];
                            ?></dd>

                            <?php if ($booking['booking_type'] === 'fixed' && $booking['booking_time']): ?>
                                <dt>Uhrzeit:</dt>
                                <dd><?php
                                    $time = DateTime::createFromFormat('H:i:s', $booking['booking_time']);
                                    echo $time ? $time->format('H:i') : $booking['booking_time'];
                                ?> Uhr</dd>
                            <?php endif; ?>

                            <dt>Name:</dt>
                            <dd><?php echo htmlspecialchars($booking['customer_firstname'] . ' ' . $booking['customer_lastname']); ?></dd>

                            <?php if ($booking['customer_company']): ?>
                                <dt>Firma:</dt>
                                <dd><?php echo htmlspecialchars($booking['customer_company']); ?></dd>
                            <?php endif; ?>

                            <dt>E-Mail:</dt>
                            <dd><?php echo htmlspecialchars($booking['customer_email']); ?></dd>

                            <dt>Telefon (Mobil):</dt>
                            <dd><?php echo htmlspecialchars($booking['customer_phone_country'] . ' ' . $booking['customer_phone_mobile']); ?></dd>

                            <?php if ($booking['customer_phone_landline']): ?>
                                <dt>Telefon (Festnetz):</dt>
                                <dd><?php echo htmlspecialchars($booking['customer_phone_country'] . ' ' . $booking['customer_phone_landline']); ?></dd>
                            <?php endif; ?>

                            <dt>Adresse:</dt>
                            <dd><?php echo htmlspecialchars($booking['customer_street'] . ' ' . $booking['customer_house_number'] . ', ' . $booking['customer_postal_code'] . ' ' . $booking['customer_city']); ?></dd>

                            <?php if ($booking['customer_notes']): ?>
                                <dt>Anmerkungen:</dt>
                                <dd><div class="note-box"><?php echo nl2br(htmlspecialchars($booking['customer_notes'])); ?></div></dd>
                            <?php endif; ?>

                            <dt>Status:</dt>
                            <dd>
                                <?php
                                $statusLabels = [
                                    'pending' => 'Ausstehend',
                                    'confirmed' => 'Bestätigt',
                                    'completed' => 'Abgeschlossen',
                                    'cancelled' => 'Storniert'
                                ];
                                $statusColors = [
                                    'pending' => '#f39c12',
                                    'confirmed' => '#27ae60',
                                    'completed' => '#95a5a6',
                                    'cancelled' => '#e74c3c'
                                ];
                                $status = $booking['status'];
                                ?>
                                <span style="color: <?php echo $statusColors[$status] ?? '#333'; ?>; font-weight: bold;">
                                    <?php echo $statusLabels[$status] ?? $status; ?>
                                </span>
                            </dd>
                        </dl>
                    </div>

                    <hr style="margin: var(--space-xl) 0;">

                    <!-- Aktionen -->
                    <div class="form-actions">
                        <?php if ($canModify): ?>
                            <button type="button" class="btn btn-secondary" onclick="enableEditMode()">
                                Termin ändern
                            </button>
                        <?php else: ?>
                            <div class="alert alert-info" style="margin-bottom: var(--space-md);">
                                Terminänderungen sind nur bis 48 Stunden vor dem Termin möglich.
                            </div>
                        <?php endif; ?>

                        <?php if ($canCancel): ?>
                            <button type="button" class="btn btn-danger" onclick="confirmCancellation()">
                                Termin stornieren
                            </button>
                        <?php else: ?>
                            <div class="alert alert-info" style="margin-bottom: var(--space-md);">
                                Stornierungen sind nur bis 24 Stunden vor dem Termin möglich.
                                Bitte kontaktieren Sie uns telefonisch.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Terminänderungs-Formular -->
                <div id="reschedule-form-container" style="display: none; margin-top: var(--space-lg);">
                    <div class="card">
                        <h3>Termin ändern</h3>
                        <p>Wählen Sie einen neuen Termin aus:</p>

                        <form id="reschedule-form">
                            <div class="form-group">
                                <label for="new_booking_date">Neues Datum *</label>
                                <div style="position: relative;">
                                    <input type="text"
                                           id="new_booking_date"
                                           name="new_booking_date"
                                           class="form-control"
                                           placeholder="📅 Klicken Sie hier, um einen Termin zu wählen"
                                           required
                                           readonly
                                           style="cursor: pointer; padding-right: 2.5rem;">
                                    <span style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 1.25rem;">📅</span>
                                </div>
                                <small class="form-help">Ausgebuchte Tage sind im Kalender ausgegraut</small>
                            </div>

                            <?php if ($booking['booking_type'] === 'fixed'): ?>
                            <!-- Zeit-Auswahl nur für feste Termine -->
                            <div class="form-group" id="new-time-selection">
                                <label>Neue Uhrzeit *</label>
                                <div class="time-slots" id="new-time-slots">
                                    <p style="text-align: center; color: var(--text-muted);">Bitte wählen Sie zuerst ein Datum aus.</p>
                                </div>
                                <input type="hidden" name="new_booking_time" id="new_booking_time">
                            </div>
                            <?php endif; ?>

                            <div class="form-actions">
                                <button type="button" class="btn btn-outline" onclick="cancelEditMode()">
                                    Abbrechen
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    Termin ändern
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            <?php endif; ?>
    </div>
</section>

<script>
        function confirmCancellation() {
            if (confirm('Möchten Sie diesen Termin wirklich stornieren? Diese Aktion kann nicht rückgängig gemacht werden.')) {
                // API-Call zur Stornierung
                cancelBooking();
            }
        }

        async function cancelBooking() {
            const token = '<?php echo htmlspecialchars($token ?? '', ENT_QUOTES); ?>';

            try {
                const response = await fetch('/api/booking-cancel', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ token })
                });

                const result = await response.json();

                if (result.success) {
                    alert('Ihr Termin wurde erfolgreich storniert. Sie erhalten eine Bestätigungs-Email.');
                    location.reload();
                } else {
                    alert('Fehler beim Stornieren: ' + (result.error || 'Unbekannter Fehler'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.');
            }
        }

        // === Terminänderung ===

        let flatpickrInstance = null;
        let fullyBookedDates = [];
        const bookingType = '<?php echo htmlspecialchars($booking['booking_type'] ?? ''); ?>';

        function enableEditMode() {
            console.log('🔧 DEBUG: Version 2.0 - URLs sind jetzt relativ ohne BASE_URL');
            // Formular anzeigen
            document.getElementById('reschedule-form-container').style.display = 'block';

            // Scroll zum Formular
            document.getElementById('reschedule-form-container').scrollIntoView({ behavior: 'smooth', block: 'start' });

            // Ausgebuchte Tage laden
            loadFullyBookedDates();
        }

        function cancelEditMode() {
            // Formular verstecken
            document.getElementById('reschedule-form-container').style.display = 'none';

            // Felder zurücksetzen
            document.getElementById('new_booking_date').value = '';
            if (document.getElementById('new_booking_time')) {
                document.getElementById('new_booking_time').value = '';
            }
        }

        // Ausgebuchte Tage laden
        async function loadFullyBookedDates() {
            try {
                const response = await fetch('/api/fully-booked-dates?weeks=8');
                const result = await response.json();

                if (result.success) {
                    fullyBookedDates = result.fully_booked_dates || [];
                    console.log('Fully booked dates loaded:', fullyBookedDates);

                    // Flatpickr initialisieren
                    initFlatpickr();
                }
            } catch (error) {
                console.error('Fehler beim Laden der ausgebuchten Tage:', error);
                // Flatpickr trotzdem initialisieren
                initFlatpickr();
            }
        }

        // Flatpickr initialisieren
        function initFlatpickr() {
            const dateInput = document.getElementById('new_booking_date');

            // Falls bereits initialisiert, destroy und neu initialisieren
            if (flatpickrInstance) {
                flatpickrInstance.destroy();
            }

            // Wochentage die deaktiviert werden sollen
            let disabledDays = [];
            if (bookingType === 'fixed') {
                // Feste Termine: nur Di-Fr (2-5) erlaubt -> Mo, Sa, So deaktivieren
                disabledDays = [0, 1, 6]; // Sonntag, Montag, Samstag
            } else if (bookingType === 'walkin') {
                // Walk-in: Di-Sa (2-6) erlaubt -> So, Mo deaktivieren
                disabledDays = [0, 1]; // Sonntag, Montag
            }

            // Flatpickr initialisieren
            flatpickrInstance = flatpickr(dateInput, {
                locale: 'de',
                dateFormat: 'Y-m-d',
                minDate: 'today',

                // Tage deaktivieren
                disable: [
                    // Wochentage deaktivieren
                    function(date) {
                        return disabledDays.includes(date.getDay());
                    },
                    // Ausgebuchte Tage deaktivieren
                    ...fullyBookedDates
                ],

                // Bei Auswahl
                onChange: function(selectedDates, dateStr, instance) {
                    console.log('Flatpickr: Date selected:', dateStr);

                    // Zeitslots laden für feste Termine
                    if (bookingType === 'fixed') {
                        loadNewTimeSlots(dateStr);
                    }
                }
            });
        }

        // Zeitslots laden für neuen Termin
        async function loadNewTimeSlots(selectedDate) {
            console.log('🔍 DEBUG loadNewTimeSlots called with:', selectedDate, 'type:', typeof selectedDate);
            const timeSlotsContainer = document.getElementById('new-time-slots');

            if (!selectedDate) {
                console.warn('⚠️ selectedDate is empty!');
                timeSlotsContainer.innerHTML = '<p style="text-align: center; color: var(--text-muted);">Bitte wählen Sie zuerst ein Datum aus.</p>';
                return;
            }

            // Lade-Indikator anzeigen
            timeSlotsContainer.innerHTML = '<p style="text-align: center;">Lade verfügbare Zeiten...</p>';

            const url = '/api/available-slots?date=' + selectedDate;
            console.log('🌐 Fetching URL:', url);

            try {
                const response = await fetch(url);
                console.log('📥 Response status:', response.status, 'Content-Type:', response.headers.get('Content-Type'));

                const responseText = await response.text();
                console.log('📄 Response text (first 100 chars):', responseText.substring(0, 100));

                const result = JSON.parse(responseText);

                if (!result.success) {
                    timeSlotsContainer.innerHTML = '<p style="text-align: center; color: var(--color-error);">' + (result.error || 'Fehler beim Laden der Zeitslots') + '</p>';
                    return;
                }

                timeSlotsContainer.innerHTML = '';

                if (result.slots.length === 0) {
                    timeSlotsContainer.innerHTML = '<p style="text-align: center; color: var(--text-muted);">Keine Zeitslots verfügbar für diesen Tag.</p>';
                    return;
                }

                result.slots.forEach(slot => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'time-slot-btn';

                    // Verfügbarkeitsinfo anzeigen
                    const availableCount = slot.max - slot.booked;
                    const availabilityText = availableCount > 0
                        ? ` (${availableCount} von ${slot.max} frei)`
                        : ' (ausgebucht)';

                    button.innerHTML = `<span class="time">${slot.time}</span><small class="availability">${availabilityText}</small>`;

                    if (!slot.available) {
                        button.classList.add('disabled');
                        button.disabled = true;
                        button.title = 'Bereits ausgebucht';
                    } else {
                        button.onclick = () => selectNewTimeSlot(slot.time, button);
                        button.title = `Noch ${availableCount} ${availableCount === 1 ? 'Platz' : 'Plätze'} verfügbar`;
                    }

                    timeSlotsContainer.appendChild(button);
                });

            } catch (error) {
                console.error('Fehler beim Laden der Zeitslots:', error);
                timeSlotsContainer.innerHTML = '<p style="text-align: center; color: var(--color-error);">Fehler beim Laden der verfügbaren Zeiten. Bitte versuchen Sie es später erneut.</p>';
            }
        }

        // Zeitslot auswählen
        function selectNewTimeSlot(time, button) {
            document.querySelectorAll('#new-time-slots .time-slot-btn').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            document.getElementById('new_booking_time').value = time;
        }

        // Formular absenden
        document.getElementById('reschedule-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const token = '<?php echo htmlspecialchars($token ?? '', ENT_QUOTES); ?>';
            const newDate = document.getElementById('new_booking_date').value;
            const newTime = document.getElementById('new_booking_time')?.value || null;

            // Validierung
            if (!newDate) {
                alert('Bitte wählen Sie ein neues Datum aus.');
                return;
            }

            if (bookingType === 'fixed' && !newTime) {
                alert('Bitte wählen Sie eine neue Uhrzeit aus.');
                return;
            }

            console.log('📤 Submitting reschedule with:', { token: token.substring(0, 10) + '...', newDate, newTime });

            try {
                const response = await fetch('/api/booking-reschedule', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        token: token,
                        new_date: newDate,
                        new_time: newTime
                    })
                });

                console.log('📥 Reschedule response status:', response.status, 'Content-Type:', response.headers.get('Content-Type'));

                const responseText = await response.text();
                console.log('📄 Reschedule response text (first 200 chars):', responseText.substring(0, 200));

                const result = JSON.parse(responseText);

                if (result.success) {
                    alert('Ihr Termin wurde erfolgreich geändert. Sie erhalten eine Bestätigungs-Email.');
                    location.reload();
                } else {
                    if (result.error_code === 'SLOT_FULL') {
                        alert(result.error + '\n\nBitte wählen Sie einen anderen Zeitslot.');
                        // Zeitslots neu laden
                        loadNewTimeSlots(newDate);
                    } else {
                        alert('Fehler beim Ändern des Termins: ' + (result.error || 'Unbekannter Fehler'));
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.');
            }
        });
    </script>

    <!-- Flatpickr JS (CSS wird im Header geladen) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/de.js"></script>

    <style>
        .note-box {
            background: var(--bg-secondary);
            padding: var(--space-md);
            border-radius: var(--border-radius-md);
            margin-top: var(--space-sm);
        }

        .alert-info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: var(--space-md);
            border-radius: var(--border-radius-md);
        }

        /* Darkmode Support */
        @media (prefers-color-scheme: dark) {
            :root:not([data-theme="light"]) .note-box {
                background: #2C3E50;
                color: #E8E8E8;
            }

            :root:not([data-theme="light"]) .alert-info {
                background-color: #1a4f5c;
                border-color: #1d5a6a;
                color: #9ed7e6;
            }
        }

        [data-theme="dark"] .note-box {
            background: #2C3E50;
            color: #E8E8E8;
        }

        [data-theme="dark"] .alert-info {
            background-color: #1a4f5c;
            border-color: #1d5a6a;
            color: #9ed7e6;
        }

        /* === Flatpickr Styling === */

        /* LIGHTMODE */
        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange,
        .flatpickr-day.selected.inRange,
        .flatpickr-day.startRange.inRange,
        .flatpickr-day.endRange.inRange,
        .flatpickr-day.selected:focus,
        .flatpickr-day.startRange:focus,
        .flatpickr-day.endRange:focus,
        .flatpickr-day.selected:hover,
        .flatpickr-day.startRange:hover,
        .flatpickr-day.endRange:hover {
            background: #8BC34A !important;
            border-color: #8BC34A !important;
        }

        .flatpickr-day.today {
            border-color: #8BC34A !important;
        }

        .flatpickr-day.today:hover,
        .flatpickr-day.today:focus {
            border-color: #8BC34A !important;
            background: #C5E1A5 !important;
            color: #2C3E50 !important;
        }

        .flatpickr-day:hover:not(.flatpickr-disabled):not(.selected) {
            background: #C5E1A5 !important;
            border-color: #8BC34A !important;
        }

        /* DARKMODE */
        @media (prefers-color-scheme: dark) {
            :root:not([data-theme="light"]) .flatpickr-calendar {
                background: #1A1F26 !important;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.5) !important;
            }

            :root:not([data-theme="light"]) .flatpickr-months {
                background: #2C3E50 !important;
            }

            :root:not([data-theme="light"]) .flatpickr-month,
            :root:not([data-theme="light"]) .flatpickr-current-month .flatpickr-monthDropdown-months,
            :root:not([data-theme="light"]) .flatpickr-current-month input.cur-year {
                color: #E8E8E8 !important;
            }

            :root:not([data-theme="light"]) .flatpickr-weekday {
                color: #B8B8B8 !important;
            }

            :root:not([data-theme="light"]) .flatpickr-day {
                color: #E8E8E8 !important;
            }

            :root:not([data-theme="light"]) .flatpickr-day:hover:not(.flatpickr-disabled):not(.selected) {
                background: #374151 !important;
                border-color: #8BC34A !important;
                color: #E8E8E8 !important;
            }

            :root:not([data-theme="light"]) .flatpickr-day.today {
                border-color: #8BC34A !important;
                color: #E8E8E8 !important;
            }

            :root:not([data-theme="light"]) .flatpickr-day.flatpickr-disabled {
                color: #4B5563 !important;
            }
        }

        [data-theme="dark"] .flatpickr-calendar {
            background: #1A1F26 !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.5) !important;
        }

        [data-theme="dark"] .flatpickr-months {
            background: #2C3E50 !important;
        }

        [data-theme="dark"] .flatpickr-month,
        [data-theme="dark"] .flatpickr-current-month .flatpickr-monthDropdown-months,
        [data-theme="dark"] .flatpickr-current-month input.cur-year {
            color: #E8E8E8 !important;
        }

        [data-theme="dark"] .flatpickr-weekday {
            color: #B8B8B8 !important;
        }

        [data-theme="dark"] .flatpickr-day {
            color: #E8E8E8 !important;
        }

        [data-theme="dark"] .flatpickr-day:hover:not(.flatpickr-disabled):not(.selected) {
            background: #374151 !important;
            border-color: #8BC34A !important;
            color: #E8E8E8 !important;
        }

        [data-theme="dark"] .flatpickr-day.today {
            border-color: #8BC34A !important;
            color: #E8E8E8 !important;
        }

        [data-theme="dark"] .flatpickr-day.flatpickr-disabled {
            color: #4B5563 !important;
        }
    </style>

<?php include __DIR__ . '/../templates/footer.php'; ?>
