<?php
/**
 * Development Router für PHP Built-in Server
 *
 * Verwendung: php -S localhost:8000 dev-router.php
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = urldecode($uri);

// Asset-Dateien direkt ausliefern
if (preg_match('/\.(css|js|jpg|jpeg|png|gif|svg|webp|woff|woff2|ttf|eot|ico|pdf)$/', $uri)) {
    return false; // PHP Built-in Server liefert die Datei aus
}

// Vorhandene PHP-Dateien direkt ausliefern (für test-db.php etc.)
if ($uri !== '/' && file_exists(__DIR__ . $uri) && is_file(__DIR__ . $uri)) {
    return false;
}

// Alle anderen Anfragen an router.php weiterleiten
$_GET['route'] = trim($uri, '/');
require __DIR__ . '/router.php';
