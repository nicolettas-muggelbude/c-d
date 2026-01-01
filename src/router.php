<?php
/**
 * Router - PC-Wittfoot UG
 *
 * Zentraler Router für schöne URLs
 * Routen werden auf entsprechende Controller/Views gemappt
 */

require_once __DIR__ . '/core/config.php';

start_session_safe();

// Route aus URL holen
$route = isset($_GET['route']) ? trim($_GET['route'], '/') : '';

// Leere Route = Startseite
if (empty($route)) {
    require __DIR__ . '/index.php';
    exit;
}

// Route in Teile zerlegen
$parts = explode('/', $route);
$page = $parts[0];
$param = $parts[1] ?? null;

// Routen-Mapping
switch ($page) {

    // ========================================
    // HAUPTSEITEN
    // ========================================

    case 'start':
    case 'home':
        require __DIR__ . '/index.php';
        break;

    case 'leistungen':
        require __DIR__ . '/pages/leistungen.php';
        break;

    case 'kontakt':
        require __DIR__ . '/pages/kontakt.php';
        break;

    case 'termin':
        require __DIR__ . '/pages/termin.php';
        break;

    case 'impressum':
        require __DIR__ . '/pages/impressum.php';
        break;

    case 'datenschutz':
        require __DIR__ . '/pages/datenschutz.php';
        break;

    case 'agb':
        require __DIR__ . '/pages/agb.php';
        break;

    case 'widerruf':
        require __DIR__ . '/pages/widerruf.php';
        break;

    // ========================================
    // SHOP
    // ========================================

    case 'shop':
        // Kategorie-Filter aus Query-String
        require __DIR__ . '/pages/shop.php';
        break;

    case 'produkt':
        // Produkt-Detail: /produkt/dell-latitude-e7470
        if ($param) {
            $_GET['slug'] = $param;
            require __DIR__ . '/pages/produkt-detail.php';
        } else {
            require __DIR__ . '/pages/404.php';
        }
        break;

    case 'warenkorb':
        require __DIR__ . '/pages/warenkorb.php';
        break;

    case 'kasse':
        require __DIR__ . '/pages/kasse.php';
        break;

    case 'bestellung':
        // Bestellbestätigung: /bestellung/123
        if ($param) {
            $_GET['id'] = $param;
            require __DIR__ . '/pages/bestellung.php';
        } else {
            require __DIR__ . '/pages/404.php';
        }
        break;

    // ========================================
    // BLOG
    // ========================================

    case 'blog':
        if ($param) {
            // Blog-Post: /blog/windows-11-upgrade-tipps
            $_GET['slug'] = $param;
            require __DIR__ . '/pages/blog-detail.php';
        } else {
            // Blog-Übersicht
            require __DIR__ . '/pages/blog.php';
        }
        break;

    // ========================================
    // ADMIN
    // ========================================

    case 'admin':
        if ($param === 'login') {
            require __DIR__ . '/admin/login.php';
        } elseif ($param === 'logout') {
            require __DIR__ . '/admin/logout.php';
        } elseif ($param === 'blog-posts') {
            require_admin();
            require __DIR__ . '/admin/blog-posts.php';
        } elseif ($param === 'blog-post-edit') {
            require_admin();
            require __DIR__ . '/admin/blog-post-edit.php';
        } elseif ($param === 'booking-settings') {
            require_admin();
            require __DIR__ . '/admin/booking-settings.php';
        } elseif ($param === 'bookings') {
            require_admin();
            require __DIR__ . '/admin/bookings.php';
        } elseif ($param === 'booking-detail') {
            require_admin();
            require __DIR__ . '/admin/booking-detail.php';
        } elseif ($param === 'booking-calendar') {
            require_admin();
            require __DIR__ . '/admin/booking-calendar-v2.php';
        } elseif ($param === 'booking-week') {
            require_admin();
            require __DIR__ . '/admin/booking-week.php';
        } elseif ($param === 'email-templates') {
            require_admin();
            require __DIR__ . '/admin/email-templates.php';
        } else {
            require_admin();
            require __DIR__ . '/admin/index.php';
        }
        break;

    // ========================================
    // API
    // ========================================

    case 'api':
        header('Content-Type: application/json; charset=UTF-8');

        switch ($param) {
            case 'cart':
                require __DIR__ . '/api/cart.php';
                break;

            case 'contact':
                require __DIR__ . '/api/contact.php';
                break;

            case 'booking':
                require __DIR__ . '/api/booking.php';
                break;

            case 'available-slots':
                require __DIR__ . '/api/available-slots.php';
                break;

            case 'hellocash-search':
                require __DIR__ . '/api/hellocash-search.php';
                break;

            default:
                http_response_code(404);
                echo json_encode(['error' => 'API-Endpoint nicht gefunden']);
        }
        break;

    // ========================================
    // 404 - Seite nicht gefunden
    // ========================================

    default:
        http_response_code(404);
        require __DIR__ . '/pages/404.php';
        break;
}
