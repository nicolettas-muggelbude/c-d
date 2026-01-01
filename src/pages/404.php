<?php
/**
 * 404 - Seite nicht gefunden
 */

$page_title = '404 - Seite nicht gefunden | PC-Wittfoot UG';
$page_description = 'Die angeforderte Seite wurde nicht gefunden.';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container text-center">
        <div style="font-size: 8rem; margin-bottom: var(--space-lg);">🔍</div>
        <h1>404 - Seite nicht gefunden</h1>
        <p class="lead mb-xl">
            Die von Ihnen gesuchte Seite existiert leider nicht oder wurde verschoben.
        </p>

        <div class="btn-group">
            <a href="<?= BASE_URL ?>" class="btn btn-primary">Zur Startseite</a>
            <a href="<?= BASE_URL ?>/shop" class="btn btn-outline">Zum Shop</a>
            <a href="<?= BASE_URL ?>/kontakt" class="btn btn-outline">Kontakt</a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
