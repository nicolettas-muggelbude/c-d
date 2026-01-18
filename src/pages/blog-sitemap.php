<?php
/**
 * Blog XML-Sitemap für SEO
 * Route: /blog/sitemap.xml
 *
 * Dynamische Sitemap mit allen veröffentlichten Blog-Posts
 */

header('Content-Type: application/xml; charset=UTF-8');

$db = Database::getInstance();

// Alle veröffentlichten Blog-Posts laden
$posts = $db->query("
    SELECT slug, published_at, updated_at
    FROM blog_posts
    WHERE published = 1
    ORDER BY published_at DESC
");

// XML-Header ausgeben
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">

    <!-- Blog-Übersicht -->
    <url>
        <loc><?= BASE_URL ?>/blog</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
        <?php if (!empty($posts)): ?>
        <lastmod><?= date('c', strtotime($posts[0]['published_at'])) ?></lastmod>
        <?php endif; ?>
    </url>

    <!-- Blog-Posts -->
    <?php foreach ($posts as $post): ?>
    <url>
        <loc><?= BASE_URL ?>/blog/<?= htmlspecialchars($post['slug'], ENT_XML1) ?></loc>
        <lastmod><?= date('c', strtotime($post['updated_at'] ?? $post['published_at'])) ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <?php endforeach; ?>

</urlset>
