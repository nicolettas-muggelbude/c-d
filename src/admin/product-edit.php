<?php
/**
 * Produkt bearbeiten/erstellen (Admin)
 * PC-Wittfoot UG
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();
require_admin();

$db = Database::getInstance();

// Produkt-ID aus URL (falls Bearbeitung)
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$is_edit = $product_id > 0;

// Bestehendenes Produkt laden
$product = null;
if ($is_edit) {
    $product = $db->querySingle("SELECT * FROM products WHERE id = :id", [':id' => $product_id]);
    if (!$product) {
        set_flash('error', 'Produkt nicht gefunden.');
        redirect(BASE_URL . '/admin/products');
    }
}

// Löschen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!csrf_verify($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF-Token ungültig.';
    } else {
        // Prüfen ob Produkt in Bestellungen vorkommt
        $order_count = $db->querySingle(
            "SELECT COUNT(*) as count FROM order_items WHERE product_id = :id",
            [':id' => $product_id]
        );

        if ($order_count && $order_count['count'] > 0) {
            $error = 'Produkt kann nicht gelöscht werden, da es in ' . $order_count['count'] . ' Bestellung(en) vorkommt. Deaktivieren Sie das Produkt stattdessen.';
        } else {
            // Bild löschen
            if ($product && !empty($product['image'])) {
                $image_file = __DIR__ . '/../../uploads/' . $product['image'];
                if (file_exists($image_file)) {
                    unlink($image_file);
                }
            }

            // Produkt löschen
            $db->update("DELETE FROM products WHERE id = :id", [':id' => $product_id]);

            set_flash('success', 'Produkt gelöscht.');
            redirect(BASE_URL . '/admin/products');
        }
    }
}

// Formular-Verarbeitung
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_POST['action']) || $_POST['action'] !== 'delete')) {
    // CSRF-Check
    if (!csrf_verify($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF-Token ungültig.';
    } else {
        $name = sanitize($_POST['name'] ?? '');
        $sku = sanitize($_POST['sku'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $stock = (int)($_POST['stock'] ?? 0);
        $category_id = (int)($_POST['category_id'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $in_showroom = isset($_POST['in_showroom']) ? 1 : 0;
        $sync_with_hellocash = isset($_POST['sync_with_hellocash']) ? 1 : 0;
        $source = $is_edit && $product ? $product['source'] : 'manual';

        // Validierung
        if (empty($name)) {
            $error = 'Name ist erforderlich.';
        } elseif (empty($sku)) {
            $error = 'SKU ist erforderlich.';
        } elseif ($price <= 0) {
            $error = 'Preis muss größer als 0 sein.';
        } else {
            // SKU-Duplikat prüfen
            $existing = $db->querySingle(
                "SELECT id FROM products WHERE sku = :sku AND id != :id",
                [':sku' => $sku, ':id' => $product_id]
            );
            if ($existing) {
                $error = 'SKU existiert bereits.';
            }
        }

        // Bild-Upload
        $image_path = $is_edit && $product ? ($product['image'] ?? null) : null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/../../uploads/products/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($file_ext, $allowed_ext)) {
                $error = 'Nur JPG, PNG und WEBP Bilder erlaubt.';
            } else {
                $filename = uniqid() . '.' . $file_ext;
                $upload_path = $upload_dir . $filename;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    // Altes Bild löschen
                    if ($is_edit && $product && !empty($product['image'])) {
                        $old_file = __DIR__ . '/../../uploads/' . $product['image'];
                        if (file_exists($old_file)) {
                            unlink($old_file);
                        }
                    }
                    $image_path = 'products/' . $filename;
                } else {
                    $error = 'Bild-Upload fehlgeschlagen.';
                }
            }
        }

        // Speichern
        if (!isset($error)) {
            if ($is_edit) {
                // Update
                $db->update("
                    UPDATE products SET
                        name = :name,
                        sku = :sku,
                        description = :description,
                        price = :price,
                        stock = :stock,
                        image = :image,
                        category_id = :category_id,
                        is_active = :is_active,
                        in_showroom = :in_showroom,
                        sync_with_hellocash = :sync_with_hellocash,
                        updated_at = NOW()
                    WHERE id = :id
                ", [
                    ':name' => $name,
                    ':sku' => $sku,
                    ':description' => $description,
                    ':price' => $price,
                    ':stock' => $stock,
                    ':image' => $image_path,
                    ':category_id' => $category_id ?: null,
                    ':is_active' => $is_active,
                    ':in_showroom' => $in_showroom,
                    ':sync_with_hellocash' => $sync_with_hellocash,
                    ':id' => $product_id
                ]);

                set_flash('success', 'Produkt aktualisiert.');
            } else {
                // Insert
                $db->insert("
                    INSERT INTO products (
                        name, sku, slug, description, price, stock, image, category_id,
                        is_active, source, in_showroom, sync_with_hellocash, created_at
                    ) VALUES (
                        :name, :sku, :slug, :description, :price, :stock, :image, :category_id,
                        :is_active, :source, :in_showroom, :sync_with_hellocash, NOW()
                    )
                ", [
                    ':name' => $name,
                    ':sku' => $sku,
                    ':slug' => create_slug($name),
                    ':description' => $description,
                    ':price' => $price,
                    ':stock' => $stock,
                    ':image' => $image_path,
                    ':category_id' => $category_id ?: null,
                    ':is_active' => $is_active,
                    ':source' => $source,
                    ':in_showroom' => $in_showroom,
                    ':sync_with_hellocash' => $sync_with_hellocash
                ]);

                set_flash('success', 'Produkt erstellt.');
            }

            redirect(BASE_URL . '/admin/products');
        }
    }
}

// Kategorien laden
$categories = $db->query("SELECT * FROM categories WHERE parent_id IS NULL ORDER BY name ASC");

$page_title = ($is_edit ? 'Produkt bearbeiten' : 'Neues Produkt') . ' | Admin | PC-Wittfoot UG';
$page_description = 'Produkt verwalten';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container" style="max-width: 800px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1><?= $is_edit ? 'Produkt bearbeiten' : 'Neues Produkt erstellen' ?></h1>
            <a href="<?= BASE_URL ?>/admin/products" class="btn btn-outline">← Zurück zur Übersicht</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-error mb-lg"><?= e($error) ?></div>
        <?php endif; ?>

        <div class="card">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                <!-- Basis-Informationen -->
                <h2 class="mb-lg">Basis-Informationen</h2>

                <div class="form-group">
                    <label for="name">Produktname *</label>
                    <input type="text" id="name" name="name" value="<?= $is_edit ? e($product['name']) : '' ?>" required>
                </div>

                <div class="form-group">
                    <label for="sku">SKU / Artikelnummer *</label>
                    <input type="text" id="sku" name="sku" value="<?= $is_edit ? e($product['sku']) : '' ?>" required>
                    <small class="text-muted">Eindeutige Artikelnummer</small>
                </div>

                <div class="form-group">
                    <label for="description">Beschreibung</label>
                    <textarea id="description" name="description" rows="6"><?= $is_edit ? e($product['description']) : '' ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Preis (EUR) *</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" value="<?= $is_edit ? $product['price'] : '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="stock">Lagerbestand</label>
                        <input type="number" id="stock" name="stock" min="0" value="<?= $is_edit ? $product['stock'] : 0 ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="category_id">Kategorie</label>
                    <select id="category_id" name="category_id">
                        <option value="">Keine Kategorie</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"
                                <?= $is_edit && $product['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                <?= e($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="image">Produktbild</label>
                    <?php if ($is_edit && $product && !empty($product['image'])): ?>
                        <div style="margin-bottom: 1rem;">
                            <img src="<?= upload($product['image']) ?>" alt="Aktuelles Bild" style="max-width: 200px; border-radius: 4px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
                    <small class="text-muted">JPG, PNG oder WEBP (max. 5MB)</small>
                </div>

                <!-- Erweiterte Einstellungen -->
                <h2 class="mb-lg" style="margin-top: 2rem;">Einstellungen</h2>

                <?php if ($is_edit && $product && $product['source'] !== 'manual'): ?>
                    <div class="alert alert-info mb-lg">
                        <strong>Quelle:</strong>
                        <?php
                        $source_labels = [
                            'csv_import' => '📄 CSV-Import',
                            'hellocash' => '💳 HelloCash'
                        ];
                        echo $source_labels[$product['source']] ?? $product['source'];
                        ?>
                        <?php if ($product['supplier_name']): ?>
                            <br><strong>Lieferant:</strong> <?= e($product['supplier_name']) ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" <?= $is_edit && $product && $product['is_active'] ? 'checked' : ($is_edit ? '' : 'checked') ?>>
                        <span>Produkt ist aktiv (im Shop sichtbar)</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="in_showroom" <?= $is_edit && $product && $product['in_showroom'] ? 'checked' : '' ?>>
                        <span>Verfügbar in Oldenburg</span>
                    </label>
                    <small class="text-muted">Produkt ist vor Ort verfügbar (Ausstellung oder Lager)</small>
                </div>

                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="sync_with_hellocash" <?= $is_edit && $product && $product['sync_with_hellocash'] ? 'checked' : '' ?>>
                        <span>Mit HelloCash synchronisieren</span>
                    </label>
                    <small class="text-muted">Produkt wird bei Änderungen zu HelloCash übertragen</small>
                </div>

                <div class="form-group" style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">
                        <?= $is_edit ? 'Änderungen speichern' : 'Produkt erstellen' ?>
                    </button>
                    <a href="<?= BASE_URL ?>/admin/products" class="btn btn-outline">Abbrechen</a>
                </div>
            </form>
        </div>

        <?php if ($is_edit): ?>
            <div class="card" style="margin-top: 2rem; border-color: var(--color-error);">
                <h3 style="color: var(--color-error); margin-bottom: 1rem;">Gefahrenzone</h3>
                <p class="text-muted">Das Löschen eines Produkts kann nicht rückgängig gemacht werden.</p>
                <form method="POST" onsubmit="return confirm('Produkt wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden!')">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="btn btn-outline" style="color: var(--color-error); border-color: var(--color-error);">
                        🗑️ Produkt löschen
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
