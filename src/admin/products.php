<?php
/**
 * Produktverwaltung (Admin)
 * PC-Wittfoot UG
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();
require_admin();

$db = Database::getInstance();

// Bulk-Delete: Unverknüpfte, inaktive/ausverkaufte Produkte
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'bulk_delete_unused') {
    if (!csrf_verify($_POST['csrf_token'] ?? '')) {
        set_flash('error', 'CSRF-Token ungültig.');
    } else {
        // Produkte finden die gelöscht werden können
        $deletable = $db->query("
            SELECT p.id, p.name, p.image
            FROM products p
            LEFT JOIN order_items oi ON p.id = oi.product_id
            WHERE oi.id IS NULL
            AND (p.is_active = 0 OR p.stock = 0)
        ");

        $deleted_count = 0;
        foreach ($deletable as $product) {
            // Bild löschen
            if (!empty($product['image'])) {
                $image_file = __DIR__ . '/../../uploads/' . $product['image'];
                if (file_exists($image_file)) {
                    unlink($image_file);
                }
            }

            // Produkt löschen
            $db->update("DELETE FROM products WHERE id = :id", [':id' => $product['id']]);
            $deleted_count++;
        }

        set_flash('success', "$deleted_count Produkt(e) gelöscht.");
        redirect(BASE_URL . '/admin/products');
    }
}

// Filter
$source_filter = $_GET['source'] ?? 'all';
$status_filter = $_GET['status'] ?? 'all';
$search = $_GET['search'] ?? '';

// SQL aufbauen
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if ($source_filter !== 'all') {
    $sql .= " AND source = :source";
    $params[':source'] = $source_filter;
}

if ($status_filter === 'active') {
    $sql .= " AND is_active = 1";
} elseif ($status_filter === 'inactive') {
    $sql .= " AND is_active = 0";
} elseif ($status_filter === 'showroom') {
    $sql .= " AND in_showroom = 1";
} elseif ($status_filter === 'low_stock') {
    $sql .= " AND stock <= 5 AND stock > 0";
} elseif ($status_filter === 'out_of_stock') {
    $sql .= " AND stock = 0";
}

if (!empty($search)) {
    $sql .= " AND (name LIKE :search OR sku LIKE :search OR description LIKE :search)";
    $params[':search'] = "%$search%";
}

$sql .= " ORDER BY created_at DESC LIMIT 200";

$products = $db->query($sql, $params);

// Anzahl löschbarer Produkte ermitteln
$deletable_count = $db->querySingle("
    SELECT COUNT(*) as count
    FROM products p
    LEFT JOIN order_items oi ON p.id = oi.product_id
    WHERE oi.id IS NULL
    AND (p.is_active = 0 OR p.stock = 0)
");

$page_title = 'Produktverwaltung | Admin | PC-Wittfoot UG';
$page_description = 'Produkte verwalten';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1>Produktverwaltung</h1>
            <div style="display: flex; gap: 1rem;">
                <a href="<?= BASE_URL ?>/admin/product-edit" class="btn btn-primary">+ Neues Produkt</a>
                <a href="<?= BASE_URL ?>/admin" class="btn btn-outline">← Zurück zum Dashboard</a>
            </div>
        </div>

        <!-- Filter -->
        <div class="card mb-lg">
            <form method="GET" class="form-row">
                <div class="form-group">
                    <label for="source">Quelle</label>
                    <select id="source" name="source" onchange="this.form.submit()">
                        <option value="all" <?= $source_filter === 'all' ? 'selected' : '' ?>>Alle</option>
                        <option value="manual" <?= $source_filter === 'manual' ? 'selected' : '' ?>>Manuell</option>
                        <option value="csv_import" <?= $source_filter === 'csv_import' ? 'selected' : '' ?>>CSV-Import</option>
                        <option value="hellocash" <?= $source_filter === 'hellocash' ? 'selected' : '' ?>>HelloCash</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" onchange="this.form.submit()">
                        <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>Alle</option>
                        <option value="active" <?= $status_filter === 'active' ? 'selected' : '' ?>>Aktiv</option>
                        <option value="inactive" <?= $status_filter === 'inactive' ? 'selected' : '' ?>>Inaktiv</option>
                        <option value="showroom" <?= $status_filter === 'showroom' ? 'selected' : '' ?>>Verfügbar in Oldenburg</option>
                        <option value="low_stock" <?= $status_filter === 'low_stock' ? 'selected' : '' ?>>Niedriger Bestand</option>
                        <option value="out_of_stock" <?= $status_filter === 'out_of_stock' ? 'selected' : '' ?>>Ausverkauft</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="search">Suche</label>
                    <input type="text" id="search" name="search" placeholder="Name, SKU, Beschreibung..." value="<?= e($search) ?>">
                </div>

                <div class="form-group" style="align-self: flex-end;">
                    <button type="submit" class="btn btn-primary">Filtern</button>
                    <?php if ($source_filter !== 'all' || $status_filter !== 'all' || !empty($search)): ?>
                        <a href="<?= BASE_URL ?>/admin/products" class="btn btn-outline">Zurücksetzen</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Bulk-Delete-Option -->
        <?php if ($deletable_count && $deletable_count['count'] > 0): ?>
            <div class="card mb-lg" style="border-color: var(--color-warning); background-color: #fffbf0;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h3 style="margin: 0 0 0.5rem 0; color: var(--color-warning);">🗑️ Reorganisierung</h3>
                        <p style="margin: 0;">
                            <strong><?= $deletable_count['count'] ?></strong> Produkt(e) können gelöscht werden
                            <br><small class="text-muted">(Inaktiv oder ausverkauft, ohne Bestellungen)</small>
                        </p>
                    </div>
                    <form method="POST" onsubmit="return confirm('<?= $deletable_count['count'] ?> Produkt(e) wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden!');">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <input type="hidden" name="action" value="bulk_delete_unused">
                        <button type="submit" class="btn btn-outline" style="color: var(--color-warning); border-color: var(--color-warning);">
                            Alle löschen
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- Produkte -->
        <h2 class="mb-lg">Produkte (<?= count($products) ?>)</h2>

        <?php if (empty($products)): ?>
            <div class="card">
                <p class="text-muted">Keine Produkte gefunden.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 grid-cols-md-2 grid-cols-lg-3 gap-lg">
                <?php foreach ($products as $product):
                    // Quellen-Labels
                    $source_labels = [
                        'manual' => '✏️ Manuell',
                        'csv_import' => '📄 CSV-Import',
                        'hellocash' => '💳 HelloCash'
                    ];
                    $source_label = $source_labels[$product['source']] ?? $product['source'];

                    // Status-Badge
                    $status_badge = '';
                    if (!$product['is_active']) {
                        $status_badge = '<span style="color: var(--color-error);">⭕ Inaktiv</span>';
                    } elseif ($product['in_showroom']) {
                        $status_badge = '<span style="color: var(--color-success);">📍 Oldenburg</span>';
                    } elseif ($product['stock'] == 0) {
                        $status_badge = '<span style="color: var(--color-error);">📭 Ausverkauft</span>';
                    } elseif ($product['stock'] <= 5) {
                        $status_badge = '<span style="color: var(--color-warning);">⚠️ Niedriger Bestand</span>';
                    }

                    // Lieferanten-Info
                    $supplier_info = '';
                    if ($product['source'] === 'csv_import' && !empty($product['supplier_name'])) {
                        $supplier_info = '<small class="text-muted">Lieferant: ' . e($product['supplier_name']) . '</small><br>';
                        if ($product['supplier_stock'] > 0) {
                            $supplier_info .= '<small class="text-muted">Lager Lieferant: ' . $product['supplier_stock'] . '</small>';
                        }
                    }
                ?>
                    <div class="card">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                            <div style="flex: 1;">
                                <h3 style="margin: 0; font-size: 1.1rem;"><?= e($product['name']) ?></h3>
                                <small class="text-muted">SKU: <?= e($product['sku']) ?></small>
                            </div>
                            <?php if ($status_badge): ?>
                                <div style="font-size: 1.1rem; margin-left: 0.5rem;"><?= $status_badge ?></div>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($product['image'])): ?>
                            <div style="margin-bottom: 1rem;">
                                <img src="<?= upload($product['image']) ?>" alt="<?= e($product['name']) ?>" style="width: 100%; height: 150px; object-fit: cover; border-radius: 4px;">
                            </div>
                        <?php endif; ?>

                        <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span class="text-muted">Preis:</span>
                                <strong style="font-size: 1.25rem; color: var(--color-primary);"><?= format_price($product['price']) ?></strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                                <span class="text-muted">Lagerbestand:</span>
                                <span><strong><?= $product['stock'] ?></strong> Stück</span>
                            </div>
                            <?php if ($product['free_shipping']): ?>
                                <div style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--color-success);">
                                    📦 Versandkostenfrei Deutschland
                                </div>
                            <?php endif; ?>
                        </div>

                        <div style="margin-bottom: 1rem; font-size: 0.9rem;">
                            <div style="margin-bottom: 0.25rem;">
                                <strong>Quelle:</strong> <?= $source_label ?>
                            </div>
                            <?php if ($supplier_info): ?>
                                <div style="margin-top: 0.5rem;">
                                    <?= $supplier_info ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <a href="<?= BASE_URL ?>/admin/product-edit?id=<?= $product['id'] ?>" class="btn btn-primary btn-block">
                            Bearbeiten →
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
