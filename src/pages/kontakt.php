<?php
/**
 * Kontaktformular
 */

$db = Database::getInstance();

// Formular verarbeiten
$success = false;
$error = null;
$form_data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF-Schutz
    if (!csrf_verify($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF-Token ungültig. Bitte versuchen Sie es erneut.';
    } else {
        // Daten validieren
        $name = sanitize($_POST['name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $subject = sanitize($_POST['subject'] ?? '');
        $message = sanitize($_POST['message'] ?? '');

        // Validierung
        $errors = [];

        if (empty($name)) {
            $errors[] = 'Bitte geben Sie Ihren Namen an.';
        }

        if (empty($email) || !is_valid_email($email)) {
            $errors[] = 'Bitte geben Sie eine gültige E-Mail-Adresse an.';
        }

        if (empty($subject)) {
            $errors[] = 'Bitte geben Sie einen Betreff an.';
        }

        if (empty($message)) {
            $errors[] = 'Bitte geben Sie eine Nachricht ein.';
        }

        if (empty($errors)) {
            // In Datenbank speichern
            $inserted = $db->insert("
                INSERT INTO contact_submissions
                (name, email, phone, subject, message, ip_address, user_agent)
                VALUES (:name, :email, :phone, :subject, :message, :ip, :user_agent)
            ", [
                ':name' => $name,
                ':email' => $email,
                ':phone' => $phone,
                ':subject' => $subject,
                ':message' => $message,
                ':ip' => $_SERVER['REMOTE_ADDR'] ?? '',
                ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);

            if ($inserted) {
                // E-Mails versenden
                $emailService = new EmailService();
                $emailResults = $emailService->sendContactFormEmails([
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'subject' => $subject,
                    'message' => $message
                ]);

                // Erfolg, auch wenn E-Mails fehlschlagen (Daten sind gespeichert)
                $success = true;

                // Optional: Warnung anzeigen, wenn E-Mails fehlgeschlagen sind
                if (!$emailResults['customer'] || !$emailResults['admin']) {
                    error_log("Contact form: Email sending partially failed - Customer: " . ($emailResults['customer'] ? 'OK' : 'FAILED') . ", Admin: " . ($emailResults['admin'] ? 'OK' : 'FAILED'));
                }
            } else {
                $error = 'Fehler beim Speichern. Bitte versuchen Sie es später erneut.';
            }
        } else {
            $error = implode('<br>', $errors);
            $form_data = $_POST; // Formulardaten behalten
        }
    }
}

$page_title = 'Kontakt | PC-Wittfoot UG';
$page_description = 'Kontaktieren Sie uns per Telefon, E-Mail oder Formular.';
$current_page = 'kontakt';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <h1>Kontakt</h1>
        <p class="lead">
            Sie haben Fragen oder möchten einen Termin vereinbaren? Wir sind für Sie da!
        </p>

        <?php if ($success): ?>
            <!-- Erfolgsmeldung -->
            <div class="alert alert-success" role="alert">
                <h2><span aria-hidden="true">✓</span> Vielen Dank für Ihre Nachricht!</h2>
                <p>Wir haben Ihre Anfrage erhalten und werden uns schnellstmöglich bei Ihnen melden.</p>
                <p class="mt-md">
                    <a href="<?= BASE_URL ?>" class="btn btn-primary">Zur Startseite</a>
                    <a href="<?= BASE_URL ?>/shop" class="btn btn-outline">Zum Shop</a>
                </p>
            </div>

        <?php else: ?>
            <div class="contact-layout">
                <!-- Kontaktinformationen -->
                <div class="contact-info">
                    <div class="card">
                        <h2>Kontaktdaten</h2>

                        <div class="contact-item">
                            <h3><span aria-hidden="true">📍</span> Adresse</h3>
                            <p>
                                PC-Wittfoot UG<br>
                                Melkbrink 61<br>
                                26121 Oldenburg
                            </p>
                        </div>

                        <div class="contact-item">
                            <h3><span aria-hidden="true">📞</span> Telefon</h3>
                            <p>
                                <a href="tel:+4944140576020">+49 (0) 441 40576020</a><br>
                                <span class="text-muted">Di-Fr: 14:00 - 17:00 Uhr<br>Sa: 12:00 - 16:00 Uhr</span>
                            </p>
                        </div>

                        <div class="contact-item">
                            <h3><span aria-hidden="true">✉️</span> E-Mail</h3>
                            <p>
                                <a href="mailto:info@pc-wittfoot.de">info@pc-wittfoot.de</a>
                            </p>
                        </div>

                        <div class="contact-item">
                            <h3><span aria-hidden="true">💬</span> Messenger</h3>
                            <p>
                                <a href="https://wa.me/4944140576020" target="_blank" rel="noopener">WhatsApp Business</a><br>
                                Telegram • Signal
                            </p>
                        </div>

                        <div class="contact-item">
                            <h3><span aria-hidden="true">🕐</span> Öffnungszeiten</h3>
                            <p>
                                <strong>Dienstag - Freitag:</strong> 14:00 - 17:00 Uhr<br>
                                <strong>Samstag:</strong> 12:00 - 16:00 Uhr<br>
                                <strong>Montag:</strong> Geschlossen
                            </p>
                            <p class="text-muted">
                                Termine außerhalb der Öffnungszeiten nach Vereinbarung möglich.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Kontaktformular -->
                <div class="contact-form">
                    <div class="card">
                        <h2>Nachricht senden</h2>

                        <?php if ($error): ?>
                            <div class="alert alert-error" role="alert">
                                <strong>Fehler:</strong><br>
                                <?= $error ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                            <div class="form-group">
                                <label for="name">Name *</label>
                                <input type="text"
                                       id="name"
                                       name="name"
                                       value="<?= e($form_data['name'] ?? '') ?>"
                                       required
                                       autocomplete="name">
                            </div>

                            <div class="form-group">
                                <label for="email">E-Mail *</label>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       value="<?= e($form_data['email'] ?? '') ?>"
                                       required
                                       autocomplete="email">
                            </div>

                            <div class="form-group">
                                <label for="phone">Telefon (optional)</label>
                                <input type="tel"
                                       id="phone"
                                       name="phone"
                                       value="<?= e($form_data['phone'] ?? '') ?>"
                                       autocomplete="tel">
                            </div>

                            <div class="form-group">
                                <label for="subject">Betreff *</label>
                                <select id="subject" name="subject" required>
                                    <option value="">Bitte wählen...</option>
                                    <option value="Allgemeine Anfrage" <?= ($form_data['subject'] ?? '') === 'Allgemeine Anfrage' ? 'selected' : '' ?>>Allgemeine Anfrage</option>
                                    <option value="Produktanfrage" <?= ($form_data['subject'] ?? '') === 'Produktanfrage' ? 'selected' : '' ?>>Produktanfrage</option>
                                    <option value="Reparatur" <?= ($form_data['subject'] ?? '') === 'Reparatur' ? 'selected' : '' ?>>Reparatur</option>
                                    <option value="Beratung" <?= ($form_data['subject'] ?? '') === 'Beratung' ? 'selected' : '' ?>>Beratung</option>
                                    <option value="Support" <?= ($form_data['subject'] ?? '') === 'Support' ? 'selected' : '' ?>>Support</option>
                                    <option value="Sonstiges" <?= ($form_data['subject'] ?? '') === 'Sonstiges' ? 'selected' : '' ?>>Sonstiges</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="message">Ihre Nachricht *</label>
                                <textarea id="message"
                                          name="message"
                                          rows="6"
                                          required><?= e($form_data['message'] ?? '') ?></textarea>
                                <span class="form-help">Mindestens 10 Zeichen</span>
                            </div>

                            <div class="form-group">
                                <label class="form-check">
                                    <input type="checkbox" name="privacy" required>
                                    <span>Ich habe die <a href="<?= BASE_URL ?>/datenschutz" target="_blank">Datenschutzerklärung</a> gelesen und akzeptiert. *</span>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                Nachricht senden
                            </button>

                            <p class="text-muted text-center mt-md" style="font-size: var(--font-size-sm);">
                                * Pflichtfelder
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
