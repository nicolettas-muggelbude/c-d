<?php
/**
 * Terminbuchungsseite
 * PC-Wittfoot UG
 */

$page_title = 'Termin buchen | PC-Wittfoot UG';
$page_description = 'Buchen Sie einen Termin bei PC-Wittfoot. Feste Termine oder spontane Besuche - wir sind für Sie da.';
$current_page = 'termin';
$extra_css = ['css/booking.css'];

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
                            <span class="icon">📅</span>
                            <h3>Fester Termin vor Ort</h3>
                            <p>Buchen Sie einen festen Termin zwischen 11:00-12:00 Uhr (Di-Fr)</p>
                            <ul>
                                <li>✓ Garantierte Bedienung</li>
                                <li>✓ Keine Wartezeit</li>
                                <li>✓ Persönliche Beratung</li>
                            </ul>
                        </div>
                    </label>

                    <label class="booking-type-card">
                        <input type="radio" name="booking_type" value="walkin" required onchange="nextStep(2)">
                        <div class="card-content">
                            <span class="icon">🚶</span>
                            <h3>Ich komme vorbei</h3>
                            <p>Kommen Sie spontan vorbei - ohne feste Uhrzeit</p>
                            <ul>
                                <li>✓ Di-Fr: 14:00-17:00 Uhr</li>
                                <li>✓ Sa: 12:00-16:00 Uhr</li>
                                <li>✓ Wir informieren Sie bei Wartezeiten</li>
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
                        <input type="radio" name="service_type" value="pc-reparatur" required onchange="nextStep(3)">
                        <div class="card-content">
                            <span class="icon">🖥️</span>
                            <h3>PC-Reparatur</h3>
                        </div>
                    </label>

                    <label class="service-card">
                        <input type="radio" name="service_type" value="notebook-reparatur" required onchange="nextStep(3)">
                        <div class="card-content">
                            <span class="icon">💻</span>
                            <h3>Notebook-Reparatur</h3>
                        </div>
                    </label>

                    <label class="service-card">
                        <input type="radio" name="service_type" value="beratung" required onchange="nextStep(3)">
                        <div class="card-content">
                            <span class="icon">💬</span>
                            <h3>Beratung</h3>
                        </div>
                    </label>

                    <label class="service-card">
                        <input type="radio" name="service_type" value="software" required onchange="nextStep(3)">
                        <div class="card-content">
                            <span class="icon">📀</span>
                            <h3>Software-Installation</h3>
                        </div>
                    </label>

                    <label class="service-card">
                        <input type="radio" name="service_type" value="datenrettung" required onchange="nextStep(3)">
                        <div class="card-content">
                            <span class="icon">💾</span>
                            <h3>Datenrettung</h3>
                        </div>
                    </label>

                    <label class="service-card">
                        <input type="radio" name="service_type" value="virus-entfernung" required onchange="nextStep(3)">
                        <div class="card-content">
                            <span class="icon">🦠</span>
                            <h3>Virus-Entfernung</h3>
                        </div>
                    </label>

                    <label class="service-card">
                        <input type="radio" name="service_type" value="upgrade" required onchange="nextStep(3)">
                        <div class="card-content">
                            <span class="icon">⬆️</span>
                            <h3>Hardware-Upgrade</h3>
                        </div>
                    </label>

                    <label class="service-card">
                        <input type="radio" name="service_type" value="sonstiges" required onchange="nextStep(3)">
                        <div class="card-content">
                            <span class="icon">🔧</span>
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
                    <input type="date"
                           id="booking_date"
                           name="booking_date"
                           class="form-control"
                           required
                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                    <small class="form-help">Termine sind möglich: Dienstag bis Freitag</small>
                </div>

                <!-- Zeit-Auswahl (nur für feste Termine) -->
                <div class="form-group" id="time-selection" style="display: none;">
                    <label>Uhrzeit wählen</label>
                    <div class="time-slots" id="time-slots">
                        <!-- Wird dynamisch befüllt -->
                    </div>
                    <input type="hidden" name="booking_time" id="booking_time">
                </div>

                <!-- Info für Walk-ins -->
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
                               required>
                    </div>

                    <div class="form-group">
                        <label for="customer_lastname">Nachname *</label>
                        <input type="text"
                               id="customer_lastname"
                               name="customer_lastname"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="customer_company">Firma (optional)</label>
                    <input type="text"
                           id="customer_company"
                           name="customer_company"
                           placeholder="Firmenname">
                </div>

                <div class="form-group">
                    <label for="customer_email">E-Mail-Adresse *</label>
                    <input type="email"
                           id="customer_email"
                           name="customer_email"
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
                           placeholder="030 12345678">
                </div>

                <!-- Adresse -->
                <div class="form-row">
                    <div class="form-group" style="flex: 3;">
                        <label for="customer_street">Straße *</label>
                        <input type="text"
                               id="customer_street"
                               name="customer_street"
                               required
                               placeholder="Musterstraße">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="customer_house_number">Nr. *</label>
                        <input type="text"
                               id="customer_house_number"
                               name="customer_house_number"
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
                               required
                               placeholder="12345">
                    </div>
                    <div class="form-group" style="flex: 2;">
                        <label for="customer_city">Ort *</label>
                        <input type="text"
                               id="customer_city"
                               name="customer_city"
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
            <div class="success-icon">✓</div>
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

