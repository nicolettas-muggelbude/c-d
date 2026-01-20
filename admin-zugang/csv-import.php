<?php
/**
 * CSV-Import durchführen (Admin)
 * PC-Wittfoot UG
 */

require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . '/../core/CSVImporter.php';
start_session_safe();
require_admin();

$db = Database::getInstance();

// Lieferant-ID aus URL
$supplier_id = isset($_GET['supplier_id']) ? (int)$_GET['supplier_id'] : 0;

if ($supplier_id === 0) {
    set_flash('error', 'Kein Lieferant ausgewählt.');
    redirect(BASE_URL . '/admin/suppliers');
}

// Lieferant laden
$supplier = $db->querySingle("SELECT * FROM suppliers WHERE id = :id", [':id' => $supplier_id]);
if (!$supplier) {
    set_flash('error', 'Lieferant nicht gefunden.');
    redirect(BASE_URL . '/admin/suppliers');
}

// Import durchführen
$import_result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'start_import') {
    if (!csrf_verify($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF-Token ungültig.';
    } else {
        try {
            $csv_path = $supplier['csv_url'];

            // Falls URL, herunterladen
            if (preg_match('/^https?:\/\//i', $csv_path)) {
                $temp_file = tempnam(sys_get_temp_dir(), 'csv_');
                $csv_content = file_get_contents($csv_path);
                if ($csv_content === false) {
                    throw new Exception("CSV-Datei konnte nicht heruntergeladen werden");
                }
                file_put_contents($temp_file, $csv_content);
                $csv_path = $temp_file;
            }

            // Import durchführen
            $importer = new CSVImporter($supplier_id);
            $import_result = $importer->import($csv_path);

            // Temp-Datei löschen
            if (isset($temp_file) && file_exists($temp_file)) {
                unlink($temp_file);
            }

        } catch (Exception $e) {
            $error = 'Import-Fehler: ' . $e->getMessage();
        }
    }
}

// Import-Logs laden
$logs = $db->query("
    SELECT * FROM product_import_logs
    WHERE supplier_id = :supplier_id
    ORDER BY created_at DESC
    LIMIT 10
", [':supplier_id' => $supplier_id]);

$page_title = 'CSV-Import: ' . $supplier['name'] . ' | Admin | PC-Wittfoot UG';
$page_description = 'CSV-Import durchführen';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container" style="max-width: 900px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1>CSV-Import: <?= e($supplier['name']) ?></h1>
            <a href="<?= BASE_URL ?>/admin/suppliers" class="btn btn-outline">← Zurück zur Übersicht</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-error mb-lg"><?= e($error) ?></div>
        <?php endif; ?>

        <?php if ($import_result): ?>
            <?php if ($import_result['success']): ?>
                <div class="alert alert-success mb-lg">
                    <h3 style="margin: 0 0 0.5rem 0;">✅ Import erfolgreich</h3>
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-top: 1rem;">
                        <div>
                            <strong style="font-size: 1.5rem; color: var(--color-success);"><?= $import_result['stats']['imported'] ?></strong>
                            <br><small>Neu importiert</small>
                        </div>
                        <div>
                            <strong style="font-size: 1.5rem;"><?= $import_result['stats']['updated'] ?></strong>
                            <br><small>Aktualisiert</small>
                        </div>
                        <div>
                            <strong style="font-size: 1.5rem;"><?= $import_result['stats']['skipped'] ?></strong>
                            <br><small>Übersprungen</small>
                        </div>
                        <div>
                            <strong style="font-size: 1.5rem; color: var(--color-error);"><?= $import_result['stats']['errors'] ?></strong>
                            <br><small>Fehler</small>
                        </div>
                    </div>
                    <p style="margin-top: 1rem; margin-bottom: 0;">
                        <small>Dauer: <?= $import_result['duration'] ?? 0 ?> Sekunden</small>
                    </p>
                </div>

                <?php if (!empty($import_result['errors'])): ?>
                    <div class="alert alert-warning mb-lg">
                        <h4>⚠️ Fehler beim Import:</h4>
                        <ul style="margin: 0.5rem 0 0 1.5rem;">
                            <?php foreach (array_slice($import_result['errors'], 0, 10) as $err): ?>
                                <li><small><?= e($err) ?></small></li>
                            <?php endforeach; ?>
                            <?php if (count($import_result['errors']) > 10): ?>
                                <li><small>... und <?= count($import_result['errors']) - 10 ?> weitere Fehler</small></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-error mb-lg">
                    <h3 style="margin: 0;">❌ Import fehlgeschlagen</h3>
                    <p style="margin: 0.5rem 0 0 0;"><?= e($import_result['error']) ?></p>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Import-Konfiguration -->
        <div class="card mb-lg">
            <h2 class="mb-lg">Konfiguration</h2>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; font-size: 0.9rem;">
                <div>
                    <strong>CSV-Quelle:</strong><br>
                    <span class="text-muted"><?= e($supplier['csv_url']) ?></span>
                </div>
                <div>
                    <strong>Aufschlag:</strong><br>
                    <span class="text-muted"><?= number_format($supplier['price_markup'], 1, ',', '.') ?>%</span>
                </div>
                <div>
                    <strong>Trennzeichen:</strong><br>
                    <span class="text-muted"><?= $supplier['csv_delimiter'] === ',' ? 'Komma' : ($supplier['csv_delimiter'] === ';' ? 'Semikolon' : 'Tab') ?></span>
                </div>
                <div>
                    <strong>Encoding:</strong><br>
                    <span class="text-muted"><?= e($supplier['csv_encoding']) ?></span>
                </div>
            </div>

            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                <strong>Spalten-Mapping:</strong>
                <div style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--color-text-muted);">
                    <?php
                    $mapping = json_decode($supplier['column_mapping'], true);
                    if ($mapping) {
                        foreach ($mapping as $field => $csv_column) {
                            echo e(ucfirst($field)) . ': <code>' . e($csv_column) . '</code> &nbsp;&nbsp; ';
                        }
                    }
                    ?>
                </div>
            </div>

            <div style="margin-top: 1.5rem;">
                <a href="<?= BASE_URL ?>/admin/supplier-edit?id=<?= $supplier['id'] ?>" class="btn btn-outline">
                    ✏️ Konfiguration bearbeiten
                </a>
            </div>
        </div>

        <!-- Import starten -->
        <div class="card mb-lg" style="border-color: var(--color-primary);">
            <h2 class="mb-lg">Import durchführen</h2>
            <p class="text-muted">
                Importiert Produkte aus der CSV-Datei. Bestehende Produkte (gleiche SKU) werden aktualisiert, neue Produkte werden angelegt.
            </p>

            <form method="POST" onsubmit="return confirm('CSV-Import jetzt starten?');">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="action" value="start_import">
                <button type="submit" class="btn btn-primary btn-lg">
                    📥 Import jetzt starten
                </button>
            </form>
        </div>

        <!-- Import-Historie -->
        <?php if (!empty($logs)): ?>
            <div class="card">
                <h2 class="mb-lg">Import-Historie</h2>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Datum</th>
                            <th>Status</th>
                            <th>Neu</th>
                            <th>Aktualisiert</th>
                            <th>Fehler</th>
                            <th>Dauer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?= date('d.m.Y H:i', strtotime($log['created_at'])) ?></td>
                                <td>
                                    <?php
                                    $status_icons = [
                                        'completed' => '✅',
                                        'failed' => '❌',
                                        'running' => '⏳'
                                    ];
                                    echo $status_icons[$log['status']] ?? '?';
                                    ?>
                                </td>
                                <td><?= $log['imported_count'] ?></td>
                                <td><?= $log['updated_count'] ?></td>
                                <td><?= $log['error_count'] ?></td>
                                <td><?= $log['duration_seconds'] ? $log['duration_seconds'] . 's' : '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
