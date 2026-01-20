<?php
/**
 * Admin - Blog-Post bearbeiten/erstellen (Markdown Edition)
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();

// Admin-Rechte prüfen
require_admin();

$db = Database::getInstance();

// ID aus URL (null = neuer Post)
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Bestehenden Post laden
$post = null;
if ($id) {
    $post = $db->querySingle("SELECT * FROM blog_posts WHERE id = :id", [':id' => $id]);
    if (!$post) {
        set_flash('error', 'Blog-Post nicht gefunden.');
        redirect(BASE_URL . '/admin/blog-posts');
    }
}

$errors = [];
$form_data = $post ?? [
    'emoji' => '📝',
    'title' => '',
    'slug' => '',
    'excerpt' => '',
    'content' => '',
    'hero_image' => '',
    'hero_image_alt' => '',
    'author_name' => 'PC-Wittfoot Team',
    'keywords' => '',
    'category' => 'Allgemein',
    'published' => 0,
    'published_at' => date('Y-m-d H:i:s'),
];

// Formular-Verarbeitung
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';

    if (!csrf_verify($csrf_token)) {
        $errors[] = 'Ungültiger Sicherheitstoken.';
    } else {
        // Daten sammeln
        $form_data = [
            'emoji' => sanitize($_POST['emoji'] ?? '📝'),
            'title' => sanitize($_POST['title'] ?? ''),
            'slug' => sanitize($_POST['slug'] ?? ''),
            'excerpt' => sanitize($_POST['excerpt'] ?? ''),
            'content' => trim($_POST['content'] ?? ''),
            'hero_image' => sanitize($_POST['hero_image'] ?? ''),
            'hero_image_alt' => sanitize($_POST['hero_image_alt'] ?? ''),
            'author_name' => sanitize($_POST['author_name'] ?? 'PC-Wittfoot Team'),
            'keywords' => sanitize($_POST['keywords'] ?? ''),
            'category' => sanitize($_POST['category'] ?? 'Allgemein'),
            'published' => isset($_POST['published']) ? 1 : 0,
            'published_at' => sanitize($_POST['published_at'] ?? ''),
        ];

        // Validierung
        if (empty($form_data['title'])) {
            $errors[] = 'Bitte geben Sie einen Titel an.';
        }

        if (empty($form_data['slug'])) {
            $errors[] = 'Bitte geben Sie einen Slug an.';
        }

        if (empty($form_data['content'])) {
            $errors[] = 'Bitte geben Sie Inhalt an.';
        }

        if (empty($form_data['author_name'])) {
            $errors[] = 'Bitte geben Sie einen Autor an.';
        }

        // Slug-Eindeutigkeit prüfen
        if (!empty($form_data['slug'])) {
            $slug_check = $db->querySingle("
                SELECT id FROM blog_posts
                WHERE slug = :slug AND id != :id
            ", [
                ':slug' => $form_data['slug'],
                ':id' => $id ?? 0
            ]);

            if ($slug_check) {
                $errors[] = 'Dieser Slug wird bereits verwendet.';
            }
        }

        // Speichern wenn keine Fehler
        if (empty($errors)) {
            if ($id) {
                // Update
                $db->update("
                    UPDATE blog_posts SET
                        emoji = :emoji,
                        title = :title,
                        slug = :slug,
                        excerpt = :excerpt,
                        content = :content,
                        hero_image = :hero_image,
                        hero_image_alt = :hero_image_alt,
                        author_name = :author_name,
                        keywords = :keywords,
                        category = :category,
                        published = :published,
                        published_at = :published_at
                    WHERE id = :id
                ", [
                    ':emoji' => $form_data['emoji'],
                    ':title' => $form_data['title'],
                    ':slug' => $form_data['slug'],
                    ':excerpt' => $form_data['excerpt'],
                    ':content' => $form_data['content'],
                    ':hero_image' => !empty($form_data['hero_image']) ? $form_data['hero_image'] : null,
                    ':hero_image_alt' => !empty($form_data['hero_image_alt']) ? $form_data['hero_image_alt'] : null,
                    ':author_name' => $form_data['author_name'],
                    ':keywords' => !empty($form_data['keywords']) ? $form_data['keywords'] : null,
                    ':category' => $form_data['category'],
                    ':published' => $form_data['published'],
                    ':published_at' => $form_data['published'] ? $form_data['published_at'] : null,
                    ':id' => $id
                ]);

                set_flash('success', 'Blog-Post wurde aktualisiert.');
            } else {
                // Insert
                $db->insert("
                    INSERT INTO blog_posts (emoji, title, slug, excerpt, content, hero_image, hero_image_alt, author_name, keywords, category, published, published_at)
                    VALUES (:emoji, :title, :slug, :excerpt, :content, :hero_image, :hero_image_alt, :author_name, :keywords, :category, :published, :published_at)
                ", [
                    ':emoji' => $form_data['emoji'],
                    ':title' => $form_data['title'],
                    ':slug' => $form_data['slug'],
                    ':excerpt' => $form_data['excerpt'],
                    ':content' => $form_data['content'],
                    ':hero_image' => !empty($form_data['hero_image']) ? $form_data['hero_image'] : null,
                    ':hero_image_alt' => !empty($form_data['hero_image_alt']) ? $form_data['hero_image_alt'] : null,
                    ':author_name' => $form_data['author_name'],
                    ':keywords' => !empty($form_data['keywords']) ? $form_data['keywords'] : null,
                    ':category' => $form_data['category'],
                    ':published' => $form_data['published'],
                    ':published_at' => $form_data['published'] ? $form_data['published_at'] : null
                ]);

                set_flash('success', 'Blog-Post wurde erstellt.');
            }

            redirect(BASE_URL . '/admin/blog-posts');
        }
    }
}

$page_title = ($id ? 'Blog-Post bearbeiten' : 'Neuer Blog-Post') . ' | Admin | PC-Wittfoot UG';
$page_description = 'Admin-Bereich';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <div class="d-flex justify-between align-center mb-lg" style="flex-wrap: wrap; gap: var(--space-md);">
            <h1 class="mb-0"><?= $id ? 'Blog-Post bearbeiten' : 'Neuer Blog-Post' ?></h1>
            <a href="<?= BASE_URL ?>/admin/blog-posts" class="btn btn-outline">← Zurück</a>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error mb-lg">
                <strong>Fehler:</strong>
                <ul style="margin: var(--space-sm) 0 0 0; padding-left: var(--space-lg);">
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <div class="row" style="align-items: flex-start;">
                <!-- Hauptbereich -->
                <div class="col-12 col-lg-8">
                    <div class="card mb-lg">
                        <!-- Emoji Picker -->
                        <div class="form-group">
                            <label for="emoji">
                                Emoji <span class="text-muted">(GitHub-Style großes Emoji)</span>
                            </label>
                            <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                <button type="button" id="emoji-display" class="btn btn-outline" style="font-size: 2rem; padding: var(--space-xs) var(--space-md); min-width: 80px;">
                                    <?= e($form_data['emoji']) ?>
                                </button>
                                <input type="hidden" id="emoji" name="emoji" value="<?= e($form_data['emoji']) ?>">
                                <div id="emoji-picker" style="display: none; position: absolute; z-index: 1000; background: var(--bg-primary); border: 1px solid var(--border-color); border-radius: var(--border-radius-md); padding: var(--space-sm); box-shadow: 0 4px 12px rgba(0,0,0,0.15); max-height: 300px; overflow-y: auto;"></div>
                            </div>
                        </div>

                        <!-- Titel -->
                        <div class="form-group">
                            <label for="title">Titel *</label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   value="<?= e($form_data['title']) ?>"
                                   required
                                   autofocus>
                        </div>

                        <!-- Slug -->
                        <div class="form-group">
                            <label for="slug">
                                Slug *
                                <small class="text-muted">(URL-freundlicher Name, z.B. "mein-erster-beitrag")</small>
                            </label>
                            <input type="text"
                                   id="slug"
                                   name="slug"
                                   value="<?= e($form_data['slug']) ?>"
                                   required>
                            <small class="text-muted">
                                URL: <?= BASE_URL ?>/blog/<span id="slug-preview"><?= e($form_data['slug'] ?: 'slug') ?></span>
                            </small>
                        </div>

                        <!-- Hero Image -->
                        <div class="form-group">
                            <label for="hero_image">
                                Hero-Bild <span class="text-muted">(Optional - URL oder Upload)</span>
                            </label>
                            <input type="text"
                                   id="hero_image"
                                   name="hero_image"
                                   placeholder="<?= UPLOADS_URL ?>/blog/hero-image.jpg"
                                   value="<?= e($form_data['hero_image'] ?? '') ?>">
                            <small class="text-muted">Tipp: Bilder in /uploads/blog/ hochladen</small>
                        </div>

                        <!-- Hero Image Alt-Text -->
                        <div class="form-group">
                            <label for="hero_image_alt">
                                Hero-Bild Alt-Text <span class="text-muted">(Barrierefreiheit)</span>
                            </label>
                            <input type="text"
                                   id="hero_image_alt"
                                   name="hero_image_alt"
                                   placeholder="Beschreibung des Hero-Bildes für Screenreader"
                                   value="<?= e($form_data['hero_image_alt'] ?? '') ?>">
                            <small class="text-muted">Beschreibt das Bild für sehbehinderte Nutzer</small>
                        </div>

                        <!-- Autor -->
                        <div class="form-group">
                            <label for="author_name">
                                Autor *
                            </label>
                            <input type="text"
                                   id="author_name"
                                   name="author_name"
                                   value="<?= e($form_data['author_name']) ?>"
                                   placeholder="PC-Wittfoot Team"
                                   required>
                        </div>

                        <!-- Kurzbeschreibung -->
                        <div class="form-group">
                            <label for="excerpt">
                                Kurzbeschreibung
                                <small class="text-muted">(Optional, wird in Übersichten angezeigt)</small>
                            </label>
                            <textarea id="excerpt"
                                      name="excerpt"
                                      rows="3"><?= e($form_data['excerpt']) ?></textarea>
                        </div>

                        <!-- Markdown Editor mit Live Preview -->
                        <div class="form-group">
                            <label for="content">
                                Inhalt (Markdown) *
                                <button type="button" class="btn btn-sm btn-outline" onclick="togglePreview()" style="margin-left: var(--space-sm);">
                                    <span id="preview-toggle-text">📄 Vorschau anzeigen</span>
                                </button>
                            </label>

                            <div id="editor-container" style="display: grid; grid-template-columns: 1fr; gap: var(--space-md);">
                                <!-- Markdown Editor -->
                                <div id="markdown-editor-wrap">
                                    <textarea id="content"
                                              name="content"
                                              rows="20"
                                              required><?= e($form_data['content']) ?></textarea>
                                    <small class="text-muted">Markdown-Syntax verwenden. Beispiel: **fett**, *kursiv*, ## Überschrift</small>
                                </div>

                                <!-- Live Preview -->
                                <div id="markdown-preview" style="display: none; border: 1px solid var(--border-color); border-radius: var(--border-radius-md); padding: var(--space-md); background: var(--bg-secondary); overflow-y: auto; max-height: 500px;">
                                    <div id="preview-content" class="blog-post-content"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-12 col-lg-4">
                    <!-- Veröffentlichung -->
                    <div class="card sidebar-card mb-md">
                        <h3 class="sidebar-title">Veröffentlichung</h3>

                        <div class="form-check-wrapper">
                            <label class="form-check">
                                <input type="checkbox"
                                       id="published"
                                       name="published"
                                       <?= $form_data['published'] ? 'checked' : '' ?>>
                                <span>Veröffentlicht</span>
                            </label>
                        </div>

                        <div class="form-group mb-sm">
                            <label for="published_at" class="label-sm">Datum</label>
                            <input type="datetime-local"
                                   id="published_at"
                                   name="published_at"
                                   value="<?= date('Y-m-d\TH:i', strtotime($form_data['published_at']))?>">
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <?= $id ? 'Aktualisieren' : 'Erstellen' ?>
                        </button>

                        <?php if ($id && $form_data['published']): ?>
                            <a href="<?= BASE_URL ?>/blog/<?= e($form_data['slug']) ?>"
                               class="btn btn-outline btn-block mt-xs"
                               target="_blank">
                                Ansehen
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Bild-Upload & Galerie -->
                    <div class="card sidebar-card mb-md">
                        <h3 class="sidebar-title">📷 Bilder</h3>

                        <!-- Upload -->
                        <div class="upload-area" id="upload-area">
                            <input type="file" id="image-upload" accept="image/*" style="display: none;">
                            <button type="button" class="btn btn-outline btn-block btn-sm" onclick="document.getElementById('image-upload').click();">
                                + Neues Bild hochladen
                            </button>
                            <p class="text-muted" style="font-size: 0.75rem; margin-top: 4px;">JPG, PNG, GIF, WebP (max. 5 MB)</p>
                        </div>
                        <div id="upload-progress" style="display: none;">
                            <div class="progress-bar">
                                <div class="progress-fill" id="progress-fill"></div>
                            </div>
                            <p class="text-muted mt-xs" id="upload-status">Lade hoch...</p>
                        </div>

                        <!-- Bildergalerie -->
                        <div class="image-gallery mt-sm" id="image-gallery">
                            <p class="text-muted" style="font-size: 0.875rem;">Lade Bilder...</p>
                        </div>
                    </div>

                    <!-- Kategorie & SEO -->
                    <div class="card sidebar-card mb-md">
                        <h3 class="sidebar-title">Kategorie & SEO</h3>
                        <div class="form-group mb-sm">
                            <label for="category" class="label-sm">Kategorie</label>
                            <select id="category" name="category">
                                <option value="Allgemein" <?= ($form_data['category'] ?? 'Allgemein') === 'Allgemein' ? 'selected' : '' ?>>📝 Allgemein</option>
                                <option value="Hardware" <?= ($form_data['category'] ?? '') === 'Hardware' ? 'selected' : '' ?>>🖥️ Hardware</option>
                                <option value="Software" <?= ($form_data['category'] ?? '') === 'Software' ? 'selected' : '' ?>>💻 Software</option>
                                <option value="Tipps" <?= ($form_data['category'] ?? '') === 'Tipps' ? 'selected' : '' ?>>💡 Tipps</option>
                                <option value="News" <?= ($form_data['category'] ?? '') === 'News' ? 'selected' : '' ?>>📢 News</option>
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label for="keywords" class="label-sm">Keywords <span class="text-muted">(Komma-getrennt)</span></label>
                            <input type="text"
                                   id="keywords"
                                   name="keywords"
                                   placeholder="Laptop, Reparatur, Oldenburg"
                                   value="<?= e($form_data['keywords'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- Markdown-Hilfe -->
                    <div class="card sidebar-card">
                        <h3 class="sidebar-title">📖 Markdown-Referenz</h3>
                        <div class="markdown-reference">
                            <table class="reference-table">
                                <tr><td><strong>Fett</strong></td><td><code>**text**</code></td></tr>
                                <tr><td><em>Kursiv</em></td><td><code>*text*</code></td></tr>
                                <tr><td>Überschrift</td><td><code>## H2</code></td></tr>
                                <tr><td>Link</td><td><code>[Text](URL)</code></td></tr>
                                <tr><td>Bild</td><td><code>![Alt](URL)</code></td></tr>
                                <tr><td>Bild (Größe)</td><td><code>![Alt](URL){width=50%}</code></td></tr>
                                <tr><td>Liste</td><td><code>- Punkt</code></td></tr>
                                <tr><td>Nummeriert</td><td><code>1. Punkt</code></td></tr>
                                <tr><td>Code</td><td><code>`code`</code></td></tr>
                                <tr><td>Zitat</td><td><code>> Zitat</code></td></tr>
                                <tr><td>Trennlinie</td><td><code>---</code></td></tr>
                            </table>
                            <a href="<?= BASE_URL ?>/admin/markdown-hilfe" target="_blank" class="btn btn-outline btn-sm btn-block mt-sm">
                                Vollständige Hilfe öffnen
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<style>
/* Sidebar Cards */
.sidebar-card {
    padding: var(--space-md);
    background: #ffffff;
}

