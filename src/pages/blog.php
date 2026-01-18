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

<section class="section blog-section">
    <div class="container">
        <div class="blog-header">
            <h1>Blog & Wissensdatenbank</h1>
            <p class="lead">
                IT-Tipps, Neuigkeiten und Wissenswertes rund um Computer, Hardware und Software.
            </p>
        </div>

        <!-- Suchfeld -->
        <div class="blog-search-box">
            <form method="get" action="<?= BASE_URL ?>/blog" class="blog-search-form">
                <div class="search-input-group">
                    <input type="search"
                           name="s"
                           placeholder="Durchsuchen Sie unsere Wissensdatenbank..."
                           value="<?= e($search_query) ?>"
                           aria-label="Blog durchsuchen"
                           autocomplete="off">
                    <button type="submit" class="btn btn-primary">
                        🔍 Suchen
                    </button>
                </div>
                <?php if ($is_search): ?>
                    <div class="search-info">
                        <span class="search-results-count">
                            <?= $total_count ?> Ergebnis<?= $total_count !== 1 ? 'se' : '' ?> für "<?= e($search_query) ?>"
                        </span>
                        <a href="<?= BASE_URL ?>/blog" class="btn btn-sm btn-outline">
                            ✕ Suche zurücksetzen
                        </a>
                    </div>
                <?php endif; ?>
            </form>
        </div>

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
                        <!-- Emoji -->
                        <?php if (!empty($post['emoji'])): ?>
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

.blog-header {
    text-align: center;
    margin-bottom: var(--space-xl);
    padding-bottom: var(--space-lg);
    border-bottom: 1px solid var(--border-color);
}

.blog-header h1 {
    font-size: 2.25rem;
    margin-bottom: var(--space-sm);
}

.blog-header .lead {
    font-size: 1.125rem;
    color: var(--text-muted);
    max-width: 600px;
    margin: 0 auto;
}

/* Suchbox - Dezenter, passend zum Design */
.blog-search-box {
    background: var(--bg-secondary);
    padding: var(--space-lg);
    border-radius: var(--border-radius-md);
    margin-bottom: var(--space-xl);
    border: 1px solid var(--border-color);
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}

.blog-search-form {
    width: 100%;
}

.search-input-group {
    display: flex;
    gap: var(--space-sm);
}

.search-input-group input[type="search"] {
    flex: 1;
    padding: var(--space-sm) var(--space-md);
    font-size: 1rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-md);
    background: #ffffff;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.search-input-group input[type="search"]:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(139, 195, 74, 0.15);
}

.search-input-group button {
    white-space: nowrap;
    padding: var(--space-sm) var(--space-lg);
}

.search-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: var(--space-md);
    padding-top: var(--space-md);
    border-top: 1px solid var(--border-color);
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

.blog-card-emoji {
    font-size: 2.5rem;
    margin-bottom: var(--space-sm);
}

.blog-card .card-meta {
    font-size: 0.875rem;
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
    font-size: 0.875rem;
}

.post-tags {
    display: flex;
    gap: var(--space-xs);
    flex-wrap: wrap;
    margin-top: var(--space-sm);
}

.post-tags .tag {
    font-size: 0.8125rem;
    padding: 4px 10px;
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
    .blog-header h1 {
        font-size: 1.75rem;
    }

    .blog-header .lead {
        font-size: 1rem;
    }

    .blog-search-box {
        padding: var(--space-md);
    }

    .search-input-group {
        flex-direction: column;
    }

    .search-input-group button {
        width: 100%;
    }

    .search-info {
        flex-direction: column;
        align-items: flex-start;
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

    :root:not([data-theme="light"]) .blog-search-box {
        background: var(--bg-tertiary);
    }

    :root:not([data-theme="light"]) .search-input-group input[type="search"] {
        background: var(--bg-secondary);
    }
}

[data-theme="dark"] .blog-section {
    background: var(--bg-primary);
}

[data-theme="dark"] .blog-card {
    background: var(--bg-secondary);
}

[data-theme="dark"] .blog-search-box {
    background: var(--bg-tertiary);
}

[data-theme="dark"] .search-input-group input[type="search"] {
    background: var(--bg-secondary);
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

// Search Input Focus beim Laden
const searchInput = document.querySelector('input[type="search"]');
if (searchInput && !searchInput.value) {
    // Nur fokussieren wenn leer (nicht bei Suchergebnissen)
    const urlParams = new URLSearchParams(window.location.search);
    if (!urlParams.has('s')) {
        searchInput.focus();
    }
}
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
