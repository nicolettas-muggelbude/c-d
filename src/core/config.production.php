<?php
/**
 * PRODUCTION CONFIGURATION TEMPLATE
 * PC-Wittfoot UG
 *
 * ANLEITUNG:
 * 1. Diese Datei auf dem Server zu "config.php" umbenennen
 * 2. Alle PLATZHALTER mit echten Produktionsdaten füllen
 * 3. Sicherstellen, dass config.php NICHT in Git committed wird (.gitignore)
 *
 * WICHTIG: Diese Datei enthält sensible Zugangsdaten!
 */

// =============================================
// UMGEBUNG
// =============================================

// Produktionsmodus (MUSS true sein!)
define('PRODUCTION_MODE', true);

// Error Reporting
error_reporting(E_ALL);
if (PRODUCTION_MODE) {
    // Produktion: Fehler nur loggen, nicht anzeigen
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
} else {
    // Entwicklung: Fehler anzeigen
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

define('DB_HOST', 'YOUR_DB_HOST');              // z.B. 'localhost' oder IP-Adresse
define('DB_NAME', 'YOUR_DB_NAME');              // Name der Datenbank
define('DB_USER', 'YOUR_DB_USER');              // Datenbank-Benutzername
define('DB_PASS', 'YOUR_DB_PASSWORD');          // Datenbank-Passwort (STARK!)
define('DB_CHARSET', 'utf8mb4');

// =============================================
// PFADE
// =============================================

define('BASE_PATH', dirname(__DIR__));          // /src
define('CORE_PATH', BASE_PATH . '/core');
define('ASSETS_PATH', BASE_PATH . '/assets');
define('UPLOADS_PATH', BASE_PATH . '/uploads');
define('CACHE_PATH', BASE_PATH . '/cache');

// URL-Basis (PRODUKTIONS-DOMAIN!)
define('BASE_URL', 'https://www.pc-wittfoot.de');    // MIT https:// !
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
// WICHTIG: Generiere einen zufälligen String (min. 64 Zeichen!)
// Befehl: php -r "echo bin2hex(random_bytes(32));"
define('CSRF_SECRET', 'YOUR_RANDOM_SECRET_64_CHARACTERS_LONG');

// Session-Name
define('SESSION_NAME', 'pc_wittfoot_session');

// =============================================
// SHOP-EINSTELLUNGEN
// =============================================

define('CURRENCY', '€');
define('CURRENCY_CODE', 'EUR');
define('TAX_RATE', 0.19);  // 19% MwSt.

// =============================================
// E-MAIL-KONFIGURATION
// =============================================

define('MAIL_FROM', 'info@pc-wittfoot.de');
define('MAIL_FROM_NAME', 'PC-Wittfoot UG');
define('MAIL_ADMIN', 'YOUR_ADMIN_EMAIL');       // Admin-Email für Benachrichtigungen

// SMTP-Einstellungen (PHPMailer)
// WICHTIG: SMTP_ENABLED auf true setzen!
define('SMTP_ENABLED', true);                    // true = SMTP verwenden
define('SMTP_HOST', 'YOUR_SMTP_HOST');           // z.B. 'smtp.strato.de' oder 'smtp.gmail.com'
define('SMTP_PORT', 587);                        // Port (587 = TLS, 465 = SSL)
define('SMTP_ENCRYPTION', 'tls');                // 'tls' oder 'ssl'
define('SMTP_USERNAME', 'YOUR_SMTP_USERNAME');   // SMTP Benutzername (oft = Email)
define('SMTP_PASSWORD', 'YOUR_SMTP_PASSWORD');   // SMTP Passwort (App-Passwort bei Gmail!)
define('SMTP_DEBUG', 0);                         // 0 = aus, 1 = Fehler, 2 = verbose

// =============================================
// API-KEYS (für Produktion ausfüllen)
// =============================================

// Google Places API (für Reviews)
define('GOOGLE_PLACES_API_KEY', '');

// hellocash API - PRODUKTIV-KEY!
// WICHTIG: Nicht den Test-Key verwenden!
define('HELLOCASH_API_KEY', 'YOUR_HELLOCASH_PRODUCTION_API_KEY');
define('HELLOCASH_API_URL', 'https://api.hellocash.business/api/v1/');
define('HELLOCASH_LANDLINE_FIELD', 'Festnetz'); // Name des Custom Fields für Festnetznummer

// PayPal (SHOP - wird später aktiviert)
define('PAYPAL_CLIENT_ID', '');
define('PAYPAL_SECRET', '');
define('PAYPAL_MODE', 'live');  // 'live' für Produktion!

// SumUp (SHOP - wird später aktiviert)
define('SUMUP_APP_ID', '');
define('SUMUP_APP_SECRET', '');

// =============================================
// VERSCHIEDENES
// =============================================

// Debug-Modus (MUSS false sein in Produktion!)
define('DEBUG_MODE', false);

// Produkte pro Seite
define('PRODUCTS_PER_PAGE', 12);

// Blog-Posts pro Seite
define('POSTS_PER_PAGE', 10);

// Cache-Laufzeiten (in Sekunden)
define('CACHE_REVIEWS', 86400);      // 24 Stunden
define('CACHE_PRODUCTS', 3600);      // 1 Stunde
define('CACHE_PAGES', 7200);         // 2 Stunden

// =============================================
// AUTO-LOADING
// =============================================

// Composer Autoload (für PHPMailer & andere Packages)
require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

// Autoload-Funktion für Klassen
spl_autoload_register(function($class) {
    $paths = [
        CORE_PATH . '/' . $class . '.php',
        BASE_PATH . '/shop/classes/' . $class . '.php',
        BASE_PATH . '/admin/classes/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// =============================================
// CORE-DATEIEN LADEN
// =============================================

require_once CORE_PATH . '/helpers.php';
require_once CORE_PATH . '/database.php';

require_once CORE_PATH . '/Security.php';
require_once CORE_PATH . '/DeviceFingerprint.php';

// Security-Headers setzen
if (class_exists('Security')) {
    $security = new Security();
    $security->setSecurityHeaders();
}
