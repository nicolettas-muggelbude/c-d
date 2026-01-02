<?php
/**
 * Produkt-Detail-Seite
 */

$db = Database::getInstance();

// Slug aus URL holen
$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';

if (empty($slug)) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

// Produkt laden
$product = $db->querySingle("
    SELECT p.*, c.name as category_name, c.slug as category_slug
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.slug = :slug AND p.is_active = 1
", [':slug' => $slug]);

if (!$product) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

// Spezifikationen dekodieren
$specifications = [];
if (!empty($product['specifications'])) {
    $specifications = json_decode($product['specifications'], true) ?? [];
}

// Zusätzliche Bilder dekodieren
$additional_images = [];
if (!empty($product['images'])) {
    $additional_images = json_decode($product['images'], true) ?? [];
}

// Ähnliche Produkte laden (gleiche Kategorie)
$similar_products = $db->query("
    SELECT p.*, c.name as category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.category_id = :category_id
    AND p.id != :current_id
    AND p.is_active = 1
    ORDER BY p.is_featured DESC, RAND()
    LIMIT 4
", [
    ':category_id' => $product['category_id'],
    ':current_id' => $product['id']
]);

// Page-Meta
$page_title = e($product['name']) . ' | PC-Wittfoot UG';
$page_description = e($product['short_description']);
$current_page = 'shop';

include __DIR__ . '/../templates/header.php';
?>

<style>
/* Bildergalerie Styles */
.product-image-gallery {
    display: flex;
    flex-direction: column;
    gap: var(--space-md);
}

.main-image {
    position: relative;
    background: var(--color-light);
    border-radius: var(--radius);
    overflow: hidden;
    aspect-ratio: 1 / 1;
    display: flex;
    align-items: center;
    justify-content: center;
}

.main-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    cursor: pointer;
}

.image-thumbnails {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: var(--space-sm);
}

.thumbnail {
    aspect-ratio: 1 / 1;
    border: 2px solid var(--border-color);
    border-radius: var(--radius);
    overflow: hidden;
    cursor: pointer;
    transition: all 0.2s ease;
    background: var(--color-light);
}

.thumbnail:hover {
    border-color: var(--color-primary);
    transform: scale(1.05);
}

.thumbnail.active {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 2px var(--color-primary-light);
}

.thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Trust Badges */
.trust-badges {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-md);
    padding: var(--space-lg) 0;
    border-top: 1px solid var(--border-color);
    border-bottom: 1px solid var(--border-color);
    margin: var(--space-lg) 0;
}

.trust-item {
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    color: var(--color-success);
    font-weight: 500;
}

.trust-icon {
    font-size: 1.5rem;
}

/* Badge Größe */
.badge-large {
    padding: var(--space-sm) var(--space-md);
    font-size: 1.1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .image-thumbnails {
        grid-template-columns: repeat(4, 1fr);
    }

    .trust-badges {
        flex-direction: column;
        gap: var(--space-sm);
    }
}
</style>

