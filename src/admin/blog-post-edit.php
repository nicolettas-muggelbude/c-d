<?php
/**
 * Admin - Blog-Post bearbeiten/erstellen
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
    'title' => '',
    'slug' => '',
    'excerpt' => '',
    'content' => '',
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
            'title' => sanitize($_POST['title'] ?? ''),
            'slug' => sanitize($_POST['slug'] ?? ''),
            'excerpt' => sanitize($_POST['excerpt'] ?? ''),
            'content' => trim($_POST['content'] ?? ''),
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
                        title = :title,
                        slug = :slug,
                        excerpt = :excerpt,
                        content = :content,
                        published = :published,
                        published_at = :published_at,
                        author_id = :author_id
                    WHERE id = :id
                ", [
                    ':title' => $form_data['title'],
                    ':slug' => $form_data['slug'],
                    ':excerpt' => $form_data['excerpt'],
                    ':content' => $form_data['content'],
                    ':published' => $form_data['published'],
                    ':published_at' => $form_data['published'] ? $form_data['published_at'] : null,
                    ':author_id' => $_SESSION['user_id'],
                    ':id' => $id
                ]);

                set_flash('success', 'Blog-Post wurde aktualisiert.');
            } else {
                // Insert
                $db->insert("
                    INSERT INTO blog_posts (title, slug, excerpt, content, published, published_at, author_id)
                    VALUES (:title, :slug, :excerpt, :content, :published, :published_at, :author_id)
                ", [
                    ':title' => $form_data['title'],
                    ':slug' => $form_data['slug'],
                    ':excerpt' => $form_data['excerpt'],
                    ':content' => $form_data['content'],
                    ':published' => $form_data['published'],
                    ':published_at' => $form_data['published'] ? $form_data['published_at'] : null,
                    ':author_id' => $_SESSION['user_id']
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

            <div class="row">
                <!-- Hauptbereich -->
                <div class="col-12 col-lg-8">
                    <div class="card mb-lg">
                        <div class="form-group">
                            <label for="title">Titel *</label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   value="<?= e($form_data['title']) ?>"
                                   required
                                   autofocus>
                        </div>

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

                        <div class="form-group">
                            <label for="excerpt">
                                Kurzbeschreibung
                                <small class="text-muted">(Optional, wird in Übersichten angezeigt)</small>
                            </label>
                            <textarea id="excerpt"
                                      name="excerpt"
                                      rows="3"><?= e($form_data['excerpt']) ?></textarea>
                        </div>

                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-sm);">
                                <label for="content">Inhalt *</label>
                                <button type="button" class="btn btn-sm btn-outline" onclick="toggleFormatHelp()">
                                    📖 Formatierungs-Hilfe
                                </button>
                            </div>

                            <!-- Formatierungs-Hilfe (kompakt, oberhalb) -->
                            <div id="format-help" style="display: none; background: var(--color-bg, #fff); border: 1px solid var(--color-border, #ddd); border-radius: 4px; padding: var(--space-md); margin-bottom: var(--space-md); max-height: 400px; overflow-y: auto; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                <div style="color: var(--color-text, #000);">

                                <h5>Überschriften</h5>
                                <code>&lt;h2&gt;Große Überschrift&lt;/h2&gt;</code><br>
                                <code>&lt;h3&gt;Mittlere Überschrift&lt;/h3&gt;</code><br>
                                <code>&lt;h4&gt;Kleine Überschrift&lt;/h4&gt;</code>

                                <h5 style="margin-top: var(--space-md);">Text-Formatierung</h5>
                                <code>&lt;strong&gt;Fett&lt;/strong&gt;</code> → <strong>Fett</strong><br>
                                <code>&lt;em&gt;Kursiv&lt;/em&gt;</code> → <em>Kursiv</em><br>
                                <code>&lt;mark&gt;Markiert&lt;/mark&gt;</code> → <mark>Markiert</mark><br>
                                <code>&lt;code&gt;Code&lt;/code&gt;</code> → <code>Code</code>

                                <h5 style="margin-top: var(--space-md);">Absätze & Zeilenumbrüche</h5>
                                <code>&lt;p&gt;Absatz&lt;/p&gt;</code><br>
                                <code>&lt;br&gt;</code> → Zeilenumbruch

                                <h5 style="margin-top: var(--space-md);">Listen</h5>
                                <pre style="background: var(--color-bg, #fff); padding: var(--space-sm); border-radius: 4px; overflow-x: auto;"><code>&lt;ul&gt;
  &lt;li&gt;Erster Punkt&lt;/li&gt;
  &lt;li&gt;Zweiter Punkt&lt;/li&gt;
&lt;/ul&gt;</code></pre>

                                <pre style="background: var(--color-bg, #fff); padding: var(--space-sm); border-radius: 4px; overflow-x: auto;"><code>&lt;ol&gt;
  &lt;li&gt;Punkt 1&lt;/li&gt;
  &lt;li&gt;Punkt 2&lt;/li&gt;
&lt;/ol&gt;</code></pre>

                                <h5 style="margin-top: var(--space-md);">Links</h5>
                                <code>&lt;a href="https://example.com"&gt;Link-Text&lt;/a&gt;</code>

                                <h5 style="margin-top: var(--space-md);">Bilder</h5>
                                <code>&lt;img src="<?= UPLOADS_URL ?>/blog/bild.jpg" alt="Beschreibung"&gt;</code>

                                <h5 style="margin-top: var(--space-md);">Bilder mit Bildunterschrift</h5>
                                <pre style="background: var(--color-bg, #fff); padding: var(--space-sm); border-radius: 4px; overflow-x: auto;"><code>&lt;figure&gt;
  &lt;img src="<?= UPLOADS_URL ?>/blog/bild.jpg" alt="Beschreibung"&gt;
  &lt;figcaption&gt;Bildunterschrift&lt;/figcaption&gt;
&lt;/figure&gt;</code></pre>

                                <h5 style="margin-top: var(--space-md);">Zitate</h5>
                                <code>&lt;blockquote&gt;Zitattext&lt;/blockquote&gt;</code>

                                <h5 style="margin-top: var(--space-md);">Code-Blöcke</h5>
                                <pre style="background: var(--color-bg, #fff); padding: var(--space-sm); border-radius: 4px; overflow-x: auto;"><code>&lt;pre&gt;&lt;code&gt;
function hello() {
  console.log("Hello World");
}
&lt;/code&gt;&lt;/pre&gt;</code></pre>

                                <p style="margin-top: var(--space-md); margin-bottom: 0; padding: var(--space-sm); background: #e3f2fd; border-radius: 4px; color: #000;">
                                    <strong>💡 Tipp:</strong> Bilder müssen zuerst in <code>/uploads/blog/</code> hochgeladen werden.
                                </p>
                                </div>
                            </div>

                            <textarea id="content"
                                      name="content"
                                      rows="20"
                                      required><?= e($form_data['content']) ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-12 col-lg-4">
                    <div class="card mb-lg">
                        <h3 class="mb-md">Veröffentlichung</h3>

                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox"
                                       id="published"
                                       name="published"
                                       <?= $form_data['published'] ? 'checked' : '' ?>>
                                <span>Veröffentlicht</span>
                            </label>
                        </div>

                        <div class="form-group mb-lg">
                            <label for="published_at">Veröffentlichungsdatum</label>
                            <input type="datetime-local"
                                   id="published_at"
                                   name="published_at"
                                   value="<?= date('Y-m-d\TH:i', strtotime($form_data['published_at'])) ?>">
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <?= $id ? 'Aktualisieren' : 'Erstellen' ?>
                        </button>

                        <?php if ($id && $form_data['published']): ?>
                            <a href="<?= BASE_URL ?>/blog/<?= e($form_data['slug']) ?>"
                               class="btn btn-outline btn-block mt-md"
                               target="_blank">
                                Ansehen
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
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

// Formatierungs-Hilfe ein-/ausblenden
function toggleFormatHelp() {
    const helpDiv = document.getElementById('format-help');
    if (helpDiv.style.display === 'none') {
        helpDiv.style.display = 'block';
    } else {
        helpDiv.style.display = 'none';
    }
}
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
