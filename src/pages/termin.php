<?php
/**
 * Terminbuchungsseite
 * PC-Wittfoot UG
 */

$page_title = 'Termin buchen | PC-Wittfoot UG';
$page_description = 'Buchen Sie einen Termin bei PC-Wittfoot. Feste Termine oder spontane Besuche - wir sind für Sie da.';
$current_page = 'termin';
$extra_css = ['css/booking.css', 'css/flatpickr.min.css'];

// Kein externes CSS mehr nötig

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <h1>Termin buchen</h1>
        <p class="lead">Wählen Sie, wie Sie uns besuchen möchten: Mit festem Termin oder spontan</p>

        <!-- Fortschrittsanzeige -->
        <div class="booking-progress">
            <div class="progress-step active" data-step="1">
                <span class="step-number">1</span>
                <span class="step-label">Terminart</span>
            </div>
            <div class="progress-step" data-step="2">
                <span class="step-number">2</span>
                <span class="step-label">Dienstleistung</span>
            </div>
            <div class="progress-step" data-step="3">
                <span class="step-number">3</span>
                <span class="step-label">Datum & Zeit</span>
            </div>
            <div class="progress-step" data-step="4">
                <span class="step-number">4</span>
                <span class="step-label">Ihre Daten</span>
            </div>
        </div>

        <!-- Buchungsformular -->
        <form id="booking-form" class="booking-form">

            <!-- Schritt 1: Terminart wählen -->
            <div class="booking-step active" data-step="1">
                <h2>Wie möchten Sie uns besuchen?</h2>

                <div class="booking-type-options">
                    <label class="booking-type-card">
                        <input type="radio" name="booking_type" value="fixed" required onchange="nextStep(2)">
                        <div class="card-content">
                            <span class="icon" aria-hidden="true">📅</span>
                            <h3>Fester Termin</h3>
                            <p>Buchen Sie einen festen Termin zwischen 11:00-12:00 Uhr (Di-Fr)</p>
                            <ul>
                                <li><span aria-hidden="true">✓</span> Garantierte Bedienung</li>
                                <li><span aria-hidden="true">✓</span> Keine Wartezeit</li>
                                <li><span aria-hidden="true">✓</span> Persönliche Beratung</li>
                            </ul>
                        </div>
                    </label>

                    <label class="booking-type-card">
                        <input type="radio" name="booking_type" value="walkin" required onchange="nextStep(2)">
                        <div class="card-content">
                            <span class="icon" aria-hidden="true">🚶</span>
                            <h3>Ich komme vorbei</h3>
                            <p>Kommen Sie spontan vorbei - ohne feste Uhrzeit</p>
                            <ul>
                                <li><span aria-hidden="true">✓</span> Di-Fr: 14:00-17:00 Uhr</li>
                                <li><span aria-hidden="true">✓</span> Sa: 12:00-16:00 Uhr</li>
                                <li><span aria-hidden="true">✓</span> Wir informieren Sie bei Wartezeiten</li>
                            </ul>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Schritt 2: Dienstleistung wählen -->
            <div class="booking-step" data-step="2">
                <h2>Worum geht es?</h2>

                <div class="service-options">
                    <label class="service-card">
                        <input type="radio" name="service_type" value="beratung" required onchange="nextStep(3)">
                        <div class="card-content">
                            <span class="icon" aria-hidden="true">💬</span>
                            <h3>Beratung</h3>
                        </div>
                    </label>

                    <label class="service-card">
                        <input type="radio" name="service_type" value="verkauf" required onchange="nextStep(3)">
                        <div class="card-content">
                            <span class="icon" aria-hidden="true">🛒</span>
                            <h3>Verkauf</h3>
                        </div>
                    </label>

                    <label class="service-card" data-service-onsite-only="true">
                        <input type="radio" name="service_type" value="fernwartung" required onchange="nextStep(3)">
                        <div class="card-content">
                            <span class="icon" aria-hidden="true">💻</span>
                            <h3>Fernwartung</h3>
                        </div>
                    </label>

                    <label class="service-card" data-service-onsite-only="true">
                        <input type="radio" name="service_type" value="hausbesuch" required onchange="nextStep(3)">
                        <div class="card-content">
                            <span class="icon" aria-hidden="true">🏠</span>
                            <h3>Hausbesuch</h3>
                        </div>
                    </label>

                    <label class="service-card">
                        <input type="radio" name="service_type" value="installation" required onchange="nextStep(3)">
                        <div class="card-content">
                            <span class="icon" aria-hidden="true">⚙️</span>
                            <h3>Installation</h3>
                        </div>
                    </label>

                    <label class="service-card">
                        <input type="radio" name="service_type" value="diagnose" required onchange="nextStep(3)">
                        <div class="card-content">
                            <span class="icon" aria-hidden="true">🔍</span>
                            <h3>Diagnose</h3>
                        </div>
                    </label>

                    <label class="service-card">
                        <input type="radio" name="service_type" value="reparatur" required onchange="nextStep(3)">
                        <div class="card-content">
                            <span class="icon" aria-hidden="true">🛠️</span>
                            <h3>Reparatur</h3>
                        </div>
                    </label>

                    <label class="service-card">
                        <input type="radio" name="service_type" value="sonstiges" required onchange="nextStep(3)">
                        <div class="card-content">
                            <span class="icon" aria-hidden="true">🔧</span>
                            <h3>Sonstiges</h3>
                        </div>
                    </label>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-outline" onclick="prevStep(1)">Zurück</button>
                </div>
            </div>

            <!-- Schritt 3: Datum & Zeit wählen -->
            <div class="booking-step" data-step="3">
                <h2 id="step3-title">Wann möchten Sie kommen?</h2>

                <div class="form-group">
                    <label for="booking_date">Datum wählen</label>
                    <div style="position: relative;">
                        <input type="text"
                               id="booking_date"
                               name="booking_date"
                               class="form-control"
                               placeholder="📅 Klicken Sie hier, um einen Termin zu wählen"
                               required
                               readonly
                               style="cursor: pointer; padding-right: 2.5rem;">
                        <span style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 1.25rem;" aria-hidden="true">📅</span>
                    </div>
                    <small class="form-help" id="date-help">Ausgebuchte Tage sind im Kalender ausgegraut</small>
                    <div id="date-warning" class="alert alert-error" style="display: none; margin-top: 0.5rem;"></div>
                </div>

                <!-- Zeit-Auswahl (nur für feste Termine) -->
                <div class="form-group" id="time-selection" style="display: none;">
                    <label>Uhrzeit wählen</label>
                    <div class="time-slots" id="time-slots">
                        <!-- Wird dynamisch befüllt -->
                    </div>
                    <input type="hidden" name="booking_time" id="booking_time">
                </div>

                <!-- Info für "Ich komme vorbei" -->
                <div class="alert alert-info" id="walkin-info" style="display: none;">
                    <strong>Hinweis:</strong> Sie können zwischen 14:00-17:00 Uhr ohne feste Uhrzeit vorbeikommen.
                    Wir benachrichtigen Sie per Email, falls mit längeren Wartezeiten zu rechnen ist.
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-outline" onclick="prevStep(2)">Zurück</button>
                    <button type="button" class="btn btn-primary" onclick="nextStep(4)">Weiter</button>
                </div>
            </div>

            <!-- Schritt 4: Kontaktdaten -->
            <div class="booking-step" data-step="4">
                <h2>Ihre Kontaktdaten</h2>

                <div class="form-row">
                    <div class="form-group">
                        <label for="customer_firstname">Vorname *</label>
                        <input type="text"
                               id="customer_firstname"
                               name="customer_firstname"
                               class="form-control"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="customer_lastname">Nachname *</label>
                        <input type="text"
                               id="customer_lastname"
                               name="customer_lastname"
                               class="form-control"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="customer_company">Firma (optional)</label>
                    <input type="text"
                           id="customer_company"
                           name="customer_company"
                           class="form-control"
                           placeholder="Firmenname">
                </div>

                <div class="form-group">
                    <label for="customer_email">E-Mail-Adresse *</label>
                    <input type="email"
                           id="customer_email"
                           name="customer_email"
                           class="form-control"
                           required>
                </div>

                <!-- Telefonnummern -->
                <div class="form-group">
                    <label>Mobilnummer *</label>
                    <div class="phone-input-group">
                        <select name="customer_phone_country" id="customer_phone_country" class="phone-country" required>
                            <option value="+49" selected>+49</option>
                            <option value="+43">+43</option>
                            <option value="+41">+41</option>
                            <option value="+1">+1</option>
                            <option value="+44">+44</option>
                        </select>
                        <input type="tel"
                               id="customer_phone_mobile"
                               name="customer_phone_mobile"
                               class="phone-number"
                               required
                               placeholder="170 1234567">
                    </div>
                </div>

                <div class="form-group">
                    <label for="customer_phone_landline">Festnetznummer (optional)</label>
                    <input type="tel"
                           id="customer_phone_landline"
                           name="customer_phone_landline"
                           class="form-control"
                           placeholder="030 12345678">
                </div>

                <!-- Adresse -->
                <div class="form-row">
                    <div class="form-group" style="flex: 3;">
                        <label for="customer_street">Straße *</label>
                        <input type="text"
                               id="customer_street"
                               name="customer_street"
                               class="form-control"
                               required
                               placeholder="Musterstraße">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="customer_house_number">Nr. *</label>
                        <input type="text"
                               id="customer_house_number"
                               name="customer_house_number"
                               class="form-control"
                               required
                               placeholder="123">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label for="customer_postal_code">PLZ *</label>
                        <input type="text"
                               id="customer_postal_code"
                               name="customer_postal_code"
                               class="form-control"
                               required
                               placeholder="12345">
                    </div>
                    <div class="form-group" style="flex: 2;">
                        <label for="customer_city">Ort *</label>
                        <input type="text"
                               id="customer_city"
                               name="customer_city"
                               class="form-control"
                               required
                               placeholder="Berlin">
                    </div>
                </div>

                <div class="form-group">
                    <label for="customer_notes">Bemerkungen (optional)</label>
                    <textarea id="customer_notes"
                              name="customer_notes"
                              class="form-control"
                              rows="4"
                              placeholder="Beschreiben Sie kurz Ihr Anliegen..."></textarea>
                </div>

                <!-- Zusammenfassung -->
                <div class="booking-summary card">
                    <h3>Ihre Buchung im Überblick</h3>
                    <dl>
                        <dt>Terminart:</dt>
                        <dd id="summary-type">-</dd>

                        <dt>Dienstleistung:</dt>
                        <dd id="summary-service">-</dd>

                        <dt>Datum:</dt>
                        <dd id="summary-date">-</dd>

                        <dt id="summary-time-label" style="display: none;">Uhrzeit:</dt>
                        <dd id="summary-time" style="display: none;">-</dd>
                    </dl>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-outline" onclick="prevStep(3)">Zurück</button>
                    <button type="submit" class="btn btn-primary btn-lg">Verbindlich buchen</button>
                </div>
            </div>

        </form>

        <!-- Erfolgs-Meldung (versteckt) -->
        <div id="booking-success" class="booking-success" style="display: none;">
            <div class="success-icon" aria-hidden="true">✓</div>
            <h2>Termin erfolgreich gebucht!</h2>
            <p>Vielen Dank für Ihre Buchung. Wir haben Ihnen eine Bestätigung per Email gesendet.</p>
            <div class="card">
                <h3>Ihre Buchungsnummer</h3>
                <p class="booking-number" id="booking-number">-</p>
            </div>
            <a href="<?= BASE_URL ?>" class="btn btn-primary">Zur Startseite</a>
        </div>

    </div>