<section class="section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="<?= BASE_URL ?>">Start</a>
            <span>›</span>
            <a href="<?= BASE_URL ?>/shop">Shop</a>
            <span>›</span>
            <a href="<?= BASE_URL ?>/shop?kategorie=<?= e($product['category_slug']) ?>">
                <?= e($product['category_name']) ?>
            </a>
            <span>›</span>
            <span class="current"><?= e($product['name']) ?></span>
        </nav>

        <div class="product-detail-layout">
            <!-- Produktbild-Galerie -->
            <div class="product-image-gallery">
                <!-- Hauptbild -->
                <div class="main-image">
                    <?php if (!empty($product['image_url'])): ?>
                        <img id="main-product-image" src="<?= e($product['image_url']) ?>" alt="<?= e($product['name']) ?>">
                    <?php else: ?>
                        <div class="image-placeholder">
                            <span class="icon-large">📦</span>
                            <p class="text-muted">Kein Bild verfügbar</p>
                        </div>
                    <?php endif; ?>

                    <?php if ($product['is_featured']): ?>
                        <div class="featured-badge">⭐ Empfohlen</div>
                    <?php endif; ?>
                </div>

                <!-- Thumbnails (wenn zusätzliche Bilder vorhanden) -->
                <?php if (!empty($product['image_url']) || !empty($additional_images)): ?>
                    <div class="image-thumbnails">
                        <!-- Hauptbild als Thumbnail -->
                        <?php if (!empty($product['image_url'])): ?>
                            <div class="thumbnail active" data-image="<?= e($product['image_url']) ?>">
                                <img src="<?= e($product['image_url']) ?>" alt="Bild 1">
                            </div>
                        <?php endif; ?>

                        <!-- Zusätzliche Bilder -->
                        <?php foreach ($additional_images as $index => $img_url): ?>
                            <div class="thumbnail" data-image="<?= e($img_url) ?>">
                                <img src="<?= e($img_url) ?>" alt="Bild <?= $index + 2 ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Produktinfo -->
            <div class="product-info">
                <h1><?= e($product['name']) ?></h1>

                <div class="product-meta">
                    <span class="badge badge-large <?= $product['condition_type'] === 'neu' ? 'primary' : ($product['condition_type'] === 'refurbished' ? 'warning' : 'secondary') ?>">
                        <?php
                        $condition_labels = [
                            'neu' => '✨ Neu',
                            'refurbished' => '🔧 Refurbished',
                            'gebraucht' => '📦 Gebraucht'
                        ];
                        echo $condition_labels[$product['condition_type']] ?? e(ucfirst($product['condition_type']));
                        ?>
                    </span>
                    <span class="text-muted">SKU: <?= e($product['sku']) ?></span>
                    <?php if (!empty($product['brand'])): ?>
                        <span class="text-muted">Marke: <strong><?= e($product['brand']) ?></strong></span>
                    <?php endif; ?>
                </div>

                <div class="product-price">
                    <div class="price-large"><?= format_price($product['price']) ?></div>
                    <p class="text-muted">inkl. <?= number_format($product['tax_rate'] ?? 19, 0) ?>% MwSt.</p>
                </div>

                <!-- Trust-Elemente -->
                <div class="trust-badges">
                    <?php if ($product['free_shipping']): ?>
                        <div class="trust-item">
                            <span class="trust-icon">📦</span>
                            <span>Versandkostenfrei</span>
                        </div>
                    <?php endif; ?>
                    <?php if (($product['warranty_months'] ?? 24) > 0): ?>
                        <div class="trust-item">
                            <span class="trust-icon">✓</span>
                            <span><?= $product['warranty_months'] ?? 24 ?> Monate Garantie</span>
                        </div>
                    <?php endif; ?>
                    <?php if ($product['in_showroom']): ?>
                        <div class="trust-item">
                            <span class="trust-icon">📍</span>
                            <span>Abholung in Oldenburg</span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Lagerbestand -->
                <div class="stock-info">
                    <?php if ($product['stock'] > 5): ?>
                        <span class="text-success">✓ Auf Lager (<?= $product['stock'] ?> Stück verfügbar)</span>
                    <?php elseif ($product['stock'] > 0): ?>
                        <span class="text-warning">⚠ Nur noch <?= $product['stock'] ?> Stück verfügbar</span>
                    <?php else: ?>
                        <span class="text-error">✗ Aktuell nicht verfügbar</span>
                    <?php endif; ?>
                </div>

                <!-- Kurzbeschreibung -->
                <p class="lead"><?= e($product['short_description']) ?></p>

                <!-- Warenkorb-Button -->
                <?php if ($product['stock'] > 0): ?>
                    <form id="add-to-cart-form" class="add-to-cart-form">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                        <div class="quantity-selector">
                            <label for="quantity">Menge:</label>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            🛒 In den Warenkorb
                        </button>

                        <div id="cart-message" style="display: none; margin-top: var(--space-md);"></div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-info">
                        <p>Dieses Produkt ist aktuell nicht verfügbar. Kontaktieren Sie uns für Verfügbarkeit oder Alternativen.</p>
                        <a href="<?= BASE_URL ?>/kontakt" class="btn btn-outline">Kontakt aufnehmen</a>
                    </div>
                <?php endif; ?>

                <!-- Kontakt-Info -->
                <div class="product-contact mt-lg">
                    <p class="text-muted">
                        Haben Sie Fragen zu diesem Produkt?<br>
                        <a href="tel:+49123456789">📞 +49 (0) 123 456789</a> oder
                        <a href="<?= BASE_URL ?>/kontakt">✉️ Kontaktformular</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Tabs: Beschreibung & Spezifikationen -->
        <div class="product-tabs mt-3xl">
            <div class="tabs">
                <button class="tab-button active" data-tab="description">Beschreibung</button>
                <?php if (!empty($specifications)): ?>
                    <button class="tab-button" data-tab="specs">Spezifikationen</button>
                <?php endif; ?>
                <button class="tab-button" data-tab="delivery">Garantie & Lieferung</button>
            </div>

            <div class="tab-content active" id="description">
                <div class="card">
                    <?php if (!empty($product['description'])): ?>
                        <p><?= nl2br(e($product['description'])) ?></p>
                    <?php else: ?>
                        <p class="text-muted">Keine detaillierte Beschreibung vorhanden.</p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($specifications)): ?>
            <div class="tab-content" id="specs">
                <div class="card">
                    <table class="specs-table">
                        <tbody>
                            <?php foreach ($specifications as $key => $value): ?>
                                <tr>
                                    <th><?= e($key) ?></th>
                                    <td><?= e($value) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <div class="tab-content" id="delivery">
                <div class="card">
                    <h3>Garantie</h3>
                    <p><strong><?= $product['warranty_months'] ?? 24 ?> Monate Herstellergarantie</strong></p>
                    <p>Auf dieses Produkt erhalten Sie <?= $product['warranty_months'] ?? 24 ?> Monate Garantie ab Kaufdatum. Die Garantie deckt Herstellungsfehler und Materialfehler ab.</p>

                    <h3 style="margin-top: 2rem;">Lieferung</h3>
                    <ul>
                        <li><strong>Versand:</strong> <?= $product['free_shipping'] ? 'Kostenlos innerhalb Deutschlands' : 'Versandkosten nach Gewicht' ?></li>
                        <li><strong>Lieferzeit:</strong> 2-3 Werktage nach Zahlungseingang</li>
                        <?php if ($product['in_showroom']): ?>
                            <li><strong>Abholung:</strong> Möglich in unserem Showroom in Oldenburg</li>
                        <?php endif; ?>
                    </ul>

                    <h3 style="margin-top: 2rem;">Zustand</h3>
                    <p>
                        <?php
                        switch ($product['condition_type']) {
                            case 'neu':
                                echo '<strong>✨ Neu:</strong> Originalverpacktes, fabrikneues Gerät mit voller Herstellergarantie.';
                                break;
                            case 'refurbished':
                                echo '<strong>🔧 Refurbished:</strong> Professionell generalüberholt, technisch einwandfrei, optisch sehr gut. Getestet und gereinigt.';
                                break;
                            case 'gebraucht':
                                echo '<strong>📦 Gebraucht:</strong> Geprüftes Gebrauchtgerät, voll funktionsfähig. Kann optische Gebrauchsspuren aufweisen.';
                                break;
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Ähnliche Produkte -->
        <?php if (!empty($similar_products)): ?>
        <section class="similar-products mt-3xl">
            <h2>Ähnliche Produkte</h2>

            <div class="grid grid-cols-1 grid-cols-md-2 grid-cols-lg-4 gap-lg mt-lg">
                <?php foreach ($similar_products as $similar): ?>
                    <div class="card">
                        <div class="card-header">
                            <span class="badge <?= $similar['condition_type'] === 'neu' ? 'primary' : 'secondary' ?>">
                                <?= e(ucfirst($similar['condition_type'])) ?>
                            </span>
                        </div>

                        <h3><?= e($similar['name']) ?></h3>
                        <p class="text-muted"><?= e($similar['brand']) ?></p>
                        <p><?= e(truncate($similar['short_description'], 60)) ?></p>

                        <div class="card-footer">
                            <strong class="price"><?= format_price($similar['price']) ?></strong>
                            <a href="<?= BASE_URL ?>/produkt/<?= e($similar['slug']) ?>" class="btn btn-primary btn-sm">
                                Details
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</section>

<script>
// Bildergalerie: Thumbnail-Klick
document.querySelectorAll('.thumbnail').forEach(thumb => {
    thumb.addEventListener('click', function() {
        const imageUrl = this.dataset.image;
        const mainImage = document.getElementById('main-product-image');

        if (mainImage && imageUrl) {
            mainImage.src = imageUrl;
        }

        // Aktiven Thumbnail markieren
        document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
    });
});

// Tab-Switching
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', () => {
        const tabId = button.dataset.tab;

        // Alle Tabs deaktivieren
        document.querySelectorAll('.tab-button').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

        // Aktiven Tab aktivieren
        button.classList.add('active');
        document.getElementById(tabId).classList.add('active');
    });
});

