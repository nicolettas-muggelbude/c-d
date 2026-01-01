<?php
/**
 * Lieferant bearbeiten/erstellen (Admin)
 * PC-Wittfoot UG
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();
require_admin();

$db = Database::getInstance();

// Lieferant-ID aus URL
$supplier_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$is_edit = $supplier_id > 0;

// Bestehenden Lieferant laden
$supplier = null;
if ($is_edit) {
    $supplier = $db->querySingle("SELECT * FROM suppliers WHERE id = :id", [':id' => $supplier_id]);
    if (!$supplier) {
        set_flash('error', 'Lieferant nicht gefunden.');
        redirect(BASE_URL . '/admin/suppliers');
    }
}

// Formular-Verarbeitung
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF-Token ungültig.';
    } else {
        $name = sanitize($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $csv_url = sanitize($_POST['csv_url'] ?? '');
        $csv_delimiter = $_POST['csv_delimiter'] ?? ',';
        $csv_encoding = $_POST['csv_encoding'] ?? 'UTF-8';
        $price_markup = (float)str_replace(',', '.', $_POST['price_markup'] ?? 0);
        $free_shipping = isset($_POST['free_shipping']) ? 1 : 0;
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        // Spalten-Mapping
        $column_mapping = [];
        if (!empty($_POST['mapping_name'])) $column_mapping['name'] = $_POST['mapping_name'];
        if (!empty($_POST['mapping_sku'])) $column_mapping['sku'] = $_POST['mapping_sku'];
        if (!empty($_POST['mapping_ean'])) $column_mapping['ean'] = $_POST['mapping_ean'];
        if (!empty($_POST['mapping_price'])) $column_mapping['price'] = $_POST['mapping_price'];
        if (!empty($_POST['mapping_stock'])) $column_mapping['stock'] = $_POST['mapping_stock'];
        if (!empty($_POST['mapping_description'])) $column_mapping['description'] = $_POST['mapping_description'];
        if (!empty($_POST['mapping_category'])) $column_mapping['category'] = $_POST['mapping_category'];

        // Beschreibungs-Filter (ein String pro Zeile)
        $description_filter = trim($_POST['description_filter'] ?? '');

        // Kategorie-Mapping (CSV-Name → Shop-Kategorie-ID)
        $category_mapping = [];
        if (!empty($_POST['category_csv_name']) && is_array($_POST['category_csv_name'])) {
            foreach ($_POST['category_csv_name'] as $index => $csv_name) {
                $shop_category_id = $_POST['category_shop_id'][$index] ?? '';
                if (!empty($csv_name) && !empty($shop_category_id)) {
                    $category_mapping[$csv_name] = (int)$shop_category_id;
                }
            }
        }
        $category_mapping_json = json_encode($category_mapping);

        // Validierung
        if (empty($name)) {
            $error = 'Name ist erforderlich.';
        } elseif (empty($csv_url)) {
            $error = 'CSV-URL/Pfad ist erforderlich.';
        } elseif (empty($column_mapping['name']) || empty($column_mapping['sku']) || empty($column_mapping['price'])) {
            $error = 'Spalten-Mapping für Name, SKU und Preis ist erforderlich.';
        }

        if (!isset($error)) {
            $column_mapping_json = json_encode($column_mapping);

            if ($is_edit) {
                // Update
                $db->update("
                    UPDATE suppliers SET
                        name = :name,
                        description = :description,
                        csv_url = :csv_url,
                        csv_delimiter = :csv_delimiter,
                        csv_encoding = :csv_encoding,
                        column_mapping = :column_mapping,
                        description_filter = :description_filter,
                        category_mapping = :category_mapping,
                        price_markup = :price_markup,
                        free_shipping = :free_shipping,
                        is_active = :is_active,
                        updated_at = NOW()
                    WHERE id = :id
                ", [
                    ':name' => $name,
                    ':description' => $description,
                    ':csv_url' => $csv_url,
                    ':csv_delimiter' => $csv_delimiter,
                    ':csv_encoding' => $csv_encoding,
                    ':column_mapping' => $column_mapping_json,
                    ':description_filter' => $description_filter ?: null,
                    ':category_mapping' => $category_mapping_json ?: null,
                    ':price_markup' => $price_markup,
                    ':free_shipping' => $free_shipping,
                    ':is_active' => $is_active,
                    ':id' => $supplier_id
                ]);

                set_flash('success', 'Lieferant aktualisiert.');
            } else {
                // Insert
                $db->insert("
                    INSERT INTO suppliers (
                        name, description, csv_url, csv_delimiter, csv_encoding,
                        column_mapping, description_filter, category_mapping, price_markup, free_shipping, is_active, created_at
                    ) VALUES (
                        :name, :description, :csv_url, :csv_delimiter, :csv_encoding,
                        :column_mapping, :description_filter, :category_mapping, :price_markup, :free_shipping, :is_active, NOW()
                    )
                ", [
                    ':name' => $name,
                    ':description' => $description,
                    ':csv_url' => $csv_url,
                    ':csv_delimiter' => $csv_delimiter,
                    ':csv_encoding' => $csv_encoding,
                    ':column_mapping' => $column_mapping_json,
                    ':description_filter' => $description_filter ?: null,
                    ':category_mapping' => $category_mapping_json ?: null,
                    ':price_markup' => $price_markup,
                    ':free_shipping' => $free_shipping,
                    ':is_active' => $is_active
                ]);

                set_flash('success', 'Lieferant erstellt.');
            }

            redirect(BASE_URL . '/admin/suppliers');
        }
    }
}

// Spalten-Mapping dekodieren für Formular
$column_mapping = [];
if ($is_edit && $supplier && $supplier['column_mapping']) {
    $column_mapping = json_decode($supplier['column_mapping'], true);
}

// Kategorie-Mapping dekodieren
$category_mapping = [];
if ($is_edit && $supplier && $supplier['category_mapping']) {
    $category_mapping = json_decode($supplier['category_mapping'], true);
}

// Kategorien laden
$categories = $db->query("SELECT id, name FROM categories ORDER BY name ASC");

$page_title = ($is_edit ? 'Lieferant bearbeiten' : 'Neuer Lieferant') . ' | Admin | PC-Wittfoot UG';
$page_description = 'Lieferant verwalten';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container" style="max-width: 800px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1><?= $is_edit ? 'Lieferant bearbeiten' : 'Neuer Lieferant' ?></h1>
            <a href="<?= BASE_URL ?>/admin/suppliers" class="btn btn-outline">← Zurück zur Übersicht</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-error mb-lg"><?= e($error) ?></div>
        <?php endif; ?>

        <div class="card">
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                <h2 class="mb-lg">Basis-Informationen</h2>

                <div class="form-group">
                    <label for="name">Lieferanten-Name *</label>
                    <input type="text" id="name" name="name" value="<?= $is_edit ? e($supplier['name']) : '' ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">Beschreibung</label>
                    <textarea id="description" name="description" rows="3"><?= $is_edit ? e($supplier['description']) : '' ?></textarea>
                </div>

                <h2 class="mb-lg" style="margin-top: 2rem;">CSV-Konfiguration</h2>

                <div class="form-group">
                    <label for="csv_url">CSV-URL oder Pfad *</label>
                    <input type="text" id="csv_url" name="csv_url" value="<?= $is_edit ? e($supplier['csv_url']) : '' ?>" required placeholder="https://lieferant.de/export.csv oder /pfad/zur/datei.csv">
                    <small class="text-muted">HTTP(S)-URL oder absoluter Dateipfad auf dem Server</small>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="csv_delimiter">Trennzeichen</label>
                        <select id="csv_delimiter" name="csv_delimiter">
                            <option value="," <?= $is_edit && $supplier['csv_delimiter'] === ',' ? 'selected' : '' ?>>, (Komma)</option>
                            <option value=";" <?= $is_edit && $supplier['csv_delimiter'] === ';' ? 'selected' : '' ?>>; (Semikolon)</option>
                            <option value="\t" <?= $is_edit && $supplier['csv_delimiter'] === "\t" ? 'selected' : '' ?>>Tab</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="csv_encoding">Encoding</label>
                        <select id="csv_encoding" name="csv_encoding">
                            <option value="UTF-8" <?= $is_edit && $supplier['csv_encoding'] === 'UTF-8' ? 'selected' : '' ?>>UTF-8</option>
                            <option value="ISO-8859-1" <?= $is_edit && $supplier['csv_encoding'] === 'ISO-8859-1' ? 'selected' : '' ?>>ISO-8859-1 (Latin-1)</option>
                            <option value="Windows-1252" <?= $is_edit && $supplier['csv_encoding'] === 'Windows-1252' ? 'selected' : '' ?>>Windows-1252</option>
                        </select>
                    </div>
                </div>

                <h2 class="mb-lg" style="margin-top: 2rem;">Spalten-Mapping</h2>
                <p class="text-muted mb-lg">Geben Sie die exakten Spaltennamen aus der CSV-Datei an.</p>

                <div class="form-row">
                    <div class="form-group">
                        <label for="mapping_name">Produktname * (CSV-Spalte)</label>
                        <input type="text" id="mapping_name" name="mapping_name" value="<?= e($column_mapping['name'] ?? '') ?>" required placeholder="z.B. 'name' oder 'Produktbezeichnung'">
                    </div>

                    <div class="form-group">
                        <label for="mapping_sku">SKU/Artikelnummer * (CSV-Spalte)</label>
                        <input type="text" id="mapping_sku" name="mapping_sku" value="<?= e($column_mapping['sku'] ?? '') ?>" required placeholder="z.B. 'sku' oder 'Artikelnummer'">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="mapping_ean">EAN/Barcode (CSV-Spalte)</label>
                        <input type="text" id="mapping_ean" name="mapping_ean" value="<?= e($column_mapping['ean'] ?? '') ?>" placeholder="z.B. 'ean' oder 'Barcode'">
                    </div>

                    <div class="form-group">
                        <label for="mapping_price">Preis * (CSV-Spalte)</label>
                        <input type="text" id="mapping_price" name="mapping_price" value="<?= e($column_mapping['price'] ?? '') ?>" required placeholder="z.B. 'price' oder 'Preis'">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="mapping_stock">Lagerbestand (CSV-Spalte)</label>
                        <input type="text" id="mapping_stock" name="mapping_stock" value="<?= e($column_mapping['stock'] ?? '') ?>" placeholder="z.B. 'stock' oder 'Lagerbestand'">
                    </div>

                    <div class="form-group">
                        <!-- Platzhalter für Symmetrie -->
                    </div>
                </div>

                <div class="form-group">
                    <label for="mapping_description">Beschreibung (CSV-Spalte)</label>
                    <input type="text" id="mapping_description" name="mapping_description" value="<?= e($column_mapping['description'] ?? '') ?>" placeholder="z.B. 'description' oder 'Beschreibung'">
                </div>

                <div class="form-group">
                    <label for="description_filter">Beschreibungs-Filter</label>
                    <textarea id="description_filter" name="description_filter" rows="4" placeholder="Texte/Wörter die aus Beschreibungen entfernt werden sollen (ein String pro Zeile)"><?= $is_edit && $supplier ? e($supplier['description_filter']) : '' ?></textarea>
                    <small class="text-muted">Unerwünschte Texte oder Werbebotschaften entfernen. Ein Text pro Zeile. Groß-/Kleinschreibung wird ignoriert.</small>
                </div>

                <div class="form-group">
                    <label for="mapping_category">Kategorie (CSV-Spalte)</label>
                    <input type="text" id="mapping_category" name="mapping_category" value="<?= e($column_mapping['category'] ?? '') ?>" placeholder="z.B. 'category' oder 'Kategorie'">
                    <small class="text-muted">Optional: CSV-Spalte mit Kategorie-Namen</small>
                </div>

                <h3 style="margin-top: 1.5rem; margin-bottom: 1rem;">Kategorie-Zuordnung</h3>
                <p class="text-muted" style="margin-bottom: 1rem;">Ordnen Sie CSV-Kategorie-Namen zu Shop-Kategorien zu.</p>

                <div id="category-mappings">
                    <?php if (!empty($category_mapping)): ?>
                        <?php foreach ($category_mapping as $csv_name => $shop_id): ?>
                            <div class="form-row" style="margin-bottom: 0.5rem;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <input type="text" name="category_csv_name[]" value="<?= e($csv_name) ?>" placeholder="CSV-Kategorie-Name">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <select name="category_shop_id[]">
                                        <option value="">Shop-Kategorie wählen</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id'] ?>" <?= $shop_id == $cat['id'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="form-row" style="margin-bottom: 0.5rem;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <input type="text" name="category_csv_name[]" placeholder="CSV-Kategorie-Name">
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <select name="category_shop_id[]">
                                    <option value="">Shop-Kategorie wählen</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="button" class="btn btn-outline" onclick="addCategoryMapping()" style="margin-top: 0.5rem;">+ Weitere Zuordnung</button>

                <script>
                function addCategoryMapping() {
                    const container = document.getElementById('category-mappings');
                    const row = document.createElement('div');
                    row.className = 'form-row';
                    row.style.marginBottom = '0.5rem';
                    row.innerHTML = `
                        <div class="form-group" style="margin-bottom: 0;">
                            <input type="text" name="category_csv_name[]" placeholder="CSV-Kategorie-Name">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <select name="category_shop_id[]">
                                <option value="">Shop-Kategorie wählen</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    `;
                    container.appendChild(row);
                }
                </script>

                <h2 class="mb-lg" style="margin-top: 2rem;">Preis-Kalkulation</h2>

                <div class="form-group">
                    <label for="price_markup">Aufschlag in % *</label>
                    <input type="text" id="price_markup" name="price_markup" value="<?= $is_edit ? number_format($supplier['price_markup'], 1, ',', '.') : '20,0' ?>" required placeholder="z.B. 20,5">
                    <small class="text-muted">
                        Preisberechnung: (Lieferanten-Preis × (1 + Aufschlag/100)) → gerundet auf nächste 10er minus 1<br>
                        Beispiel: 40,20 € mit 20% → 48,24 € → 49 €
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="free_shipping" <?= $is_edit && $supplier && $supplier['free_shipping'] ? 'checked' : '' ?>>
                        <span>Versandkostenfrei Deutschland</span>
                    </label>
                    <small class="text-muted">Diese Einstellung wird bei CSV-Import auf die Produkte übertragen</small>
                </div>

                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" <?= $is_edit && $supplier && $supplier['is_active'] ? 'checked' : ($is_edit ? '' : 'checked') ?>>
                        <span>Lieferant ist aktiv</span>
                    </label>
                </div>

                <div class="form-group" style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">
                        <?= $is_edit ? 'Änderungen speichern' : 'Lieferant erstellen' ?>
                    </button>
                    <a href="<?= BASE_URL ?>/admin/suppliers" class="btn btn-outline">Abbrechen</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