.sidebar-title {
    margin: 0 0 var(--space-sm) 0;
    font-size: 1rem;
    font-weight: 600;
    padding-bottom: var(--space-xs);
    border-bottom: 1px solid var(--border-color);
}

.label-sm {
    font-size: 0.875rem;
    display: block;
    margin-bottom: 4px;
}

.form-check-wrapper {
    margin-bottom: var(--space-sm);
}

.mt-xs {
    margin-top: var(--space-xs);
}

.mb-sm {
    margin-bottom: var(--space-sm);
}

.mb-0 {
    margin-bottom: 0;
}

/* Markdown Reference Table */
.markdown-reference {
    font-size: 0.875rem;
}

.reference-table {
    width: 100%;
    border-collapse: collapse;
}

.reference-table td {
    padding: 6px 8px;
    border-bottom: 1px solid var(--border-color);
}

.reference-table td:first-child {
    width: 40%;
}

.reference-table code {
    background: var(--bg-tertiary);
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.8rem;
}

/* Upload Area */
.upload-area {
    text-align: center;
    padding: var(--space-sm);
    border: 2px dashed var(--border-color);
    border-radius: var(--border-radius-md);
    background: var(--bg-secondary);
}

.upload-area.dragover {
    border-color: var(--color-primary);
    background: rgba(139, 195, 74, 0.1);
}

