# Technische Architektur - PC-Wittfoot Webseite

## Projekt-Übersicht

**Projektname:** PC-Wittfoot Firmenwebseite mit Shop
**Technologie-Stack:** PHP 8.2, MySQL 5.7, HTML5, CSS3, Vanilla JavaScript
**Hosting:** Shared Hosting (FTP-only, kein SSH)
**Start:** 2025-12-31

---

## 1. System-Architektur

### 1.1 High-Level Übersicht

```
┌─────────────────────────────────────────────────────────────┐
│                        Browser (Client)                      │
│  - HTML5/CSS3 (Responsive, Darkmode, WCAG 2.1 AA)          │
│  - Vanilla JavaScript (keine Frameworks)                    │
└─────────────────────────────────────────────────────────────┘
                              ↕
┌─────────────────────────────────────────────────────────────┐
│                    Webserver (Apache/LiteSpeed)              │
│  - PHP 8.2 (156MB Memory, 60s Max Execution)               │
│  - SSL (Shared Certificate)                                 │
└─────────────────────────────────────────────────────────────┘
                              ↕
┌─────────────────────────────────────────────────────────────┐
│                      Application Layer                       │
│  ┌──────────────┬──────────────┬──────────────┐            │
│  │   Frontend   │     Shop     │   Admin      │            │
│  │   (Static)   │   (Dynamic)  │  (Backend)   │            │
│  └──────────────┴──────────────┴──────────────┘            │
└─────────────────────────────────────────────────────────────┘
                              ↕
┌─────────────────────────────────────────────────────────────┐
│                        Data Layer                            │
│  ┌──────────────┬──────────────┬──────────────┐            │
│  │   MySQL 5.7  │  File System │  External    │            │
│  │   Database   │  (Uploads)   │  APIs        │            │
│  └──────────────┴──────────────┴──────────────┘            │
└─────────────────────────────────────────────────────────────┘
```

---

## 2. Verzeichnis-Struktur

```
/public_html/                          # Document Root
│
├── index.php                          # Router / Entry Point
├── .htaccess                          # URL Rewriting, Security
│
├── /assets/                           # Statische Ressourcen
│   ├── /css/
│   │   ├── main.css                   # Haupt-Stylesheet
│   │   ├── darkmode.css              # Darkmode-Variablen
│   │   └── admin.css                 # Admin-Interface Styles
│   ├── /js/
│   │   ├── main.js                   # Haupt-JavaScript
│   │   ├── darkmode.js               # Darkmode-Toggle
│   │   ├── shop.js                   # Warenkorb-Logik
│   │   └── admin.js                  # Admin-Interface
│   └── /images/
│       ├── logo.jpg                  # Firmenlogo
│       └── /icons/                   # SVG Icons
│
├── /pages/                           # Content-Seiten (aus Markdown)
│   ├── home.html                     # Startseite
│   ├── leistungen.html              # Leistungen
│   ├── ueber-uns.html               # Über uns
│   └── kontakt.html                 # Kontakt
│
├── /shop/                            # Shop-Modul
│   ├── index.php                     # Shop-Übersicht
│   ├── product.php                   # Produkt-Detail
│   ├── cart.php                      # Warenkorb
│   ├── checkout.php                  # Kasse
│   └── /classes/
│       ├── Product.php               # Produkt-Klasse
│       ├── Cart.php                  # Warenkorb-Klasse
│       └── Order.php                 # Bestellungs-Klasse
│
├── /admin/                           # Admin-Backend
│   ├── index.php                     # Dashboard
│   ├── login.php                     # Login
│   ├── products.php                  # Produktverwaltung
│   ├── orders.php                    # Bestellverwaltung
│   ├── pages.php                     # Content-Verwaltung
│   └── /classes/
│       └── Auth.php                  # Authentifizierung
│
├── /api/                             # API-Endpunkte
│   ├── reviews.php                   # Google Reviews (gecacht)
│   ├── calendar.php                  # hellocash Integration
│   └── contact.php                   # Kontaktformular
│
├── /core/                            # Core-Funktionen
│   ├── config.php                    # Konfiguration
│   ├── database.php                  # Datenbank-Wrapper
│   ├── router.php                    # URL-Routing
│   ├── markdown.php                  # Markdown-Parser
│   └── helpers.php                   # Helper-Funktionen
│
├── /uploads/                         # User-Uploads
│   ├── /products/                    # Produktbilder
│   └── /temp/                        # Temporäre Dateien
│
├── /cache/                           # Cache-Dateien
│   ├── reviews.json                  # Google Reviews Cache
│   └── /pages/                       # Seiten-Cache
│
└── /content/                         # Markdown-Content (lokal)
    ├── blog/                         # Blog-Beiträge (Markdown)
    └── pages/                        # Seiten-Content (Markdown)
```

