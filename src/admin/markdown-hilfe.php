<?php
/**
 * Markdown-Hilfe für Blog-Autoren
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();

// Admin-Rechte prüfen
require_admin();

$page_title = 'Markdown-Hilfe | Admin | PC-Wittfoot UG';
$page_description = 'Markdown-Syntax Referenz';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container" style="max-width: 900px;">
        <div class="d-flex justify-between align-center mb-lg">
            <h1>📖 Markdown-Hilfe</h1>
            <a href="<?= BASE_URL ?>/admin/blog-posts" class="btn btn-outline">← Zurück</a>
        </div>

        <div class="card">
            <p class="lead">
                Markdown ist eine einfache Markup-Sprache, die sich leicht lesen und schreiben lässt.
                Diese Anleitung zeigt die wichtigsten Formatierungen.
            </p>

            <hr>

            <!-- Überschriften -->
            <h2>Überschriften</h2>
            <div class="markdown-example">
                <div class="example-code">
                    <pre><code># Überschrift H1
## Überschrift H2
### Überschrift H3
#### Überschrift H4</code></pre>
                </div>
                <div class="example-result">
                    <h1>Überschrift H1</h1>
                    <h2>Überschrift H2</h2>
                    <h3>Überschrift H3</h3>
                    <h4>Überschrift H4</h4>
                </div>
            </div>

            <hr>

            <!-- Text-Formatierung -->
            <h2>Text-Formatierung</h2>
            <div class="markdown-example">
                <div class="example-code">
                    <pre><code>**Fetter Text**
*Kursiver Text*
***Fett und Kursiv***
~~Durchgestrichen~~
`Inline-Code`</code></pre>
                </div>
                <div class="example-result">
                    <p><strong>Fetter Text</strong></p>
                    <p><em>Kursiver Text</em></p>
                    <p><strong><em>Fett und Kursiv</em></strong></p>
                    <p><del>Durchgestrichen</del></p>
                    <p><code>Inline-Code</code></p>
                </div>
            </div>

            <hr>

            <!-- Listen -->
            <h2>Listen</h2>
            <h3>Ungeordnete Liste</h3>
            <div class="markdown-example">
                <div class="example-code">
                    <pre><code>- Punkt 1
- Punkt 2
  - Unterpunkt 2.1
  - Unterpunkt 2.2
- Punkt 3</code></pre>
                </div>
                <div class="example-result">
                    <ul>
                        <li>Punkt 1</li>
                        <li>Punkt 2
                            <ul>
                                <li>Unterpunkt 2.1</li>
                                <li>Unterpunkt 2.2</li>
                            </ul>
                        </li>
                        <li>Punkt 3</li>
                    </ul>
                </div>
            </div>

            <h3>Geordnete Liste</h3>
            <div class="markdown-example">
                <div class="example-code">
                    <pre><code>1. Erster Punkt
2. Zweiter Punkt
3. Dritter Punkt</code></pre>
                </div>
                <div class="example-result">
                    <ol>
                        <li>Erster Punkt</li>
                        <li>Zweiter Punkt</li>
                        <li>Dritter Punkt</li>
                    </ol>
                </div>
            </div>

            <hr>

            <!-- Links -->
            <h2>Links</h2>
            <div class="markdown-example">
                <div class="example-code">
                    <pre><code>[PC-Wittfoot Website](https://pc-wittfoot.de)
[Kontakt](<?= BASE_URL ?>/kontakt)</code></pre>
                </div>
                <div class="example-result">
                    <p><a href="https://pc-wittfoot.de">PC-Wittfoot Website</a></p>
                    <p><a href="<?= BASE_URL ?>/kontakt">Kontakt</a></p>
                </div>
            </div>

            <hr>

            <!-- Bilder -->
            <h2>Bilder</h2>
            <div class="markdown-example">
                <div class="example-code">
                    <pre><code>![Alt-Text](<?= UPLOADS_URL ?>/blog/bild.jpg)</code></pre>
                </div>
                <div class="example-result">
                    <p class="text-muted"><em>Beispiel-Bild würde hier angezeigt</em></p>
                    <p class="text-muted">Syntax: <code>![Beschreibung](URL)</code></p>
                </div>
            </div>

            <div class="alert" style="background: var(--bg-secondary); padding: var(--space-md); border-radius: var(--border-radius-md); margin-top: var(--space-md);">
                <strong>💡 Tipp:</strong> Lade Bilder in <code>/uploads/blog/</code> hoch und verwende dann:<br>
                <code>![Beschreibung](<?= UPLOADS_URL ?>/blog/dateiname.jpg)</code>
            </div>

            <hr>

            <!-- Zitate -->
            <h2>Zitate</h2>
            <div class="markdown-example">
                <div class="example-code">
                    <pre><code>> Dies ist ein Zitat.
> Es kann mehrere Zeilen haben.</code></pre>
                </div>
                <div class="example-result">
                    <blockquote>
                        <p>Dies ist ein Zitat.<br>
                        Es kann mehrere Zeilen haben.</p>
                    </blockquote>
                </div>
            </div>

            <hr>

            <!-- Code-Blöcke -->
            <h2>Code-Blöcke</h2>
            <div class="markdown-example">
                <div class="example-code">
                    <pre><code>```php
function hello() {
    echo "Hallo Welt!";
}
```</code></pre>
                </div>
                <div class="example-result">
                    <pre><code>function hello() {
    echo "Hallo Welt!";
}</code></pre>
                </div>
            </div>

            <hr>

            <!-- Trennlinien -->
            <h2>Trennlinien</h2>
            <div class="markdown-example">
                <div class="example-code">
                    <pre><code>---

Text davor

---

Text danach</code></pre>
                </div>
                <div class="example-result">
                    <p>Text davor</p>
                    <hr>
                    <p>Text danach</p>
                </div>
            </div>

            <hr>

            <!-- Tabellen -->
            <h2>Tabellen</h2>
            <div class="markdown-example">
                <div class="example-code">
                    <pre><code>| Spalte 1 | Spalte 2 | Spalte 3 |
|----------|----------|----------|
| Zeile 1  | Wert A   | Wert B   |
| Zeile 2  | Wert C   | Wert D   |</code></pre>
                </div>
                <div class="example-result">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th style="border: 1px solid var(--border-color); padding: 8px;">Spalte 1</th>
                                <th style="border: 1px solid var(--border-color); padding: 8px;">Spalte 2</th>
                                <th style="border: 1px solid var(--border-color); padding: 8px;">Spalte 3</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border: 1px solid var(--border-color); padding: 8px;">Zeile 1</td>
                                <td style="border: 1px solid var(--border-color); padding: 8px;">Wert A</td>
                                <td style="border: 1px solid var(--border-color); padding: 8px;">Wert B</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid var(--border-color); padding: 8px;">Zeile 2</td>
                                <td style="border: 1px solid var(--border-color); padding: 8px;">Wert C</td>
                                <td style="border: 1px solid var(--border-color); padding: 8px;">Wert D</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr>

            <!-- Checklisten -->
            <h2>Checklisten</h2>
            <div class="markdown-example">
                <div class="example-code">
                    <pre><code>- [ ] Aufgabe 1 (offen)
- [x] Aufgabe 2 (erledigt)
- [ ] Aufgabe 3 (offen)</code></pre>
                </div>
                <div class="example-result">
                    <ul style="list-style: none; padding-left: 0;">
                        <li>☐ Aufgabe 1 (offen)</li>
                        <li>☑ Aufgabe 2 (erledigt)</li>
                        <li>☐ Aufgabe 3 (offen)</li>
                    </ul>
                </div>
            </div>

            <hr>

            <!-- Quick Reference -->
            <h2>Quick Reference</h2>
            <div style="background: var(--bg-secondary); padding: var(--space-md); border-radius: var(--border-radius-md);">
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="text-align: left; padding: 8px; border-bottom: 2px solid var(--border-color);">Element</th>
                            <th style="text-align: left; padding: 8px; border-bottom: 2px solid var(--border-color);">Markdown</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 8px;">Überschrift</td>
                            <td style="padding: 8px;"><code># H1 ## H2 ### H3</code></td>
                        </tr>
                        <tr>
                            <td style="padding: 8px;">Fett</td>
                            <td style="padding: 8px;"><code>**fett**</code></td>
                        </tr>
                        <tr>
                            <td style="padding: 8px;">Kursiv</td>
                            <td style="padding: 8px;"><code>*kursiv*</code></td>
                        </tr>
                        <tr>
                            <td style="padding: 8px;">Link</td>
                            <td style="padding: 8px;"><code>[Text](URL)</code></td>
                        </tr>
                        <tr>
                            <td style="padding: 8px;">Bild</td>
                            <td style="padding: 8px;"><code>![Alt](URL)</code></td>
                        </tr>
                        <tr>
                            <td style="padding: 8px;">Liste</td>
                            <td style="padding: 8px;"><code>- Punkt</code></td>
                        </tr>
                        <tr>
                            <td style="padding: 8px;">Code</td>
                            <td style="padding: 8px;"><code>`code`</code></td>
                        </tr>
                        <tr>
                            <td style="padding: 8px;">Zitat</td>
                            <td style="padding: 8px;"><code>&gt; Zitat</code></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="alert" style="background: var(--color-primary); color: white; padding: var(--space-md); border-radius: var(--border-radius-md); margin-top: var(--space-xl);">
                <h3 style="margin: 0 0 var(--space-sm) 0;">💡 Weitere Ressourcen</h3>
                <ul style="margin: var(--space-sm) 0 0 0; padding-left: var(--space-lg);">
                    <li><a href="https://www.markdownguide.org/" target="_blank" style="color: white; text-decoration: underline;">Markdown Guide (English)</a></li>
                    <li><a href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet" target="_blank" style="color: white; text-decoration: underline;">Markdown Cheatsheet</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<style>
.markdown-example {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-md);
    margin-bottom: var(--space-lg);
}

@media (max-width: 768px) {
    .markdown-example {
        grid-template-columns: 1fr;
    }
}

.example-code {
    background: var(--bg-secondary);
    padding: var(--space-md);
    border-radius: var(--border-radius-md);
    border: 1px solid var(--border-color);
}

.example-code pre {
    margin: 0;
    white-space: pre-wrap;
    font-size: 0.9em;
}

.example-code code {
    background: none;
    padding: 0;
}

.example-result {
    padding: var(--space-md);
    border-radius: var(--border-radius-md);
    border: 1px solid var(--border-color);
}

.example-result h1 {
    font-size: 2rem;
    margin: 0;
}

.example-result h2 {
    font-size: 1.5rem;
    margin: 0;
}

.example-result h3 {
    font-size: 1.25rem;
    margin: 0;
}

.example-result h4 {
    font-size: 1rem;
    margin: 0;
}

.example-result blockquote {
    border-left: 4px solid var(--color-primary);
    padding-left: var(--space-md);
    margin: 0;
    color: var(--text-muted);
}

.example-result code {
    background: var(--bg-tertiary);
    padding: 2px 6px;
    border-radius: 4px;
    font-family: monospace;
}

.example-result pre {
    background: var(--bg-tertiary);
    padding: var(--space-md);
    border-radius: var(--border-radius-md);
    overflow-x: auto;
}

.example-result pre code {
    background: none;
    padding: 0;
}
</style>

<?php include __DIR__ . '/../templates/footer.php'; ?>
