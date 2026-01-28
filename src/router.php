<?php
/**
 * Router - PC-Wittfoot UG
 *
 * Zentraler Router für schöne URLs
 * Routen werden auf entsprechende Controller/Views gemappt
 */

require_once __DIR__ . '/core/config.php';

start_session_safe();

// Wartungsmodus-Check (vor allen Routen)
require_once __DIR__ . '/core/maintenance.php';

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
        if ($param === 'verwalten') {
            // Buchungsverwaltung: /termin/verwalten
            // Token aus Query-String (?token=...)
            require __DIR__ . '/pages/termin-verwalten.php';
        } else {
            // Normale Terminbuchung
            require __DIR__ . '/pages/termin.php';
        }
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
        if ($param === 'feed.xml') {
            // RSS Feed: /blog/feed.xml
            require __DIR__ . '/pages/blog-feed.php';
        } elseif ($param === 'sitemap.xml') {
            // XML Sitemap: /blog/sitemap.xml
            require __DIR__ . '/pages/blog-sitemap.php';
        } elseif ($param) {
            // Blog-Post: /blog/windows-11-upgrade-tipps
            $_GET['slug'] = $param;
            require __DIR__ . '/pages/blog-detail.php';
        } else {
            // Blog-Übersicht
            require __DIR__ . '/pages/blog.php';
        }
        break;

    // ========================================
    // DOWNLOADS
    // ========================================

    case 'downloads':
        require __DIR__ . '/pages/downloads.php';
        break;

    // ========================================
    // ADMIN
    // ========================================

    case 'admin':
        if ($param === 'login') {
            require __DIR__ . '/admin/login.php';
        } elseif ($param === 'logout') {
            require __DIR__ . '/admin/logout.php';
        } elseif ($param === 'forgot-password') {
            require __DIR__ . '/admin/forgot-password.php';
        } elseif ($param === 'reset-password') {
            require __DIR__ . '/admin/reset-password.php';
        } elseif ($param === '2fa-verify') {
            require __DIR__ . '/admin/2fa-verify.php';
        } elseif ($param === '2fa-setup') {
            require_admin();
            require __DIR__ . '/admin/2fa-setup.php';
        } elseif ($param === 'blog-posts') {
            require_admin();
            require __DIR__ . '/admin/blog-posts.php';
        } elseif ($param === 'blog-post-edit') {
            require_admin();
            require __DIR__ . '/admin/blog-post-edit.php';
        } elseif ($param === 'markdown-hilfe') {
            require_admin();
            require __DIR__ . '/admin/markdown-hilfe.php';
        } elseif ($param === 'preview-markdown.php' || $param === 'preview-markdown') {
            require_admin();
            require __DIR__ . '/admin/preview-markdown.php';
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
        } elseif ($param === 'smtp-settings') {
            require_admin();
            require __DIR__ . '/admin/smtp-settings.php';
        } elseif ($param === 'smtp-test') {
            require_admin();
            require __DIR__ . '/admin/smtp-test.php';
        } elseif ($param === 'maintenance') {
            require_admin();
            require __DIR__ . '/admin/maintenance.php';
        } elseif ($param === 'orders') {
            require_admin();
            require __DIR__ . '/admin/orders.php';
        } elseif ($param === 'order') {
            require_admin();
            // Order ID aus dem dritten Teil: /admin/order/123
            $order_id = $parts[2] ?? null;
            if ($order_id) {
                $_GET['id'] = $order_id;
                require __DIR__ . '/admin/order.php';
            } else {
                require __DIR__ . '/pages/404.php';
            }
        } elseif ($param === 'products') {
            require_admin();
            require __DIR__ . '/admin/products.php';
        } elseif ($param === 'product-edit') {
            require_admin();
            require __DIR__ . '/admin/product-edit.php';
        } elseif ($param === 'suppliers') {
            require_admin();
            require __DIR__ . '/admin/suppliers.php';
        } elseif ($param === 'supplier-edit') {
            require_admin();
            require __DIR__ . '/admin/supplier-edit.php';
        } elseif ($param === 'csv-import') {
            require_admin();
            require __DIR__ . '/admin/csv-import.php';
        } elseif ($param === 'categories') {
            require_admin();
            require __DIR__ . '/admin/categories.php';
        } elseif ($param === 'category-edit') {
            require_admin();
            require __DIR__ . '/admin/category-edit.php';
        } elseif ($param === 'upload-image.php' || $param === 'upload-image') {
            require_admin();
            require __DIR__ . '/admin/upload-image.php';
        } elseif ($param === 'list-images.php' || $param === 'list-images') {
            require_admin();
            require __DIR__ . '/admin/list-images.php';
        } elseif ($param === 'downloads') {
            require_admin();
            require __DIR__ . '/admin/downloads.php';
        } elseif ($param === 'download-edit') {
            require_admin();
            require __DIR__ . '/admin/download-edit.php';
        } else {
            require_admin();
            require __DIR__ . '/admin/index.php';
        }
        break;

    // ========================================
    // API
    // ========================================

    case 'api':
        // Download-API: Kein JSON-Header, da Datei ausgeliefert wird
        if ($param === 'download') {
            // Slug aus drittem Teil: /api/download/backup-tool-pro
            $slug = $parts[2] ?? null;
            if ($slug) {
                $_GET['slug'] = $slug;
                require __DIR__ . '/api/download.php';
            } else {
                http_response_code(404);
                header('Content-Type: application/json; charset=UTF-8');
                echo json_encode(['error' => 'Kein Download-Slug angegeben']);
            }
            break;
        }

        // Alle anderen APIs: JSON-Header
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

            case 'fully-booked-dates':
                require __DIR__ . '/api/fully-booked-dates.php';
                break;

            case 'booking-cancel':
                require __DIR__ . '/api/booking-cancel.php';
                break;

            case 'booking-reschedule':
                require __DIR__ . '/api/booking-reschedule.php';
                break;

            case 'hellocash-search':
                require __DIR__ . '/api/hellocash-search.php';
                break;

            case 'health-check':
                require __DIR__ . '/api/health-check.php';
                break;

            case 'email-preview':
                require __DIR__ . '/api/email-preview.php';
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
