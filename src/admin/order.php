<?php
/**
 * Bestellungs-Details (Admin)
 * PC-Wittfoot UG
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();
require_admin();

$db = Database::getInstance();

// Order ID aus URL
$order_id = (int)($_GET['id'] ?? 0);

if ($order_id === 0) {
    set_flash('error', 'Bestellung nicht gefunden.');
    redirect(BASE_URL . '/admin/orders');
}

// Bestellung laden
$order = $db->querySingle("SELECT * FROM orders WHERE id = :id", [':id' => $order_id]);

if (!$order) {
    set_flash('error', 'Bestellung nicht gefunden.');
    redirect(BASE_URL . '/admin/orders');
}

// Bestellpositionen laden
$items = $db->query("SELECT * FROM order_items WHERE order_id = :id", [':id' => $order_id]);

// Status ändern
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    if (!csrf_verify($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF-Token ungültig.';
    } else {
        $new_status = $_POST['order_status'] ?? '';
        $db->update("UPDATE orders SET order_status = :status WHERE id = :id", [
            ':status' => $new_status,
            ':id' => $order_id
        ]);

        set_flash('success', 'Status aktualisiert');
        redirect(BASE_URL . '/admin/order/' . $order_id);
    }
}

$page_title = 'Bestellung #' . e($order['order_number']) . ' | Admin | PC-Wittfoot UG';
$page_description = 'Bestellungs-Details';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1>Bestellung #<?= e($order['order_number']) ?></h1>
            <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-outline">← Zurück zur Übersicht</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-error mb-lg"><?= e($error) ?></div>
        <?php endif; ?>

        <div class="grid grid-cols-1 grid-cols-lg-3 gap-lg">
            <!-- Hauptbereich -->
            <div style="grid-column: span 2;">
                <!-- Bestellpositionen -->
                <div class="card mb-lg">
                    <h2 class="mb-lg">Bestellpositionen</h2>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produkt</th>
                                <th>Menge</th>
                                <th>Einzelpreis</th>
                                <th>Gesamt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <strong><?= e($item['product_name']) ?></strong><br>
                                        <small class="text-muted">Produkt-ID: <?= $item['product_id'] ?></small>
                                    </td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td><?= format_price($item['unit_price']) ?></td>
                                    <td><strong><?= format_price($item['total_price']) ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Zwischensumme:</strong></td>
                                <td><strong><?= format_price($order['subtotal']) ?></strong></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right"><strong>MwSt (19%):</strong></td>
                                <td><strong><?= format_price($order['tax']) ?></strong></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Gesamt:</strong></td>
                                <td><strong style="font-size: 1.25rem;"><?= format_price($order['total']) ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Kundendaten -->
                <div class="card mb-lg">
                    <h2 class="mb-lg">Kundendaten</h2>

                    <div class="grid grid-cols-1 grid-cols-md-2 gap-md">
                        <div>
                            <h3 style="font-size: 1rem; margin-bottom: 0.5rem;">Rechnungsadresse</h3>
                            <p>
                                <?php if (!empty($order['customer_company'])): ?>
                                    <strong><?= e($order['customer_company']) ?></strong><br>
                                <?php endif; ?>
                                <?= e($order['customer_firstname']) ?> <?= e($order['customer_lastname']) ?><br>
                                <?= e($order['customer_street']) ?> <?= e($order['customer_housenumber']) ?><br>
                                <?= e($order['customer_zip']) ?> <?= e($order['customer_city']) ?><br>
                                <br>
                                E-Mail: <a href="mailto:<?= e($order['customer_email']) ?>"><?= e($order['customer_email']) ?></a><br>
                                <?php if (!empty($order['customer_phone'])): ?>
                                    Telefon: <?= e($order['customer_phone']) ?><br>
                                <?php endif; ?>
                            </p>
                        </div>

                        <?php if (!empty($order['shipping_firstname']) && !empty($order['shipping_lastname'])): ?>
                            <div>
                                <h3 style="font-size: 1rem; margin-bottom: 0.5rem;">Lieferadresse</h3>
                                <p>
                                    <?= e($order['shipping_firstname']) ?> <?= e($order['shipping_lastname']) ?><br>
                                    <?= e($order['shipping_street']) ?> <?= e($order['shipping_housenumber']) ?><br>
                                    <?= e($order['shipping_zip']) ?> <?= e($order['shipping_city']) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($order['order_notes'])): ?>
                        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                            <h3 style="font-size: 1rem; margin-bottom: 0.5rem;">Anmerkungen</h3>
                            <p><?= nl2br(e($order['order_notes'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Status -->
                <div class="card mb-lg">
                    <h3 class="mb-md">Bestellstatus</h3>

                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <input type="hidden" name="action" value="update_status">

                        <div class="form-group">
                            <select name="order_status" class="form-control" onchange="this.form.submit()">
                                <option value="pending" <?= $order['order_status'] === 'pending' ? 'selected' : '' ?>>⏳ Ausstehend</option>
                                <option value="new" <?= $order['order_status'] === 'new' ? 'selected' : '' ?>>🆕 Neu</option>
                                <option value="processing" <?= $order['order_status'] === 'processing' ? 'selected' : '' ?>>⚙️ In Bearbeitung</option>
                                <option value="shipped" <?= $order['order_status'] === 'shipped' ? 'selected' : '' ?>>📦 Versandt</option>
                                <option value="completed" <?= $order['order_status'] === 'completed' ? 'selected' : '' ?>>✅ Abgeschlossen</option>
                                <option value="cancelled" <?= $order['order_status'] === 'cancelled' ? 'selected' : '' ?>>❌ Storniert</option>
                            </select>
                        </div>
                    </form>
                </div>

                <!-- Bestellinformationen -->
                <div class="card mb-lg">
                    <h3 class="mb-md">Bestellinformationen</h3>

                    <table style="width: 100%; font-size: 0.9rem;">
                        <tr>
                            <td><strong>Datum:</strong></td>
                            <td><?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Zahlungsart:</strong></td>
                            <td>
                                <?php
                                $payment_labels = [
                                    'prepayment' => 'Vorkasse',
                                    'paypal' => 'PayPal'
                                ];
                                echo $payment_labels[$order['payment_method']] ?? $order['payment_method'];
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Lieferart:</strong></td>
                            <td>
                                <?php
                                $delivery_labels = [
                                    'billing' => 'An Rechnungsadresse',
                                    'pickup' => 'Abholung',
                                    'shipping' => 'An andere Adresse'
                                ];
                                echo $delivery_labels[$order['delivery_method']] ?? $order['delivery_method'];
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- HelloCash Integration -->
                <?php if (!empty($order['hellocash_invoice_link'])): ?>
                    <div class="card">
                        <h3 class="mb-md">HelloCash Rechnung</h3>

                        <p style="font-size: 0.9rem; margin-bottom: 1rem;">
                            <strong>Invoice-ID:</strong> <?= e($order['hellocash_invoice_id']) ?>
                        </p>

                        <a href="<?= e($order['hellocash_invoice_link']) ?>" target="_blank" class="btn btn-primary btn-block">
                            Rechnung anzeigen →
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
