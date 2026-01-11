<?php
/**
 * BEISPIEL-KONFIGURATIONSDATEI
 * PC-Wittfoot UG
 *
 * VERWENDUNG:
 * 1. Kopiere diese Datei nach config.php
 * 2. Passe die Werte für deine Umgebung an
 * 3. config.php wird NICHT in Git committed (siehe .gitignore)
 */

// =============================================
// UMGEBUNG
// =============================================

// Produktionsmodus
// Local: false
// Production: true
define('PRODUCTION_MODE', false);

// Error Reporting
error_reporting(E_ALL);
if (PRODUCTION_MODE) {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
} else {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}
ini_set('log_errors', 1);
ini_set('error_log', dirname(dirname(__DIR__)) . '/logs/error.log');

// Timezone
date_default_timezone_set('Europe/Berlin');

// =============================================
// DATENBANK-KONFIGURATION
// =============================================

// Local Development:
define('DB_HOST', 'localhost');
define('DB_NAME', 'pc_wittfoot');
define('DB_USER', 'pc_wittfoot');
define('DB_PASS', 'your_password_here');

// Production:
// define('DB_HOST', 'sql116.c.artfiles.de');
// define('DB_NAME', 'db285520001');
// define('DB_USER', 'dcp285520007');
// define('DB_PASS', 'your_production_password');

define('DB_CHARSET', 'utf8mb4');

// =============================================
// PFADE
// =============================================

define('BASE_PATH', dirname(__DIR__));
define('CORE_PATH', BASE_PATH . '/core');
define('ASSETS_PATH', BASE_PATH . '/assets');
define('UPLOADS_PATH', BASE_PATH . '/uploads');
define('CACHE_PATH', BASE_PATH . '/cache');

// URL-Basis
// Local: http://localhost:8000
// Production: https://pc-wittfoot.de
define('BASE_URL', 'http://localhost:8000');
define('ASSETS_URL', BASE_URL . '/assets');
define('UPLOADS_URL', BASE_URL . '/uploads');

// =============================================
// SICHERHEIT
// =============================================

// Session-Einstellungen
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Lax');

// Secure Cookie nur über HTTPS (in Produktion)
if (PRODUCTION_MODE && (!empty($_SERVER['HTTPS']) || $_SERVER['SERVER_PORT'] == 443)) {
    ini_set('session.cookie_secure', 1);
}

// Session-Lifetime (12 Stunden)
ini_set('session.gc_maxlifetime', 43200);
ini_set('session.cookie_lifetime', 43200);

// CSRF-Token Secret
// WICHTIG: In Production durch echten Random-String ersetzen!
define('CSRF_SECRET', PRODUCTION_MODE ?
    getenv('CSRF_SECRET') ?: 'CHANGE_THIS_TO_RANDOM_STRING_IN_PRODUCTION' :
    'dev_secret_' . md5(BASE_PATH)
);

// Session-Name
define('SESSION_NAME', 'pc_wittfoot_session');

// =============================================
// SHOP-EINSTELLUNGEN
// =============================================

define('CURRENCY', '€');
define('CURRENCY_CODE', 'EUR');
define('TAX_RATE', 0.19);

// =============================================
// E-MAIL-KONFIGURATION
// =============================================

define('MAIL_FROM', 'info@pc-wittfoot.de');
define('MAIL_FROM_NAME', 'PC-Wittfoot UG');
define('MAIL_ADMIN', 'admin@pc-wittfoot.de');

// =============================================
// HELLOCASH API
// =============================================

// API-Credentials (aus HelloCash Dashboard)
define('HELLOCASH_USERNAME', 'your_username_here');
define('HELLOCASH_API_KEY', 'your_api_key_here');
define('HELLOCASH_BASE_URL', 'https://api.hellocash.business');

// =============================================
// AUTOLOAD & INCLUDES
// =============================================

// Composer Autoloader (optional, falls composer install ausgeführt wurde)
$composerAutoload = dirname(dirname(__DIR__)) . '/vendor/autoload.php';
if (file_exists($composerAutoload)) {
    require_once $composerAutoload;
}

// Core-Funktionen
require_once CORE_PATH . '/functions.php';

// Core-Klassen (Database, Security, etc.)
// Werden bei Bedarf geladen
