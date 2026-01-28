<?php
/**
 * API-Endpoint: Download mit Counter
 * PC-Wittfoot UG
 *
 * GET /api/download/{slug} - Datei herunterladen
 */

require_once __DIR__ . '/../core/config.php';

// Slug aus URL holen (wird vom Router gesetzt)
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    http_response_code(404);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['error' => 'Kein Download angegeben']);
    exit;
}

$db = Database::getInstance();

// Download aus Datenbank laden
$download = $db->querySingle("
    SELECT *
    FROM downloads
    WHERE slug = :slug
    LIMIT 1
", [':slug' => $slug]);

// Nicht gefunden
if (!$download) {
    http_response_code(404);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['error' => 'Download nicht gefunden']);
    exit;
}

// Nicht aktiv (nur im öffentlichen Bereich)
if (!$download['is_active']) {
    http_response_code(404);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['error' => 'Download nicht verfügbar']);
    exit;
}

// Sicherheit: Directory Traversal verhindern
// Nur Dateinamen erlauben, keine Pfade
$filename = basename($download['filename']);
if ($filename !== $download['filename']) {
    http_response_code(400);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['error' => 'Ungültiger Dateiname']);
    error_log("Security: Directory traversal attempt detected in download API: " . $download['filename']);
    exit;
}

// Vollständiger Dateipfad
$file_path = __DIR__ . '/../../uploads/downloads/' . $filename;

// Prüfen ob Datei existiert
if (!file_exists($file_path) || !is_readable($file_path)) {
    http_response_code(404);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['error' => 'Datei nicht gefunden']);
    error_log("Download API: File not found: " . $file_path);
    exit;
}

// Download-Counter erhöhen (asynchron, Fehler ignorieren)
try {
    $db->update("
        UPDATE downloads
        SET download_count = download_count + 1
        WHERE id = :id
    ", [':id' => $download['id']]);
} catch (Exception $e) {
    // Counter-Update-Fehler nicht an User weitergeben
    error_log("Download counter update failed: " . $e->getMessage());
}

// Dateigröße ermitteln
$file_size = filesize($file_path);

// MIME-Type ermitteln (mit Fallback)
$mime_type = $download['file_type'] ?: 'application/octet-stream';
if (function_exists('mime_content_type')) {
    $detected_mime = mime_content_type($file_path);
    if ($detected_mime) {
        $mime_type = $detected_mime;
    }
}

// Download-Name für Browser (aus Titel + Extension)
$extension = pathinfo($filename, PATHINFO_EXTENSION);
$download_name = create_slug($download['title']);
if (!empty($extension)) {
    $download_name .= '.' . $extension;
} else {
    // Fallback: Original-Dateiname
    $download_name = $filename;
}

// Headers für Download setzen
header('Content-Type: ' . $mime_type);
header('Content-Disposition: attachment; filename="' . $download_name . '"');
header('Content-Length: ' . $file_size);
header('Cache-Control: private, max-age=3600');
header('Pragma: public');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');

// Output-Buffering ausschalten für große Dateien
if (ob_get_level()) {
    ob_end_clean();
}

// Datei ausgeben
readfile($file_path);
exit;
