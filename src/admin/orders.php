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
        <div class="card">
            <h2 class="mb-lg">Bestellungen (<?= count($orders) ?>)</h2>

            <?php if (empty($orders)): ?>
                <p class="text-muted">Keine Bestellungen gefunden.</p>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Bestellnr.</th>
                                <th>Datum</th>
                                <th>Kunde</th>
                                <th>Gesamt</th>
                                <th>Zahlungsart</th>
                                <th>Status</th>
                                <th>Aktionen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><strong><?= e($order['order_number']) ?></strong></td>
                                    <td><?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></td>
                                    <td>
                                        <?= e($order['customer_name']) ?><br>
                                        <small class="text-muted"><?= e($order['customer_email']) ?></small>
                                    </td>
                                    <td><strong><?= format_price($order['total']) ?></strong></td>
                                    <td>
                                        <?php
                                        $payment_labels = [
                                            'prepayment' => 'Vorkasse',
                                            'paypal' => 'PayPal'
                                        ];
                                        echo $payment_labels[$order['payment_method']] ?? $order['payment_method'];
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $status_labels = [
                                            'pending' => '⏳ Ausstehend',
                                            'new' => '🆕 Neu',
                                            'processing' => '⚙️ In Bearbeitung',
                                            'shipped' => '📦 Versandt',
                                            'completed' => '✅ Abgeschlossen',
                                            'cancelled' => '❌ Storniert'
                                        ];
                                        echo $status_labels[$order['order_status']] ?? $order['order_status'];
                                        ?>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/admin/order/<?= $order['id'] ?>" class="btn btn-sm btn-outline">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
