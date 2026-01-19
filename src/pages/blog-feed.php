<?php
/**
 * RSS Feed für Blog
 * Route: /blog/feed.xml
 */

header('Content-Type: application/rss+xml; charset=UTF-8');

$db = Database::getInstance();

// Neueste Blog-Posts laden (maximal 20)
$posts = $db->query("
    SELECT *
    FROM blog_posts
    WHERE published = 1
    ORDER BY published_at DESC
    LIMIT 20
");

// Build-Datum (neuester Post oder jetzt)
$buildDate = !empty($posts) ? strtotime($posts[0]['published_at']) : time();

// XML-Header ausgeben
echo '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/">
    <channel>
        <title>PC-Wittfoot Blog</title>
        <link><?= BASE_URL ?>/blog</link>
        <description>IT-Tipps, Neuigkeiten und Wissenswertes rund um Computer, Hardware und Software</description>
        <language>de-DE</language>
        <lastBuildDate><?= date('r', $buildDate) ?></lastBuildDate>
        <atom:link href="<?= BASE_URL ?>/blog/feed.xml" rel="self" type="application/rss+xml" />
        <image>
            <url><?= BASE_URL ?>/assets/images/logo.png</url>
            <title>PC-Wittfoot UG</title>
            <link><?= BASE_URL ?></link>
        </image>

        <?php foreach ($posts as $post): ?>
        <item>
            <title><?= htmlspecialchars($post['title'], ENT_XML1) ?></title>
            <link><?= BASE_URL ?>/blog/<?= e($post['slug']) ?></link>
            <guid isPermaLink="true"><?= BASE_URL ?>/blog/<?= e($post['slug']) ?></guid>
            <pubDate><?= date('r', strtotime($post['published_at'])) ?></pubDate>

            <?php if (!empty($post['author_name'])): ?>
            <author>info@pc-wittfoot.de (<?= htmlspecialchars($post['author_name'], ENT_XML1) ?>)</author>
            <?php endif; ?>

            <?php if (!empty($post['keywords'])): ?>
                <?php
                $keywords = explode(',', $post['keywords']);
                foreach ($keywords as $keyword):
                    $keyword = trim($keyword);
                    if (!empty($keyword)):
                ?>
            <category><?= htmlspecialchars($keyword, ENT_XML1) ?></category>
                <?php
                    endif;
                endforeach;
                ?>
            <?php endif; ?>

            <?php if (!empty($post['excerpt'])): ?>
            <description><?= htmlspecialchars($post['excerpt'], ENT_XML1) ?></description>
            <?php endif; ?>

            <content:encoded><![CDATA[
                <?php if (!empty($post['emoji'])): ?>
                <div style="font-size: 4rem; text-align: center; margin-bottom: 1rem;">
                    <?= htmlspecialchars($post['emoji']) ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($post['hero_image'])): ?>
                <p><img src="<?= htmlspecialchars($post['hero_image']) ?>" alt="<?= htmlspecialchars($post['hero_image_alt'] ?: $post['title']) ?>" style="max-width: 100%; height: auto; border-radius: 8px;" /></p>
                <?php endif; ?>

                <?= markdown_to_html($post['content'], true) ?>

                <hr style="margin-top: 2rem; border: none; border-top: 1px solid #ccc;" />
                <p style="text-align: center; color: #666;">
                    <a href="<?= BASE_URL ?>/blog/<?= e($post['slug']) ?>">Vollständigen Beitrag lesen »</a>
                </p>
            ]]></content:encoded>
        </item>
        <?php endforeach; ?>
    </channel>
</rss>
