<?php
/**
 * Bestellbestätigung
 */

$db = Database::getInstance();

// Bestellnummer aus URL
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$order_id) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

// Bestellung laden
$order = $db->querySingle("
    SELECT * FROM orders WHERE id = :id
", [':id' => $order_id]);

if (!$order) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

// Bestellpositionen laden
$order_items = $db->query("
    SELECT * FROM order_items WHERE order_id = :order_id
", [':order_id' => $order_id]);

$page_title = 'Bestellbestätigung | PC-Wittfoot UG';
$page_description = 'Ihre Bestellung wurde erfolgreich aufgegeben';
$current_page = 'shop';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container" style="max-width: 800px;">
        <div class="text-center mb-xl">
            <div style="font-size: 5rem; color: var(--color-success);">✓</div>
            <h1>Vielen Dank für Ihre Bestellung!</h1>
            <p class="lead">
                Bestellnummer: <strong>#<?= e($order_id) ?></strong>
            </p>
        </div>

        <div class="alert alert-success">
            <p><strong>Ihre Bestellung wurde erfolgreich aufgenommen.</strong></p>
            <p>
                Wir haben Ihre Bestellung erhalten und werden sie schnellstmöglich bearbeiten.
                Sie erhalten in Kürze eine Bestätigung per E-Mail an
                <strong><?= e($order['customer_email']) ?></strong>.
            </p>
        </div>

        <!-- Bestelldetails -->
        <div class="card mb-lg">
            <h2>Bestelldetails</h2>

            <table class="order-details-table">
                <tr>
                    <th>Bestellnummer:</th>
                    <td>#<?= e($order_id) ?></td>
                </tr>
                <tr>
                    <th>Datum:</th>
                    <td><?= format_datetime($order['created_at']) ?> Uhr</td>
                </tr>
                <tr>
                    <th>Status:</th>
                    <td><span class="badge warning">In Bearbeitung</span></td>
                </tr>
            </table>
        </div>

        <!-- Kundendaten -->
        <div class="card mb-lg">
            <h2>Kundendaten</h2>

            <table class="order-details-table">
                <tr>
                    <th>Name:</th>
                    <td><?= e($order['customer_firstname']) ?> <?= e($order['customer_lastname']) ?></td>
                </tr>
                <?php if ($order['customer_company']): ?>
                <tr>
                    <th>Firma:</th>
                    <td><?= e($order['customer_company']) ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <th>E-Mail:</th>
                    <td><?= e($order['customer_email']) ?></td>
                </tr>
                <?php if ($order['customer_phone']): ?>
                <tr>
                    <th>Telefon:</th>
                    <td><?= e($order['customer_phone']) ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <th>Adresse:</th>
                    <td>
                        <?= e($order['customer_street']) ?> <?= e($order['customer_housenumber']) ?><br>
                        <?= e($order['customer_zip']) ?> <?= e($order['customer_city']) ?>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Lieferung -->
        <div class="card mb-lg">
            <h2>Lieferung</h2>

            <table class="order-details-table">
                <tr>
                    <th>Lieferart:</th>
                    <td>
                        <?php if ($order['delivery_method'] === 'pickup'): ?>
                            Abholung im Ladengeschäft
                        <?php else: ?>
                            Versand
                        <?php endif; ?>
                    </td>
                </tr>
                <?php if ($order['delivery_method'] === 'shipping'): ?>
                <tr>
                    <th>Lieferadresse:</th>
                    <td>
                        <?= e($order['shipping_firstname']) ?> <?= e($order['shipping_lastname']) ?><br>
                        <?= e($order['shipping_street']) ?> <?= e($order['shipping_housenumber']) ?><br>
                        <?= e($order['shipping_zip']) ?> <?= e($order['shipping_city']) ?>
                    </td>
                </tr>
                <?php endif; ?>
            </table>
        </div>

        <!-- Zahlungsart -->
        <div class="card mb-lg">
            <h2>Zahlung</h2>

            <table class="order-details-table">
                <tr>
                    <th>Zahlungsart:</th>
                    <td>
                        <?php
                        $payment_methods = [
                            'prepayment' => 'Vorkasse / Überweisung',
                            'paypal' => 'PayPal',
                            'cash' => 'Barzahlung bei Abholung'
                        ];
                        echo e($payment_methods[$order['payment_method']] ?? $order['payment_method']);
                        ?>
                    </td>
                </tr>
            </table>

            <?php if ($order['payment_method'] === 'prepayment'): ?>
                <div class="alert alert-info mt-md">
                    <h3>Überweisungsdaten</h3>
                    <p>
                        Bitte überweisen Sie den Betrag auf folgendes Konto:<br><br>
                        <strong>Kontoinhaber:</strong> PC-Wittfoot UG<br>
                        <strong>IBAN:</strong> DE12 3456 7890 1234 5678 90<br>
                        <strong>BIC:</strong> GENODEF1XXX<br>
                        <strong>Verwendungszweck:</strong> Bestellung #<?= e($order_id) ?><br>
                        <strong>Betrag:</strong> <?= format_price($order['total']) ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Bestellte Artikel -->
        <div class="card mb-lg">
            <h2>Bestellte Artikel</h2>

            <table class="order-items-table">
                <thead>
                    <tr>
                        <th>Artikel</th>
                        <th>Anzahl</th>
                        <th>Preis</th>
                        <th>Gesamt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_items as $item): ?>
                        <tr>
                            <td><?= e($item['product_name']) ?></td>
                            <td><?= e($item['quantity']) ?></td>
                            <td><?= format_price($item['unit_price']) ?></td>
                            <td><strong><?= format_price($item['total_price']) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Zwischensumme (netto):</strong></td>
                        <td><strong><?= format_price($order['subtotal']) ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right"><strong>MwSt (19%):</strong></td>
                        <td><strong><?= format_price($order['tax']) ?></strong></td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="3" class="text-right"><strong>Gesamt (brutto):</strong></td>
                        <td><strong class="price"><?= format_price($order['total']) ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Weitere Schritte -->
        <div class="alert alert-info">
            <h3>Wie geht es weiter?</h3>
            <ol>
                <li>Sie erhalten eine Bestellbestätigung per E-Mail</li>
                <?php if ($order['payment_method'] === 'prepayment'): ?>
                    <li>Bitte überweisen Sie den Betrag auf unser Konto</li>
                    <li>Nach Zahlungseingang wird Ihre Bestellung bearbeitet</li>
                <?php endif; ?>
                <li>Sie werden über den Versand bzw. die Abholbereitschaft informiert</li>
            </ol>
        </div>

        <div class="text-center mt-xl">
            <a href="<?= BASE_URL ?>/shop" class="btn btn-primary">Weiter einkaufen</a>
            <a href="<?= BASE_URL ?>" class="btn btn-outline">Zur Startseite</a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
