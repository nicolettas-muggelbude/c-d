<?php
/**
 * Lieferanten-Verwaltung (Admin)
 * PC-Wittfoot UG
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();
require_admin();

$db = Database::getInstance();

// Lieferanten laden mit Import-Statistiken
$suppliers = $db->query("
    SELECT
        s.*,
        COUNT(p.id) as product_count,
        (SELECT COUNT(*) FROM product_import_logs WHERE supplier_id = s.id) as import_count,
        (SELECT created_at FROM product_import_logs WHERE supplier_id = s.id ORDER BY created_at DESC LIMIT 1) as last_log_date
    FROM suppliers s
    LEFT JOIN products p ON s.id = p.supplier_id
    GROUP BY s.id
    ORDER BY s.created_at DESC
");

$page_title = 'Lieferanten | Admin | PC-Wittfoot UG';
$page_description = 'Lieferanten verwalten';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1>Lieferanten-Verwaltung</h1>
            <div style="display: flex; gap: 1rem;">
                <a href="<?= BASE_URL ?>/admin/supplier-edit" class="btn btn-primary">+ Neuer Lieferant</a>
                <a href="<?= BASE_URL ?>/admin" class="btn btn-outline">← Zurück zum Dashboard</a>
            </div>
        </div>

        <?php if (empty($suppliers)): ?>
            <div class="card">
                <p class="text-muted">Noch keine Lieferanten angelegt.</p>
                <a href="<?= BASE_URL ?>/admin/supplier-edit" class="btn btn-primary">Ersten Lieferant anlegen</a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 grid-cols-md-2 gap-lg">
                <?php foreach ($suppliers as $supplier): ?>
                    <div class="card" style="height: fit-content;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                            <div>
                                <h3 style="margin: 0;"><?= e($supplier['name']) ?></h3>
                                <?php if (!$supplier['is_active']): ?>
                                    <span style="color: var(--color-error); font-size: 0.9rem;">⭕ Inaktiv</span>
                                <?php endif; ?>
                            </div>
                            <span style="font-size: 1.5rem;">📦</span>
                        </div>

                        <?php if ($supplier['description']): ?>
                            <p style="margin-bottom: 1rem; color: var(--color-text-muted);">
                                <?= e(truncate($supplier['description'], 100)) ?>
                            </p>
                        <?php endif; ?>

                        <div style="margin-bottom: 1rem; padding: 1rem; background-color: var(--color-background); border-radius: 4px; font-size: 0.9rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span>Produkte:</span>
                                <strong><?= $supplier['product_count'] ?></strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span>Importe:</span>
                                <strong><?= $supplier['import_count'] ?></strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span>Aufschlag:</span>
                                <strong><?= number_format($supplier['price_markup'], 2) ?>%</strong>
                            </div>
                            <?php if ($supplier['last_import_at']): ?>
                                <div style="display: flex; justify-content: space-between;">
                                    <span>Letzter Import:</span>
                                    <strong><?= date('d.m.Y H:i', strtotime($supplier['last_import_at'])) ?></strong>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div style="display: flex; gap: 0.5rem;">
                            <a href="<?= BASE_URL ?>/admin/supplier-edit?id=<?= $supplier['id'] ?>" class="btn btn-outline" style="flex: 1;">
                                ✏️ Bearbeiten
                            </a>
                            <a href="<?= BASE_URL ?>/admin/csv-import?supplier_id=<?= $supplier['id'] ?>" class="btn btn-primary" style="flex: 1;">
                                📥 Import
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
