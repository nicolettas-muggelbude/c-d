<?php
/**
 * Blog - Übersicht & Suche (Wissensdatenbank)
 */

$db = Database::getInstance();

// Pagination
$page_num = isset($_GET['seite']) ? max(1, intval($_GET['seite'])) : 1;

// Suchbegriff
$search_query = isset($_GET['s']) ? trim($_GET['s']) : '';
$is_search = !empty($search_query);

// Kategorie-Filter
$category_filter = isset($_GET['kategorie']) ? trim($_GET['kategorie']) : '';
$valid_categories = ['Allgemein', 'Hardware', 'Software', 'Tipps', 'News'];
if (!empty($category_filter) && !in_array($category_filter, $valid_categories)) {
    $category_filter = '';
}
$is_filtered = !empty($category_filter);

// WHERE-Bedingungen aufbauen
$where_conditions = ['published = 1'];
$count_params = [];
$search_params = [];

if ($is_search) {
    $where_conditions[] = 'MATCH(title, excerpt, content, keywords) AGAINST(:search_where IN NATURAL LANGUAGE MODE)';
    $count_params[':search_where'] = $search_query;
    $search_params[':search_where'] = $search_query;
    $search_params[':search_select'] = $search_query;
}

if ($is_filtered) {
    $where_conditions[] = 'category = :category';
    $count_params[':category'] = $category_filter;
    $search_params[':category'] = $category_filter;
}

$where_clause = implode(' AND ', $where_conditions);

