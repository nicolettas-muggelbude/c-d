<?php
/**
 * Email-Template Verwaltung
 * PC-Wittfoot UG
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();
require_admin();

$db = Database::getInstance();

// POST-Handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_template') {
        $id = (int)$_POST['template_id'];
        $subject = trim($_POST['subject']);
        $body = trim($_POST['body']);

        $sql = "UPDATE email_templates SET subject = :subject, body = :body, updated_at = NOW() WHERE id = :id";
        $db->update($sql, [':subject' => $subject, ':body' => $body, ':id' => $id]);

        $success = "Template erfolgreich aktualisiert";
    }

    if ($action === 'update_signature') {
        $signatureHtml = trim($_POST['signature_html']);
        $signaturePlaintext = trim($_POST['signature_plaintext']);
        $logoFilename = trim($_POST['logo_filename']);

        $sql = "UPDATE email_signature SET
                signature_html = :html,
                signature_plaintext = :plaintext,
                logo_filename = :logo,
                updated_at = NOW()
                WHERE id = 1";

        $db->update($sql, [
            ':html' => $signatureHtml,
            ':plaintext' => $signaturePlaintext,
            ':logo' => $logoFilename
        ]);

        $success = "Signatur erfolgreich aktualisiert";
    }

    if ($action === 'toggle_active') {
        $id = (int)$_POST['template_id'];
        $sql = "UPDATE email_templates SET is_active = NOT is_active WHERE id = :id";
        $db->update($sql, [':id' => $id]);

        $success = "Template-Status geändert";
    }
}

// Templates laden und nach Typ gruppieren
$bookingTemplates = $db->query("
    SELECT * FROM email_templates
    WHERE template_type IN ('confirmation', 'booking_notification', 'cancellation', 'reschedule',
                            'admin_cancellation', 'admin_reschedule', 'reminder_24h', 'reminder_1h')
    ORDER BY template_name
");

$shopTemplates = $db->query("
    SELECT * FROM email_templates
    WHERE template_type IN ('order_confirmation', 'order_notification')
    ORDER BY template_name
");

// Signatur laden
$signature = $db->querySingle("SELECT * FROM email_signature WHERE id = 1");

$page_title = 'Email-Templates | Admin | PC-Wittfoot UG';
$page_description = 'Email-Template Verwaltung';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1>Email-Templates verwalten</h1>
            <a href="<?= BASE_URL ?>/admin" class="btn btn-outline">← Zurück zum Dashboard</a>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
        <?php endif; ?>

        <!-- Buchungs-Templates -->
        <div class="card mb-xl">
            <h2 class="mb-lg">📅 Buchungs-Templates</h2>
            <p class="text-muted mb-lg">Email-Vorlagen für Terminbuchungen</p>

            <?php foreach ($bookingTemplates as $template): ?>
                <div class="card mb-lg" style="background: var(--bg-secondary);">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                        <div>
                            <h3 style="margin-bottom: 0.25rem;"><?= e($template['template_name']) ?></h3>
                            <small class="text-muted">Typ: <?= e($template['template_type']) ?></small>
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="<?= BASE_URL ?>/test-email-preview.php?type=<?= urlencode($template['template_type']) ?>&id=1"
                               target="_blank"
                               class="btn btn-sm btn-outline">
                                👁️ Vorschau
                            </a>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="toggle_active">
                                <input type="hidden" name="template_id" value="<?= $template['id'] ?>">
                                <button type="submit" class="btn btn-sm <?= $template['is_active'] ? 'btn-primary' : 'btn-outline' ?>">
                                    <?= $template['is_active'] ? 'Aktiv ✓' : 'Inaktiv' ?>
                                </button>
                            </form>
                        </div>
                    </div>

                    <form method="POST">
                        <input type="hidden" name="action" value="update_template">
                        <input type="hidden" name="template_id" value="<?= $template['id'] ?>">

                        <div class="form-group">
                            <label>Betreff</label>
                            <input type="text" name="subject" class="form-control"
                                   value="<?= e($template['subject']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Email-Text</label>
                            <textarea name="body" class="form-control" rows="15" required><?= e($template['body']) ?></textarea>
                            <small class="text-muted">
                                <strong>Verfügbare Platzhalter:</strong><br>
                                {customer_firstname}, {customer_lastname}, {customer_email}, {customer_company},
                                {customer_phone_country}, {customer_phone_mobile}, {customer_phone_landline},
                                {customer_street}, {customer_house_number}, {customer_postal_code}, {customer_city},
                                {booking_id}, {booking_date_formatted}, {booking_time_formatted},
                                {service_type_label}, {booking_type_label}, {customer_notes_section}, {admin_booking_link}
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary">Template speichern</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Shop-Templates -->
        <div class="card mb-xl">
            <h2 class="mb-lg">🛒 Shop-Templates</h2>
            <p class="text-muted mb-lg">Email-Vorlagen für Bestellungen</p>

            <?php foreach ($shopTemplates as $template): ?>
                <div class="card mb-lg" style="background: var(--bg-secondary);">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                        <div>
                            <h3 style="margin-bottom: 0.25rem;"><?= e($template['template_name']) ?></h3>
                            <small class="text-muted">Typ: <?= e($template['template_type']) ?></small>
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="<?= BASE_URL ?>/test-email-preview.php?type=<?= urlencode($template['template_type']) ?>&id=1"
                               target="_blank"
                               class="btn btn-sm btn-outline">
                                👁️ Vorschau
                            </a>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="toggle_active">
                                <input type="hidden" name="template_id" value="<?= $template['id'] ?>">
                                <button type="submit" class="btn btn-sm <?= $template['is_active'] ? 'btn-primary' : 'btn-outline' ?>">
                                    <?= $template['is_active'] ? 'Aktiv ✓' : 'Inaktiv' ?>
                                </button>
                            </form>
                        </div>
                    </div>

                    <form method="POST">
                        <input type="hidden" name="action" value="update_template">
                        <input type="hidden" name="template_id" value="<?= $template['id'] ?>">

                        <div class="form-group">
                            <label>Betreff</label>
                            <input type="text" name="subject" class="form-control"
                                   value="<?= e($template['subject']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Email-Text</label>
                            <textarea name="body" class="form-control" rows="15" required><?= e($template['body']) ?></textarea>
                            <small class="text-muted">
                                <strong>Verfügbare Platzhalter:</strong><br>
                                {customer_firstname}, {customer_lastname}, {customer_email}, {customer_company_line},
                                {customer_phone_line}, {customer_address}, {order_number}, {order_date}, {order_items},
                                {order_subtotal}, {order_tax}, {order_total}, {delivery_method}, {payment_method},
                                {invoice_link_section}, {order_notes_section}, {admin_order_link}
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary">Template speichern</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Email-Signatur -->
        <div class="card">
            <h2 class="mb-lg">📧 Email-Signatur (global)</h2>
            <p class="text-muted mb-lg">Diese Signatur wird automatisch an alle Emails angehängt (HTML + Plaintext-Fallback).</p>

            <form method="POST">
                <input type="hidden" name="action" value="update_signature">

                <!-- Logo-Auswahl -->
                <div class="form-group">
                    <label><strong>Logo für Signatur</strong></label>
                    <select name="logo_filename" class="form-control" id="logoSelect">
                        <option value="">Kein Logo</option>
                        <option value="logo-modern.svg" <?= ($signature['logo_filename'] ?? '') === 'logo-modern.svg' ? 'selected' : '' ?>>logo-modern.svg</option>
                        <option value="logo-square.svg" <?= ($signature['logo_filename'] ?? '') === 'logo-square.svg' ? 'selected' : '' ?>>logo-square.svg</option>
                    </select>
                    <small class="text-muted">
                        Logo wird in der HTML-Signatur über den Platzhalter {logo_url} eingebunden.<br>
                        Logos liegen in: /src/assets/images/email/
                    </small>
                </div>

                <!-- HTML-Signatur -->
                <div class="form-group">
                    <label><strong>HTML-Signatur</strong></label>
                    <textarea name="signature_html" class="form-control" rows="15" style="font-family: monospace; font-size: 12px;"><?= e($signature['signature_html'] ?? '') ?></textarea>
                    <small class="text-muted">
                        <strong>Verfügbare Platzhalter:</strong> {logo_url}<br>
                        <strong>Beispiel:</strong> &lt;img src="{logo_url}" alt="Logo" style="max-width: 120px;" /&gt;
                    </small>
                </div>

                <!-- Plaintext-Signatur -->
                <div class="form-group">
                    <label><strong>Plaintext-Signatur (Fallback)</strong></label>
                    <textarea name="signature_plaintext" class="form-control" rows="8"><?= e($signature['signature_plaintext'] ?? $signature['signature_text'] ?? '') ?></textarea>
                    <small class="text-muted">
                        Diese Version wird in Plaintext-Emails verwendet (ohne Logo).
                    </small>
                </div>

                <!-- Vorschau -->
                <div class="form-group">
                    <button type="button" class="btn btn-outline" onclick="previewSignature()">👁️ Vorschau</button>
                </div>

                <button type="submit" class="btn btn-primary">Signatur speichern</button>
            </form>

            <!-- Vorschau-Bereich -->
            <div id="signaturePreview" style="display: none; margin-top: 2rem; padding: 1.5rem; background: var(--bg-secondary); border-radius: 8px;">
                <h3 class="mb-md">Vorschau</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <h4 style="color: var(--accent); margin-bottom: 0.5rem;">HTML-Version</h4>
                        <div id="htmlPreview" style="border: 2px solid var(--accent); padding: 1rem; background: white; border-radius: 4px; min-height: 200px;"></div>
                    </div>
                    <div>
                        <h4 style="color: var(--accent); margin-bottom: 0.5rem;">Plaintext-Version</h4>
                        <div id="plaintextPreview" style="border: 2px solid var(--accent); padding: 1rem; background: white; border-radius: 4px; white-space: pre-wrap; font-family: monospace; font-size: 12px; min-height: 200px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function previewSignature() {
            const logoSelect = document.getElementById('logoSelect');
            const htmlSignature = document.querySelector('textarea[name="signature_html"]').value;
            const plaintextSignature = document.querySelector('textarea[name="signature_plaintext"]').value;
            const logoFilename = logoSelect.value;

            // Logo-URL generieren
            const logoUrl = logoFilename ? '<?= BASE_URL ?>/assets/images/email/' + logoFilename : '';

            // HTML-Signatur mit Logo-URL ersetzen
            const htmlWithLogo = htmlSignature.replace(/{logo_url}/g, logoUrl);

            // Vorschau anzeigen
            document.getElementById('htmlPreview').innerHTML = htmlWithLogo;
            document.getElementById('plaintextPreview').textContent = plaintextSignature;
            document.getElementById('signaturePreview').style.display = 'block';

            // Scroll zur Vorschau
            document.getElementById('signaturePreview').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        </script>
    </div>
</section>

<!-- Email-Vorschau Modal -->
<div id="previewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; padding: 20px; overflow: auto;">
    <div style="max-width: 1400px; margin: 0 auto; background: white; border-radius: 8px; padding: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0;">📧 Email-Vorschau</h2>
            <button onclick="closePreview()" class="btn btn-outline">✕ Schließen</button>
        </div>

        <div id="previewLoading" style="text-align: center; padding: 40px;">
            <p>Lade Vorschau...</p>
        </div>

        <div id="previewContent" style="display: none;">
            <div style="background: #f5f5f5; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Buchung-ID:</label>
                        <input type="number" id="previewBookingId" value="1" min="1" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100px;">
                    </div>
                    <div style="flex: 1;">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Betreff:</label>
                        <div id="previewSubject" style="padding: 8px; background: white; border: 1px solid #ddd; border-radius: 4px;"></div>
                    </div>
                    <div>
                        <button onclick="refreshPreview()" class="btn btn-primary">🔄 Aktualisieren</button>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <h3 style="margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #8BC34A; color: #8BC34A;">
                        🎨 HTML-Version
                    </h3>
                    <iframe id="previewHtml" style="width: 100%; min-height: 400px; max-height: 600px; border: 2px solid #8BC34A; border-radius: 4px; background: #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"></iframe>
                </div>

                <div>
                    <h3 style="margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #8BC34A; color: #8BC34A;">
                        📄 Plaintext-Version
                    </h3>
                    <div id="previewPlain" style="border: 2px solid #8BC34A; padding: 20px; background: #ffffff; border-radius: 4px; white-space: pre-wrap; font-family: 'Courier New', monospace; font-size: 14px; line-height: 1.6; min-height: 400px; max-height: 600px; overflow-y: auto; color: #000000; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentTemplateType = '';

function showPreview(templateType) {
    currentTemplateType = templateType;
    document.getElementById('previewModal').style.display = 'block';
    document.getElementById('previewLoading').style.display = 'block';
    document.getElementById('previewContent').style.display = 'none';
    document.body.style.overflow = 'hidden';

    loadPreview();
}

function closePreview() {
    document.getElementById('previewModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function refreshPreview() {
    document.getElementById('previewLoading').style.display = 'block';
    document.getElementById('previewContent').style.display = 'none';
    loadPreview();
}

async function loadPreview() {
    const bookingId = document.getElementById('previewBookingId').value || 1;

    try {
        const response = await fetch(`/api/email-preview?type=${encodeURIComponent(currentTemplateType)}&id=${bookingId}`);
        const data = await response.json();

        if (data.success) {
            document.getElementById('previewSubject').textContent = data.subject;

            // Alle Links auf target="_blank" setzen
            let html = data.html.replace(/<a /g, '<a target="_blank" rel="noopener noreferrer" ');

            // HTML in iframe schreiben (komplett isoliert vom Admin-CSS)
            const iframe = document.getElementById('previewHtml');
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            iframeDoc.open();
            iframeDoc.write('<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body style="margin: 20px; font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6; color: #000000;">' + html + '</body></html>');
            iframeDoc.close();

            document.getElementById('previewPlain').textContent = data.plain;

            document.getElementById('previewLoading').style.display = 'none';
            document.getElementById('previewContent').style.display = 'block';
        } else {
            alert('Fehler beim Laden der Vorschau: ' + (data.error || 'Unbekannter Fehler'));
            closePreview();
        }
    } catch (error) {
        console.error('Preview error:', error);
        alert('Fehler beim Laden der Vorschau. Bitte versuchen Sie es erneut.');
        closePreview();
    }
}

// ESC-Taste zum Schließen
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('previewModal').style.display === 'block') {
        closePreview();
    }
});

// Click außerhalb des Modals zum Schließen
document.getElementById('previewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePreview();
    }
});
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