</section>

<!-- Flatpickr JS (CSS wird im Header geladen) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/de.js"></script>

<!-- Flatpickr Custom Styling (PC-Wittfoot Grün + Darkmode) -->
<style>
/* === LIGHTMODE === */

/* Ausgewähltes Datum - PC-Wittfoot Grün */
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
.flatpickr-day.endRange:hover,
.flatpickr-day.selected.prevMonthDay,
.flatpickr-day.startRange.prevMonthDay,
.flatpickr-day.endRange.prevMonthDay,
.flatpickr-day.selected.nextMonthDay,
.flatpickr-day.startRange.nextMonthDay,
.flatpickr-day.endRange.nextMonthDay {
    background: #8BC34A !important;
    border-color: #8BC34A !important;
}

/* Heutiger Tag - helleres Grün */
.flatpickr-day.today {
    border-color: #8BC34A !important;
}

.flatpickr-day.today:hover,
.flatpickr-day.today:focus {
    border-color: #8BC34A !important;
    background: #C5E1A5 !important;
    color: #2C3E50 !important;
}

/* Hover-Effekt für verfügbare Tage */
.flatpickr-day:hover:not(.flatpickr-disabled):not(.selected) {
    background: #C5E1A5 !important;
    border-color: #8BC34A !important;
}

