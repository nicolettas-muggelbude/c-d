<?php
/**
 * Kategorie bearbeiten/erstellen (Admin)
 * PC-Wittfoot UG
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();
require_admin();

$db = Database::getInstance();

// Kategorie-ID aus URL
$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$is_edit = $category_id > 0;

// Bestehende Kategorie laden
$category = null;
if ($is_edit) {
    $category = $db->querySingle("SELECT * FROM categories WHERE id = :id", [':id' => $category_id]);
    if (!$category) {
        set_flash('error', 'Kategorie nicht gefunden.');
        redirect(BASE_URL . '/admin/categories');
    }
}

// Löschen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!csrf_verify($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF-Token ungültig.';
    } else {
        // Prüfen ob Kategorie Produkte hat
        $product_count = $db->querySingle(
            "SELECT COUNT(*) as count FROM products WHERE category_id = :id",
            [':id' => $category_id]
        );

        if ($product_count && $product_count['count'] > 0) {
            $error = 'Kategorie kann nicht gelöscht werden, da sie ' . $product_count['count'] . ' Produkt(e) enthält.';
        } else {
            // Prüfen ob Unterkategorien existieren
            $sub_count = $db->querySingle(
                "SELECT COUNT(*) as count FROM categories WHERE parent_id = :id",
                [':id' => $category_id]
            );

            if ($sub_count && $sub_count['count'] > 0) {
                $error = 'Kategorie kann nicht gelöscht werden, da sie ' . $sub_count['count'] . ' Unterkategorie(n) hat.';
            } else {
                $db->update("DELETE FROM categories WHERE id = :id", [':id' => $category_id]);
                set_flash('success', 'Kategorie gelöscht.');
                redirect(BASE_URL . '/admin/categories');
            }
        }
    }
}

// Formular-Verarbeitung
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_POST['action']) || $_POST['action'] !== 'delete')) {
    if (!csrf_verify($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF-Token ungültig.';
    } else {
        $name = sanitize($_POST['name'] ?? '');
        $slug = sanitize($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $parent_id = (int)($_POST['parent_id'] ?? 0);
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        // Auto-generate slug from name if empty
        if (empty($slug)) {
            $slug = create_slug($name);
        }

        // Validierung
        if (empty($name)) {
            $error = 'Name ist erforderlich.';
        } elseif (empty($slug)) {
            $error = 'Slug ist erforderlich.';
        } else {
            // Slug-Duplikat prüfen
            $existing = $db->querySingle(
                "SELECT id FROM categories WHERE slug = :slug AND id != :id",
                [':slug' => $slug, ':id' => $category_id]
            );
            if ($existing) {
                $error = 'Slug existiert bereits.';
            }
        }

        // Speichern
        if (!isset($error)) {
            if ($is_edit) {
                // Update
                $db->update("
                    UPDATE categories SET
                        name = :name,
                        slug = :slug,
                        description = :description,
                        parent_id = :parent_id,
                        sort_order = :sort_order,
                        is_active = :is_active,
                        updated_at = NOW()
                    WHERE id = :id
                ", [
                    ':name' => $name,
                    ':slug' => $slug,
                    ':description' => $description,
                    ':parent_id' => $parent_id ?: null,
                    ':sort_order' => $sort_order,
                    ':is_active' => $is_active,
                    ':id' => $category_id
                ]);

                set_flash('success', 'Kategorie aktualisiert.');
            } else {
                // Insert
                $db->insert("
                    INSERT INTO categories (
                        name, slug, description, parent_id, sort_order, is_active, created_at
                    ) VALUES (
                        :name, :slug, :description, :parent_id, :sort_order, :is_active, NOW()
                    )
                ", [
                    ':name' => $name,
                    ':slug' => $slug,
                    ':description' => $description,
                    ':parent_id' => $parent_id ?: null,
                    ':sort_order' => $sort_order,
                    ':is_active' => $is_active
                ]);

                set_flash('success', 'Kategorie erstellt.');
            }

            redirect(BASE_URL . '/admin/categories');
        }
    }
}

// Alle Kategorien für Parent-Dropdown laden (außer sich selbst)
$parent_categories = $db->query(
    "SELECT id, name FROM categories WHERE id != :id ORDER BY name ASC",
    [':id' => $category_id]
);

// Lösch-Bedingungen prüfen (für Gefahrenzone)
$can_delete = false;
$delete_blockers = [];
if ($is_edit) {
    // Prüfen ob Kategorie Produkte hat
    $product_count = $db->querySingle(
        "SELECT COUNT(*) as count FROM products WHERE category_id = :id",
        [':id' => $category_id]
    );
    if ($product_count && $product_count['count'] > 0) {
        $delete_blockers[] = $product_count['count'] . ' Produkt(e) in dieser Kategorie';
    }

    // Prüfen ob Unterkategorien existieren
    $sub_count = $db->querySingle(
        "SELECT COUNT(*) as count FROM categories WHERE parent_id = :id",
        [':id' => $category_id]
    );
    if ($sub_count && $sub_count['count'] > 0) {
        $delete_blockers[] = $sub_count['count'] . ' Unterkategorie(n)';
    }

    $can_delete = empty($delete_blockers);
}

$page_title = ($is_edit ? 'Kategorie bearbeiten' : 'Neue Kategorie') . ' | Admin | PC-Wittfoot UG';
$page_description = 'Kategorie verwalten';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container" style="max-width: 800px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1><?= $is_edit ? 'Kategorie bearbeiten' : 'Neue Kategorie' ?></h1>
            <a href="<?= BASE_URL ?>/admin/categories" class="btn btn-outline">← Zurück zur Übersicht</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-error mb-lg"><?= e($error) ?></div>
        <?php endif; ?>

        <div class="card">
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                <div class="form-group">
                    <label for="name">Kategorie-Name *</label>
                    <input type="text" id="name" name="name" value="<?= $is_edit ? e($category['name']) : '' ?>" required>
                </div>

                <div class="form-group">
                    <label for="slug">Slug *</label>
                    <input type="text" id="slug" name="slug" value="<?= $is_edit ? e($category['slug']) : '' ?>" required>
                    <small class="text-muted">URL-freundlicher Name (wird automatisch aus dem Namen generiert)</small>
                </div>

                <div class="form-group">
                    <label for="description">Beschreibung</label>
                    <textarea id="description" name="description" rows="4"><?= $is_edit ? e($category['description']) : '' ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="parent_id">Übergeordnete Kategorie</label>
                        <select id="parent_id" name="parent_id">
                            <option value="">Hauptkategorie</option>
                            <?php foreach ($parent_categories as $parent): ?>
                                <option value="<?= $parent['id'] ?>"
                                    <?= $is_edit && $category['parent_id'] == $parent['id'] ? 'selected' : '' ?>>
                                    <?= e($parent['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sort_order">Sortierung</label>
                        <input type="number" id="sort_order" name="sort_order" min="0" value="<?= $is_edit ? $category['sort_order'] : 0 ?>">
                        <small class="text-muted">Niedrigere Zahlen erscheinen zuerst</small>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" <?= $is_edit && $category && $category['is_active'] ? 'checked' : ($is_edit ? '' : 'checked') ?>>
                        <span>Kategorie ist aktiv (im Shop sichtbar)</span>
                    </label>
                </div>

                <div class="form-group" style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">
                        <?= $is_edit ? 'Änderungen speichern' : 'Kategorie erstellen' ?>
                    </button>
                    <a href="<?= BASE_URL ?>/admin/categories" class="btn btn-outline">Abbrechen</a>
                </div>
            </form>
        </div>

        <?php if ($is_edit): ?>
            <div class="card" style="margin-top: 2rem; border-color: var(--color-error);">
                <h3 style="color: var(--color-error); margin-bottom: 1rem;">Gefahrenzone</h3>

                <?php if (!$can_delete): ?>
                    <div class="alert alert-error mb-lg">
                        <strong>Kategorie kann nicht gelöscht werden</strong>
                        <p style="margin-top: 0.5rem; margin-bottom: 0;">Folgende Bedingungen verhindern das Löschen:</p>
                        <ul style="margin: 0.5rem 0 0 1.5rem;">
                            <?php foreach ($delete_blockers as $blocker): ?>
                                <li><?= e($blocker) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <p class="text-muted">
                        Bitte entfernen oder verschieben Sie alle Produkte und Unterkategorien, bevor Sie diese Kategorie löschen.
                    </p>
                <?php else: ?>
                    <p class="text-muted">Das Löschen einer Kategorie kann nicht rückgängig gemacht werden.</p>
                    <form method="POST" onsubmit="return confirm('Kategorie wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden!')">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="btn btn-outline" style="color: var(--color-error); border-color: var(--color-error);">
                            🗑️ Kategorie löschen
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
    const slug = document.getElementById('slug');
    if (!slug.dataset.manuallyEdited) {
        slug.value = this.value
            .toLowerCase()
            .replace(/ä/g, 'ae')
            .replace(/ö/g, 'oe')
            .replace(/ü/g, 'ue')
            .replace(/ß/g, 'ss')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }
});

document.getElementById('slug').addEventListener('input', function() {
    this.dataset.manuallyEdited = 'true';
});
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
