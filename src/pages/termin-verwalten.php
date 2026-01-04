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
$extra_css = ['css/booking.css'];

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

                <!-- Änderungs-Hinweis Modal (später implementieren) -->
                <div id="edit-info" style="display: none; margin-top: var(--space-lg);">
                    <div class="card">
                        <div class="alert alert-info">
                            Die Terminänderungs-Funktion wird in Kürze verfügbar sein.
                            Bitte kontaktieren Sie uns in der Zwischenzeit telefonisch oder per E-Mail.
                        </div>
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

        function enableEditMode() {
            // Placeholder für Änderungs-Funktion
            document.getElementById('edit-info').style.display = 'block';
            alert('Die Terminänderungs-Funktion wird in Kürze verfügbar sein.\n\nBitte kontaktieren Sie uns in der Zwischenzeit:\nTelefon: +49 123 456789\nE-Mail: info@pc-wittfoot.de');
        }
    </script>

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
    </style>

<?php include __DIR__ . '/../templates/footer.php'; ?>
