<?php
/**
 * Liste aller Blog-Bilder
 * PC-Wittfoot UG
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../core/config.php';
start_session_safe();

// Admin-Rechte prüfen
if (!is_admin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Keine Berechtigung']);
    exit;
}

// Bilder-Verzeichnis
$uploadDir = UPLOADS_PATH . '/blog/';

if (!is_dir($uploadDir)) {
    echo json_encode(['success' => true, 'images' => []]);
    exit;
}

// Alle Bilder auflisten
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$images = [];

$files = scandir($uploadDir, SCANDIR_SORT_DESCENDING);
foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;

    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExtensions)) continue;

    $filepath = $uploadDir . $file;
    $images[] = [
        'filename' => $file,
        'url' => UPLOADS_URL . '/blog/' . $file,
        'size' => filesize($filepath),
        'modified' => filemtime($filepath)
    ];
}

// Nach Datum sortieren (neueste zuerst)
usort($images, function($a, $b) {
    return $b['modified'] - $a['modified'];
});

echo json_encode([
    'success' => true,
    'images' => $images
]);
