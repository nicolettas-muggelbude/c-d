<?php
/**
 * Bild-Upload für Blog-Posts
 * PC-Wittfoot UG
 */

// JSON-Response Header immer setzen
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../core/config.php';
start_session_safe();

// Admin-Rechte prüfen
if (!is_admin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Keine Berechtigung']);
    exit;
}

// CSRF-Check
$csrf_token = $_POST['csrf_token'] ?? '';
if (!csrf_verify($csrf_token)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Ungültiger Sicherheitstoken']);
    exit;
}

// Prüfen ob Datei hochgeladen wurde
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    $error = $_FILES['image']['error'] ?? 'Keine Datei';
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Upload-Fehler: ' . $error]);
    exit;
}

$file = $_FILES['image'];

// Erlaubte MIME-Types
$allowedTypes = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/gif' => 'gif',
    'image/webp' => 'webp'
];

// MIME-Type prüfen (sicher mit finfo)
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!isset($allowedTypes[$mimeType])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Ungültiger Dateityp. Erlaubt: JPG, PNG, GIF, WebP']);
    exit;
}

// Dateigröße prüfen (max. 5 MB)
$maxSize = 5 * 1024 * 1024;
if ($file['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datei zu groß (max. 5 MB)']);
    exit;
}

// Upload-Verzeichnis (UPLOADS_PATH aus config.php)
$uploadDir = UPLOADS_PATH . '/blog/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Sicherer Dateiname generieren
$extension = $allowedTypes[$mimeType];
$originalName = pathinfo($file['name'], PATHINFO_FILENAME);
$safeName = preg_replace('/[^a-z0-9\-]/', '-', strtolower($originalName));
$safeName = preg_replace('/-+/', '-', $safeName);
$safeName = trim($safeName, '-');

// Kürzen wenn zu lang
if (strlen($safeName) > 50) {
    $safeName = substr($safeName, 0, 50);
}

// Eindeutigen Dateinamen erstellen
$timestamp = date('Ymd-His');
$uniqueId = substr(uniqid(), -4);
$filename = "{$timestamp}-{$safeName}-{$uniqueId}.{$extension}";
$filepath = $uploadDir . $filename;

// Datei verschieben
if (!move_uploaded_file($file['tmp_name'], $filepath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Speichern fehlgeschlagen']);
    exit;
}

// Erfolg - relative URL für Portabilität
$url = '/uploads/blog/' . $filename;

echo json_encode([
    'success' => true,
    'url' => $url,
    'filename' => $filename
]);
exit;