/* === DARKMODE === */

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

    :root:not([data-theme="light"]) .flatpickr-day.flatpickr-disabled,
    :root:not([data-theme="light"]) .flatpickr-day.prevMonthDay:not(.selected),
    :root:not([data-theme="light"]) .flatpickr-day.nextMonthDay:not(.selected) {
        color: #4B5563 !important;
    }

    :root:not([data-theme="light"]) .flatpickr-months .flatpickr-prev-month:hover svg,
    :root:not([data-theme="light"]) .flatpickr-months .flatpickr-next-month:hover svg {
        fill: #8BC34A !important;
    }
}

/* Manueller Darkmode-Toggle */
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

[data-theme="dark"] .flatpickr-day.flatpickr-disabled,
[data-theme="dark"] .flatpickr-day.prevMonthDay:not(.selected),
[data-theme="dark"] .flatpickr-day.nextMonthDay:not(.selected) {
    color: #4B5563 !important;
}

[data-theme="dark"] .flatpickr-months .flatpickr-prev-month:hover svg,
[data-theme="dark"] .flatpickr-months .flatpickr-next-month:hover svg {
    fill: #8BC34A !important;
}
</style>

<script>
console.log('=== Booking Script Started ===');

// Globale Variablen
let currentStep = 1;
let fullyBookedDates = [];
let formData = {
    booking_type: '',
    service_type: '',
    booking_date: '',
    booking_time: ''
};