---

## 3. Datenbank-Schema

### 3.1 Tabellen-Übersicht

```sql
-- Produkte
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sku VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    category VARCHAR(100),
    brand VARCHAR(100),
    condition ENUM('neu', 'refurbished', 'gebraucht') DEFAULT 'refurbished',
    image_url VARCHAR(255),
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bestellungen
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50),
    shipping_address TEXT NOT NULL,
    billing_address TEXT,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('paypal', 'sumup', 'vorkasse') NOT NULL,
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    order_status ENUM('new', 'processing', 'shipped', 'completed', 'cancelled') DEFAULT 'new',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (order_status),
    INDEX idx_payment (payment_status),
    INDEX idx_email (customer_email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bestellpositionen
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id),
    INDEX idx_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Blog-Beiträge / News
CREATE TABLE blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(255) UNIQUE NOT NULL,
    title VARCHAR(255) NOT NULL,
    excerpt TEXT,
    content TEXT NOT NULL,
    author VARCHAR(100),
    published BOOLEAN DEFAULT 0,
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_published (published),
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admin-User
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    full_name VARCHAR(255),
    role ENUM('admin', 'editor') DEFAULT 'editor',
    is_active BOOLEAN DEFAULT 1,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Kontaktformular-Einträge
CREATE TABLE contact_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    subject VARCHAR(255),
    message TEXT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    status ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Produktkategorien
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    parent_id INT NULL,
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT 1,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Cache für externe APIs
CREATE TABLE api_cache (
    cache_key VARCHAR(255) PRIMARY KEY,
    cache_data TEXT NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## 4. Kern-Module

### 4.1 Content-Management (Markdown-basiert)

**Workflow:**
1. **Lokal:** Markdown-Dateien schreiben (`/content/*.md`)
2. **Lokal:** Build-Script ausführen → generiert HTML in `/pages/`
3. **Upload:** Per FTP auf Server hochladen

**Build-Script (lokal):**
```php
// build.php - Lokales Build-Tool
// Konvertiert Markdown → HTML
// Optimiert CSS/JS
// Erstellt Upload-Ready Package
```

### 4.2 Shop-Modul

**Features:**
- Produktkatalog mit Suche & Filter
- Warenkorb (Session-basiert)
- Checkout mit PayPal/SumUp/Vorkasse
- Bestellverwaltung im Admin

**Warenkorb-Flow:**
```
Produkt wählen → In Warenkorb → Checkout → Zahlung → Bestellung
```

### 4.3 Admin-Backend

**Features:**
- Login (Session + CSRF-Schutz)
- Produktverwaltung (CRUD)
- Bestellverwaltung
- Blog-Verwaltung
- Content-Editor (für Seiten)

**Zugriff:** `/admin/` (Passwort-geschützt)

### 4.4 API-Integrationen

#### Google Reviews
```php
// /api/reviews.php
// Lädt Reviews von Google Places API
// Cacht Ergebnis für 24h (via Cronjob)
// Fallback auf gecachte Version
```

#### hellocash Terminbuchung
```php
// /api/calendar.php
// Integriert hellocash API
// Zeigt verfügbare Termine
// Ermöglicht Buchung
```

---

## 5. Sicherheit

### 5.1 Maßnahmen

- **SQL-Injection:** Prepared Statements (PDO)
- **XSS:** `htmlspecialchars()` für alle Ausgaben
- **CSRF:** Token-System für Formulare
- **Session:** Sichere Session-Settings (httponly, secure)
- **Passwords:** `password_hash()` mit bcrypt
- **File-Uploads:** Validierung (Typ, Größe, Extension)
- **Rate-Limiting:** Für Login & Kontaktformular
- **.htaccess:** Directory-Browsing deaktiviert, sensible Dateien geschützt

### 5.2 .htaccess Beispiel

```apache
# Kein Directory Listing
Options -Indexes

# PHP-Konfiguration
php_value upload_max_filesize 10M
php_value post_max_size 12M

# URL Rewriting
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?route=$1 [QSA,L]

# Sichere Dateien schützen
<FilesMatch "^(config\.php|\.htaccess)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

---

## 6. Performance-Optimierung

### 6.1 Caching-Strategie

- **Browser-Cache:** Assets (CSS/JS/Images) mit Cache-Headers
- **Page-Cache:** Statische Seiten als HTML gecacht
- **API-Cache:** Google Reviews (24h), hellocash (1h)
- **Datenbank:** Query-Cache, Indexierung

### 6.2 Asset-Optimierung

- **CSS:** Minifiziert, kombiniert
- **JavaScript:** Minifiziert, lazy loading
- **Bilder:** WebP-Format, responsive srcset
- **Fonts:** Subset, preload

---

## 7. Barrierefreiheit (WCAG 2.1 AA)

### 7.1 Technische Umsetzung

**HTML:**
```html
<!-- Semantisches HTML5 -->
<header role="banner">
  <nav role="navigation" aria-label="Hauptnavigation">
    <a href="#main" class="skip-link">Zum Hauptinhalt</a>
  </nav>
</header>

<main id="main" role="main">
  <article>
    <h1>Überschrift</h1>
  </article>
</main>
```

**CSS:**
```css
/* Fokus-Indikatoren */
:focus {
  outline: 3px solid #8BC34A;
  outline-offset: 2px;
}