.progress-bar {
    height: 6px;
    background: var(--bg-tertiary);
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: var(--color-primary);
    width: 0%;
    transition: width 0.3s;
}

.uploaded-images {
    display: flex;
    flex-direction: column;
    gap: var(--space-xs);
}

.uploaded-image-item {
    display: flex;
    align-items: center;
    gap: var(--space-xs);
    padding: var(--space-xs);
    background: var(--bg-secondary);
    border-radius: var(--border-radius-sm);
    font-size: 0.8rem;
}

.uploaded-image-item img {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
}

.uploaded-image-item .image-url {
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: var(--text-muted);
}

.uploaded-image-item .copy-btn {
    padding: 4px 8px;
    font-size: 0.75rem;
    cursor: pointer;
}

/* Bildergalerie */
.image-gallery {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-sm);
    padding: var(--space-xs);
    background: var(--bg-secondary);
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 6px;
}

.gallery-item {
    position: relative;
    aspect-ratio: 1;
    cursor: pointer;
    border-radius: 4px;
    overflow: hidden;
    border: 2px solid transparent;
    transition: border-color 0.2s, transform 0.2s;
}

.gallery-item:hover {
    border-color: var(--color-primary);
    transform: scale(1.05);
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.gallery-item .copy-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.85);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    opacity: 0;
    transition: opacity 0.2s;
}

