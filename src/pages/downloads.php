<?php
/**
 * Downloads - Nützliche Tools & Dokumentation
 */

$db = Database::getInstance();

// Kategorie-Filter
$category_filter = isset($_GET['kategorie']) ? trim($_GET['kategorie']) : '';
$valid_categories = ['tools', 'drivers', 'documentation', 'updates', 'other'];
if (!empty($category_filter) && !in_array($category_filter, $valid_categories)) {
    $category_filter = '';
}
$is_filtered = !empty($category_filter);

// WHERE-Bedingungen aufbauen
$where_conditions = ['is_active = 1'];
$params = [];

if ($is_filtered) {
    $where_conditions[] = 'category = :category';
    $params[':category'] = $category_filter;
}

$where_clause = implode(' AND ', $where_conditions);

// Downloads laden
$downloads = $db->query("
    SELECT *
    FROM downloads
    WHERE {$where_clause}
    ORDER BY sort_order ASC, created_at DESC
", $params);

// Kategorie-Zählung für Filter-Buttons
$category_counts = $db->query("
    SELECT category, COUNT(*) as count
    FROM downloads
    WHERE is_active = 1
    GROUP BY category
    ORDER BY FIELD(category, 'tools', 'drivers', 'documentation', 'updates', 'other')
");

// Kategorie-Labels
$category_labels = [
    'tools' => '🔧 Tools',
    'drivers' => '💾 Treiber',
    'documentation' => '📄 Dokumentation',
    'updates' => '🔄 Updates',
    'other' => '📦 Sonstiges'
];

// Gesamtzahl Downloads
$total_count = count($downloads);

// Page-Meta
$page_title = $is_filtered
    ? $category_labels[$category_filter] . ' - Downloads | PC-Wittfoot UG'
    : 'Downloads - Nützliche Tools & Dokumentation | PC-Wittfoot UG';
$page_description = 'Kostenlose Tools, Treiber und Dokumentationen. Laden Sie nützliche Software und Informationen herunter.';
$current_page = 'downloads';

include __DIR__ . '/../templates/header.php';
?>

<!-- Hero Section -->
<section class="hero hero-downloads" aria-label="Downloads">
    <div class="container">
        <div class="hero-content">
            <h1>📥 Downloads</h1>
            <p class="lead">
                Nützliche Tools, Treiber und Dokumentationen für Ihre IT-Bedürfnisse.
            </p>

            <!-- Stats -->
            <div class="hero-stats">
                <span><span aria-hidden="true">📦</span> <?= $total_count ?> Downloads</span>
                <span><span aria-hidden="true">🏷️</span> <?= count($category_counts) ?> Kategorien</span>
                <span><span aria-hidden="true">✓</span> Kostenlos</span>
            </div>
        </div>
    </div>
</section>

<section class="section downloads-section">
    <div class="container">
        <!-- Kategorie-Filter -->
        <?php if (!empty($category_counts)): ?>
        <div class="category-filter mb-xl">
            <a href="<?= BASE_URL ?>/downloads" class="filter-btn <?= !$is_filtered ? 'active' : '' ?>">
                Alle (<?= array_sum(array_column($category_counts, 'count')) ?>)
            </a>
            <?php foreach ($category_counts as $cat): ?>
                <a href="<?= BASE_URL ?>/downloads?kategorie=<?= e($cat['category']) ?>"
                   class="filter-btn <?= $category_filter === $cat['category'] ? 'active' : '' ?>">
                    <?= $category_labels[$cat['category']] ?? e($cat['category']) ?> (<?= $cat['count'] ?>)
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (empty($downloads)): ?>
            <!-- Keine Downloads -->
            <div class="card text-center">
                <div style="font-size: 4rem; margin-bottom: var(--space-md);" aria-hidden="true">📥</div>
                <h3>Keine Downloads verfügbar</h3>
                <p>Schauen Sie bald wieder vorbei für neue Inhalte.</p>
                <?php if ($is_filtered): ?>
                    <a href="<?= BASE_URL ?>/downloads" class="btn btn-primary mt-md">Alle Downloads anzeigen</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <!-- Download-Liste -->
            <div class="downloads-list">
                <?php foreach ($downloads as $download): ?>
                    <article class="download-card card">
                        <div class="download-icon">
                            <?php
                            // Icon basierend auf Kategorie
                            $icons = [
                                'tools' => '🔧',
                                'drivers' => '💾',
                                'documentation' => '📄',
                                'updates' => '🔄',
                                'other' => '📦'
                            ];
                            echo $icons[$download['category']] ?? '📦';
                            ?>
                        </div>
                        <div class="download-content">
                            <div class="download-header">
                                <h3><?= e($download['title']) ?></h3>
                                <?php if (!empty($download['version'])): ?>
                                    <span class="download-version"><?= e($download['version']) ?></span>
                                <?php endif; ?>
                            </div>
                            <p class="download-description"><?= e($download['description']) ?></p>
                            <div class="download-meta">
                                <?php if ($download['file_size']): ?>
                                    <span class="meta-item">
                                        💾 <?= format_file_size($download['file_size']) ?>
                                    </span>
                                <?php endif; ?>
                                <span class="meta-item">
                                    📊 <?= number_format($download['download_count'], 0, ',', '.') ?> Downloads
                                </span>
                            </div>
                        </div>
                        <div class="download-action">
                            <a href="<?= BASE_URL ?>/api/download/<?= e($download['slug']) ?>"
                               class="btn btn-primary"
                               download>
                                📥 Download
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Hinweis-Box -->
        <div class="card mt-xl" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
            <h3 style="margin-top: 0;">💡 Benötigen Sie individuelle Software?</h3>
            <p>
                Wir entwickeln maßgeschneiderte Software-Lösungen für Ihr Unternehmen.
                Von einfachen Tools bis zu komplexen Anwendungen.
            </p>
            <div style="display: flex; gap: var(--space-sm); flex-wrap: wrap; margin-top: var(--space-md);">
                <a href="<?= BASE_URL ?>/kontakt" class="btn btn-primary">
                    Anfrage stellen
                </a>
                <a href="<?= BASE_URL ?>/leistungen" class="btn btn-outline">
                    Mehr über unsere Leistungen
                </a>
            </div>
        </div>
    </div>
</section>

<style>
/* =====================================================
   Downloads-Seite - Einheitliches Design
   ===================================================== */

/* Downloads Section */
.downloads-section {
    background: var(--bg-primary);
}

/* Kategorie-Filter */
.category-filter {
    display: flex;
    gap: var(--space-sm);
    flex-wrap: wrap;
    justify-content: center;
    padding: var(--space-lg);
    background: var(--bg-secondary);
    border-radius: var(--border-radius-md);
    border: 1px solid var(--border-color);
}

.filter-btn {
    padding: 10px 20px;
    background: var(--color-white);
    border: 2px solid var(--border-color);
    border-radius: var(--border-radius-sm);
    color: var(--text-primary);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
    cursor: pointer;
}

.filter-btn:hover {
    border-color: var(--color-primary);
    background: var(--color-primary-light);
}

.filter-btn.active {
    background: var(--color-primary);
    color: white;
    border-color: var(--color-primary);
}

/* Download-Liste */
.downloads-list {
    display: flex;
    flex-direction: column;
    gap: var(--space-lg);
}

.download-card {
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: var(--space-lg);
    align-items: center;
    padding: var(--space-lg);
    background: var(--color-white);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-md);
    transition: transform 0.2s, box-shadow 0.2s;
}

.download-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.download-icon {
    font-size: 3rem;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-secondary);
    border-radius: var(--border-radius-md);
}

