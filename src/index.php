<?php
/**
 * Startseite - PC-Wittfoot UG
 */

require_once __DIR__ . '/core/config.php';

start_session_safe();

// Datenbank-Instanz
$db = Database::getInstance();

// Featured Produkte laden
$featured_products = $db->query("
    SELECT p.*, c.name as category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.is_active = 1 AND p.is_featured = 1
    ORDER BY p.created_at DESC
    LIMIT 6
");

// Neueste Blog-Posts laden
$blog_posts = $db->query("
    SELECT bp.*, u.full_name
    FROM blog_posts bp
    LEFT JOIN users u ON bp.author_id = u.id
    WHERE bp.published = 1
    ORDER BY bp.published_at DESC
    LIMIT 3
");

// Kategorien für Showcase laden
$categories = $db->query("
    SELECT *
    FROM categories
    WHERE is_active = 1
    ORDER BY sort_order
    LIMIT 4
");

// Page-Meta für Template
$page_title = 'PC-Wittfoot UG - IT-Fachbetrieb mit Herz';
$page_description = 'IT-Fachbetrieb in [Ort]. Beratung, Verkauf, Reparatur & Softwareentwicklung. Refurbished Hardware & exone Neugeräte. Persönlicher Service mit Kaffee!';
$current_page = 'home';

// Header includen
include __DIR__ . '/templates/header.php';
?>

<!-- Hero-Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>IT-Lösungen mit Herz</h1>
            <p class="lead">
                Willkommen bei PC-Wittfoot im Systemhäuschen! Wir beraten Sie persönlich, verständlich und mit einer Tasse Kaffee.
                Von der Diagnose über die Reparatur bis zum Verkauf - bei uns sind Sie in guten Händen.
            </p>
            <div class="btn-group">
                <a href="<?= BASE_URL ?>/shop" class="btn btn-primary">Zum Shop</a>
                <a href="<?= BASE_URL ?>/termin" class="btn btn-warning">Termin buchen</a>
            </div>
        </div>
    </div>
</section>

<!-- Leistungen Overview -->
<section class="section">
    <div class="container">
        <h2 class="text-center mb-lg">Unsere Leistungen</h2>

        <div class="grid grid-cols-1 grid-cols-md-2 grid-cols-lg-3 gap-lg">
            <div class="card text-center">
                <div class="card-icon" aria-hidden="true">🔧</div>
                <h3>Diagnose & Reparatur</h3>
                <p>Professionelle Fehlerdiagnose und Reparatur für alle Geräte. Schnell, zuverlässig und transparent.</p>
            </div>

            <div class="card text-center">
                <div class="card-icon" aria-hidden="true">💻</div>
                <h3>Hardware-Verkauf</h3>
                <p>Technik wie Neu! Hochwertige Refurbished Hardware mit 24 Monate Garantie. Neue Business Hardware <strong>exone Business</strong>.</p>
            </div>

            <div class="card text-center">
                <div class="card-icon" aria-hidden="true">💡</div>
                <h3>Beratung & Planung</h3>
                <p>Individuelle IT-Beratung für Privatkunden und Gewerbe. Wir finden die passende Lösung für Sie.</p>
            </div>

            <div class="card text-center">
                <div class="card-icon" aria-hidden="true">⚙️</div>
                <h3>Softwareentwicklung</h3>
                <p>Maßgeschneiderte Software-Lösungen, Webseiten/Apps und Tools für Linux & Windows. Von der Idee bis zur Umsetzung.</p>
            </div>

            <div class="card text-center">
                <div class="card-icon" aria-hidden="true">🛡️</div>
                <h3>Wartung & Support</h3>
                <p>Regelmäßige Wartung, Sicherheits-Check und Support. Damit Ihre IT immer zuverlässig läuft.</p>
            </div>

            <div class="card text-center">
                <div class="card-icon" aria-hidden="true">📦</div>
                <h3>Projektierung</h3>
                <p>Komplette IT-Projekte aus einer Hand. Planung, Beschaffung, Installation und Schulung.</p>
            </div>
        </div>

        <div class="text-center mt-xl">
            <a href="<?= BASE_URL ?>/leistungen" class="btn btn-outline">Alle Leistungen ansehen</a>
        </div>
    </div>
</section>

<!-- Featured Products -->
<?php if (!empty($featured_products)): ?>
<section class="section bg-secondary">
    <div class="container">
        <h2 class="text-center mb-lg">Empfohlene Produkte</h2>

        <div class="grid grid-cols-1 grid-cols-md-2 grid-cols-lg-3 gap-lg">
            <?php foreach ($featured_products as $product): ?>
                <div class="card product-card" data-href="<?= BASE_URL ?>/produkt/<?= e($product['slug']) ?>">
                    <div class="card-header">
                        <span class="badge <?= $product['condition_type'] === 'neu' ? 'primary' : 'secondary' ?>">
                            <?= e(ucfirst($product['condition_type'])) ?>
                        </span>
                        <?php if ($product['stock'] <= 3 && $product['stock'] > 0): ?>
                            <span class="badge warning">Nur noch <?= $product['stock'] ?> verfügbar</span>
                        <?php elseif ($product['stock'] == 0): ?>
                            <span class="badge error">Ausverkauft</span>
                        <?php endif; ?>
                    </div>

                    <h3><?= e($product['name']) ?></h3>
                    <p class="text-muted"><?= e($product['brand']) ?> • <?= e($product['category_name']) ?></p>
                    <p><?= e($product['short_description']) ?></p>

                    <div class="card-footer">
                        <strong class="price"><?= format_price($product['price']) ?></strong>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-xl">
            <a href="<?= BASE_URL ?>/shop" class="btn btn-primary">Alle Produkte ansehen</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Kategorien -->
<?php if (!empty($categories)): ?>
<section class="section">
    <div class="container">
        <h2 class="text-center mb-lg">Kategorien entdecken</h2>

        <div class="grid grid-cols-2 grid-cols-md-4 gap-md">
            <?php foreach ($categories as $category): ?>
                <a href="<?= BASE_URL ?>/shop?kategorie=<?= e($category['slug']) ?>" class="category-card">
                    <div class="category-icon" aria-hidden="true"><?= e($category['icon'] ?? '📦') ?></div>
                    <h3><?= e($category['name']) ?></h3>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Blog Preview -->
<?php if (!empty($blog_posts)): ?>
<section class="section">
    <div class="container">
        <h2 class="text-center mb-lg">Aktuelles & Tipps</h2>

        <div class="grid grid-cols-1 grid-cols-md-3 gap-lg">
            <?php foreach ($blog_posts as $post): ?>
                <article class="card">
                    <div class="card-meta">
                        <time datetime="<?= e($post['published_at']) ?>">
                            <?= format_date($post['published_at']) ?>
                        </time>
                    </div>

                    <h3><?= e($post['title']) ?></h3>
                    <p><?= e($post['excerpt']) ?></p>

                    <a href="<?= BASE_URL ?>/blog/<?= e($post['slug']) ?>" class="btn btn-outline btn-sm">
                        Weiterlesen
                    </a>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-xl">
            <a href="<?= BASE_URL ?>/blog" class="btn btn-outline">Alle Beiträge ansehen</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Warum PC-Wittfoot -->
<section class="section bg-primary-dark text-white">
    <div class="container">
        <h2 class="text-center mb-lg">Warum PC-Wittfoot?</h2>

        <div class="grid grid-cols-1 grid-cols-md-2 grid-cols-lg-4 gap-lg">
            <div class="text-center">
                <div class="icon-large" aria-hidden="true">⭐</div>
                <h3>5 Sterne Bewertung</h3>
                <p>Exzellente Bewertungen auf Google und Kleinanzeigen.de</p>
            </div>

            <div class="text-center">
                <div class="icon-large" aria-hidden="true">☕</div>
                <h3>Persönlich & entspannt</h3>
                <p>Beratung im Sitzen mit Kaffee. Wir nehmen uns Zeit für Sie.</p>
            </div>

            <div class="text-center">
                <div class="icon-large" aria-hidden="true">🗣️</div>
                <h3>Verständlich erklärt</h3>
                <p>Keine Fachchinesisch. Wir erklären IT so, dass jeder es versteht.</p>
            </div>

            <div class="text-center">
                <div class="icon-large" aria-hidden="true">🐕</div>
                <h3>Mit Baileys</h3>
                <p>Unser Bürohund Baileys ist Teil des Teams und sorgt für gute Laune.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section bg-secondary">
    <div class="container text-center">
        <h2>Haben Sie Fragen oder benötigen Sie Hilfe?</h2>
        <p class="lead mb-lg">
            Kontaktieren Sie uns per Telefon, E-Mail oder buchen Sie direkt einen Termin in unserem Ladengeschäft.
        </p>

        <div class="btn-group">
            <a href="tel:+49123456789" class="btn btn-primary">
                Jetzt anrufen
            </a>
            <a href="<?= BASE_URL ?>/termin" class="btn btn-warning">
                Termin buchen
            </a>
            <a href="<?= BASE_URL ?>/kontakt" class="btn btn-outline">
                Kontaktformular
            </a>
        </div>
    </div>
</section>

<script>
// Produkt-Cards klickbar machen (mit Keyboard-Support)
document.querySelectorAll('.product-card[data-href]').forEach(card => {
    // Tastatur-Navigation ermöglichen
    card.setAttribute('tabindex', '0');
    card.setAttribute('role', 'link');
    card.setAttribute('aria-label', card.querySelector('h3').textContent);

    // Click-Handler
    card.addEventListener('click', function() {
        window.location.href = this.dataset.href;
    });

    // Keyboard-Handler (Enter und Space)
    card.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            window.location.href = this.dataset.href;
        }
    });

    // Visuelles Feedback (Cursor)
    card.style.cursor = 'pointer';
});
</script>

<?php
// Footer includen
include __DIR__ . '/templates/footer.php';
?>
