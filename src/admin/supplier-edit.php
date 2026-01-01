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
        $price_markup = (float)($_POST['price_markup'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        // Spalten-Mapping
        $column_mapping = [];
        if (!empty($_POST['mapping_name'])) $column_mapping['name'] = $_POST['mapping_name'];
        if (!empty($_POST['mapping_sku'])) $column_mapping['sku'] = $_POST['mapping_sku'];
        if (!empty($_POST['mapping_price'])) $column_mapping['price'] = $_POST['mapping_price'];
        if (!empty($_POST['mapping_stock'])) $column_mapping['stock'] = $_POST['mapping_stock'];
        if (!empty($_POST['mapping_description'])) $column_mapping['description'] = $_POST['mapping_description'];

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
                        price_markup = :price_markup,
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
                    ':price_markup' => $price_markup,
                    ':is_active' => $is_active,
                    ':id' => $supplier_id
                ]);

                set_flash('success', 'Lieferant aktualisiert.');
            } else {
                // Insert
                $db->insert("
                    INSERT INTO suppliers (
                        name, description, csv_url, csv_delimiter, csv_encoding,
                        column_mapping, price_markup, is_active, created_at
                    ) VALUES (
                        :name, :description, :csv_url, :csv_delimiter, :csv_encoding,
                        :column_mapping, :price_markup, :is_active, NOW()
                    )
                ", [
                    ':name' => $name,
                    ':description' => $description,
                    ':csv_url' => $csv_url,
                    ':csv_delimiter' => $csv_delimiter,
                    ':csv_encoding' => $csv_encoding,
                    ':column_mapping' => $column_mapping_json,
                    ':price_markup' => $price_markup,
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
                        <label for="mapping_price">Preis * (CSV-Spalte)</label>
                        <input type="text" id="mapping_price" name="mapping_price" value="<?= e($column_mapping['price'] ?? '') ?>" required placeholder="z.B. 'price' oder 'Preis'">
                    </div>

                    <div class="form-group">
                        <label for="mapping_stock">Lagerbestand (CSV-Spalte)</label>
                        <input type="text" id="mapping_stock" name="mapping_stock" value="<?= e($column_mapping['stock'] ?? '') ?>" placeholder="z.B. 'stock' oder 'Lagerbestand'">
                    </div>
                </div>

                <div class="form-group">
                    <label for="mapping_description">Beschreibung (CSV-Spalte)</label>
                    <input type="text" id="mapping_description" name="mapping_description" value="<?= e($column_mapping['description'] ?? '') ?>" placeholder="z.B. 'description' oder 'Beschreibung'">
                </div>

                <h2 class="mb-lg" style="margin-top: 2rem;">Preis-Kalkulation</h2>

                <div class="form-group">
                    <label for="price_markup">Aufschlag in % *</label>
                    <input type="number" id="price_markup" name="price_markup" step="0.01" min="0" value="<?= $is_edit ? $supplier['price_markup'] : '20' ?>" required>
                    <small class="text-muted">Verkaufspreis = Lieferanten-Preis × (1 + Aufschlag/100)</small>
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
