<?php
/**
 * Kasse / Checkout
 */

$db = Database::getInstance();
$cart = new Cart();

// Warenkorb leer? -> Zurück zum Shop
if ($cart->isEmpty()) {
    set_flash('error', 'Ihr Warenkorb ist leer.');
    redirect(BASE_URL . '/shop');
}

// Lagerbestände validieren
$validation = $cart->validateStock();
if (!$validation['valid']) {
    set_flash('error', 'Einige Produkte sind nicht mehr verfügbar:<br>' . implode('<br>', $validation['errors']));
    redirect(BASE_URL . '/warenkorb');
}

$success = false;
$error = null;
$form_data = [];

// Bestellung verarbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF-Schutz
    if (!csrf_verify($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF-Token ungültig.';
    } else {
        // Formulardaten
        $customer_data = [
            'email' => sanitize($_POST['email'] ?? ''),
            'firstname' => sanitize($_POST['firstname'] ?? ''),
            'lastname' => sanitize($_POST['lastname'] ?? ''),
            'company' => sanitize($_POST['company'] ?? ''),
            'phone' => sanitize($_POST['phone'] ?? ''),
            'street' => sanitize($_POST['street'] ?? ''),
            'housenumber' => sanitize($_POST['housenumber'] ?? ''),
            'zip' => sanitize($_POST['zip'] ?? ''),
            'city' => sanitize($_POST['city'] ?? ''),
            'delivery_method' => sanitize($_POST['delivery_method'] ?? ''),
            'payment_method' => sanitize($_POST['payment_method'] ?? 'prepayment'),
            'notes' => sanitize($_POST['notes'] ?? ''),
        ];

        // Lieferadresse (nur bei Versand)
        $shipping_data = [
            'firstname' => sanitize($_POST['shipping_firstname'] ?? ''),
            'lastname' => sanitize($_POST['shipping_lastname'] ?? ''),
            'street' => sanitize($_POST['shipping_street'] ?? ''),
            'housenumber' => sanitize($_POST['shipping_housenumber'] ?? ''),
            'zip' => sanitize($_POST['shipping_zip'] ?? ''),
            'city' => sanitize($_POST['shipping_city'] ?? ''),
        ];

        // Validierung
        $errors = [];

        if (empty($customer_data['email']) || !is_valid_email($customer_data['email'])) {
            $errors[] = 'Bitte geben Sie eine gültige E-Mail-Adresse an.';
        }
        if (empty($customer_data['firstname'])) {
            $errors[] = 'Bitte geben Sie Ihren Vornamen an.';
        }
        if (empty($customer_data['lastname'])) {
            $errors[] = 'Bitte geben Sie Ihren Nachnamen an.';
        }

        if (empty($customer_data['street'])) {
            $errors[] = 'Bitte geben Sie Ihre Straße an.';
        }
        if (empty($customer_data['housenumber'])) {
            $errors[] = 'Bitte geben Sie Ihre Hausnummer an.';
        }
        if (empty($customer_data['zip'])) {
            $errors[] = 'Bitte geben Sie Ihre Postleitzahl an.';
        }
        if (empty($customer_data['city'])) {
            $errors[] = 'Bitte geben Sie Ihren Ort an.';
        }

        if (empty($customer_data['delivery_method'])) {
            $errors[] = 'Bitte wählen Sie eine Lieferart aus.';
        }

        // Bei Versand: Lieferadresse erforderlich
        if ($customer_data['delivery_method'] === 'shipping') {
            if (empty($shipping_data['firstname'])) {
                $errors[] = 'Bitte geben Sie den Vornamen für die Lieferadresse an.';
            }
            if (empty($shipping_data['lastname'])) {
                $errors[] = 'Bitte geben Sie den Nachnamen für die Lieferadresse an.';
            }
            if (empty($shipping_data['street'])) {
                $errors[] = 'Bitte geben Sie die Straße für die Lieferadresse an.';
            }
            if (empty($shipping_data['housenumber'])) {
                $errors[] = 'Bitte geben Sie die Hausnummer für die Lieferadresse an.';
            }
            if (empty($shipping_data['zip'])) {
                $errors[] = 'Bitte geben Sie die Postleitzahl für die Lieferadresse an.';
            }
            if (empty($shipping_data['city'])) {
                $errors[] = 'Bitte geben Sie den Ort für die Lieferadresse an.';
            }
        }

        if (!isset($_POST['accept_terms'])) {
            $errors[] = 'Bitte akzeptieren Sie die AGB.';
        }

        // Nochmal Lagerbestand prüfen
        $validation = $cart->validateStock();
        if (!$validation['valid']) {
            $errors = array_merge($errors, $validation['errors']);
        }

        if (empty($errors)) {
            // HelloCash API Integration
            $hellocashUserId = null;
            $hellocashClient = new HelloCashClient();

            if ($hellocashClient->isConfigured()) {
                // Telefonnummer bereinigen (führende 0 entfernen)
                $phoneNumber = ltrim(trim($customer_data['phone']), '0');

                $customerData = [
                    'firstname' => $customer_data['firstname'],
                    'lastname' => $customer_data['lastname'],
                    'email' => $customer_data['email'],
                    'phone_country' => '+49', // Deutschland als Standard
                    'phone_mobile' => $phoneNumber,
                    'phone_landline' => null, // Shop hat nur ein Telefon-Feld
                    'company' => $customer_data['company'] ?: null,
                    // Adresse
                    'street' => $customer_data['street'],
                    'house_number' => $customer_data['housenumber'],
                    'postal_code' => $customer_data['zip'],
                    'city' => $customer_data['city']
                ];

                $result = $hellocashClient->findOrCreateUser($customerData);

                if ($result['user_id']) {
                    $hellocashUserId = $result['user_id'];
                    error_log('HelloCash User (Shop): ' . ($result['is_new'] ? 'Neu erstellt' : 'Gefunden') . ' - ID: ' . $hellocashUserId);
                } else if ($result['error']) {
                    error_log('HelloCash Error (Shop): ' . $result['error']);
                }
            } else {
                error_log('HelloCash API not configured');
            }

            // Bestellung in Datenbank speichern
            $db->beginTransaction();

            try {
                // Bestellnummer generieren (Format: ORD-YYYY-NNNN)
                $orderNumber = 'ORD-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

                // Sicherstellen dass Bestellnummer eindeutig ist
                $exists = $db->querySingle("SELECT id FROM orders WHERE order_number = :num", [':num' => $orderNumber]);
                while ($exists) {
                    $orderNumber = 'ORD-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                    $exists = $db->querySingle("SELECT id FROM orders WHERE order_number = :num", [':num' => $orderNumber]);
                }

                // Lieferadresse zusammenstellen (für legacy shipping_address Feld)
                $shippingAddressText = '';
                if (!empty($shipping_data['firstname']) && !empty($shipping_data['lastname'])) {
                    // Separate Lieferadresse
                    $shippingAddressText = trim($shipping_data['firstname'] . ' ' . $shipping_data['lastname']) . "\n";
                    $shippingAddressText .= trim($shipping_data['street'] . ' ' . $shipping_data['housenumber']) . "\n";
                    $shippingAddressText .= trim($shipping_data['zip'] . ' ' . $shipping_data['city']);
                } else {
                    // Kundenadresse verwenden
                    $shippingAddressText = trim($customer_data['firstname'] . ' ' . $customer_data['lastname']) . "\n";
                    $shippingAddressText .= trim($customer_data['street'] . ' ' . $customer_data['housenumber']) . "\n";
                    $shippingAddressText .= trim($customer_data['zip'] . ' ' . $customer_data['city']);
                }

                // Order erstellen
                $order_id = $db->insert("
                    INSERT INTO orders (
                        order_number, customer_name, customer_email, customer_firstname, customer_lastname, customer_company, customer_phone,
                        customer_street, customer_housenumber, customer_zip, customer_city,
                        shipping_firstname, shipping_lastname, shipping_street, shipping_housenumber, shipping_zip, shipping_city,
                        shipping_address, delivery_method, payment_method, order_notes,
                        subtotal, tax, total, total_amount, hellocash_customer_id, order_status
                    ) VALUES (
                        :order_number, :customer_name, :email, :firstname, :lastname, :company, :phone,
                        :street, :housenumber, :zip, :city,
                        :shipping_firstname, :shipping_lastname, :shipping_street, :shipping_housenumber, :shipping_zip, :shipping_city,
                        :shipping_address, :delivery_method, :payment_method, :notes,
                        :subtotal, :tax, :total, :total_amount, :hellocash_customer_id, 'pending'
                    )
                ", [
                    ':order_number' => $orderNumber,
                    ':customer_name' => trim($customer_data['firstname'] . ' ' . $customer_data['lastname']),
                    ':email' => $customer_data['email'],
                    ':firstname' => $customer_data['firstname'],
                    ':lastname' => $customer_data['lastname'],
                    ':company' => $customer_data['company'],
                    ':phone' => $customer_data['phone'],
                    ':street' => $customer_data['street'],
                    ':housenumber' => $customer_data['housenumber'],
                    ':zip' => $customer_data['zip'],
                    ':city' => $customer_data['city'],
                    ':shipping_firstname' => $shipping_data['firstname'] ?: null,
                    ':shipping_lastname' => $shipping_data['lastname'] ?: null,
                    ':shipping_street' => $shipping_data['street'] ?: null,
                    ':shipping_housenumber' => $shipping_data['housenumber'] ?: null,
                    ':shipping_zip' => $shipping_data['zip'] ?: null,
                    ':shipping_city' => $shipping_data['city'] ?: null,
                    ':shipping_address' => $shippingAddressText,
                    ':delivery_method' => $customer_data['delivery_method'],
                    ':payment_method' => $customer_data['payment_method'],
                    ':notes' => $customer_data['notes'],
                    ':subtotal' => $cart->getNet(),
                    ':tax' => $cart->getTax(),
                    ':total' => $cart->getTotal(),
                    ':total_amount' => $cart->getTotal(),
                    ':hellocash_customer_id' => $hellocashUserId
                ]);

                if (!$order_id) {
                    throw new Exception('Fehler beim Erstellen der Bestellung');
                }

                // Order Items erstellen & Lagerbestand reduzieren
                foreach ($cart->getItems() as $item) {
                    // Order Item
                    $db->insert("
                        INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, total_price)
                        VALUES (:order_id, :product_id, :product_name, :quantity, :unit_price, :total_price)
                    ", [
                        ':order_id' => $order_id,
                        ':product_id' => $item['id'],
                        ':product_name' => $item['name'],
                        ':quantity' => $item['quantity'],
                        ':unit_price' => $item['price'],
                        ':total_price' => $item['price'] * $item['quantity'],
                    ]);

                    // Lagerbestand reduzieren
                    $db->update("
                        UPDATE products
                        SET stock = stock - :quantity
                        WHERE id = :product_id AND stock >= :quantity_check
                    ", [
                        ':product_id' => $item['id'],
                        ':quantity' => $item['quantity'],
                        ':quantity_check' => $item['quantity'],
                    ]);
                }

                $db->commit();

                // HelloCash Invoice erstellen (falls User vorhanden)
                $hellocashInvoiceId = null;
                $hellocashInvoiceLink = null;

                if ($hellocashUserId && $hellocashClient->isConfigured()) {
                    // Items für HelloCash vorbereiten
                    $invoiceItems = [];
                    foreach ($cart->getItems() as $item) {
                        $invoiceItems[] = [
                            'name' => $item['name'],
                            'quantity' => $item['quantity'],
                            'price' => $item['price'],
                            'tax_rate' => 19 // 19% MwSt
                        ];
                    }

                    // Zahlungsmethode mappen
                    $paymentMethodMap = [
                        'prepayment' => 'Vorkasse',
                        'cash' => 'Bar',
                        'paypal' => 'PayPal'
                    ];
                    $hcPaymentMethod = $paymentMethodMap[$customer_data['payment_method']] ?? 'Vorkasse';

                    $invoiceResult = $hellocashClient->createInvoice([
                        'user_id' => $hellocashUserId,
                        'items' => $invoiceItems,
                        'payment_method' => $hcPaymentMethod,
                        'notes' => $customer_data['notes']
                    ]);

                    if ($invoiceResult['invoice_id']) {
                        $hellocashInvoiceId = $invoiceResult['invoice_id'];
                        $hellocashInvoiceLink = $invoiceResult['invoice_link'];

                        // Invoice-Daten in Datenbank speichern
                        $db->update("
                            UPDATE orders
                            SET hellocash_invoice_id = :invoice_id,
                                hellocash_invoice_link = :invoice_link
                            WHERE id = :order_id
                        ", [
                            ':invoice_id' => $hellocashInvoiceId,
                            ':invoice_link' => $hellocashInvoiceLink,
                            ':order_id' => $order_id
                        ]);

                        error_log("HelloCash Invoice erstellt: ID=$hellocashInvoiceId, Link=$hellocashInvoiceLink");
                    } else {
                        error_log("HelloCash Invoice-Erstellung fehlgeschlagen: " . ($invoiceResult['error'] ?? 'Unbekannter Fehler'));
                    }
                }

                // E-Mail-Benachrichtigungen versenden (asynchron)
                $emailService = new EmailService();

                // Bestätigungs-E-Mail an Kunden
                try {
                    $emailService->sendOrderConfirmation($order_id);
                } catch (Exception $e) {
                    error_log("Fehler beim Versenden der Bestellbestätigung: " . $e->getMessage());
                }

                // Benachrichtigungs-E-Mail an Admin
                try {
                    $emailService->sendOrderNotification($order_id);
                } catch (Exception $e) {
                    error_log("Fehler beim Versenden der Admin-Benachrichtigung: " . $e->getMessage());
                }

                // Warenkorb leeren
                $cart->clear();

                // Flash-Message setzen
                set_flash('success', "Vielen Dank für Ihre Bestellung! Bestellnummer: #$order_id");

                // Redirect zur Bestätigungsseite
                redirect(BASE_URL . '/bestellung/' . $order_id);

            } catch (Exception $e) {
                $db->rollback();
                $error = 'Fehler beim Speichern der Bestellung. Bitte versuchen Sie es erneut.';
                if (DEBUG_MODE) {
                    $error .= '<br>' . $e->getMessage();
                }
            }
        } else {
            $error = implode('<br>', $errors);
            $form_data = array_merge($customer_data, [
                'shipping_firstname' => $shipping_data['firstname'],
                'shipping_lastname' => $shipping_data['lastname'],
                'shipping_street' => $shipping_data['street'],
                'shipping_housenumber' => $shipping_data['housenumber'],
                'shipping_zip' => $shipping_data['zip'],
                'shipping_city' => $shipping_data['city'],
            ]);
        }
    }
}

