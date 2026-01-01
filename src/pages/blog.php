<?php
/**
 * Blog - Übersicht
 */

$db = Database::getInstance();

// Pagination
$page_num = isset($_GET['seite']) ? max(1, intval($_GET['seite'])) : 1;

// Gesamtzahl Blog-Posts
$total_posts = $db->querySingle("
    SELECT COUNT(*) as count
    FROM blog_posts
    WHERE published = 1
");

$total_count = $total_posts['count'] ?? 0;

// Pagination berechnen
$pagination = paginate($total_count, 12, $page_num);

// Blog-Posts laden
$posts = $db->query("
    SELECT bp.*, u.full_name
    FROM blog_posts bp
    LEFT JOIN users u ON bp.author_id = u.id
    WHERE bp.published = 1
    ORDER BY bp.published_at DESC
    LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}
");

// Page-Meta
$page_title = 'Blog - Aktuelles & Tipps | PC-Wittfoot UG';
$page_description = 'IT-Tipps, Neuigkeiten und Wissenswertes rund um Computer, Hardware und Software.';
$current_page = 'blog';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <h1>Blog</h1>
        <p class="lead mb-xl">
            IT-Tipps, Neuigkeiten und Wissenswertes rund um Computer, Hardware und Software.
        </p>

        <?php if (empty($posts)): ?>
            <div class="card text-center">
                <div style="font-size: 4rem; margin-bottom: var(--space-md);">📝</div>
                <h3>Noch keine Beiträge</h3>
                <p>Schauen Sie bald wieder vorbei für neue Inhalte.</p>
            </div>
        <?php else: ?>
            <!-- Ergebnisinfo -->
            <?php if ($pagination['total_pages'] > 1): ?>
            <div class="results-info mb-lg">
                <p class="text-muted">
                    <?= $total_count ?> Beitrag<?= $total_count !== 1 ? 'e' : '' ?>
                    (Seite <?= $pagination['current_page'] ?> von <?= $pagination['total_pages'] ?>)
                </p>
            </div>
            <?php endif; ?>

            <!-- Blog-Grid -->
            <div class="grid grid-cols-1 grid-cols-md-2 grid-cols-lg-3 gap-lg">
                <?php foreach ($posts as $post): ?>
                    <article class="card blog-card" data-href="<?= BASE_URL ?>/blog/<?= e($post['slug']) ?>">
                        <div class="card-meta">
                            <time datetime="<?= e($post['published_at']) ?>">
                                <?= format_date($post['published_at']) ?>
                            </time>
                            <?php if (!empty($post['full_name'])): ?>
                                <span class="text-muted">• von <?= e($post['full_name']) ?></span>
                            <?php endif; ?>
                        </div>

                        <h3><?= e($post['title']) ?></h3>
                        <p><?= e($post['excerpt']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($pagination['total_pages'] > 1): ?>
            <nav class="pagination mt-xl" aria-label="Seitennavigation">
                <?php if ($pagination['has_prev']): ?>
                    <a href="<?= BASE_URL ?>/blog?seite=<?= $pagination['current_page'] - 1 ?>" class="btn btn-outline">
                        ← Zurück
                    </a>
                <?php endif; ?>

                <span class="pagination-info">
                    Seite <?= $pagination['current_page'] ?> von <?= $pagination['total_pages'] ?>
                </span>

                <?php if ($pagination['has_next']): ?>
                    <a href="<?= BASE_URL ?>/blog?seite=<?= $pagination['current_page'] + 1 ?>" class="btn btn-outline">
                        Weiter →
                    </a>
                <?php endif; ?>
            </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<script>
// Blog-Cards klickbar machen
document.querySelectorAll('.blog-card[data-href]').forEach(card => {
    card.addEventListener('click', function() {
        window.location.href = this.dataset.href;
    });
});
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