/* Kontrast-Verhältnisse validiert */
/* Mindestgröße für Touch-Targets: 44x44px */
```

**JavaScript:**
```javascript
// Keyboard-Navigation
// Screen-Reader Announcements (ARIA live regions)
```

---

## 8. Deployment-Workflow

### 8.1 Lokale Entwicklung

```bash
# XAMPP/MAMP mit PHP 8.2
# MySQL 5.7
# Git für Versionskontrolle
```

### 8.2 Build-Prozess (lokal)

```bash
1. Markdown → HTML konvertieren
2. CSS/JS minifizieren
3. Bilder optimieren
4. Upload-Package erstellen
```

### 8.3 Deployment (FTP)

```bash
1. FTP-Upload per FileZilla/WinSCP
2. Datenbank-Migrationen per phpMyAdmin
3. Cache leeren (manuell oder per Admin)
```

---

## 9. Cronjobs

### 9.1 Geplante Tasks

```bash
# Täglich um 03:00 - Google Reviews Cache aktualisieren
0 3 * * * php /public_html/cron/update-reviews.php

# Täglich um 04:00 - Alte Sessions löschen
0 4 * * * php /public_html/cron/cleanup-sessions.php

# Wöchentlich - Datenbank-Backup
0 2 * * 0 php /public_html/cron/backup-database.php
```

---

## 10. Monitoring & Wartung

### 10.1 Log-Files

- **Error-Log:** PHP-Fehler protokollieren
- **Access-Log:** Zugriffe überwachen
- **Shop-Log:** Bestellungen & Zahlungen

### 10.2 Backups

- **Datenbank:** Täglich via Cronjob
- **Files:** Wöchentlich manuell/automatisch
- **Speicherort:** Lokal + externes Backup

---

## 11. Zukünftige Erweiterungen

### 11.1 Mögliche Features (Phase 2)

- Newsletter-System
- Kunden-Accounts (Login, Bestellhistorie)
- Produkt-Bewertungen
- Erweiterte Such-Filter
- Lager-Verwaltung
- Multi-Language (DE/EN)
- Progressive Web App (PWA)

---

## 12. Technologie-Entscheidungen - Begründung

### Warum kein Framework?

- ✅ Keine SSH/Composer-Anforderung
- ✅ Volle Kontrolle, kein Overhead
- ✅ Maximale Performance
- ✅ Einfache Wartung
- ✅ Perfekt für FTP-Deployment

### Warum Vanilla JavaScript?

- ✅ Keine Dependencies
- ✅ Schneller Pageload
- ✅ Moderne Browser unterstützen alles Nötige
- ✅ Einfacher zu warten

### Warum Markdown für Content?

- ✅ Einfach zu schreiben
- ✅ Versionskontrolle (Git)
- ✅ Kein WYSIWYG-Editor nötig
- ✅ Kann zu statischem HTML kompiliert werden → Performance

---

**Version:** 1.0
**Erstellt:** 2025-12-31
**Nächstes Review:** Nach Implementierung Phase 1