<script>
// Globale Variablen
let currentStep = 1;
let formData = {
    booking_type: '',
    service_type: '',
    booking_date: '',
    booking_time: ''
};

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
    if (step === 3) {
        setupStep3();
    } else if (step === 4) {
        updateSummary();
    }

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function prevStep(step) {
    nextStep(step);
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

    if (isFixed) {
        loadTimeSlots();
    }
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
            button.textContent = slot.time;

            if (!slot.available) {
                button.classList.add('disabled');
                button.disabled = true;
                button.title = 'Bereits gebucht';
            } else {
                button.onclick = () => selectTimeSlot(slot.time, button);
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

// Formular absenden
document.getElementById('booking-form').addEventListener('submit', async function(e) {
    e.preventDefault();

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
            // Formular verstecken, Erfolg anzeigen
            document.getElementById('booking-form').style.display = 'none';
            document.querySelector('.booking-progress').style.display = 'none';
            document.getElementById('booking-success').style.display = 'block';
            document.getElementById('booking-number').textContent = '#' + result.booking_id;

            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            alert('Fehler bei der Buchung: ' + (result.error || 'Unbekannter Fehler'));
        }
    } catch (error) {
        alert('Fehler bei der Buchung. Bitte versuchen Sie es später erneut.');
        console.error(error);
    }
});

// Datum-Validierung: Di-Fr für fixed, Di-Sa für walkin
document.getElementById('booking_date').addEventListener('change', function() {
    const date = new Date(this.value + 'T00:00:00');
    const day = date.getDay();
    const bookingType = formData.booking_type;

    // 0 = Sonntag, 1 = Montag, 2 = Dienstag, ..., 6 = Samstag
    if (bookingType === 'fixed') {
        // Feste Termine: nur Di-Fr (2-5)
        if (day === 0 || day === 6 || day === 1) {
            alert('Feste Termine sind nur Dienstag bis Freitag möglich.');
            this.value = '';
            return;
        }
    } else if (bookingType === 'walkin') {
        // Walk-in: Di-Sa (2-6)
        if (day === 0 || day === 1) {
            alert('Walk-in Termine sind nur Dienstag bis Samstag möglich.');
            this.value = '';
            return;
        }
    }

    // Zeitslots neu laden für das gewählte Datum
    formData.booking_date = this.value;
    if (currentStep === 3) {
        loadTimeSlots();
    }
});
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