console.log('Variables initialized');

// Ausgebuchte Tage laden
async function loadFullyBookedDates() {
    console.log('loadFullyBookedDates() called');
    try {
        const response = await fetch('<?= BASE_URL ?>/api/fully-booked-dates?weeks=8');
        console.log('API response:', response);
        const result = await response.json();
        console.log('API result:', result);

        if (result.success) {
            fullyBookedDates = result.fully_booked_dates || [];
            console.log('Fully booked dates loaded:', fullyBookedDates);
        }
    } catch (error) {
        console.error('Fehler beim Laden der ausgebuchten Tage:', error);
    }
}

// Beim Laden der Seite ausgebuchte Tage laden
console.log('Registering DOMContentLoaded listener...');
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded fired!');
    loadFullyBookedDates();
});

// Schritt-Navigation
function nextStep(step) {
    // Validierung des aktuellen Schritts
    if (!validateStep(currentStep)) {
        return;
    }

    // Formular-Daten speichern
    saveStepData(currentStep);

    // Schritt wechseln
    document.querySelectorAll('.booking-step').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.progress-step').forEach(el => el.classList.remove('active', 'completed'));

    document.querySelector(`.booking-step[data-step="${step}"]`).classList.add('active');
    for (let i = 1; i < step; i++) {
        document.querySelector(`.progress-step[data-step="${i}"]`).classList.add('completed');
    }
    document.querySelector(`.progress-step[data-step="${step}"]`).classList.add('active');

    currentStep = step;

    // Spezielle Aktionen pro Schritt
    if (step === 2) {
        updateServiceVisibility();
    } else if (step === 3) {
        setupStep3();
    } else if (step === 4) {
        updateSummary();
    }

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function prevStep(step) {
    // Beim Zurückgehen KEINE Validierung - Nutzer soll immer zurück können

    // Radio-Buttons im Zielschritt deaktivieren, damit sie erneut klickbar sind
    if (step === 1) {
        // Terminart-Auswahl zurücksetzen
        document.querySelectorAll('input[name="booking_type"]').forEach(radio => {
            radio.checked = false;
        });
    } else if (step === 2) {
        // Dienstleistungs-Auswahl zurücksetzen
        document.querySelectorAll('input[name="service_type"]').forEach(radio => {
            radio.checked = false;
        });
    }

    // Schritt wechseln
    document.querySelectorAll('.booking-step').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.progress-step').forEach(el => el.classList.remove('active', 'completed'));

    document.querySelector(`.booking-step[data-step="${step}"]`).classList.add('active');
    for (let i = 1; i < step; i++) {
        document.querySelector(`.progress-step[data-step="${i}"]`).classList.add('completed');
    }
    document.querySelector(`.progress-step[data-step="${step}"]`).classList.add('active');

    currentStep = step;

    // Spezielle Aktionen pro Schritt
    if (step === 2) {
        updateServiceVisibility();
    } else if (step === 3) {
        setupStep3();
    }

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Schritt-Validierung
function validateStep(step) {
    const stepEl = document.querySelector(`.booking-step[data-step="${step}"]`);
    const inputs = stepEl.querySelectorAll('input[required], select[required]');

    for (let input of inputs) {
        if (!input.value || (input.type === 'radio' && !stepEl.querySelector(`input[name="${input.name}"]:checked`))) {
            alert('Bitte füllen Sie alle erforderlichen Felder aus.');
            return false;
        }
    }

    // Zusätzliche Validierung für Schritt 3: Zeit muss bei festem Termin ausgewählt sein
    if (step === 3 && formData.booking_type === 'fixed') {
        const bookingTime = document.getElementById('booking_time').value;
        if (!bookingTime) {
            alert('Bitte wählen Sie eine Uhrzeit aus.');
            return false;
        }
    }

    return true;
}

// Daten des aktuellen Schritts speichern
function saveStepData(step) {
    if (step === 1) {
        formData.booking_type = document.querySelector('input[name="booking_type"]:checked').value;
    } else if (step === 2) {
        formData.service_type = document.querySelector('[name="service_type"]').value;
    } else if (step === 3) {
        formData.booking_date = document.querySelector('[name="booking_date"]').value;
        formData.booking_time = document.querySelector('[name="booking_time"]').value || null;
    }
}

// Schritt 3 Setup (Datum/Zeit)
function setupStep3() {
    const isFixed = formData.booking_type === 'fixed';

    document.getElementById('time-selection').style.display = isFixed ? 'block' : 'none';
    document.getElementById('walkin-info').style.display = isFixed ? 'none' : 'block';
    document.getElementById('step3-title').textContent = isFixed
        ? 'Wann möchten Sie kommen?'
        : 'An welchem Tag möchten Sie vorbeikommen?';

    // Datum-Validierung hinzufügen
    setupDateValidation();

    if (isFixed && formData.booking_date) {
        loadTimeSlots();
    }
}

// Flatpickr Datepicker initialisieren
let flatpickrInstance = null;

function setupDateValidation() {
    console.log('setupDateValidation() called - initializing Flatpickr');

    const dateInput = document.getElementById('booking_date');
    const bookingType = formData.booking_type;

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
        // Ich komme vorbei: Di-Sa (2-6) erlaubt -> So, Mo deaktivieren
        disabledDays = [0, 1]; // Sonntag, Montag
    }

    console.log('Disabled weekdays:', disabledDays);
    console.log('Fully booked dates:', fullyBookedDates);

    // Flatpickr initialisieren
    flatpickrInstance = flatpickr(dateInput, {
        locale: 'de',
        dateFormat: 'Y-m-d', // Internes Format für API
        altInput: true, // Separates Anzeigefeld für Benutzer
        altFormat: 'd.m.Y', // Deutsches Format: TT.MM.JJJJ
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
            formData.booking_date = dateStr;

            // Zeitslots laden für feste Termine
            if (bookingType === 'fixed') {
                loadTimeSlots();
            }
        },

        onReady: function(selectedDates, dateStr, instance) {
            console.log('Flatpickr ready!', instance);
        }
    });

    console.log('Flatpickr instance created:', flatpickrInstance);
}

