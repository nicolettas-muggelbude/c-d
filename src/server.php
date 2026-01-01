<?php
/**
 * Router für PHP Built-in Server
 * Verwendung: php -S localhost:8000 server.php
 */

error_log("=== ROUTER CALLED ===");
error_log("URI: " . $_SERVER['REQUEST_URI']);

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
error_log("Parsed URI: " . $uri);

// Asset-Dateien direkt ausliefern
if (preg_match('/\.(css|js|jpg|jpeg|png|gif|svg|webp|woff|woff2|ttf|eot|ico|pdf)$/', $uri)) {
    return false;
}

// Direkte PHP-Dateien ausliefern (test-db.php etc.)
if ($uri !== '/' && $uri !== '/index.php') {
    $file = __DIR__ . $uri;
    if (is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        return false;
    }
}

// Für alle anderen Anfragen: Route setzen und router.php laden
$route = trim($uri, '/');
if (empty($route) || $route === 'index.php') {
    $route = '';
}

$_GET['route'] = $route;
require __DIR__ . '/router.php';
