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
                    INSERT INTO blog_posts (emoji, title, slug, excerpt, content, hero_image, author_name, keywords, category, published, published_at)
                    VALUES (:emoji, :title, :slug, :excerpt, :content, :hero_image, :author_name, :keywords, :category, :published, :published_at)
                ", [
                    ':emoji' => $form_data['emoji'],
                    ':title' => $form_data['title'],
                    ':slug' => $form_data['slug'],
                    ':excerpt' => $form_data['excerpt'],
                    ':content' => $form_data['content'],
                    ':hero_image' => !empty($form_data['hero_image']) ? $form_data['hero_image'] : null,
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
            <div style="display: flex; gap: var(--space-sm); flex-wrap: wrap;">
                <a href="<?= BASE_URL ?>/admin/markdown-hilfe" target="_blank" class="btn btn-outline btn-sm">
                    📖 Markdown-Hilfe
                </a>
                <a href="<?= BASE_URL ?>/admin/blog-posts" class="btn btn-outline">← Zurück</a>
            </div>
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
                                   pattern="[a-z0-9-]+"
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
                    <div class="card mb-md" style="padding: var(--space-md);">
                        <h3 style="margin: 0 0 var(--space-sm) 0; font-size: 1.1em;">Veröffentlichung</h3>

                        <div style="margin-bottom: var(--space-xs);">
                            <label class="form-check">
                                <input type="checkbox"
                                       id="published"
                                       name="published"
                                       <?= $form_data['published'] ? 'checked' : '' ?>>
                                <span>Veröffentlicht</span>
                            </label>
                        </div>

                        <div style="margin-bottom: var(--space-sm);">
                            <label for="published_at" style="font-size: 0.85em; display: block; margin-bottom: 4px;">Datum</label>
                            <input type="datetime-local"
                                   id="published_at"
                                   name="published_at"
                                   style="font-size: 0.9em;"
                                   value="<?= date('Y-m-d\TH:i', strtotime($form_data['published_at']))?>">
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" style="padding: var(--space-sm) var(--space-md);">
                            <?= $id ? 'Aktualisieren' : 'Erstellen' ?>
                        </button>

                        <?php if ($id && $form_data['published']): ?>
                            <a href="<?= BASE_URL ?>/blog/<?= e($form_data['slug']) ?>"
                               class="btn btn-outline btn-block"
                               style="margin-top: var(--space-xs); padding: var(--space-sm) var(--space-md);"
                               target="_blank">
                                Ansehen
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Kategorie -->
                    <div class="card mb-md" style="padding: var(--space-md);">
                        <h3 style="margin: 0 0 var(--space-sm) 0; font-size: 1.1em;">Kategorie</h3>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="category" style="font-size: 0.85em; display: block; margin-bottom: 4px;">
                                Kategorie
                            </label>
                            <select id="category" name="category" style="font-size: 0.9em;">
                                <option value="Allgemein" <?= ($form_data['category'] ?? 'Allgemein') === 'Allgemein' ? 'selected' : '' ?>>📝 Allgemein</option>
                                <option value="Hardware" <?= ($form_data['category'] ?? '') === 'Hardware' ? 'selected' : '' ?>>🖥️ Hardware</option>
                                <option value="Software" <?= ($form_data['category'] ?? '') === 'Software' ? 'selected' : '' ?>>💻 Software</option>
                                <option value="Tipps" <?= ($form_data['category'] ?? '') === 'Tipps' ? 'selected' : '' ?>>💡 Tipps</option>
                                <option value="News" <?= ($form_data['category'] ?? '') === 'News' ? 'selected' : '' ?>>📢 News</option>
                            </select>
                        </div>
                    </div>

                    <!-- SEO Keywords -->
                    <div class="card" style="padding: var(--space-md);">
                        <h3 style="margin: 0 0 var(--space-sm) 0; font-size: 1.1em;">SEO Keywords</h3>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="keywords" style="font-size: 0.85em; display: block; margin-bottom: 4px;">
                                Keywords <span class="text-muted">(Komma-getrennt)</span>
                            </label>
                            <input type="text"
                                   id="keywords"
                                   name="keywords"
                                   placeholder="Laptop, Reparatur, Oldenburg"
                                   value="<?= e($form_data['keywords'] ?? '') ?>"
                                   style="font-size: 0.9em;">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<style>
@media (max-width: 991px) {
    .hide-on-mobile {
        display: none !important;
    }
}

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
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