// Zeitslots laden (dynamisch von API)
async function loadTimeSlots() {
    const timeSlotsContainer = document.getElementById('time-slots');
    const selectedDate = formData.booking_date;

    if (!selectedDate) {
        timeSlotsContainer.innerHTML = '<p style="text-align: center; color: var(--text-muted);">Bitte wählen Sie zuerst ein Datum aus.</p>';
        return;
    }

    // Lade-Indikator anzeigen
    timeSlotsContainer.innerHTML = '<p style="text-align: center;">Lade verfügbare Zeiten...</p>';

    try {
        const response = await fetch('<?= BASE_URL ?>/api/available-slots?date=' + selectedDate);
        const result = await response.json();

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
                button.onclick = () => selectTimeSlot(slot.time, button);
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
function selectTimeSlot(time, button) {
    document.querySelectorAll('.time-slot-btn').forEach(btn => btn.classList.remove('active'));
    button.classList.add('active');
    document.getElementById('booking_time').value = time;
}

// Zusammenfassung aktualisieren
function updateSummary() {
    const serviceLabels = {
        'pc-reparatur': 'PC-Reparatur',
        'notebook-reparatur': 'Notebook-Reparatur',
        'beratung': 'Beratung',
        'software': 'Software-Installation',
        'datenrettung': 'Datenrettung',
        'virus-entfernung': 'Virus-Entfernung',
        'upgrade': 'Hardware-Upgrade',
        'sonstiges': 'Sonstiges'
    };

    document.getElementById('summary-type').textContent = formData.booking_type === 'fixed'
        ? 'Fester Termin'
        : 'Ich komme vorbei';

    document.getElementById('summary-service').textContent = serviceLabels[formData.service_type] || formData.service_type;

    const date = new Date(formData.booking_date + 'T00:00:00');
    document.getElementById('summary-date').textContent = date.toLocaleDateString('de-DE', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    if (formData.booking_type === 'fixed' && formData.booking_time) {
        document.getElementById('summary-time-label').style.display = 'block';
        document.getElementById('summary-time').style.display = 'block';
        document.getElementById('summary-time').textContent = formData.booking_time + ' Uhr';
    } else {
        document.getElementById('summary-time-label').style.display = 'none';
        document.getElementById('summary-time').style.display = 'none';
    }
}

// Service-Sichtbarkeit basierend auf Buchungstyp aktualisieren
function updateServiceVisibility() {
    const bookingType = formData.booking_type;
    const onsiteOnlyCards = document.querySelectorAll('.service-card[data-service-onsite-only="true"]');

    onsiteOnlyCards.forEach(card => {
        if (bookingType === 'walkin') {
            // Bei Walk-in: Fernwartung und Hausbesuch ausblenden
            card.style.display = 'none';

            // Falls dieser Service ausgewählt war, Auswahl löschen
            const radio = card.querySelector('input[type="radio"]');
            if (radio && radio.checked) {
                radio.checked = false;
                formData.service_type = '';
            }
        } else {
            // Bei festem Termin: alle Services anzeigen
            card.style.display = '';
        }
    });
}

// === sessionStorage für Kontaktdaten ===

// Felder die gespeichert werden sollen
const storageFields = [
    'customer_firstname',
    'customer_lastname',
    'customer_company',
    'customer_email',
    'customer_phone_country',
    'customer_phone_mobile',
    'customer_phone_landline',
    'customer_street',
    'customer_house_number',
    'customer_postal_code',
    'customer_city',
    'customer_notes'
];

// Formulardaten in sessionStorage speichern
function saveFormToStorage() {
    const formData = {};
    storageFields.forEach(fieldId => {
        const element = document.getElementById(fieldId);
        if (element) {
            formData[fieldId] = element.value;
        }
    });
    sessionStorage.setItem('booking_customer_data', JSON.stringify(formData));
    console.log('Kontaktdaten in sessionStorage gespeichert');
}

// Formulardaten aus sessionStorage wiederherstellen
function restoreFormFromStorage() {
    const saved = sessionStorage.getItem('booking_customer_data');
    if (!saved) return;

    try {
        const formData = JSON.parse(saved);
        let restoredCount = 0;

        storageFields.forEach(fieldId => {
            const element = document.getElementById(fieldId);
            if (element && formData[fieldId]) {
                element.value = formData[fieldId];
                restoredCount++;
            }
        });

        if (restoredCount > 0) {
            console.log(`${restoredCount} Kontaktdaten aus sessionStorage wiederhergestellt`);
        }
    } catch (error) {
        console.error('Fehler beim Wiederherstellen der Formulardaten:', error);
    }
}

// Formulardaten aus sessionStorage löschen
function clearFormStorage() {
    sessionStorage.removeItem('booking_customer_data');
    console.log('Kontaktdaten aus sessionStorage gelöscht');
}

// Event-Listener auf alle Formularfelder setzen
document.addEventListener('DOMContentLoaded', function() {
    // Daten beim Laden der Seite wiederherstellen
    restoreFormFromStorage();

    // Event-Listener für Auto-Save bei Änderungen
    storageFields.forEach(fieldId => {
        const element = document.getElementById(fieldId);
        if (element) {
            // Bei Texteingaben: input-Event (live während der Eingabe)
            // Bei Selects: change-Event
            const eventType = element.tagName === 'SELECT' ? 'change' : 'input';
            element.addEventListener(eventType, saveFormToStorage);
        }
    });
});

// Formular absenden
document.getElementById('booking-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    // Button deaktivieren und Loading-State zeigen
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Wird gebucht...';

    const formDataObj = new FormData(this);
    const data = Object.fromEntries(formDataObj);

    // Führende 0 bei Mobilnummer entfernen (z.B. 0170 → 170)
    if (data.customer_phone_mobile) {
        data.customer_phone_mobile = data.customer_phone_mobile.trim().replace(/^0+/, '');
    }

    // Führende 0 bei Festnetz entfernen (falls vorhanden)
    if (data.customer_phone_landline) {
        data.customer_phone_landline = data.customer_phone_landline.trim().replace(/^0+/, '');
    }

    try {
        const response = await fetch('<?= BASE_URL ?>/api/booking', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            // Gespeicherte Daten aus sessionStorage löschen
            clearFormStorage();

            // Formular verstecken, Erfolg anzeigen
            document.getElementById('booking-form').style.display = 'none';
            document.querySelector('.booking-progress').style.display = 'none';
            document.getElementById('booking-success').style.display = 'block';
            document.getElementById('booking-number').textContent = '#' + result.booking_id;

            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            // Button wieder aktivieren bei Fehler
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;

            // Spezielle Behandlung für ausgebuchte Slots
            if (result.error_code === 'SLOT_FULL') {
                alert(result.error + '\n\nWir leiten Sie zurück zur Zeitauswahl.');
                // Zurück zu Schritt 3 für neue Zeitauswahl
                nextStep(3);
            } else {
                alert('Fehler bei der Buchung: ' + (result.error || 'Unbekannter Fehler'));
            }
        }
    } catch (error) {
        // Button wieder aktivieren bei Fehler
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;

        alert('Fehler bei der Buchung. Bitte versuchen Sie es später erneut.');
        console.error(error);
    }
});

</script>

<style>
/* Datum-Validierung */
input.invalid {
    border-color: #f44336 !important;
    background-color: rgba(244, 67, 54, 0.05);
}

.alert-error {
    background-color: #ffebee;
    border: 1px solid #f44336;
    color: #c62828;
    padding: 0.75rem;
    border-radius: 4px;
    font-size: 0.9rem;
}

@media (prefers-color-scheme: dark) {
    .alert-error {
        background-color: rgba(244, 67, 54, 0.15);
        border-color: #f44336;
        color: #ff8a80;
    }
}

html[data-theme="dark"] .alert-error {
    background-color: rgba(244, 67, 54, 0.15);
    border-color: #f44336;
    color: #ff8a80;
}
</style>

<?php include __DIR__ . '/../templates/footer.php'; ?>
