<?php
/**
 * Blog-Detailseite
 */

// Slug aus URL holen
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    header('Location: ' . BASE_URL . '/blog');
    exit;
}

$db = Database::getInstance();

// Blog-Post laden
$post = $db->querySingle("
    SELECT bp.*, u.full_name, u.email
    FROM blog_posts bp
    LEFT JOIN users u ON bp.author_id = u.id
    WHERE bp.slug = :slug AND bp.published = 1
", [':slug' => $slug]);

// 404 wenn nicht gefunden
if (!$post) {
    header('HTTP/1.0 404 Not Found');
    include __DIR__ . '/404.php';
    exit;
}

// Weitere Posts laden (Vorschläge)
$related_posts = $db->query("
    SELECT bp.*, u.full_name
    FROM blog_posts bp
    LEFT JOIN users u ON bp.author_id = u.id
    WHERE bp.published = 1 AND bp.id != :current_id
    ORDER BY bp.published_at DESC
    LIMIT 3
", [':current_id' => $post['id']]);

// Page-Meta
$page_title = e($post['title']) . ' | Blog | PC-Wittfoot UG';
$page_description = e($post['excerpt'] ?? mb_substr(strip_tags($post['content']), 0, 155));
$current_page = 'blog';

include __DIR__ . '/../templates/header.php';
?>

<section class="blog-detail-section">
    <div class="container">
        <!-- Zurück-Link -->
        <div class="mb-lg">
            <a href="<?= BASE_URL ?>/blog" class="btn btn-outline btn-sm">
                ← Zurück zur Übersicht
            </a>
        </div>

        <!-- Blog-Post -->
        <article class="blog-post">
            <!-- Header -->
            <header class="blog-post-header">
                <h1><?= e($post['title']) ?></h1>

                <div class="blog-post-meta">
                    <time datetime="<?= e($post['published_at']) ?>">
                        <?= format_datetime($post['published_at'], 'd.m.Y') ?>
                    </time>

                    <?php if (!empty($post['full_name'])): ?>
                        <span class="text-muted">•</span>
                        <span>von <?= e($post['full_name']) ?></span>
                    <?php endif; ?>
                </div>
            </header>

            <!-- Content -->
            <div class="blog-post-content">
                <?= $post['content'] ?>
            </div>

            <!-- Footer -->
            <footer class="blog-post-footer">
                <hr>
                <div class="text-center">
                    <p class="text-muted">
                        Haben Sie Fragen zu diesem Beitrag?<br>
                        <a href="<?= BASE_URL ?>/kontakt">Kontaktieren Sie uns gerne!</a>
                    </p>
                </div>
            </footer>
        </article>

        <!-- Weitere Beiträge -->
        <?php if (!empty($related_posts)): ?>
        <section class="mt-xxxl">
            <h2 class="text-center mb-lg">Weitere Beiträge</h2>

            <div class="grid grid-cols-1 grid-cols-md-3 gap-lg">
                <?php foreach ($related_posts as $related): ?>
                    <article class="card blog-card" data-href="<?= BASE_URL ?>/blog/<?= e($related['slug']) ?>">
                        <div class="card-meta">
                            <time datetime="<?= e($related['published_at']) ?>">
                                <?= format_date($related['published_at']) ?>
                            </time>
                        </div>

                        <h3><?= e($related['title']) ?></h3>
                        <p><?= e($related['excerpt']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</section>

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
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
