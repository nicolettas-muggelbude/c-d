<?php
// Einfacher Test-Router
echo "Router wird aufgerufen!<br>";
echo "URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "GET: ";
print_r($_GET);
echo "<br><br>";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$_GET['route'] = trim($uri, '/');

echo "Route: " . $_GET['route'] . "<br>";

require __DIR__ . '/core/config.php';
start_session_safe();

$route = isset($_GET['route']) ? trim($_GET['route'], '/') : '';
$parts = explode('/', $route);
$page = $parts[0];

echo "Page: " . htmlspecialchars($page) . "<br>";

if (empty($route)) {
    echo "→ Zeige Startseite<br>";
    require __DIR__ . '/index.php';
    exit;
}

if ($page === 'shop') {
    echo "→ Zeige Shop<br>";
    require __DIR__ . '/pages/shop.php';
    exit;
}

echo "→ 404<br>";
http_response_code(404);
require __DIR__ . '/pages/404.php';
