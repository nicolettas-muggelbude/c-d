<?php
/**
 * Shop - Produktübersicht mit Filtern
 */

$db = Database::getInstance();

// Filter-Parameter
$category_filter = isset($_GET['kategorie']) ? sanitize($_GET['kategorie']) : '';
$brand_filter = isset($_GET['marke']) ? sanitize($_GET['marke']) : '';
$condition_filter = isset($_GET['zustand']) ? sanitize($_GET['zustand']) : '';
$search = isset($_GET['suche']) ? sanitize($_GET['suche']) : '';
$page_num = isset($_GET['seite']) ? max(1, intval($_GET['seite'])) : 1;

// SQL-Query aufbauen
$where = ["p.is_active = 1"];
$params = [];

if ($category_filter) {
    $where[] = "c.slug = :category";
    $params[':category'] = $category_filter;
}

if ($brand_filter) {
    $where[] = "p.brand = :brand";
    $params[':brand'] = $brand_filter;
}

if ($condition_filter) {
    $where[] = "p.condition_type = :condition";
    $params[':condition'] = $condition_filter;
}

if ($search) {
    $where[] = "(p.name LIKE :search1 OR p.description LIKE :search2 OR p.brand LIKE :search3)";
    $search_term = '%' . $search . '%';
    $params[':search1'] = $search_term;
    $params[':search2'] = $search_term;
    $params[':search3'] = $search_term;
}

$where_sql = implode(' AND ', $where);