$page_title = 'Kasse | PC-Wittfoot UG';
$page_description = 'Bestellung abschließen';
$current_page = 'shop';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <h1>Kasse</h1>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <strong>Fehler:</strong><br>
                <?= $error ?>
            </div>
        <?php endif; ?>

        <div class="checkout-layout">
            <!-- Bestellformular -->
            <div class="checkout-form">
                <form method="post" action="">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                    <!-- Kundendaten -->
                    <div class="card mb-lg">
                        <h2>Kundendaten</h2>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstname">Vorname *</label>
                                <input type="text" id="firstname" name="firstname"
                                       value="<?= e($form_data['firstname'] ?? '') ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="lastname">Nachname *</label>
                                <input type="text" id="lastname" name="lastname"
                                       value="<?= e($form_data['lastname'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="company">Firma (optional)</label>
                            <input type="text" id="company" name="company"
                                   value="<?= e($form_data['company'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="email">E-Mail *</label>
                            <input type="email" id="email" name="email"
                                   value="<?= e($form_data['email'] ?? '') ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Telefon (optional)</label>
                            <input type="tel" id="phone" name="phone"
                                   value="<?= e($form_data['phone'] ?? '') ?>">
                        </div>

                        <h3 style="margin-top: var(--space-lg);">Adresse</h3>

                        <div class="form-row">
                            <div class="form-group" style="flex: 2;">
                                <label for="street">Straße *</label>
                                <input type="text" id="street" name="street"
                                       value="<?= e($form_data['street'] ?? '') ?>" required>
                            </div>

                            <div class="form-group" style="flex: 1;">
                                <label for="housenumber">Hausnr. *</label>
                                <input type="text" id="housenumber" name="housenumber"
                                       value="<?= e($form_data['housenumber'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="zip">PLZ *</label>
                                <input type="text" id="zip" name="zip"
                                       value="<?= e($form_data['zip'] ?? '') ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="city">Ort *</label>
                                <input type="text" id="city" name="city"
                                       value="<?= e($form_data['city'] ?? '') ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- Lieferung -->
                    <div class="card mb-lg">
                        <h2>Lieferung</h2>

                        <div class="form-group">
                            <label for="delivery_method">Lieferart *</label>
                            <select id="delivery_method" name="delivery_method" required>
                                <option value="">Keine andere Lieferadresse</option>
                                <option value="pickup" <?= ($form_data['delivery_method'] ?? '') === 'pickup' ? 'selected' : '' ?>>
                                    Abholung im Laden (kostenlos)
                                </option>
                                <option value="shipping" <?= ($form_data['delivery_method'] ?? '') === 'shipping' ? 'selected' : '' ?>>
                                    Andere Lieferadresse
                                </option>
                            </select>
                        </div>

                        <div id="shipping-address">
                            <h3>Lieferadresse</h3>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="shipping_firstname">Vorname *</label>
                                    <input type="text" id="shipping_firstname" name="shipping_firstname"
                                           value="<?= e($form_data['shipping_firstname'] ?? '') ?>">
                                </div>

                                <div class="form-group">
                                    <label for="shipping_lastname">Nachname *</label>
                                    <input type="text" id="shipping_lastname" name="shipping_lastname"
                                           value="<?= e($form_data['shipping_lastname'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group" style="flex: 2;">
                                    <label for="shipping_street">Straße *</label>
                                    <input type="text" id="shipping_street" name="shipping_street"
                                           value="<?= e($form_data['shipping_street'] ?? '') ?>">
                                </div>

                                <div class="form-group" style="flex: 1;">
                                    <label for="shipping_housenumber">Hausnr. *</label>
                                    <input type="text" id="shipping_housenumber" name="shipping_housenumber"
                                           value="<?= e($form_data['shipping_housenumber'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="shipping_zip">PLZ *</label>
                                    <input type="text" id="shipping_zip" name="shipping_zip"
                                           value="<?= e($form_data['shipping_zip'] ?? '') ?>">
                                </div>

                                <div class="form-group">
                                    <label for="shipping_city">Ort *</label>
                                    <input type="text" id="shipping_city" name="shipping_city"
                                           value="<?= e($form_data['shipping_city'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Zahlung -->
                    <div class="card mb-lg">
                        <h2>Zahlungsart</h2>

                        <div class="form-group">
                            <label class="form-check">
                                <input type="radio" name="payment_method" value="prepayment"
                                       <?= ($form_data['payment_method'] ?? 'prepayment') === 'prepayment' ? 'checked' : '' ?>>
                                <span>Vorkasse / Überweisung</span>
                            </label>

                            <label class="form-check">
                                <input type="radio" name="payment_method" value="paypal"
                                       <?= ($form_data['payment_method'] ?? '') === 'paypal' ? 'checked' : '' ?>>
                                <span>PayPal</span>
                            </label>

                            <label class="form-check">
                                <input type="radio" name="payment_method" value="cash"
                                       <?= ($form_data['payment_method'] ?? '') === 'cash' ? 'checked' : '' ?>>
                                <span>Barzahlung bei Abholung</span>
                            </label>
                        </div>
                    </div>

                    <!-- Anmerkungen -->
                    <div class="card mb-lg">
                        <h2>Anmerkungen (optional)</h2>

                        <div class="form-group">
                            <label for="notes">Besondere Wünsche oder Hinweise</label>
                            <textarea id="notes" name="notes" rows="4"><?= e($form_data['notes'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- AGB -->
                    <div class="card mb-lg">
                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="accept_terms" required>
                                <span>Ich habe die <a href="<?= BASE_URL ?>/agb" target="_blank">AGB</a> und
                                      <a href="<?= BASE_URL ?>/widerruf" target="_blank">Widerrufsbelehrung</a>
                                      gelesen und akzeptiert. *</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        Kostenpflichtig bestellen
                    </button>
                </form>
            </div>

            <!-- Bestellübersicht -->
            <div class="checkout-summary">
                <div class="card">
                    <h2>Ihre Bestellung</h2>

                    <!-- Preisanzeige-Umschaltung -->
                    <div class="price-toggle mb-lg">
                        <label class="form-check">
                            <input type="checkbox" id="show-net-prices">
                            <span>Gewerbe (Nettopreise anzeigen)</span>
                        </label>
                    </div>

                    <?php foreach ($cart->getItems() as $item): ?>
                        <?php
                        $item_brutto = $item['price'];
                        $item_netto = $item['price'] / 1.19;
                        $total_brutto = $item['price'] * $item['quantity'];
                        $total_netto = $item_netto * $item['quantity'];
                        ?>
                        <div class="summary-item">
                            <div>
                                <strong><?= e($item['name']) ?></strong><br>
                                <span class="text-muted item-price-brutto"><?= $item['quantity'] ?>x <?= format_price($item_brutto) ?></span>
                                <span class="text-muted item-price-netto" style="display: none;"><?= $item['quantity'] ?>x <?= format_price($item_netto) ?> (netto)</span>
                            </div>
                            <div>
                                <strong class="item-total-brutto"><?= format_price($total_brutto) ?></strong>
                                <strong class="item-total-netto" style="display: none;"><?= format_price($total_netto) ?></strong>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <hr>

                    <!-- Bruttoansicht (Standard) -->
                    <div id="brutto-view">
                        <div class="summary-row">
                            <span>Summe (inkl. MwSt):</span>
                            <strong><?= format_price($cart->getTotal()) ?></strong>
                        </div>

                        <div class="summary-row text-muted" style="font-size: var(--font-size-sm);">
                            <span>enthält MwSt (19%):</span>
                            <span><?= format_price($cart->getTax()) ?></span>
                        </div>
                    </div>

                    <!-- Nettoansicht (Gewerbe) -->
                    <div id="netto-view" style="display: none;">
                        <div class="summary-row">
                            <span>Zwischensumme (netto):</span>
                            <strong><?= format_price($cart->getNet()) ?></strong>
                        </div>

                        <div class="summary-row">
                            <span>MwSt (19%):</span>
                            <strong><?= format_price($cart->getTax()) ?></strong>
                        </div>

                        <hr>

                        <div class="summary-row summary-total">
                            <span>Gesamt (brutto):</span>
                            <strong><?= format_price($cart->getTotal()) ?></strong>
                        </div>
                    </div>
                </div>

                <a href="<?= BASE_URL ?>/warenkorb" class="btn btn-outline btn-block mt-md">
                    ← Zurück zum Warenkorb
                </a>
            </div>
        </div>
    </div>
</section>

<script>
// Brutto/Netto Umschaltung
const showNetCheckbox = document.getElementById('show-net-prices');
const bruttoView = document.getElementById('brutto-view');
const nettoView = document.getElementById('netto-view');

function updatePriceDisplay(showNet) {
    // Summen-Ansicht umschalten
    bruttoView.style.display = showNet ? 'none' : 'block';
    nettoView.style.display = showNet ? 'block' : 'none';

    // Einzelpreise umschalten
    document.querySelectorAll('.item-price-brutto').forEach(el => {
        el.style.display = showNet ? 'none' : 'inline';
    });
    document.querySelectorAll('.item-price-netto').forEach(el => {
        el.style.display = showNet ? 'inline' : 'none';
    });

    // Gesamtpreise pro Artikel umschalten
    document.querySelectorAll('.item-total-brutto').forEach(el => {
        el.style.display = showNet ? 'none' : 'inline';
    });
    document.querySelectorAll('.item-total-netto').forEach(el => {
        el.style.display = showNet ? 'inline' : 'none';
    });
}

// Gespeicherte Einstellung laden
if (localStorage.getItem('show_net_prices') === 'true') {
    showNetCheckbox.checked = true;
    updatePriceDisplay(true);
}

showNetCheckbox?.addEventListener('change', function() {
    const showNet = this.checked;
    updatePriceDisplay(showNet);
    localStorage.setItem('show_net_prices', showNet ? 'true' : 'false');
});

// Versandadresse nur bei Versand anzeigen
const deliverySelect = document.getElementById('delivery_method');
const shippingAddress = document.getElementById('shipping-address');

function toggleShippingAddress() {
    const isShipping = deliverySelect.value === 'shipping';
    shippingAddress.style.display = isShipping ? 'block' : 'none';

    // Required-Attribute setzen/entfernen
    const addressFields = shippingAddress.querySelectorAll('input');
    addressFields.forEach(field => {
        field.required = isShipping;
    });
}

deliverySelect.addEventListener('change', toggleShippingAddress);

// Initial state
toggleShippingAddress();
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
