<?php
/**
 * Markdown Preview Endpoint
 * Rendert Markdown zu HTML für Live-Preview im Editor
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();

// Admin-Rechte prüfen
require_admin();

// Nur POST-Requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

// Markdown aus Request holen
$markdown = $_POST['markdown'] ?? '';

if (empty($markdown)) {
    echo '<p class="text-muted">Keine Vorschau verfügbar. Schreiben Sie etwas im Editor...</p>';
    exit;
}

// Markdown zu HTML konvertieren
$html = markdown_to_html($markdown, true);

// Relative Bild-URLs für Vorschau korrigieren
// /uploads/... -> volle URL für korrektes Laden im AJAX-Kontext
$html = preg_replace(
    '/src="\/uploads\//',
    'src="' . BASE_URL . '/uploads/',
    $html
);

// HTML zurückgeben
echo $html;
