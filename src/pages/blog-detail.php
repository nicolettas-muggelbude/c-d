<?php
/**
 * Blog-Detailseite (Markdown Edition)
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
    SELECT *
    FROM blog_posts
    WHERE slug = :slug AND published = 1
", [':slug' => $slug]);

// 404 wenn nicht gefunden
if (!$post) {
    header('HTTP/1.0 404 Not Found');
    include __DIR__ . '/404.php';
    exit;
}

// Weitere Posts laden (Vorschläge)
$related_posts = $db->query("
    SELECT *
    FROM blog_posts
    WHERE published = 1 AND id != :current_id
    ORDER BY published_at DESC
    LIMIT 3
", [':current_id' => $post['id']]);

// Page-Meta für SEO
$page_title = e($post['title']) . ' | Blog | PC-Wittfoot UG';
$page_description = e($post['excerpt'] ?? mb_substr(strip_tags($post['content']), 0, 155));
$page_keywords = $post['keywords'] ?? 'IT-Tipps, Computer, Hardware, Software, PC-Wittfoot';
$page_url = 'https://pc-wittfoot.de/blog/' . $post['slug'];
$current_page = 'blog';

// Open Graph für Blog-Posts
$page_og_type = 'article';
if (!empty($post['hero_image'])) {
    // Verwende Hero-Image wenn vorhanden
    $page_og_image_url = $post['hero_image'];
} else {
    // Fallback auf Standard OG-Image
    $page_og_image = 'og-image-blog.png';
}
$page_og_image_alt = e($post['title']) . ' | PC-Wittfoot Blog';

// Article Meta-Tags
$page_published = date('c', strtotime($post['published_at']));
$page_modified = date('c', strtotime($post['updated_at'] ?? $post['published_at']));
$page_author = $post['author_name'] ?? 'PC-Wittfoot Team';

include __DIR__ . '/../templates/header.php';
?>

<style>
/* Blog-Detail Styles - Direkt eingebettet für Konsistenz */
.blog-detail-section {
    background: var(--bg-secondary) !important;
    padding: 120px 0 60px 0 !important;
}

.blog-post {
    background: var(--bg-primary);
    border-radius: 8px;
    padding: 40px;
    max-width: 800px;
    margin: 0 auto;
    box-shadow: var(--shadow-sm);
}

.blog-post-header {
    text-align: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border-color);
}

.blog-post-header h1 {
    font-size: 2rem;
    margin-bottom: 8px;
    line-height: 1.3;
}

.blog-post-emoji {
    font-size: 2.5rem;
    margin-bottom: 12px;
}

.blog-post-meta {
    font-size: 1rem;
    color: var(--text-secondary);
    margin-top: 8px;
    padding-left: 16px;
}

.blog-post-hero {
    margin: 24px 0 32px 0;
}

.blog-post-hero img {
    width: 100%;
    border-radius: 8px;
}

.blog-post-content {
    font-size: 1.125rem !important;
    line-height: 1.8 !important;
    color: var(--text-primary) !important;
}

.blog-post-content h2 {
    font-size: 1.5rem !important;
    margin: 32px 0 16px 0 !important;
}

.blog-post-content h3 {
    font-size: 1.25rem !important;
    margin: 24px 0 12px 0 !important;
}

.blog-post-content p {
    margin-bottom: 1.25rem !important;
}

.blog-post-content ul,
.blog-post-content ol {
    margin: 0 0 1.25rem 0 !important;
    padding-left: 2rem !important;
    font-size: 1.125rem !important;
}

.blog-post-content ul {
    list-style-type: disc !important;
}

.blog-post-content ol {
    list-style-type: decimal !important;
}

.blog-post-content li {
    margin-bottom: 0.5rem !important;
    display: list-item !important;
}

.blog-post-content code {
    font-size: 1rem !important;
    background: var(--bg-tertiary);
    padding: 2px 6px;
    border-radius: 4px;
}

.blog-post-content img {
    max-width: 100%;
    border-radius: 8px;
    margin: 24px 0;
}

.blog-post-footer {
    margin-top: 48px;
    padding-top: 24px;
    font-size: 1rem;
}

