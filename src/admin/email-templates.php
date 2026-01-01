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
        $signature = trim($_POST['signature']);

        $sql = "UPDATE email_signature SET signature_text = :signature, updated_at = NOW() WHERE id = 1";
        $db->update($sql, [':signature' => $signature]);

        $success = "Signatur erfolgreich aktualisiert";
    }

    if ($action === 'toggle_active') {
        $id = (int)$_POST['template_id'];
        $sql = "UPDATE email_templates SET is_active = NOT is_active WHERE id = :id";
        $db->update($sql, [':id' => $id]);

        $success = "Template-Status geändert";
    }
}

// Templates laden
$templates = $db->query("SELECT * FROM email_templates ORDER BY template_type");

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

        <!-- Email-Templates -->
        <div class="card mb-xl">
            <h2 class="mb-lg">Email-Templates</h2>

            <?php foreach ($templates as $template): ?>
                <div class="card mb-lg" style="background: var(--bg-secondary);">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                        <div>
                            <h3 style="margin-bottom: 0.25rem;"><?= e($template['template_name']) ?></h3>
                            <small class="text-muted">Typ: <?= e($template['template_type']) ?></small>
                        </div>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="toggle_active">
                            <input type="hidden" name="template_id" value="<?= $template['id'] ?>">
                            <button type="submit" class="btn btn-sm <?= $template['is_active'] ? 'btn-primary' : 'btn-outline' ?>">
                                <?= $template['is_active'] ? 'Aktiv ✓' : 'Inaktiv' ?>
                            </button>
                        </form>
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
                                {customer_firstname}, {customer_lastname}, {booking_id},
                                {booking_date_formatted}, {booking_time_formatted},
                                {service_type_label}, {booking_type_label}, {customer_notes_section}
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary">Template speichern</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Email-Signatur -->
        <div class="card">
            <h2 class="mb-lg">Email-Signatur (global)</h2>

            <form method="POST">
                <input type="hidden" name="action" value="update_signature">

                <div class="form-group">
                    <label>Signatur-Text</label>
                    <textarea name="signature" class="form-control" rows="12" required><?= e($signature['signature_text'] ?? '') ?></textarea>
                    <small class="text-muted">
                        Diese Signatur wird automatisch an alle Emails angehängt.
                    </small>
                </div>

                <button type="submit" class="btn btn-primary">Signatur speichern</button>
            </form>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