// Gesamtzahl Blog-Posts
$total_posts = $db->querySingle("
    SELECT COUNT(*) as count
    FROM blog_posts
    WHERE {$where_clause}
", $count_params);

$total_count = $total_posts['count'] ?? 0;

// Pagination berechnen
$pagination = paginate($total_count, 12, $page_num);

// Blog-Posts laden
if ($is_search) {
    // FULLTEXT-Suche mit Relevanz-Scoring
    $posts = $db->query("
        SELECT *,
        MATCH(title, excerpt, content, keywords) AGAINST(:search_select IN NATURAL LANGUAGE MODE) as relevance
        FROM blog_posts
        WHERE {$where_clause}
        ORDER BY relevance DESC, published_at DESC
        LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}
    ", $search_params);
} else {
    // Normale Ansicht oder Kategorie-Filter
    $posts = $db->query("
        SELECT *
        FROM blog_posts
        WHERE {$where_clause}
        ORDER BY published_at DESC
        LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}
    ", $count_params);
}

// Kategorie-Zählung für Filter-Buttons
$category_counts = $db->query("
    SELECT category, COUNT(*) as count
    FROM blog_posts
    WHERE published = 1
    GROUP BY category
    ORDER BY FIELD(category, 'Allgemein', 'Hardware', 'Software', 'Tipps', 'News')
");

// Page-Meta
$page_title = $is_search
    ? 'Suche: ' . e($search_query) . ' | Blog | PC-Wittfoot UG'
    : 'Blog - Aktuelles & Tipps | PC-Wittfoot UG';
$page_description = 'IT-Tipps, Neuigkeiten und Wissenswertes rund um Computer, Hardware und Software.';
$current_page = 'blog';

include __DIR__ . '/../templates/header.php';
?>

<!-- Hero Section mit Suche -->
<section class="hero hero-blog-search" aria-label="Blog durchsuchen">
    <div class="container">
        <div class="hero-content">
            <h1>💡 Blog & Wissensdatenbank</h1>
            <p class="lead">
                IT-Tipps, Neuigkeiten und Wissenswertes rund um Computer, Hardware und Software.
            </p>

            <!-- Suchfeld direkt im Hero -->
            <div class="hero-search">
                <form method="get" action="<?= BASE_URL ?>/blog">
                    <div class="hero-search-input-group">
                        <input type="search"
                               name="s"
                               placeholder="Durchsuchen Sie unsere Wissensdatenbank..."
                               value="<?= e($search_query) ?>"
                               aria-label="Blog durchsuchen"
                               autocomplete="off">
                        <button type="submit">
                            🔍 Suchen
                        </button>
                    </div>
                </form>
            </div>

            <!-- Stats -->
            <div class="hero-stats">
                <span><span aria-hidden="true">📝</span> <?= $total_count ?> Beiträge</span>
                <span><span aria-hidden="true">🏷️</span> <?= count($category_counts) ?> Kategorien</span>
                <span><span aria-hidden="true">💡</span> Regelmäßig aktualisiert</span>
            </div>
        </div>
    </div>
</section>

<section class="section blog-section">
    <div class="container">
        <!-- Suchergebnis-Info -->
        <?php if ($is_search): ?>
            <div class="search-results-bar">
                <span class="search-results-count">
                    <?= $total_count ?> Ergebnis<?= $total_count !== 1 ? 'se' : '' ?> für "<?= e($search_query) ?>"
                </span>
                <a href="<?= BASE_URL ?>/blog" class="btn btn-sm btn-outline">
                    ✕ Suche zurücksetzen
                </a>
            </div>
        <?php endif; ?>

        <?php if (empty($posts)): ?>
            <?php if ($is_search): ?>
                <!-- Keine Suchergebnisse -->
                <div class="card text-center">
                    <div style="font-size: 4rem; margin-bottom: var(--space-md);" aria-hidden="true">🔍</div>
                    <h3>Keine Ergebnisse gefunden</h3>
                    <p>Ihre Suche nach "<?= e($search_query) ?>" ergab leider keine Treffer.</p>
                    <p class="text-muted">
                        <strong>Tipps:</strong><br>
                        • Überprüfen Sie die Schreibweise<br>
                        • Verwenden Sie allgemeinere Begriffe<br>
                        • Probieren Sie verschiedene Suchbegriffe
                    </p>
                    <div class="mt-md">
                        <a href="<?= BASE_URL ?>/blog" class="btn btn-primary">Alle Beiträge anzeigen</a>
                        <a href="<?= BASE_URL ?>/kontakt" class="btn btn-outline">Frage direkt stellen</a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Keine Beiträge vorhanden -->
                <div class="card text-center">
                    <div style="font-size: 4rem; margin-bottom: var(--space-md);" aria-hidden="true">📝</div>
                    <h3>Noch keine Beiträge</h3>
                    <p>Schauen Sie bald wieder vorbei für neue Inhalte.</p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- Ergebnisinfo -->
            <?php if ($pagination['total_pages'] > 1 || $is_search): ?>
            <div class="results-info mb-lg">
                <p class="text-muted">
                    <?php if ($is_search): ?>
                        Zeige <?= count($posts) ?> von <?= $total_count ?> Ergebnis<?= $total_count !== 1 ? 'sen' : '' ?>
                    <?php else: ?>
                        <?= $total_count ?> Beitrag<?= $total_count !== 1 ? 'e' : '' ?>
                        <?php if ($pagination['total_pages'] > 1): ?>
                            (Seite <?= $pagination['current_page'] ?> von <?= $pagination['total_pages'] ?>)
                        <?php endif; ?>
                    <?php endif; ?>
                </p>
            </div>
            <?php endif; ?>

            <!-- Blog-Grid -->
            <div class="grid grid-cols-1 grid-cols-md-2 grid-cols-lg-3 gap-lg">
                <?php foreach ($posts as $post): ?>
                    <article class="card blog-card <?= $is_search ? 'search-result' : '' ?>" data-href="<?= BASE_URL ?>/blog/<?= e($post['slug']) ?>">
                        <!-- Thumbnail -->
                        <?php if (!empty($post['hero_image'])): ?>
                            <div class="blog-card-thumbnail">
                                <img src="<?= e($post['hero_image']) ?>" alt="<?= e($post['hero_image_alt'] ?: $post['title']) ?>" loading="lazy">
                            </div>
                        <?php elseif (!empty($post['emoji'])): ?>
                            <!-- Emoji als Fallback -->
                            <div class="blog-card-emoji">
                                <?= e($post['emoji']) ?>
                            </div>
                        <?php endif; ?>

                        <div class="card-meta">
                            <time datetime="<?= e($post['published_at']) ?>">
                                <?= format_date($post['published_at']) ?>
                            </time>
                            <?php if (!empty($post['author_name'])): ?>
                                <span class="text-muted">• von <?= e($post['author_name']) ?></span>
                            <?php endif; ?>

                            <?php if ($is_search && isset($post['relevance'])): ?>
                                <span class="relevance-badge" title="Relevanz: <?= round($post['relevance'], 2) ?>">
                                    <?php
                                    $stars = min(3, max(1, ceil($post['relevance'] / 2)));
                                    echo str_repeat('⭐', $stars);
                                    ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <h3><?= e($post['title']) ?></h3>
                        <p><?= e($post['excerpt']) ?></p>

                        <?php if ($is_search && !empty($post['keywords'])): ?>
                            <div class="post-tags">
                                <?php
                                $keywords = array_slice(explode(',', $post['keywords']), 0, 3);
                                foreach ($keywords as $keyword):
                                    $keyword = trim($keyword);
                                    if (!empty($keyword)):
                                ?>
                                    <span class="tag"><?= e($keyword) ?></span>
                                <?php
                                    endif;
                                endforeach;
                                ?>
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($pagination['total_pages'] > 1): ?>
            <nav class="pagination mt-xl" aria-label="Seitennavigation">
                <?php if ($pagination['has_prev']): ?>
                    <a href="<?= BASE_URL ?>/blog?<?= $is_search ? 's=' . urlencode($search_query) . '&' : '' ?>seite=<?= $pagination['current_page'] - 1 ?>" class="btn btn-outline">
                        ← Zurück
                    </a>
                <?php endif; ?>

                <span class="pagination-info">
                    Seite <?= $pagination['current_page'] ?> von <?= $pagination['total_pages'] ?>
                </span>

                <?php if ($pagination['has_next']): ?>
                    <a href="<?= BASE_URL ?>/blog?<?= $is_search ? 's=' . urlencode($search_query) . '&' : '' ?>seite=<?= $pagination['current_page'] + 1 ?>" class="btn btn-outline">
                        Weiter →
                    </a>
                <?php endif; ?>
            </nav>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Hilfreiche Hinweise bei Suche -->
        <?php if ($is_search && !empty($posts)): ?>
        <div class="card mt-xl" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
            <h3 style="margin-top: 0;">💡 Nicht gefunden, was Sie suchen?</h3>
            <p>
                Unsere Wissensdatenbank wird ständig erweitert. Wenn Sie eine spezifische Frage haben:
            </p>
            <div style="display: flex; gap: var(--space-sm); flex-wrap: wrap; margin-top: var(--space-md);">
                <a href="<?= BASE_URL ?>/kontakt" class="btn btn-primary">
                    Frage direkt stellen
                </a>
                <a href="<?= BASE_URL ?>/termin" class="btn btn-outline">
                    Beratungstermin buchen
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
/* =====================================================
   Blog-Seite - Harmonisches Layout passend zum Gesamtdesign
   ===================================================== */

/* Blog Section - Einheitlicher Hintergrund */
.blog-section {
    background: #f8f6f3;
}

/* Suchergebnis-Bar */
.search-results-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-xl);
    padding: var(--space-md) var(--space-lg);
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-md);
    flex-wrap: wrap;
    gap: var(--space-sm);
}

