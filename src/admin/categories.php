<?php
/**
 * Kategorien-Verwaltung (Admin)
 * PC-Wittfoot UG
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();
require_admin();

$db = Database::getInstance();

// Kategorien laden mit Produkt-Anzahl
$categories = $db->query("
    SELECT
        c.*,
        COUNT(p.id) as product_count,
        pc.name as parent_name
    FROM categories c
    LEFT JOIN products p ON c.id = p.category_id
    LEFT JOIN categories pc ON c.parent_id = pc.id
    GROUP BY c.id
    ORDER BY c.sort_order ASC, c.name ASC
");

$page_title = 'Kategorien | Admin | PC-Wittfoot UG';
$page_description = 'Kategorien verwalten';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1>Kategorien-Verwaltung</h1>
            <div style="display: flex; gap: 1rem;">
                <a href="<?= BASE_URL ?>/admin/category-edit" class="btn btn-primary">+ Neue Kategorie</a>
                <a href="<?= BASE_URL ?>/admin" class="btn btn-outline">← Zurück zum Dashboard</a>
            </div>
        </div>

        <?php if (empty($categories)): ?>
            <div class="card">
                <p class="text-muted">Noch keine Kategorien angelegt.</p>
                <a href="<?= BASE_URL ?>/admin/category-edit" class="btn btn-primary">Erste Kategorie anlegen</a>
            </div>
        <?php else: ?>
            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Übergeordnet</th>
                            <th>Produkte</th>
                            <th>Sortierung</th>
                            <th>Status</th>
                            <th style="text-align: right;">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td>
                                    <strong><?= e($category['name']) ?></strong>
                                    <?php if ($category['description']): ?>
                                        <br><small class="text-muted"><?= e(truncate($category['description'], 50)) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><code><?= e($category['slug']) ?></code></td>
                                <td>
                                    <?php if ($category['parent_id']): ?>
                                        <small><?= e($category['parent_name']) ?></small>
                                    <?php else: ?>
                                        <small class="text-muted">Hauptkategorie</small>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= $category['product_count'] ?></strong></td>
                                <td><?= $category['sort_order'] ?></td>
                                <td>
                                    <?php if ($category['is_active']): ?>
                                        <span style="color: var(--color-success);">✓ Aktiv</span>
                                    <?php else: ?>
                                        <span style="color: var(--color-error);">⭕ Inaktiv</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;">
                                    <a href="<?= BASE_URL ?>/admin/category-edit?id=<?= $category['id'] ?>" class="btn btn-outline btn-sm">
                                        Bearbeiten
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