// Gesamtzahl Produkte
$total_products = $db->querySingle("
    SELECT COUNT(*) as count
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE $where_sql
", $params);

$total_count = $total_products['count'] ?? 0;

// Pagination
$pagination = paginate($total_count, PRODUCTS_PER_PAGE, $page_num);

// Produkte laden
$products = $db->query("
    SELECT p.*, c.name as category_name, c.slug as category_slug
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE $where_sql
    ORDER BY p.is_featured DESC, p.created_at DESC
    LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}
", $params);

// Kategorien für Filter
$categories = $db->query("
    SELECT c.*, COUNT(p.id) as product_count
    FROM categories c
    LEFT JOIN products p ON c.id = p.category_id AND p.is_active = 1
    WHERE c.is_active = 1
    GROUP BY c.id
    ORDER BY c.sort_order
");

// Marken für Filter
$brands = $db->query("
    SELECT DISTINCT brand, COUNT(*) as count
    FROM products
    WHERE is_active = 1 AND brand IS NOT NULL
    GROUP BY brand
    ORDER BY brand
");

// Page-Meta
$page_title = 'Shop - Hardware & Zubehör | PC-Wittfoot UG';
if ($category_filter) {
    $current_category = array_filter($categories, fn($c) => $c['slug'] === $category_filter);
    if ($current_category) {
        $cat = reset($current_category);
        $page_title = e($cat['name']) . ' - Shop | PC-Wittfoot UG';
    }
}
$page_description = 'Refurbished Notebooks, PCs, Tablets und IT-Zubehör. Geprüfte Qualität mit Garantie.';
$current_page = 'shop';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <h1>Shop</h1>
        <p class="lead mb-xl">
            Hochwertige Refurbished Hardware und exone Neugeräte. Alle Geräte sind geprüft und kommen mit Garantie.
        </p>

        <div class="shop-layout">
            <!-- Sidebar mit Filtern -->
            <aside class="shop-sidebar">
                <div class="card">
                    <h3>Filter</h3>

                    <!-- Suche -->
                    <form method="get" action="<?= BASE_URL ?>/shop" class="mb-lg">
                        <div class="form-group">
                            <label for="search">Suche</label>
                            <input type="text" id="search" name="suche" value="<?= e($search) ?>" placeholder="Produkt suchen...">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Suchen</button>
                    </form>

                    <!-- Kategorien -->
                    <div class="filter-group">
                        <h4>Kategorien</h4>
                        <ul class="filter-list">
                            <li>
                                <a href="<?= BASE_URL ?>/shop" class="<?= !$category_filter ? 'active' : '' ?>">
                                    Alle (<?= $total_count ?>)
                                </a>
                            </li>
                            <?php foreach ($categories as $cat): ?>
                                <li>
                                    <a href="<?= BASE_URL ?>/shop?kategorie=<?= e($cat['slug']) ?>"
                                       class="<?= $category_filter === $cat['slug'] ? 'active' : '' ?>">
                                        <?= e($cat['icon'] ?? '📦') ?> <?= e($cat['name']) ?> (<?= $cat['product_count'] ?>)
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Marken -->
                    <?php if (!empty($brands)): ?>
                    <div class="filter-group">
                        <h4>Marken</h4>
                        <ul class="filter-list">
                            <?php foreach ($brands as $brand): ?>
                                <li>
                                    <a href="<?= BASE_URL ?>/shop?marke=<?= e($brand['brand']) ?>"
                                       class="<?= $brand_filter === $brand['brand'] ? 'active' : '' ?>">
                                        <?= e($brand['brand']) ?> (<?= $brand['count'] ?>)
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <!-- Zustand -->
                    <div class="filter-group">
                        <h4>Zustand</h4>
                        <ul class="filter-list">
                            <li><a href="<?= BASE_URL ?>/shop?zustand=neu" class="<?= $condition_filter === 'neu' ? 'active' : '' ?>">Neu</a></li>
                            <li><a href="<?= BASE_URL ?>/shop?zustand=refurbished" class="<?= $condition_filter === 'refurbished' ? 'active' : '' ?>">Refurbished</a></li>
                            <li><a href="<?= BASE_URL ?>/shop?zustand=gebraucht" class="<?= $condition_filter === 'gebraucht' ? 'active' : '' ?>">Gebraucht</a></li>
                        </ul>
                    </div>

                    <!-- Filter zurücksetzen -->
                    <?php if ($category_filter || $brand_filter || $condition_filter || $search): ?>
                    <div class="mt-md">
                        <a href="<?= BASE_URL ?>/shop" class="btn btn-outline btn-block">
                            Filter zurücksetzen
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </aside>

            <!-- Produktliste -->
            <div class="shop-content">
                <?php if (empty($products)): ?>
                    <div class="card text-center">
                        <div style="font-size: 4rem; margin-bottom: var(--space-md);">📦</div>
                        <h3>Keine Produkte gefunden</h3>
                        <p>Versuchen Sie andere Filter oder kontaktieren Sie uns für spezielle Anfragen.</p>
                        <a href="<?= BASE_URL ?>/shop" class="btn btn-outline mt-md">Filter zurücksetzen</a>
                    </div>
                <?php else: ?>
                    <!-- Ergebnisinfo -->
                    <div class="results-info mb-lg">
                        <p class="text-muted">
                            <?= $total_count ?> Produkt<?= $total_count !== 1 ? 'e' : '' ?> gefunden
                            <?php if ($pagination['total_pages'] > 1): ?>
                                (Seite <?= $pagination['current_page'] ?> von <?= $pagination['total_pages'] ?>)
                            <?php endif; ?>
                        </p>
                    </div>

                    <!-- Produktgrid -->
                    <div class="grid grid-cols-1 grid-cols-md-2 grid-cols-lg-3 gap-lg">
                        <?php foreach ($products as $product): ?>
                            <div class="card product-card" data-href="<?= BASE_URL ?>/produkt/<?= e($product['slug']) ?>">
                                <div class="card-header">
                                    <span class="badge <?= $product['condition_type'] === 'neu' ? 'primary' : 'secondary' ?>">
                                        <?= e(ucfirst($product['condition_type'])) ?>
                                    </span>
                                    <?php if ($product['is_featured']): ?>
                                        <span class="badge warning">⭐ Empfohlen</span>
                                    <?php endif; ?>
                                </div>

                                <h3><?= e($product['name']) ?></h3>
                                <p class="text-muted"><?= e($product['brand']) ?> • <?= e($product['category_name']) ?></p>
                                <p><?= e($product['short_description']) ?></p>

                                <?php if ($product['stock'] > 0): ?>
                                    <p class="text-success">✓ Auf Lager (<?= $product['stock'] ?> Stück)</p>
                                <?php else: ?>
                                    <p class="text-error">✗ Nicht verfügbar</p>
                                <?php endif; ?>

                                <div class="card-footer">
                                    <strong class="price"><?= format_price($product['price']) ?></strong>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($pagination['total_pages'] > 1): ?>
                    <nav class="pagination mt-xl" aria-label="Seitennavigation">
                        <?php if ($pagination['has_prev']): ?>
                            <a href="<?= BASE_URL ?>/shop?seite=<?= $pagination['current_page'] - 1 ?><?= $category_filter ? '&kategorie=' . e($category_filter) : '' ?>" class="btn btn-outline">
                                ← Zurück
                            </a>
                        <?php endif; ?>

                        <span class="pagination-info">
                            Seite <?= $pagination['current_page'] ?> von <?= $pagination['total_pages'] ?>
                        </span>

                        <?php if ($pagination['has_next']): ?>
                            <a href="<?= BASE_URL ?>/shop?seite=<?= $pagination['current_page'] + 1 ?><?= $category_filter ? '&kategorie=' . e($category_filter) : '' ?>" class="btn btn-outline">
                                Weiter →
                            </a>
                        <?php endif; ?>
                    </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
// Produkt-Cards klickbar machen
document.querySelectorAll('.product-card[data-href]').forEach(card => {
    card.addEventListener('click', function() {
        window.location.href = this.dataset.href;
    });
});
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
