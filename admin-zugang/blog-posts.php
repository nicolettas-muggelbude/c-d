<?php
/**
 * Admin - Blog-Posts Verwaltung
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();

// Admin-Rechte prüfen
require_admin();

$db = Database::getInstance();

// Löschen
if (isset($_GET['delete']) && isset($_GET['csrf_token'])) {
    if (csrf_verify($_GET['csrf_token'])) {
        $id = intval($_GET['delete']);
        $db->delete("DELETE FROM blog_posts WHERE id = :id", [':id' => $id]);
        set_flash('success', 'Blog-Post wurde gelöscht.');
        redirect(BASE_URL . '/admin/blog-posts');
    }
}

// Alle Blog-Posts laden
$posts = $db->query("
    SELECT bp.*, u.full_name
    FROM blog_posts bp
    LEFT JOIN users u ON bp.author_id = u.id
    ORDER BY bp.created_at DESC
");

$page_title = 'Blog-Posts verwalten | Admin | PC-Wittfoot UG';
$page_description = 'Admin-Bereich';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <div class="d-flex justify-between align-center mb-lg" style="flex-wrap: wrap; gap: var(--space-md);">
            <div>
                <h1 class="mb-0">Blog-Posts verwalten</h1>
            </div>
            <div>
                <a href="<?= BASE_URL ?>/admin" class="btn btn-outline">← Dashboard</a>
                <a href="<?= BASE_URL ?>/admin/blog-post-edit" class="btn btn-primary">+ Neuer Blog-Post</a>
            </div>
        </div>

        <?php if ($flash_success = get_flash('success')): ?>
            <div class="alert alert-success mb-lg">
                <?= e($flash_success) ?>
            </div>
        <?php endif; ?>

        <?php if (empty($posts)): ?>
            <div class="card text-center">
                <div style="font-size: 4rem; margin-bottom: var(--space-md);">📝</div>
                <h3>Noch keine Blog-Posts</h3>
                <p>Erstellen Sie Ihren ersten Blog-Post.</p>
                <a href="<?= BASE_URL ?>/admin/blog-post-edit" class="btn btn-primary mt-md">
                    + Neuer Blog-Post
                </a>
            </div>
        <?php else: ?>
            <!-- Mobile: Card-Layout -->
            <div class="d-mobile-block d-tablet-none">
                <?php foreach ($posts as $post): ?>
                    <div class="card mb-md">
                        <div class="d-flex justify-between align-center mb-sm">
                            <strong><?= e($post['title']) ?></strong>
                            <?php if ($post['published']): ?>
                                <span class="badge success">Veröffentlicht</span>
                            <?php else: ?>
                                <span class="badge secondary">Entwurf</span>
                            <?php endif; ?>
                        </div>

                        <div class="mb-sm">
                            <small class="text-muted">/blog/<?= e($post['slug']) ?></small>
                        </div>

                        <?php if (!empty($post['full_name'])): ?>
                            <div class="mb-sm">
                                <small><strong>Autor:</strong> <?= e($post['full_name']) ?></small>
                            </div>
                        <?php endif; ?>

                        <div class="mb-sm">
                            <small>
                                <strong>Erstellt:</strong> <?= format_date($post['created_at']) ?>
                            </small>
                        </div>

                        <?php if ($post['published_at']): ?>
                            <div class="mb-md">
                                <small>
                                    <strong>Veröffentlicht:</strong> <?= format_date($post['published_at']) ?>
                                </small>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex gap-sm" style="flex-wrap: wrap;">
                            <?php if ($post['published']): ?>
                                <a href="<?= BASE_URL ?>/blog/<?= e($post['slug']) ?>"
                                   class="btn btn-outline btn-sm"
                                   target="_blank">
                                    👁️ Ansehen
                                </a>
                            <?php endif; ?>
                            <a href="<?= BASE_URL ?>/admin/blog-post-edit?id=<?= $post['id'] ?>"
                               class="btn btn-outline btn-sm">
                                ✏️ Bearbeiten
                            </a>
                            <a href="?delete=<?= $post['id'] ?>&csrf_token=<?= csrf_token() ?>"
                               class="btn btn-outline btn-sm"
                               style="color: var(--color-error);"
                               onclick="return confirm('Blog-Post wirklich löschen?')">
                                🗑️ Löschen
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Desktop: Tabelle -->
            <div class="card d-mobile-none" style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Titel</th>
                            <th>Autor</th>
                            <th>Status</th>
                            <th>Veröffentlicht</th>
                            <th>Erstellt</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td>
                                    <strong><?= e($post['title']) ?></strong><br>
                                    <small class="text-muted">/blog/<?= e($post['slug']) ?></small>
                                </td>
                                <td>
                                    <?php if (!empty($post['full_name'])): ?>
                                        <?= e($post['full_name']) ?>
                                    <?php else: ?>
                                        <span class="text-muted">–</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($post['published']): ?>
                                        <span class="badge success">Veröffentlicht</span>
                                    <?php else: ?>
                                        <span class="badge secondary">Entwurf</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($post['published_at']): ?>
                                        <?= format_date($post['published_at']) ?>
                                    <?php else: ?>
                                        <span class="text-muted">–</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= format_date($post['created_at']) ?></td>
                                <td>
                                    <div class="d-flex gap-sm" style="flex-wrap: wrap;">
                                        <?php if ($post['published']): ?>
                                            <a href="<?= BASE_URL ?>/blog/<?= e($post['slug']) ?>"
                                               class="btn btn-outline btn-sm"
                                               target="_blank"
                                               title="Ansehen">
                                                👁️
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= BASE_URL ?>/admin/blog-post-edit?id=<?= $post['id'] ?>"
                                           class="btn btn-outline btn-sm"
                                           title="Bearbeiten">
                                            ✏️
                                        </a>
                                        <a href="?delete=<?= $post['id'] ?>&csrf_token=<?= csrf_token() ?>"
                                           class="btn btn-outline btn-sm"
                                           style="color: var(--color-error);"
                                           title="Löschen"
                                           onclick="return confirm('Blog-Post wirklich löschen?')">
                                            🗑️
                                        </a>
                                    </div>
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