// Warenkorb: Produkt hinzufügen
document.getElementById('add-to-cart-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const messageDiv = document.getElementById('cart-message');
    const submitBtn = this.querySelector('button[type="submit"]');

    // Button deaktivieren
    submitBtn.disabled = true;
    submitBtn.textContent = '⏳ Wird hinzugefügt...';

    try {
        const response = await fetch('<?= BASE_URL ?>/api/cart', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        // Nachricht anzeigen
        messageDiv.style.display = 'block';
        if (result.success) {
            messageDiv.className = 'alert alert-success';
            messageDiv.innerHTML = `
                <p><strong>✓ ${result.message}</strong></p>
                <div style="display: flex; gap: var(--space-md); margin-top: var(--space-md);">
                    <a href="<?= BASE_URL ?>/warenkorb" class="btn btn-primary btn-sm">Zum Warenkorb</a>
                    <a href="<?= BASE_URL ?>/shop" class="btn btn-outline btn-sm">Weiter einkaufen</a>
                </div>
            `;

            // Warenkorb-Counter im Header aktualisieren
            const cartBadge = document.querySelector('.cart-badge');
            if (cartBadge) {
                cartBadge.textContent = result.cart_count;
            } else if (result.cart_count > 0) {
                // Badge erstellen falls nicht vorhanden
                const cartButton = document.querySelector('.cart-button');
                const badge = document.createElement('span');
                badge.className = 'cart-badge';
                badge.textContent = result.cart_count;
                cartButton.appendChild(badge);
            }
        } else {
            messageDiv.className = 'alert alert-error';
            messageDiv.innerHTML = `<p><strong>✗ ${result.message}</strong></p>`;
        }
    } catch (error) {
        messageDiv.style.display = 'block';
        messageDiv.className = 'alert alert-error';
        messageDiv.innerHTML = '<p><strong>Fehler beim Hinzufügen zum Warenkorb.</strong></p>';
    } finally {
        // Button wieder aktivieren
        submitBtn.disabled = false;
        submitBtn.textContent = '🛒 In den Warenkorb';
    }
});
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