.gallery-item:hover .copy-overlay {
    opacity: 1;
}

.gallery-item .copy-overlay button {
    font-size: 0.65rem;
    padding: 3px 6px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    white-space: nowrap;
}

.gallery-item .copy-overlay .copy-url {
    background: var(--color-primary);
    color: white;
}

.gallery-item .copy-overlay .copy-md {
    background: white;
    color: #333;
}

.gallery-empty {
    text-align: center;
    padding: var(--space-md);
    color: var(--text-muted);
    font-size: 0.875rem;
}

/* Emoji Picker */
#emoji-picker {
    display: grid;
    grid-template-columns: repeat(8, 1fr);
    gap: 4px;
    max-width: 320px;
}

#emoji-picker button {
    font-size: 1.5rem;
    padding: 8px;
    border: none;
    background: transparent;
    cursor: pointer;
    border-radius: 4px;
    transition: background 0.2s;
}

#emoji-picker button:hover {
    background: var(--bg-secondary);
}

/* Markdown Preview Styling */
.blog-post-content h2 {
    font-size: 1.5rem;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.blog-post-content h3 {
    font-size: 1.25rem;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}

.blog-post-content img {
    max-width: 100%;
    height: auto;
    border-radius: var(--border-radius-md);
    margin: 1rem 0;
}

.blog-post-content code {
    background: var(--bg-tertiary);
    padding: 2px 6px;
    border-radius: 4px;
    font-family: monospace;
}

.blog-post-content pre {
    background: var(--bg-tertiary);
    padding: 1rem;
    border-radius: var(--border-radius-md);
    overflow-x: auto;
}

/* Mobile */
@media (max-width: 991px) {
    .hide-on-mobile {
        display: none !important;
    }
}
</style>

<script>
// Emoji Picker
const commonEmojis = ['📝', '💻', '🖥️', '⚙️', '🛠️', '💡', '🔧', '🖱️', '⌨️', '📱', '🎮', '🔌', '💾', '📊', '🚀', '✨', '📢', '🎯', '🏆', '❤️', '👍', '🔥', '⭐', '🌟', '📈', '🎨', '🔒', '🌐', '📦', '🎁', '📝'];

const emojiDisplay = document.getElementById('emoji-display');
const emojiInput = document.getElementById('emoji');
const emojiPicker = document.getElementById('emoji-picker');

// Emoji Picker erstellen
commonEmojis.forEach(emoji => {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.textContent = emoji;
    btn.onclick = function() {
        emojiInput.value = emoji;
        emojiDisplay.textContent = emoji;
        emojiPicker.style.display = 'none';
    };
    emojiPicker.appendChild(btn);
});

// Emoji Picker Toggle
emojiDisplay.addEventListener('click', function() {
    emojiPicker.style.display = emojiPicker.style.display === 'none' ? 'grid' : 'none';
});

// Schließen wenn außerhalb geklickt
document.addEventListener('click', function(e) {
    if (!emojiDisplay.contains(e.target) && !emojiPicker.contains(e.target)) {
        emojiPicker.style.display = 'none';
    }
});

// Slug-Vorschau aktualisieren
const slugInput = document.getElementById('slug');
const slugPreview = document.getElementById('slug-preview');

slugInput?.addEventListener('input', function() {
    slugPreview.textContent = this.value || 'slug';
});

// Auto-Slug aus Titel generieren
const titleInput = document.getElementById('title');

titleInput?.addEventListener('blur', function() {
    if (!slugInput.value) {
        const slug = createSlug(this.value);
        slugInput.value = slug;
        slugPreview.textContent = slug || 'slug';
    }
});

function createSlug(text) {
    return text.toLowerCase()
        .replace(/ä/g, 'ae')
        .replace(/ö/g, 'oe')
        .replace(/ü/g, 'ue')
        .replace(/ß/g, 'ss')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

// Markdown Live Preview
const contentTextarea = document.getElementById('content');
const previewDiv = document.getElementById('markdown-preview');
const previewContent = document.getElementById('preview-content');
const editorWrap = document.getElementById('markdown-editor-wrap');
const editorContainer = document.getElementById('editor-container');
const toggleText = document.getElementById('preview-toggle-text');

let previewVisible = false;

function togglePreview() {
    previewVisible = !previewVisible;

    if (previewVisible) {
        // Vorschau anzeigen
        previewDiv.style.display = 'block';
        editorContainer.style.gridTemplateColumns = '1fr 1fr';
        toggleText.textContent = '📝 Vorschau ausblenden';

        // Markdown rendern
        updatePreview();
    } else {
        // Vorschau ausblenden
        previewDiv.style.display = 'none';
        editorContainer.style.gridTemplateColumns = '1fr';
        toggleText.textContent = '📄 Vorschau anzeigen';
    }
}

// Live Preview Update (beim Tippen)
contentTextarea.addEventListener('input', debounce(updatePreview, 500));

function updatePreview() {
    if (!previewVisible) return;

    const markdown = contentTextarea.value;

    // AJAX Request zum Backend für Parsedown
    fetch('<?= BASE_URL ?>/admin/preview-markdown.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'markdown=' + encodeURIComponent(markdown)
    })
    .then(response => response.text())
    .then(html => {
        previewContent.innerHTML = html;
    })
    .catch(error => {
        previewContent.innerHTML = '<p class="text-muted">Vorschau konnte nicht geladen werden.</p>';
    });
}

// Debounce helper
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// =====================================================
// Bild-Upload & Galerie
// =====================================================
const imageUpload = document.getElementById('image-upload');
const uploadArea = document.getElementById('upload-area');
const uploadProgress = document.getElementById('upload-progress');
const progressFill = document.getElementById('progress-fill');
const uploadStatus = document.getElementById('upload-status');
const imageGallery = document.getElementById('image-gallery');

// Galerie beim Laden füllen
loadImageGallery();

function loadImageGallery() {
    fetch('<?= BASE_URL ?>/admin/list-images.php')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.images.length > 0) {
                renderGallery(data.images);
            } else {
                imageGallery.innerHTML = '<p class="gallery-empty">Noch keine Bilder hochgeladen</p>';
            }
        })
        .catch(() => {
            imageGallery.innerHTML = '<p class="gallery-empty">Fehler beim Laden</p>';
        });
}

