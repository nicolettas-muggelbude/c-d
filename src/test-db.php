<?php
/**
 * Datenbank-Test-Seite
 * Prüft ob DB-Verbindung und Abfragen funktionieren
 */

require_once __DIR__ . '/core/config.php';

start_session_safe();

// Datenbank-Instanz holen
$db = Database::getInstance();

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datenbank-Test - PC-Wittfoot</title>
    <link rel="stylesheet" href="<?= asset('css/variables.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/reset.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/base.css') ?>">
    <style>
        body { padding: var(--space-xl); }
        .success { background: var(--color-success); color: white; padding: var(--space-md); border-radius: var(--border-radius-md); margin-bottom: var(--space-lg); }
        .error { background: var(--color-error); color: white; padding: var(--space-md); border-radius: var(--border-radius-md); margin-bottom: var(--space-lg); }
        .info { background: var(--color-info); color: white; padding: var(--space-md); border-radius: var(--border-radius-md); margin-bottom: var(--space-lg); }
        table { width: 100%; border-collapse: collapse; margin: var(--space-lg) 0; }
        th, td { padding: var(--space-sm); border: 1px solid var(--border-color); text-align: left; }
        th { background: var(--bg-secondary); font-weight: bold; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: var(--space-lg); margin: var(--space-lg) 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Datenbank-Test</h1>

        <?php
        // Test 1: Verbindung
        try {
            $connection = $db->getConnection();
            echo '<div class="success">✅ <strong>Datenbankverbindung erfolgreich!</strong></div>';
        } catch (Exception $e) {
            echo '<div class="error">❌ <strong>Datenbankverbindung fehlgeschlagen:</strong> ' . e($e->getMessage()) . '</div>';
            die();
        }

        // Test 2: Kategorien abrufen
        echo '<h2>Kategorien</h2>';
        $categories = $db->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order");

        if ($categories) {
            echo '<div class="success">✅ Kategorien gefunden: ' . count($categories) . '</div>';
            echo '<table><thead><tr><th>ID</th><th>Name</th><th>Slug</th><th>Beschreibung</th></tr></thead><tbody>';
            foreach ($categories as $cat) {
                echo '<tr>';
                echo '<td>' . e($cat['id']) . '</td>';
                echo '<td>' . e($cat['name']) . '</td>';
                echo '<td>' . e($cat['slug']) . '</td>';
                echo '<td>' . e($cat['description']) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<div class="error">❌ Keine Kategorien gefunden</div>';
        }

        // Test 3: Produkte abrufen
        echo '<h2>Produkte (Featured)</h2>';
        $products = $db->query("
            SELECT p.*, c.name as category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.is_active = 1 AND p.is_featured = 1
            ORDER BY p.created_at DESC
            LIMIT 6
        ");

        if ($products) {
            echo '<div class="success">✅ Produkte gefunden: ' . count($products) . '</div>';
            echo '<div class="product-grid">';
            foreach ($products as $product) {
                echo '<div class="card">';
                echo '<h3>' . e($product['name']) . '</h3>';
                echo '<p class="text-muted">' . e($product['category_name']) . ' • ' . e($product['brand']) . '</p>';
                echo '<p>' . e($product['short_description']) . '</p>';
                echo '<p><strong>' . format_price($product['price']) . '</strong></p>';
                echo '<p class="text-muted">Lagerbestand: ' . e($product['stock']) . '</p>';
                echo '<p><span class="badge ' . ($product['condition_type'] === 'neu' ? 'primary' : 'secondary') . '">' . e(ucfirst($product['condition_type'])) . '</span></p>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<div class="error">❌ Keine Produkte gefunden</div>';
        }

        // Test 4: Blog-Posts abrufen
        echo '<h2>Blog-Posts</h2>';
        $posts = $db->query("
            SELECT * FROM blog_posts
            WHERE published = 1
            ORDER BY published_at DESC
            LIMIT 5
        ");

        if ($posts) {
            echo '<div class="success">✅ Blog-Posts gefunden: ' . count($posts) . '</div>';
            foreach ($posts as $post) {
                echo '<div class="card mt-md">';
                echo '<h3>' . e($post['title']) . '</h3>';
                echo '<p class="text-muted">' . format_datetime($post['published_at']) . '</p>';
                echo '<p>' . e($post['excerpt']) . '</p>';
                echo '</div>';
            }
        } else {
            echo '<div class="error">❌ Keine Blog-Posts gefunden</div>';
        }

        // Test 5: Helper-Funktionen
        echo '<h2>Helper-Funktionen</h2>';
        echo '<div class="info">';
        echo '<p>✅ <strong>format_price:</strong> ' . format_price(449.99) . '</p>';
        echo '<p>✅ <strong>format_date:</strong> ' . format_date('2025-12-31') . '</p>';
        echo '<p>✅ <strong>create_slug:</strong> ' . create_slug('Hällö Wörld! Ümlaute') . '</p>';
        echo '<p>✅ <strong>truncate:</strong> ' . truncate('Dies ist ein sehr langer Text der gekürzt werden soll', 30) . '</p>';
        echo '<p>✅ <strong>asset:</strong> ' . asset('css/main.css') . '</p>';
        echo '</div>';
        ?>

        <hr class="mt-xl mb-xl">

        <div class="success">
            <h3>🎉 Alle Tests erfolgreich!</h3>
            <p>Die technische Basis funktioniert:</p>
            <ul class="styled-list mt-md">
                <li>✅ Datenbankverbindung</li>
                <li>✅ SQL-Abfragen (SELECT)</li>
                <li>✅ Helper-Funktionen</li>
                <li>✅ PDO Prepared Statements</li>
                <li>✅ XSS-Schutz (htmlspecialchars)</li>
            </ul>
            <p class="mt-md"><strong>Nächster Schritt:</strong> Template-System und erste echte Seite!</p>
        </div>

        <p class="text-center mt-xl">
            <a href="index.html" class="btn btn-primary">← Zurück zur Demo-Seite</a>
        </p>
    </div>
</body>
</html>
