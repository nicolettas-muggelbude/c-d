<?php
/**
 * Bestellungen-Übersicht (Admin)
 * PC-Wittfoot UG
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();
require_admin();

$db = Database::getInstance();

// Filter
$status_filter = $_GET['status'] ?? 'all';
$search = $_GET['search'] ?? '';

// SQL aufbauen
$sql = "SELECT * FROM orders WHERE 1=1";
$params = [];

if ($status_filter !== 'all') {
    $sql .= " AND order_status = :status";
    $params[':status'] = $status_filter;
}

if (!empty($search)) {
    $sql .= " AND (order_number LIKE :search OR customer_name LIKE :search OR customer_email LIKE :search)";
    $params[':search'] = "%$search%";
}

$sql .= " ORDER BY created_at DESC LIMIT 100";

$orders = $db->query($sql, $params);

$page_title = 'Bestellungen | Admin | PC-Wittfoot UG';
$page_description = 'Bestellungen verwalten';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1>Bestellungen</h1>
            <a href="<?= BASE_URL ?>/admin" class="btn btn-outline">← Zurück zum Dashboard</a>
        </div>

        <!-- Filter -->
        <div class="card mb-lg">
            <form method="GET" class="form-row">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" onchange="this.form.submit()">
                        <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>Alle</option>
                        <option value="pending" <?= $status_filter === 'pending' ? 'selected' : '' ?>>Ausstehend</option>
                        <option value="new" <?= $status_filter === 'new' ? 'selected' : '' ?>>Neu</option>
                        <option value="processing" <?= $status_filter === 'processing' ? 'selected' : '' ?>>In Bearbeitung</option>
                        <option value="shipped" <?= $status_filter === 'shipped' ? 'selected' : '' ?>>Versandt</option>
                        <option value="completed" <?= $status_filter === 'completed' ? 'selected' : '' ?>>Abgeschlossen</option>
                        <option value="cancelled" <?= $status_filter === 'cancelled' ? 'selected' : '' ?>>Storniert</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="search">Suche</label>
                    <input type="text" id="search" name="search" placeholder="Bestellnr., Name, E-Mail..." value="<?= e($search) ?>">
                </div>

                <div class="form-group" style="align-self: flex-end;">
                    <button type="submit" class="btn btn-primary">Filtern</button>
                    <?php if ($status_filter !== 'all' || !empty($search)): ?>
                        <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-outline">Zurücksetzen</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Bestellungen -->
        <h2 class="mb-lg">Bestellungen (<?= count($orders) ?>)</h2>

        <?php if (empty($orders)): ?>
            <div class="card">
                <p class="text-muted">Keine Bestellungen gefunden.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 grid-cols-md-2 grid-cols-lg-3 gap-lg">
                <?php foreach ($orders as $order):
                    // Status-Labels
                    $status_labels = [
                        'pending' => '⏳ Ausstehend',
                        'new' => '🆕 Neu',
                        'processing' => '⚙️ In Bearbeitung',
                        'shipped' => '📦 Versandt',
                        'completed' => '✅ Abgeschlossen',
                        'cancelled' => '❌ Storniert'
                    ];
                    $status_label = $status_labels[$order['order_status']] ?? $order['order_status'];

                    // Zahlungsart-Labels
                    $payment_labels = [
                        'prepayment' => 'Vorkasse',
                        'paypal' => 'PayPal'
                    ];
                    $payment_label = $payment_labels[$order['payment_method']] ?? $order['payment_method'];
                ?>
                    <div class="card">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                            <div>
                                <h3 style="margin: 0; font-size: 1.1rem;"><?= e($order['order_number']) ?></h3>
                                <small class="text-muted"><?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></small>
                            </div>
                            <span style="font-size: 1.25rem;"><?= $status_label ?></span>
                        </div>

                        <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                            <strong><?= e($order['customer_name']) ?></strong><br>
                            <small class="text-muted"><?= e($order['customer_email']) ?></small>
                        </div>

                        <div style="margin-bottom: 1rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span class="text-muted">Gesamt:</span>
                                <strong style="font-size: 1.25rem; color: var(--color-primary);"><?= format_price($order['total']) ?></strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                                <span class="text-muted">Zahlung:</span>
                                <span><?= $payment_label ?></span>
                            </div>
                        </div>

                        <a href="<?= BASE_URL ?>/admin/order/<?= $order['id'] ?>" class="btn btn-primary btn-block">
                            Details anzeigen →
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