function renderGallery(images) {
    let html = '<div class="gallery-grid">';
    images.forEach(img => {
        html += `
            <div class="gallery-item" title="${img.filename}">
                <img src="${img.url}" alt="${img.filename}" loading="lazy">
                <div class="copy-overlay">
                    <button type="button" class="copy-url" onclick="copyToClipboard(event, '${img.url}', this)">URL kopieren</button>
                    <button type="button" class="copy-md" onclick="copyToClipboard(event, '![${img.filename}](${img.url})', this)">Markdown</button>
                </div>
            </div>
        `;
    });
    html += '</div>';
    imageGallery.innerHTML = html;
}

function copyToClipboard(e, text, btn) {
    e.preventDefault();
    e.stopPropagation();
    navigator.clipboard.writeText(text).then(() => {
        const originalText = btn.textContent;
        btn.textContent = '✓ Kopiert!';
        setTimeout(() => {
            btn.textContent = originalText;
        }, 1500);
    }).catch(() => {
        prompt('Kopieren:', text);
    });
}

// Drag & Drop
uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('dragover');
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('dragover');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        uploadImage(files[0]);
    }
});

// File Input
imageUpload.addEventListener('change', () => {
    if (imageUpload.files.length > 0) {
        uploadImage(imageUpload.files[0]);
    }
});