@media (max-width: 768px) {
    .blog-post {
        padding: 24px 16px;
    }
    .blog-post-header h1 {
        font-size: 1.5rem;
    }
    .blog-post-content {
        font-size: 1rem !important;
    }
    .blog-post-content ul,
    .blog-post-content ol {
        padding-left: 1.5rem !important;
    }
}
</style>

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
                <!-- GitHub-Style Emoji -->
                <?php if (!empty($post['emoji'])): ?>
                    <div class="blog-post-emoji">
                        <?= e($post['emoji']) ?>
                    </div>
                <?php endif; ?>

                <h1><?= e($post['title']) ?></h1>

                <div class="blog-post-meta">
                    <time datetime="<?= e($post['published_at']) ?>">
                        <?= format_datetime($post['published_at'], 'd.m.Y') ?>
                    </time>

                    <?php if (!empty($post['author_name'])): ?>
                        <span class="text-muted">•</span>
                        <span>von <?= e($post['author_name']) ?></span>
                    <?php endif; ?>
                </div>
            </header>

            <!-- Hero Image -->
            <?php if (!empty($post['hero_image'])): ?>
                <div class="blog-post-hero">
                    <img src="<?= e($post['hero_image']) ?>"
                         alt="<?= e($post['hero_image_alt'] ?: $post['title']) ?>"
                         loading="lazy">
                </div>
            <?php endif; ?>

            <!-- Content (Markdown zu HTML) -->
            <div class="blog-post-content">
                <?= markdown_to_html($post['content'], true) ?>
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
                        <!-- Emoji in Card -->
                        <?php if (!empty($related['emoji'])): ?>
                            <div class="blog-card-emoji">
                                <?= e($related['emoji']) ?>
                            </div>
                        <?php endif; ?>

                        <div class="card-meta">
                            <time datetime="<?= e($related['published_at']) ?>">
                                <?= format_date($related['published_at']) ?>
                            </time>

                            <?php if (!empty($related['author_name'])): ?>
                                <span class="text-muted">• von <?= e($related['author_name']) ?></span>
                            <?php endif; ?>
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

<!-- Schema.org BlogPosting Markup für SEO -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": <?= json_encode($post['title'], JSON_UNESCAPED_UNICODE) ?>,
  "description": <?= json_encode($post['excerpt'] ?? mb_substr(strip_tags($post['content']), 0, 155), JSON_UNESCAPED_UNICODE) ?>,
  "url": "<?= BASE_URL ?>/blog/<?= e($post['slug']) ?>",
  "datePublished": "<?= date('c', strtotime($post['published_at'])) ?>",
  "dateModified": "<?= date('c', strtotime($post['updated_at'] ?? $post['published_at'])) ?>",
  <?php if (!empty($post['hero_image'])): ?>
  "image": {
    "@type": "ImageObject",
    "url": <?= json_encode($post['hero_image'], JSON_UNESCAPED_UNICODE) ?>,
    "width": 1200,
    "height": 630
  },
  <?php endif; ?>
  "author": {
    "@type": <?= !empty($post['author_name']) && $post['author_name'] !== 'PC-Wittfoot Team' ? '"Person"' : '"Organization"' ?>,
    "name": <?= json_encode($post['author_name'] ?? 'PC-Wittfoot Team', JSON_UNESCAPED_UNICODE) ?>
  },
  "publisher": {
    "@type": "Organization",
    "name": "PC-Wittfoot UG",
    "url": "<?= BASE_URL ?>",
    "logo": {
      "@type": "ImageObject",
      "url": "<?= BASE_URL ?>/assets/images/logo.png",
      "width": 600,
      "height": 60
    }
  },
  <?php if (!empty($post['keywords'])): ?>
  "keywords": <?= json_encode($post['keywords'], JSON_UNESCAPED_UNICODE) ?>,
  <?php endif; ?>
  "articleSection": "IT-Tipps & News",
  "inLanguage": "de-DE",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "<?= BASE_URL ?>/blog/<?= e($post['slug']) ?>"
  }
}
</script>

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
