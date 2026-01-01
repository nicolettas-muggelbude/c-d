<?php
/**
 * Warenkorb-Seite
 */

$cart = new Cart();
$items = $cart->getItems();

$page_title = 'Warenkorb | PC-Wittfoot UG';
$page_description = 'Ihr Warenkorb';
$current_page = 'shop';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <h1>🛒 Warenkorb</h1>

        <?php if ($cart->isEmpty()): ?>
            <!-- Leerer Warenkorb -->
            <div class="card text-center" style="padding: var(--space-3xl);">
                <div style="font-size: 5rem; margin-bottom: var(--space-lg);">🛒</div>
                <h2>Ihr Warenkorb ist leer</h2>
                <p class="lead">Fügen Sie Produkte aus unserem Shop hinzu.</p>
                <a href="<?= BASE_URL ?>/shop" class="btn btn-primary mt-lg">Zum Shop</a>
            </div>

        <?php else: ?>
            <!-- Warenkorb mit Produkten -->
            <div class="cart-layout">
                <!-- Warenkorb-Tabelle -->
                <div class="cart-items">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Produkt</th>
                                <th>Preis</th>
                                <th>Menge</th>
                                <th>Gesamt</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $product_id => $item): ?>
                                <?php
                                $item_brutto = $item['price'];
                                $item_netto = $item['price'] / 1.19;
                                $total_brutto = $item['price'] * $item['quantity'];
                                $total_netto = $item_netto * $item['quantity'];
                                ?>
                                <tr data-product-id="<?= $product_id ?>">
                                    <td>
                                        <div class="cart-product-info">
                                            <h3>
                                                <a href="<?= BASE_URL ?>/produkt/<?= e($item['slug']) ?>">
                                                    <?= e($item['name']) ?>
                                                </a>
                                            </h3>
                                        </div>
                                    </td>
                                    <td class="cart-price">
                                        <span class="item-price-brutto"><?= format_price($item_brutto) ?></span>
                                        <span class="item-price-netto" style="display: none;"><?= format_price($item_netto) ?></span>
                                    </td>
                                    <td class="cart-quantity">
                                        <div class="quantity-control">
                                            <button type="button" class="qty-btn" data-action="decrease" data-product-id="<?= $product_id ?>">-</button>
                                            <input type="number"
                                                   class="qty-input"
                                                   value="<?= $item['quantity'] ?>"
                                                   min="1"
                                                   data-product-id="<?= $product_id ?>"
                                                   readonly>
                                            <button type="button" class="qty-btn" data-action="increase" data-product-id="<?= $product_id ?>">+</button>
                                        </div>
                                    </td>
                                    <td class="cart-total">
                                        <strong class="item-total-brutto"><?= format_price($total_brutto) ?></strong>
                                        <strong class="item-total-netto" style="display: none;"><?= format_price($total_netto) ?></strong>
                                    </td>
                                    <td>
                                        <button type="button" class="btn-remove" data-product-id="<?= $product_id ?>" title="Entfernen">
                                            ✕
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Warenkorb-Zusammenfassung -->
                <div class="cart-summary">
                    <div class="card">
                        <h2>Zusammenfassung</h2>

                        <!-- Preisanzeige-Umschaltung -->
                        <div class="price-toggle mb-lg">
                            <label class="form-check">
                                <input type="checkbox" id="show-net-prices">
                                <span>Gewerbe (Nettopreise anzeigen)</span>
                            </label>
                        </div>

                        <!-- Bruttoansicht (Standard) -->
                        <div id="brutto-view">
                            <div class="summary-row">
                                <span>Summe (inkl. MwSt):</span>
                                <strong id="total-brutto"><?= format_price($cart->getTotal()) ?></strong>
                            </div>

                            <div class="summary-row text-muted" style="font-size: var(--font-size-sm);">
                                <span>enthält MwSt (19%):</span>
                                <span id="tax-brutto"><?= format_price($cart->getTax()) ?></span>
                            </div>
                        </div>

                        <!-- Nettoansicht (Gewerbe) -->
                        <div id="netto-view" style="display: none;">
                            <div class="summary-row">
                                <span>Zwischensumme (netto):</span>
                                <strong id="net"><?= format_price($cart->getNet()) ?></strong>
                            </div>

                            <div class="summary-row">
                                <span>MwSt (19%):</span>
                                <strong id="tax-netto"><?= format_price($cart->getTax()) ?></strong>
                            </div>

                            <hr>

                            <div class="summary-row summary-total">
                                <span>Gesamt (brutto):</span>
                                <strong id="total-netto"><?= format_price($cart->getTotal()) ?></strong>
                            </div>
                        </div>

                        <a href="<?= BASE_URL ?>/kasse" class="btn btn-primary btn-block btn-lg mt-lg">
                            Zur Kasse
                        </a>

                        <a href="<?= BASE_URL ?>/shop" class="btn btn-outline btn-block mt-md">
                            Weiter einkaufen
                        </a>

                        <button type="button" id="clear-cart" class="btn btn-outline btn-block mt-md" style="color: var(--color-error);">
                            Warenkorb leeren
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
const csrfToken = '<?= csrf_token() ?>';

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

// Menge ändern
document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', async function() {
        const productId = this.dataset.productId;
        const action = this.dataset.action;
        const input = document.querySelector(`.qty-input[data-product-id="${productId}"]`);
        let quantity = parseInt(input.value);

        if (action === 'increase') {
            quantity++;
        } else if (action === 'decrease' && quantity > 1) {
            quantity--;
        }

        await updateQuantity(productId, quantity);
    });
});

// Produkt entfernen
document.querySelectorAll('.btn-remove').forEach(btn => {
    btn.addEventListener('click', async function() {
        if (!confirm('Produkt wirklich entfernen?')) return;

        const productId = this.dataset.productId;
        await removeItem(productId);
    });
});

// Warenkorb leeren
document.getElementById('clear-cart')?.addEventListener('click', async function() {
    if (!confirm('Warenkorb wirklich leeren?')) return;

    const formData = new FormData();
    formData.append('action', 'clear');
    formData.append('csrf_token', csrfToken);

    const response = await fetch('<?= BASE_URL ?>/api/cart', {
        method: 'POST',
        body: formData
    });

    const result = await response.json();

    if (result.success) {
        location.reload();
    } else {
        alert(result.message);
    }
});

// Menge aktualisieren
async function updateQuantity(productId, quantity) {
    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('product_id', productId);
    formData.append('quantity', quantity);
    formData.append('csrf_token', csrfToken);

    const response = await fetch('<?= BASE_URL ?>/api/cart', {
        method: 'POST',
        body: formData
    });

    const result = await response.json();

    if (result.success) {
        // Seite neu laden um Preise zu aktualisieren
        location.reload();
    } else {
        alert(result.message);
    }
}

// Produkt entfernen
async function removeItem(productId) {
    const formData = new FormData();
    formData.append('action', 'remove');
    formData.append('product_id', productId);
    formData.append('csrf_token', csrfToken);

    const response = await fetch('<?= BASE_URL ?>/api/cart', {
        method: 'POST',
        body: formData
    });

    const result = await response.json();

    if (result.success) {
        location.reload();
    } else {
        alert(result.message);
    }
}
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
