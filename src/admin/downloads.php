<?php
/**
 * Admin - Downloads Verwaltung
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

        // Download-Eintrag laden (für Dateiname)
        $download = $db->querySingle("SELECT filename FROM downloads WHERE id = :id", [':id' => $id]);

        // Aus DB löschen
        $db->delete("DELETE FROM downloads WHERE id = :id", [':id' => $id]);

        // Hinweis: Datei wird NICHT automatisch gelöscht (nur DB-Eintrag)
        // Admin muss Datei manuell per SSH löschen

        set_flash('success', 'Download wurde aus der Datenbank gelöscht. Datei muss per SSH gelöscht werden: /uploads/downloads/' . $download['filename']);
        redirect(BASE_URL . '/admin/downloads');
    }
}

// Toggle Active Status
if (isset($_GET['toggle']) && isset($_GET['csrf_token'])) {
    if (csrf_verify($_GET['csrf_token'])) {
        $id = intval($_GET['toggle']);
        $db->update("
            UPDATE downloads
            SET is_active = NOT is_active
            WHERE id = :id
        ", [':id' => $id]);
        set_flash('success', 'Status wurde aktualisiert.');
        redirect(BASE_URL . '/admin/downloads');
    }
}

// Alle Downloads laden
$downloads = $db->query("
    SELECT *
    FROM downloads
    ORDER BY sort_order ASC, created_at DESC
");

// Kategorie-Labels
$category_labels = [
    'tools' => '🔧 Tools',
    'drivers' => '💾 Treiber',
    'documentation' => '📄 Dokumentation',
    'updates' => '🔄 Updates',
    'other' => '📦 Sonstiges'
];

// Statistiken
$stats = $db->querySingle("
    SELECT
        COUNT(*) as total,
        SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
        SUM(download_count) as total_downloads
    FROM downloads
");

$page_title = 'Downloads verwalten | Admin | PC-Wittfoot UG';
$page_description = 'Admin-Bereich';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <div class="d-flex justify-between align-center mb-lg" style="flex-wrap: wrap; gap: var(--space-md);">
            <div>
                <h1 class="mb-0">Downloads verwalten</h1>
                <p class="text-muted mb-0">
                    <?= $stats['total'] ?> Downloads •
                    <?= $stats['active'] ?> aktiv •
                    <?= number_format($stats['total_downloads'], 0, ',', '.') ?> Downloads gesamt
                </p>
            </div>
            <div>
                <a href="<?= BASE_URL ?>/admin" class="btn btn-outline">← Dashboard</a>
                <a href="<?= BASE_URL ?>/admin/download-edit" class="btn btn-primary">+ Neuer Download</a>
            </div>
        </div>

        <?php if ($flash_success = get_flash('success')): ?>
            <div class="alert alert-success mb-lg">
                <?= e($flash_success) ?>
            </div>
        <?php endif; ?>

        <!-- Hinweis -->
        <div class="alert alert-info mb-lg">
            <strong>💡 Hinweis zum Datei-Upload:</strong><br>
            Dateien müssen per SSH in <code>/uploads/downloads/</code> hochgeladen werden.<br>
            Beispiel: <code>scp datei.exe user@server:/pfad/zu/c-d/uploads/downloads/</code>
        </div>

        <?php if (empty($downloads)): ?>
            <div class="card text-center">
                <div style="font-size: 4rem; margin-bottom: var(--space-md);">📥</div>
                <h3>Noch keine Downloads</h3>
                <p>Erstellen Sie Ihren ersten Download-Eintrag.</p>
                <a href="<?= BASE_URL ?>/admin/download-edit" class="btn btn-primary mt-md">
                    + Neuer Download
                </a>
            </div>
        <?php else: ?>
            <!-- Mobile: Card-Layout -->
            <div class="d-mobile-block d-tablet-none">
                <?php foreach ($downloads as $download): ?>
                    <?php
                    // Prüfe ob Datei existiert
                    $file_path = __DIR__ . '/../../uploads/downloads/' . $download['filename'];
                    $file_exists = file_exists($file_path);
                    ?>
                    <div class="card mb-md">
                        <div class="d-flex justify-between align-center mb-sm">
                            <strong><?= e($download['title']) ?></strong>
                            <div style="display: flex; gap: 4px;">
                                <?php if ($download['is_active']): ?>
                                    <span class="badge success">Aktiv</span>
                                <?php else: ?>
                                    <span class="badge secondary">Inaktiv</span>
                                <?php endif; ?>
                                <?php if (!$file_exists): ?>
                                    <span class="badge warning">⚠️ Datei fehlt</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-sm">
                            <small class="text-muted">
                                <?= $category_labels[$download['category']] ?? e($download['category']) ?>
                            </small>
                        </div>

                        <?php if (!empty($download['version'])): ?>
                            <div class="mb-sm">
                                <small><strong>Version:</strong> <?= e($download['version']) ?></small>
                            </div>
                        <?php endif; ?>

                        <div class="mb-sm">
                            <small>
                                <strong>Datei:</strong> <code><?= e($download['filename']) ?></code>
                            </small>
                        </div>

                        <div class="mb-sm">
                            <small>
                                <strong>Größe:</strong> <?= format_file_size($download['file_size']) ?> •
                                <strong>Downloads:</strong> <?= number_format($download['download_count'], 0, ',', '.') ?>
                            </small>
                        </div>

                        <div class="d-flex gap-sm" style="flex-wrap: wrap;">
                            <a href="<?= BASE_URL ?>/admin/download-edit?id=<?= $download['id'] ?>"
                               class="btn btn-outline btn-sm">
                                ✏️ Bearbeiten
                            </a>
                            <a href="?toggle=<?= $download['id'] ?>&csrf_token=<?= csrf_token() ?>"
                               class="btn btn-outline btn-sm">
                                <?= $download['is_active'] ? '👁️ Deaktivieren' : '✓ Aktivieren' ?>
                            </a>
                            <a href="?delete=<?= $download['id'] ?>&csrf_token=<?= csrf_token() ?>"
                               class="btn btn-outline btn-sm"
                               style="color: var(--color-error);"
                               onclick="return confirm('Download wirklich löschen?\n\nNur der DB-Eintrag wird gelöscht.\nDatei muss per SSH gelöscht werden.')">
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
                            <th style="width: 50px;">Reihenfolge</th>
                            <th>Titel</th>
                            <th>Kategorie</th>
                            <th>Version</th>
                            <th>Datei</th>
                            <th>Größe</th>
                            <th>Downloads</th>
                            <th>Status</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($downloads as $download): ?>
                            <?php
                            // Prüfe ob Datei existiert
                            $file_path = __DIR__ . '/../../uploads/downloads/' . $download['filename'];
                            $file_exists = file_exists($file_path);
                            ?>
                            <tr>
                                <td class="text-center">
                                    <strong><?= $download['sort_order'] ?></strong>
                                </td>
                                <td>
                                    <strong><?= e($download['title']) ?></strong><br>
                                    <small class="text-muted">/downloads → <?= e($download['slug']) ?></small>
                                </td>
                                <td>
                                    <?= $category_labels[$download['category']] ?? e($download['category']) ?>
                                </td>
                                <td>
                                    <?= !empty($download['version']) ? e($download['version']) : '-' ?>
                                </td>
                                <td>
                                    <code style="font-size: 0.875rem;"><?= e($download['filename']) ?></code>
                                    <?php if (!$file_exists): ?>
                                        <br><span class="badge warning">⚠️ Datei fehlt</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= format_file_size($download['file_size']) ?>
                                </td>
                                <td class="text-center">
                                    <strong><?= number_format($download['download_count'], 0, ',', '.') ?></strong>
                                </td>
                                <td>
                                    <?php if ($download['is_active']): ?>
                                        <span class="badge success">Aktiv</span>
                                    <?php else: ?>
                                        <span class="badge secondary">Inaktiv</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 4px; white-space: nowrap;">
                                        <a href="<?= BASE_URL ?>/admin/download-edit?id=<?= $download['id'] ?>"
                                           class="btn btn-outline btn-sm"
                                           title="Bearbeiten">
                                            ✏️
                                        </a>
                                        <a href="?toggle=<?= $download['id'] ?>&csrf_token=<?= csrf_token() ?>"
                                           class="btn btn-outline btn-sm"
                                           title="<?= $download['is_active'] ? 'Deaktivieren' : 'Aktivieren' ?>">
                                            <?= $download['is_active'] ? '👁️' : '✓' ?>
                                        </a>
                                        <a href="?delete=<?= $download['id'] ?>&csrf_token=<?= csrf_token() ?>"
                                           class="btn btn-outline btn-sm"
                                           style="color: var(--color-error);"
                                           title="Löschen"
                                           onclick="return confirm('Download wirklich löschen?\n\nNur der DB-Eintrag wird gelöscht.\nDatei muss per SSH gelöscht werden.')">
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