function uploadImage(file) {
    // Validierung
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!allowedTypes.includes(file.type)) {
        alert('Nur JPG, PNG, GIF und WebP erlaubt.');
        return;
    }
    if (file.size > 5 * 1024 * 1024) {
        alert('Datei zu groß (max. 5 MB).');
        return;
    }

    // Upload starten
    uploadArea.style.display = 'none';
    uploadProgress.style.display = 'block';
    progressFill.style.width = '0%';
    uploadStatus.textContent = 'Lade hoch...';

    const formData = new FormData();
    formData.append('image', file);
    formData.append('csrf_token', '<?= csrf_token() ?>');

    const xhr = new XMLHttpRequest();

    xhr.upload.addEventListener('progress', (e) => {
        if (e.lengthComputable) {
            const percent = Math.round((e.loaded / e.total) * 100);
            progressFill.style.width = percent + '%';
            uploadStatus.textContent = percent + '% hochgeladen...';
        }
    });

    xhr.addEventListener('load', () => {
        uploadProgress.style.display = 'none';
        uploadArea.style.display = 'block';
        imageUpload.value = '';

        try {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Galerie neu laden
                loadImageGallery();
                // URL in Zwischenablage kopieren
                navigator.clipboard.writeText(response.url).then(() => {
                    alert('Bild hochgeladen!\n\nURL wurde kopiert:\n' + response.url);
                }).catch(() => {
                    alert('Bild hochgeladen!\n\nURL:\n' + response.url);
                });
            } else {
                alert('Upload-Fehler: ' + (response.error || 'Unbekannter Fehler'));
            }
        } catch (e) {
            alert('Upload-Fehler: Ungültige Server-Antwort');
        }
    });

    xhr.addEventListener('error', () => {
        uploadProgress.style.display = 'none';
        uploadArea.style.display = 'block';
        alert('Upload fehlgeschlagen. Bitte erneut versuchen.');
    });

    xhr.open('POST', '<?= BASE_URL ?>/admin/upload-image.php');
    xhr.send(formData);
}
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
