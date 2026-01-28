<?php
/**
 * Admin - Download bearbeiten/erstellen
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();

// Admin-Rechte prüfen
require_admin();

$db = Database::getInstance();

// ID aus URL (null = neuer Download)
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Bestehenden Download laden
$download = null;
if ($id) {
    $download = $db->querySingle("SELECT * FROM downloads WHERE id = :id", [':id' => $id]);
    if (!$download) {
        set_flash('error', 'Download nicht gefunden.');
        redirect(BASE_URL . '/admin/downloads');
    }
}

$errors = [];
$form_data = $download ?? [
    'title' => '',
    'slug' => '',
    'description' => '',
    'version' => '',
    'category' => 'tools',
    'filename' => '',
    'file_size' => 0,
    'file_type' => '',
    'sort_order' => 0,
    'is_active' => 1,
];

// Formular-Verarbeitung
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';

    if (!csrf_verify($csrf_token)) {
        $errors[] = 'Ungültiger Sicherheitstoken.';
    } else {
        // Daten sammeln
        $form_data = [
            'title' => sanitize($_POST['title'] ?? ''),
            'slug' => sanitize($_POST['slug'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'version' => sanitize($_POST['version'] ?? ''),
            'category' => sanitize($_POST['category'] ?? 'tools'),
            'filename' => sanitize($_POST['filename'] ?? ''),
            'sort_order' => intval($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];

        // Validierung
        if (empty($form_data['title'])) {
            $errors[] = 'Bitte geben Sie einen Titel an.';
        }

        if (empty($form_data['slug'])) {
            $errors[] = 'Bitte geben Sie einen Slug an.';
        }

        if (empty($form_data['filename'])) {
            $errors[] = 'Bitte geben Sie einen Dateinamen an.';
        }

        // Slug-Eindeutigkeit prüfen
        if (!empty($form_data['slug'])) {
            $slug_check = $db->querySingle("
                SELECT id FROM downloads
                WHERE slug = :slug AND id != :id
            ", [
                ':slug' => $form_data['slug'],
                ':id' => $id ?? 0
            ]);

            if ($slug_check) {
                $errors[] = 'Dieser Slug wird bereits verwendet.';
            }
        }

        // Datei-Existenz prüfen
        if (!empty($form_data['filename'])) {
            $file_path = __DIR__ . '/../../uploads/downloads/' . $form_data['filename'];
            if (!file_exists($file_path)) {
                $errors[] = 'Die angegebene Datei existiert nicht: /uploads/downloads/' . $form_data['filename'];
            } else {
                // Dateigröße und Typ automatisch ermitteln
                $form_data['file_size'] = filesize($file_path);
                $form_data['file_type'] = mime_content_type($file_path);
            }
        }

        // Speichern wenn keine Fehler
        if (empty($errors)) {
            if ($id) {
                // Update
                $db->update("
                    UPDATE downloads SET
                        title = :title,
                        slug = :slug,
                        description = :description,
                        version = :version,
                        category = :category,
                        filename = :filename,
                        file_size = :file_size,
                        file_type = :file_type,
                        sort_order = :sort_order,
                        is_active = :is_active
                    WHERE id = :id
                ", [
                    ':title' => $form_data['title'],
                    ':slug' => $form_data['slug'],
                    ':description' => !empty($form_data['description']) ? $form_data['description'] : null,
                    ':version' => !empty($form_data['version']) ? $form_data['version'] : null,
                    ':category' => $form_data['category'],
                    ':filename' => $form_data['filename'],
                    ':file_size' => $form_data['file_size'],
                    ':file_type' => $form_data['file_type'],
                    ':sort_order' => $form_data['sort_order'],
                    ':is_active' => $form_data['is_active'],
                    ':id' => $id
                ]);

                set_flash('success', 'Download wurde aktualisiert.');
            } else {
                // Insert
                $db->insert("
                    INSERT INTO downloads (title, slug, description, version, category, filename, file_size, file_type, sort_order, is_active)
                    VALUES (:title, :slug, :description, :version, :category, :filename, :file_size, :file_type, :sort_order, :is_active)
                ", [
                    ':title' => $form_data['title'],
                    ':slug' => $form_data['slug'],
                    ':description' => !empty($form_data['description']) ? $form_data['description'] : null,
                    ':version' => !empty($form_data['version']) ? $form_data['version'] : null,
                    ':category' => $form_data['category'],
                    ':filename' => $form_data['filename'],
                    ':file_size' => $form_data['file_size'],
                    ':file_type' => $form_data['file_type'],
                    ':sort_order' => $form_data['sort_order'],
                    ':is_active' => $form_data['is_active']
                ]);

                set_flash('success', 'Download wurde erstellt.');
            }

            redirect(BASE_URL . '/admin/downloads');
        }
    }
}

// Slug automatisch generieren (JavaScript-Fallback)
if (empty($form_data['slug']) && !empty($form_data['title'])) {
    $form_data['slug'] = create_slug($form_data['title']);
}

// Kategorie-Labels
$categories = [
    'tools' => '🔧 Tools',
    'drivers' => '💾 Treiber',
    'documentation' => '📄 Dokumentation',
    'updates' => '🔄 Updates',
    'other' => '📦 Sonstiges'
];

// Prüfe ob aktuelle Datei existiert
$current_file_exists = false;
$current_file_info = null;
if (!empty($form_data['filename'])) {
    $file_path = __DIR__ . '/../../uploads/downloads/' . $form_data['filename'];
    $current_file_exists = file_exists($file_path);
    if ($current_file_exists) {
        $current_file_info = [
            'size' => filesize($file_path),
            'type' => mime_content_type($file_path),
            'modified' => date('d.m.Y H:i', filemtime($file_path))
        ];
    }
}

$page_title = ($id ? 'Download bearbeiten' : 'Neuer Download') . ' | Admin | PC-Wittfoot UG';
$page_description = 'Admin-Bereich';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <div class="d-flex justify-between align-center mb-lg" style="flex-wrap: wrap; gap: var(--space-md);">
            <h1 class="mb-0"><?= $id ? 'Download bearbeiten' : 'Neuer Download' ?></h1>
            <a href="<?= BASE_URL ?>/admin/downloads" class="btn btn-outline">← Zurück</a>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error mb-lg">
                <strong>Fehler:</strong>
                <ul style="margin: var(--space-sm) 0 0 0; padding-left: var(--space-lg);">
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Hinweis -->
        <div class="alert alert-info mb-lg">
            <strong>💡 Datei-Upload per SSH:</strong><br>
            1. Laden Sie die Datei per SSH in <code>/uploads/downloads/</code> hoch<br>
            2. Geben Sie dann den Dateinamen unten im Formular ein<br>
            3. Dateigröße und Typ werden automatisch ermittelt<br>
            <br>
            <strong>Beispiel:</strong> <code>scp meine-datei.exe user@server:/pfad/zu/c-d/uploads/downloads/</code>
        </div>

        <form method="post" action="">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <div class="row" style="align-items: flex-start;">
                <!-- Hauptbereich -->
                <div class="col-12 col-lg-8">
                    <div class="card mb-lg">
                        <h2>Allgemeine Informationen</h2>

                        <!-- Titel -->
                        <div class="form-group">
                            <label for="title">Titel *</label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   value="<?= e($form_data['title']) ?>"
                                   required
                                   placeholder="z.B. Backup-Tool Pro">
                        </div>

                        <!-- Slug -->
                        <div class="form-group">
                            <label for="slug">
                                Slug (URL) *
                                <span class="text-muted">- wird für die URL verwendet</span>
                            </label>
                            <input type="text"
                                   id="slug"
                                   name="slug"
                                   value="<?= e($form_data['slug']) ?>"
                                   required
                                   pattern="[a-z0-9\-]+"
                                   placeholder="z.B. backup-tool-pro">
                            <small class="form-help">
                                Nur Kleinbuchstaben, Zahlen und Bindestriche erlaubt.
                                <?php if (!$id): ?>
                                    Wird automatisch aus dem Titel generiert.
                                <?php endif; ?>
                            </small>
                        </div>

                        <!-- Beschreibung -->
                        <div class="form-group">
                            <label for="description">Beschreibung</label>
                            <textarea id="description"
                                      name="description"
                                      rows="4"
                                      placeholder="Kurze Beschreibung des Downloads..."><?= e($form_data['description']) ?></textarea>
                        </div>

                        <!-- Version -->
                        <div class="form-group">
                            <label for="version">
                                Version
                                <span class="text-muted">- z.B. v2.4.1 oder Januar 2026</span>
                            </label>
                            <input type="text"
                                   id="version"
                                   name="version"
                                   value="<?= e($form_data['version']) ?>"
                                   placeholder="z.B. v2.4.1">
                        </div>
                    </div>

                    <div class="card mb-lg">
                        <h2>Datei-Informationen</h2>

                        <!-- Dateiname -->
                        <div class="form-group">
                            <label for="filename">Dateiname (auf Server) *</label>
                            <input type="text"
                                   id="filename"
                                   name="filename"
                                   value="<?= e($form_data['filename']) ?>"
                                   required
                                   placeholder="z.B. backup-tool-pro-2.4.1.exe">
                            <small class="form-help">
                                Der exakte Dateiname in <code>/uploads/downloads/</code>
                            </small>

                            <!-- Datei-Status -->
                            <?php if (!empty($form_data['filename'])): ?>
                                <?php if ($current_file_exists): ?>
                                    <div class="alert alert-success" style="margin-top: var(--space-sm);">
                                        ✓ <strong>Datei gefunden!</strong><br>
                                        Größe: <?= format_file_size($current_file_info['size']) ?><br>
                                        Typ: <code><?= e($current_file_info['type']) ?></code><br>
                                        Geändert: <?= $current_file_info['modified'] ?>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-warning" style="margin-top: var(--space-sm);">
                                        ⚠️ <strong>Datei nicht gefunden!</strong><br>
                                        Bitte laden Sie die Datei per SSH in <code>/uploads/downloads/</code> hoch.
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-12 col-lg-4">
                    <div class="card mb-lg">
                        <h3>Einstellungen</h3>

                        <!-- Kategorie -->
                        <div class="form-group">
                            <label for="category">Kategorie *</label>
                            <select id="category" name="category" required>
                                <?php foreach ($categories as $value => $label): ?>
                                    <option value="<?= e($value) ?>"
                                            <?= $form_data['category'] === $value ? 'selected' : '' ?>>
                                        <?= e($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Sortierreihenfolge -->
                        <div class="form-group">
                            <label for="sort_order">
                                Sortierung
                                <span class="text-muted">- niedrigere Werte zuerst</span>
                            </label>
                            <input type="number"
                                   id="sort_order"
                                   name="sort_order"
                                   value="<?= e($form_data['sort_order']) ?>"
                                   min="0"
                                   step="10">
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox"
                                       name="is_active"
                                       value="1"
                                       <?= $form_data['is_active'] ? 'checked' : '' ?>>
                                Download aktiv (öffentlich sichtbar)
                            </label>
                        </div>

                        <!-- Speichern -->
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary btn-block">
                                <?= $id ? '💾 Aktualisieren' : '✓ Erstellen' ?>
                            </button>
                        </div>
                    </div>

                    <?php if ($id): ?>
                        <div class="card">
                            <h3>Statistiken</h3>
                            <div style="display: flex; flex-direction: column; gap: var(--space-sm);">
                                <div>
                                    <strong>Downloads:</strong><br>
                                    <?= number_format($download['download_count'], 0, ',', '.') ?>
                                </div>
                                <div>
                                    <strong>Erstellt:</strong><br>
                                    <?= format_datetime($download['created_at']) ?>
                                </div>
                                <div>
                                    <strong>Aktualisiert:</strong><br>
                                    <?= format_datetime($download['updated_at']) ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
// Slug automatisch aus Titel generieren
document.getElementById('title').addEventListener('input', function() {
    const slugField = document.getElementById('slug');

    // Nur wenn Slug leer ist (bei neuem Download)
    <?php if (!$id): ?>
    if (!slugField.value) {
        let slug = this.value.toLowerCase();

        // Umlaute ersetzen
        const replacements = {
            'ä': 'ae', 'ö': 'oe', 'ü': 'ue', 'ß': 'ss',
            'Ä': 'ae', 'Ö': 'oe', 'Ü': 'ue'
        };

        for (let [key, value] of Object.entries(replacements)) {
            slug = slug.split(key).join(value);
        }

        // Nur Buchstaben, Zahlen und Bindestriche
        slug = slug.replace(/[^a-z0-9\-]/g, '-');
        slug = slug.replace(/-+/g, '-');
        slug = slug.replace(/^-+|-+$/g, '');

        slugField.value = slug;
    }
    <?php endif; ?>
});

// Dateiname-Check beim Verlassen des Feldes
document.getElementById('filename').addEventListener('blur', function() {
    const filename = this.value.trim();
    if (filename) {
        // Simple Client-Side Validierung
        if (filename.includes('/') || filename.includes('\\')) {
            alert('Dateiname darf keine Pfad-Zeichen enthalten. Nur den Dateinamen angeben!');
        }
    }
});
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