.search-results-count {
    font-weight: 600;
    font-size: 1rem;
    color: var(--color-primary);
}

/* Blog Cards - Einheitliches Design */
.blog-card {
    background: #ffffff;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-md);
    padding: var(--space-lg);
    transition: transform 0.2s, box-shadow 0.2s;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.blog-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.blog-card-thumbnail {
    margin: calc(-1 * var(--space-lg)) calc(-1 * var(--space-lg)) var(--space-md) calc(-1 * var(--space-lg));
    border-radius: var(--border-radius-md) var(--border-radius-md) 0 0;
    overflow: hidden;
    height: 160px;
}

.blog-card-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.blog-card-emoji {
    font-size: 2.5rem;
    margin-bottom: var(--space-sm);
}

.blog-card .card-meta {
    font-size: 1rem;
    color: var(--text-muted);
    margin-bottom: var(--space-sm);
}

.blog-card h3 {
    font-size: 1.25rem;
    margin-bottom: var(--space-sm);
    line-height: 1.4;
}

.blog-card p {
    font-size: 1rem;
    line-height: 1.6;
    color: var(--text-secondary);
    flex-grow: 1;
}

.relevance-badge {
    margin-left: var(--space-xs);
    font-size: 1rem;
}

.post-tags {
    display: flex;
    gap: var(--space-xs);
    flex-wrap: wrap;
    margin-top: var(--space-sm);
}

.post-tags .tag {
    font-size: 1rem;
    padding: 6px 14px;
    background: var(--color-primary);
    color: white;
    border-radius: 12px;
    white-space: nowrap;
}

.search-result {
    border-left: 3px solid var(--color-primary);
}

/* Ergebnis-Info */
.results-info p {
    font-size: 1rem;
}

/* Hinweis-Box */
.blog-section .card h3 {
    font-size: 1.25rem;
}

/* Mobile Optimierung */
@media (max-width: 768px) {
    .search-results-bar {
        flex-direction: column;
        align-items: flex-start;
        padding: var(--space-md);
    }

    .blog-card h3 {
        font-size: 1.125rem;
    }
}

/* Dark Mode */
@media (prefers-color-scheme: dark) {
    :root:not([data-theme="light"]) .blog-section {
        background: var(--bg-primary);
    }

    :root:not([data-theme="light"]) .blog-card {
        background: var(--bg-secondary);
    }

    :root:not([data-theme="light"]) .search-results-bar {
        background: var(--bg-tertiary);
    }
}

[data-theme="dark"] .blog-section {
    background: var(--bg-primary);
}

[data-theme="dark"] .blog-card {
    background: var(--bg-secondary);
}

[data-theme="dark"] .search-results-bar {
    background: var(--bg-tertiary);
}
</style>

<script>
// Blog-Cards klickbar machen (mit Keyboard-Support)
document.querySelectorAll('.blog-card[data-href]').forEach(card => {
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

// Search Input Focus nur auf Desktop
const searchInput = document.querySelector('.hero-search input[type="search"]');
if (searchInput && !searchInput.value && window.innerWidth >= 768) {
    // Nur fokussieren wenn leer und Desktop (kein Mobile)
    const urlParams = new URLSearchParams(window.location.search);
    if (!urlParams.has('s')) {
        searchInput.focus();
    }
}
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