.download-content {
    min-width: 0; /* Verhindert Overflow */
}

.download-header {
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    margin-bottom: var(--space-xs);
    flex-wrap: wrap;
}

.download-card h3 {
    font-size: 1.25rem;
    margin: 0;
    color: var(--text-primary);
}

.download-version {
    padding: 4px 12px;
    background: var(--color-primary);
    color: white;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 600;
    white-space: nowrap;
}

.download-description {
    color: var(--text-secondary);
    margin: var(--space-sm) 0;
    line-height: 1.6;
}

.download-meta {
    display: flex;
    gap: var(--space-md);
    flex-wrap: wrap;
    font-size: 0.875rem;
    color: var(--text-muted);
}

.meta-item {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.download-action {
    display: flex;
    align-items: center;
}

/* Mobile Optimierung */
@media (max-width: 768px) {
    .download-card {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .download-icon {
        margin: 0 auto;
    }

    .download-header {
        justify-content: center;
    }

    .download-meta {
        justify-content: center;
    }

    .download-action {
        justify-content: center;
    }

    .category-filter {
        justify-content: flex-start;
        padding: var(--space-md);
    }

    .filter-btn {
        font-size: 0.875rem;
        padding: 8px 16px;
    }
}

/* Dark Mode */
@media (prefers-color-scheme: dark) {
    :root:not([data-theme="light"]) .downloads-section {
        background: var(--bg-primary);
    }

    :root:not([data-theme="light"]) .download-card {
        background: var(--bg-secondary);
    }

    :root:not([data-theme="light"]) .category-filter {
        background: var(--bg-tertiary);
    }

    :root:not([data-theme="light"]) .filter-btn {
        background: var(--bg-secondary);
    }

    :root:not([data-theme="light"]) .download-icon {
        background: var(--bg-tertiary);
    }
}

[data-theme="dark"] .downloads-section {
    background: var(--bg-primary);
}

[data-theme="dark"] .download-card {
    background: var(--bg-secondary);
}

[data-theme="dark"] .category-filter {
    background: var(--bg-tertiary);
}

[data-theme="dark"] .filter-btn {
    background: var(--bg-secondary);
}

[data-theme="dark"] .download-icon {
    background: var(--bg-tertiary);
}
</style>

<?php include __DIR__ . '/../templates/footer.php'; ?>
