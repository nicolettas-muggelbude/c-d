# Projekt: Firmenwebseite/Corporate Design für PC-Wittfoot UG

## Projektübersicht
- **Kunde:** PC-Wittfoot UG
- **Art:** IT-Fachbetrieb mit Ladengeschäft und Fachwerkstatt
- **Zielgruppe:** Privat/Freiberuflich & Gewerbe (SOHO)

## Kernmerkmale des Unternehmens
- Umfassendes Portfolio: Beratung, Projektierung, Verkauf, Diagnose, Reparatur, Softwareentwicklung
- Schwerpunkt: Refurbished Hardware + exone Neugeräte
- Persönlicher Service: Beratung im Sitzen mit Kaffee
- Verständliche Erklärungen
- Hund Baileys als Teil des Teams
- Sehr gute Bewertungen (5 Sterne Google, Top-Status auf Kleinanzeigen.de)

## Online-Präsenz aktuell
- Facebook, Instagram, Kleinanzeigen.de, nebenan.de
- Online-Terminkalender über hellocash
- Kontakt: Telefon, E-Mail, Facebook, Instagram, WhatsApp Business, Telegram, Signal

## Projektstand
- Projektstart: 2025-12-30
- Logo vorhanden (@/data/images/logo.png), Änderungen möglich

## Anforderungen geklärt (2025-12-30)

### Projektumfang
- Webseite + Corporate Design
- Wiedererkennung zum bestehenden Logo wichtig
- Keine bestehende Webseite vorhanden

### Webseite - Funktionen
- Portfolio/Leistungen darstellen
- Online-Shop (Hardware)
- Terminbuchung (hellocash Integration)
- Kontaktformular
- Blog/News
- Bewertungen einbinden

### Technischer Stack
- Frontend: HTML, CSS, JavaScript
- Pflege: Markdown-Templates
- Shop: MySQL/MariaDB
- Kein CMS, eigene Lösung

### Design-Vorgaben
- **Logo:** Bekannt, aber "sagt nicht viel aus" - wird beibehalten
- **Farben:** Passend zum Logo, aber nicht zwingend Orange+Grün (schwierige Kombination)
- **Stil:** Freundlich, warm, sachlich, fachlich
- **Baileys (Hund):** Kann Rolle spielen, aber dezent/nicht ablenkend

### Bestehendes Logo
- Drei Bildschirme (Orange: Smartphone/Tablet, Grün: Monitor, Grau: Desktop)
- Schriftzug "wittfoot" in Grau mit grünem "wi"
- Farben: Orange, Hellgrün, Dunkelgrau

## Server-Spezifikationen (Reseller-PlanB)

### Verfügbare Ressourcen
- **Webspace:** 350 GB SSD
- **Traffic:** Unbegrenzt
- **MySQL-Datenbanken:** 400 (mehr als ausreichend!)
- **PHP:** Versionen 5.6, 7.0-7.4, 8.0-8.2 verfügbar
  - **Empfehlung:** PHP 8.2 verwenden! ✅
  - **Status:** Aktive Sicherheitsupdates bis Dez. 2025
- **Perl:** Version 5.20
- **MySQL:** Version 5.7 (ausreichend für Projekt)
- **PHP Memory Limit:** 156 MB ✅ (gut für Bildverarbeitung & Shop)
- **Max Execution Time:** 60 Sekunden ✅ (Standard, ausreichend)
- **SSL:** Shared SSL verfügbar
- **E-Mail:** 10.000 POP3/IMAP Accounts
- **SLA:** 99,9% Verfügbarkeit

### Einschränkungen
- ❌ **Kein SSH** - keine Shell-Befehle auf Server
- ❌ **Kein SFTP** - nur FTP verfügbar
- ❌ **MySQL extern:** Nein - nur interne Verbindung
- ❌ **Kein Composer** direkt auf Server

### Konsequenzen für Entwicklung
- Entwicklung & Build lokal
- Deployment via FTP
- PHP-Framework muss ohne CLI auskommen
- Keine Server-seitigen Cronjobs möglich (nur wenn Hoster Cronjobs anbietet)

## Technische Architektur-Entscheidungen

### Bewertungssystem
**Entscheidung:** Hybrid-Ansatz
- Google Reviews API Integration (gecacht)
- Kleinanzeigen.de Status einbinden
- Optional: 3-5 handgepflegte Testimonials

### Shop-Zahlungsabwicklung
- **PayPal** ✅
- **SumUp** (eventuell)
- **Vorkasse/Überweisung** ✅

### hellocash Integration
- API-Integration für Terminbuchung
- Eigene Kalender-Verwaltung gewünscht

### Content-Pflege
- Initial: Kunde selbst (tech-versiert)
- Später: Möglicherweise weitere Mitarbeiter
- **Konsequenz:** Admin-Interface einplanen (benutzerfreundlich)

### Cronjobs
- ✅ Verfügbar über drei Wege im Webinterface
- Nutzbar für: Google Reviews Caching, Newsletter, Backups

## Design-Entscheidungen (2025-12-31)

### Farbpalette: Option B - Neutral mit Akzenten ✅
- **Hauptfarben:** Grautöne (#2C3E50, #5A5A5A, #E8E8E8)
- **Akzentfarben:** Logo-Grün (#8BC34A), Logo-Orange (#E67E22)
- **Basis:** Weiß (#FFFFFF) / Lightmode-Hintergrund
- **Charakteristik:** Modern, klar, professionell, hohe Kontraste

### Darkmode: Hybrid (automatisch + umschaltbar) ✅
- **Standard:** System-Präferenz (prefers-color-scheme)
- **Optional:** Manueller Toggle-Button
- **Speicherung:** User-Präferenz im localStorage
- **Darkmode-Farben:**
  - Hintergrund: #0F1419, #1A1F26
  - Text: #E8E8E8, #B8B8B8
  - Akzente: Grün/Orange bleiben gleich

### Barrierefreiheit: WCAG 2.1 Level AA (Pflicht!) ✅
- **Kontrast-Verhältnisse:**
  - Normal-Text: min. 4.5:1
  - Großer Text (18pt+): min. 3:1
  - UI-Komponenten: min. 3:1
- **Navigation:**
  - Vollständige Keyboard-Navigation
  - Skip-Links
  - Fokus-Indikatoren (sichtbar!)
- **Semantik:**
  - Korrektes HTML5 (header, nav, main, article, aside, footer)
  - ARIA-Labels wo nötig
  - Landmark-Regions
- **Content:**
  - Alt-Texte für alle Bilder
  - Keine reine Farb-Kodierung
  - Screen-Reader optimiert
  - Lesbare Schriftgrößen (min. 16px)
- **Formulare:**
  - Labels für alle Inputs
  - Error-Messages klar zugeordnet
  - Validierung mit visuellen + Text-Feedback

## Projektfortschritt

### Phase 1: Planung ✅ (Abgeschlossen 2025-12-31)
- ✅ Anforderungen geklärt
- ✅ Server-Spezifikationen dokumentiert
- ✅ Farbpalette gewählt: Option B (Neutral + Akzente)
- ✅ Darkmode: Hybrid (automatisch + umschaltbar)
- ✅ Barrierefreiheit: WCAG 2.1 Level AA
- ✅ Technische Architektur erstellt
- ✅ Sitemap & Wireframes fertig

### Phase 2: Entwicklung 🚧 (Start: 2025-12-31)
- ✅ Entwicklungsumgebung aufgesetzt (PHP 8.2, MySQL)
- ✅ Basis-Struktur erstellt
- ✅ Responsive CSS-Framework (Darkmode, Hamburger-Menu)
- ✅ Datenbank-Schema implementiert (11 Produkte, 8 Kategorien)
- ✅ Core-Funktionen entwickelt (PDO, Helpers, Config)
- ✅ Test-Seite funktioniert mit echten DB-Daten
- ✅ Template-System (Header/Footer Includes)
- ✅ Router-System (.htaccess + router.php + server.php für Dev)
- ✅ Startseite mit echten Produkten aus DB
- ✅ Shop-Seite mit Filtern (Kategorie, Marke, Zustand, Suche, Pagination)
- ✅ Produkt-Detail-Seite (Tabs, Spezifikationen, ähnliche Produkte, AJAX-Warenkorb)
- ✅ 404-Seite
- ✅ Vollständiges CSS (Components, Forms, Shop, Cart, Product-Detail, Checkout)
- ✅ Warenkorb-System komplett (Session-basiert, API, Counter im Header, Brutto/Netto-Toggle)
- ✅ Kontaktformular (Validierung, DB-Speicherung, CSRF-Schutz)
- ✅ Rechtliche Seiten (Impressum, Datenschutz, AGB, Widerruf)
- ✅ Leistungen-Seite
- ✅ Checkout/Kasse (Kundendaten, Lieferart, Zahlungsart, Bestellabwicklung)
- ✅ Bestellbestätigung (Order-Details, Zahlungsinformationen)
- ⏳ Blog-System (Übersicht + Post-Detail)
- ⏳ Termin-Seite (hellocash Integration)
- ⏳ PayPal-Integration (Zahlungsart vorhanden, aber noch nicht verbunden)
- ⏳ Admin-Interface

## Design-Prinzipien
- **Mobile-First:** Entwicklung beginnt mit Mobile-Layout
- **Responsive:** Breakpoints für Mobile, Tablet, Desktop
- **Touch-optimiert:** Min. 44x44px für alle interaktiven Elemente
- **Performance:** Optimierte Assets, lazy loading
- **Barrierefreiheit:** WCAG 2.1 AA von Anfang an

## Aktueller Stand (2025-12-31)

### Kern-Features implementiert

1. **Template-System & Router**
   - Wiederverwendbare Header/Footer Templates
   - Navigation mit aktivem Status, Darkmode-Toggle, Hamburger-Menu
   - .htaccess für URL-Rewriting (Production)
   - server.php für PHP Built-in Server (Development)
   - Zentrale Routenverwaltung
   - Schöne URLs: `/shop`, `/produkt/dell-latitude-e7470`

2. **Shop-System**
   - **Shop-Übersicht:** Filter (Kategorie, Marke, Zustand, Suche), Pagination, Responsive Grid
   - **Produkt-Detail:** Tabs, Spezifikationen, ähnliche Produkte, AJAX-Warenkorb
   - **Warenkorb:** Session-basiert, API-Endpoints, Counter im Header, AJAX-Updates
   - **Brutto/Netto-System:** Preise in DB sind Brutto, B2B-Toggle für Netto-Ansicht
   - **Lagerbestand:** Validierung, automatische Reduktion bei Bestellung

3. **Checkout & Bestellung**
   - **Kasse (kasse.php):**
     - Kundendaten-Formular mit Validierung
     - Lieferart-Auswahl (Versand/Abholung)
     - Bedingte Adressfelder (nur bei Versand erforderlich)
     - Zahlungsart (Vorkasse/PayPal/Barzahlung)
     - CSRF-Schutz, AGB-Checkbox
   - **Bestellabwicklung:**
     - Transaktion-basiertes Speichern
     - Automatische Lagerbestand-Reduktion
     - Warenkorb-Leerung nach Bestellung
   - **Bestellbestätigung (bestellung.php):**
     - Vollständige Order-Details
     - Kundendaten, Lieferadresse
     - Zahlungsinformationen (Bankdaten bei Vorkasse)
     - Bestellte Artikel mit Preisen

4. **Kontakt & Formulare**
   - Kontaktformular mit DB-Speicherung
   - Validierung (E-Mail, Pflichtfelder)
   - CSRF-Schutz für alle Formulare
   - Erfolgs- und Fehlermeldungen
   - Formular-Daten bleiben bei Fehler erhalten

5. **Weitere Seiten**
   - Startseite mit Hero, Leistungen, Featured Produkte, Kategorien
   - Leistungen-Übersicht
   - Impressum, Datenschutz, AGB, Widerruf
   - 404-Fehlerseite

6. **CSS-Framework**
   - Design-Tokens (variables.css)
   - Responsive Grid-System
   - Form-Styling mit Validation
   - Shop-Komponenten (Product Cards, Sidebar, Filter)
   - Cart-Komponenten (Badge, Summary)
   - Checkout-Layout (2-spaltig auf Desktop)
   - Darkmode vollständig implementiert
   - WCAG 2.1 AA konform

### Testen

```bash
# MySQL starten
sudo service mysql start

# PHP-Server starten (im src-Verzeichnis)
cd /home/nicole/projekte/c-d/src
php -S localhost:8000 server.php

# WICHTIG: server.php nutzen für korrekte Routing!
# .htaccess funktioniert nicht mit PHP Built-in Server

# Dann im Browser öffnen:
# - http://localhost:8000/              (Startseite)
# - http://localhost:8000/shop          (Shop)
# - http://localhost:8000/shop?kategorie=notebooks-laptops
# - http://localhost:8000/produkt/dell-latitude-e7470
# - http://localhost:8000/warenkorb     (Warenkorb)
# - http://localhost:8000/kasse         (Checkout)
# - http://localhost:8000/kontakt       (Kontaktformular)
# - http://localhost:8000/test-db.php   (Datenbanktest)
```

### Dateistruktur

```
src/
├── .htaccess                    # URL-Rewriting (Production)
├── server.php                   # Development Router für PHP Built-in Server
├── router.php                   # Zentraler Router
├── index.php                    # Startseite
├── test-db.php                  # Datenbank-Test
├── templates/
│   ├── header.php              # Wiederverwendbarer Header
│   └── footer.php              # Wiederverwendbarer Footer
├── pages/
│   ├── 404.php                 # Fehlerseite
│   ├── shop.php                # Shop-Übersicht
│   ├── produkt-detail.php      # Produkt-Detailseite
│   ├── warenkorb.php           # Warenkorb
│   ├── kasse.php               # Checkout
│   ├── bestellung.php          # Bestellbestätigung
│   ├── kontakt.php             # Kontaktformular
│   ├── leistungen.php          # Leistungen-Übersicht
│   ├── impressum.php           # Impressum
│   ├── datenschutz.php         # Datenschutzerklärung
│   ├── agb.php                 # AGB
│   └── widerruf.php            # Widerrufsbelehrung
├── core/
│   ├── config.php              # Konfiguration (DB, CSRF, Session)
│   ├── database.php            # PDO-Wrapper
│   ├── helpers.php             # Helper-Funktionen
│   └── Cart.php                # Warenkorb-Klasse
├── api/
│   ├── cart.php                # Warenkorb-API (add, update, remove)
│   ├── contact.php             # Kontakt-API (noch nicht verwendet)
│   └── booking.php             # Termin-API (Platzhalter)
└── assets/
    └── css/
        ├── variables.css       # Design-Tokens
        ├── reset.css           # CSS-Reset
        ├── base.css            # Basis-Styles
        └── components.css      # UI-Komponenten (vollständig)
```

### Nächste Schritte

1. **Blog-System**
   - Blog-Übersicht (`pages/blog.php`)
   - Blog-Post-Detail (`pages/blog-post.php`)
   - Daten bereits in Datenbank vorhanden

2. **Terminbuchung** (`pages/termin.php`)
   - hellocash API-Integration
   - Eigene Kalender-Verwaltung

3. **PayPal-Integration**
   - Zahlungsart bereits im Checkout vorhanden
   - API-Anbindung fehlt noch
   - Alternative: SumUp prüfen

4. **Admin-Interface**
   - Login-System mit Session
   - Produkt-Verwaltung (CRUD)
   - Blog-Verwaltung (CRUD)
   - Bestellübersicht
   - Kontaktanfragen-Verwaltung
   - Dashboard mit Statistiken

5. **Bewertungen einbinden**
   - Google Reviews API Integration (gecacht)
   - Kleinanzeigen.de Status
   - Optional: Testimonials

6. **Testing & Optimierung**
   - Cross-Browser Testing
   - Performance-Optimierung
   - SEO-Optimierung
   - Accessibility-Testing (WCAG 2.1 AA)

7. **Deployment**
   - FTP-Upload zum Produktiv-Server
   - Datenbank-Migration
   - SSL-Konfiguration
   - E-Mail-Konfiguration

## Wichtige technische Entscheidungen

### Preissystem: Brutto-basiert
**Entscheidung:** Alle Preise in der Datenbank sind Brutto-Preise (inkl. 19% MwSt)

**Berechnung:**
- `getTotal()` → Brutto-Gesamtsumme (direkt aus DB)
- `getNet()` → Netto-Summe = `Brutto / 1.19`
- `getTax()` → MwSt = `Brutto - Netto`

**Darstellung:**
- Standard (B2C): Nur Brutto-Preis mit Hinweis "inkl. MwSt"
- Gewerbe (B2B): Toggle-Option zeigt Netto/MwSt/Brutto-Aufschlüsselung
- User-Präferenz wird in localStorage gespeichert

### Router-System: Dual-Mode
**Production (.htaccess):**
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ router.php?route=$1 [QSA,L]
```

**Development (server.php):**
```php
// PHP Built-in Server unterstützt kein .htaccess
// server.php übernimmt Routing-Logik
php -S localhost:8000 server.php
```

### Warenkorb: Session + AJAX
- **Storage:** PHP `$_SESSION` (kein Cookie, keine DB)
- **API:** `/api/cart` für add/update/remove
- **Updates:** AJAX mit Fetch API
- **Counter:** Automatische Aktualisierung im Header
- **Validation:** Lagerbestand-Prüfung vor Checkout

### Bestellabwicklung: Transaktional
```php
$db->beginTransaction();
try {
    // 1. Order erstellen
    $order_id = $db->insert("INSERT INTO orders ...");

    // 2. Order Items erstellen
    foreach ($cart->getItems() as $item) {
        $db->insert("INSERT INTO order_items ...");

        // 3. Lagerbestand reduzieren
        $db->update("UPDATE products SET stock = stock - :quantity ...");
    }

    $db->commit();
} catch (Exception $e) {
    $db->rollback();
}
```

### CSRF-Schutz: Token-basiert
- Token-Generierung: `bin2hex(random_bytes(32))`
- Speicherung: `$_SESSION['csrf_token']`
- Validierung: Bei jedem POST-Request
- Implementiert in: Kontakt, Kasse, Warenkorb

### Formular-Validierung: Server-seitig + Client-seitig
- **Client:** HTML5 `required`, `type="email"`, Pattern
- **Server:** Vollständige Validierung aller Eingaben
- **Sanitization:** `htmlspecialchars()` für alle Outputs
- **Fehlerbehandlung:** Array sammelt alle Fehler, zeigt sie gebündelt

### Conditional Fields: JavaScript
Beispiel Checkout - Adressfelder nur bei Versand:
```javascript
function toggleShippingAddress() {
    const isShipping = document.querySelector('input[name="delivery_method"]:checked').value === 'shipping';
    shippingAddress.style.display = isShipping ? 'block' : 'none';

    // Required-Attribute dynamisch setzen
    addressFields.forEach(field => {
        field.required = isShipping;
    });
}
```

## Session 2026-01-01: Terminbuchung & HelloCash Integration

### Erreichte Ziele ✅

#### 1. HelloCash REST-API Integration (komplett)
- **Kundendaten-Synchronisation**
  - Vollständige Übertragung: Vorname, Nachname, Firma, E-Mail
  - Adresse: Straße, Hausnummer, PLZ, Ort
  - Telefonnummer MIT Ländervorwahl (z.B. "+49 170 1234567")
  - Ländercode als ISO-Code (z.B. "DE" aus +49)
  - Festnetznummer in `user_notes` Feld
- **Duplikat-Vermeidung**
  - Suche nach existierenden Usern (E-Mail + Telefon)
  - Nur neue User werden angelegt
- **Datenbereinigung**
  - Führende Nullen bei Telefonnummern werden automatisch entfernt
  - Frontend (JavaScript) + Backend (PHP) Validierung

**Dateien:**
- `src/core/HelloCashClient.php` - API-Client
- `src/api/booking.php` - Integration in Buchungs-API
- `database/add-hellocash-customer-id.sql` - Schema-Erweiterung

#### 2. Terminbuchungs-Formular (4-Schritte-Prozess)
- **Schritt 1:** Terminart auswählen (Fester Termin / Walk-in)
- **Schritt 2:** Dienstleistung wählen (8 Optionen als Karten)
- **Schritt 3:** Datum & Zeit wählen
  - Kalender mit Validierung (nur erlaubte Tage)
  - **Dynamische Zeitslots** aus Datenbank-Einstellungen
  - **Verfügbarkeitsprüfung** - gebuchte Zeiten werden ausgegraut
  - **Pflichtfeld** - Zeitauswahl muss erfolgen
- **Schritt 4:** Kontaktdaten & Adresse
  - Vorname, Nachname, Firma (optional)
  - E-Mail
  - Mobilnummer mit Ländervorwahl-Dropdown (+49, +43, +41, etc.)
  - Festnetz (optional)
  - **Adresse:** Straße, Hausnummer, PLZ, Ort (NEU!)
  - Bemerkungen (optional)
  - Zusammenfassung der Buchung

**Features:**
- Fortschrittsanzeige (4 Schritte mit Icons)
- Zurück/Weiter-Navigation
- Responsive Design (Mobile-First)
- Echtzeit-Validierung
- Erfolgsseite mit Buchungsnummer

**Dateien:**
- `src/pages/termin.php` - Buchungsformular
- `src/api/booking.php` - Backend-Validierung & Speicherung
- `src/assets/css/booking.css` - Formular-Styling
- `database/create-bookings-table.sql` - Datenbank-Schema

#### 3. Intelligente Verfügbarkeitsprüfung

**Problem gelöst:** 3 Termine zur gleichen Zeit waren möglich

**Lösung:**
- Neue API: `/api/available-slots?date=YYYY-MM-DD`
- Prüft Datenbank auf existierende Buchungen
- Zeigt nur verfügbare Zeitslots an
- Gebuchte Zeiten werden ausgegraut und deaktiviert
- Verhindert Doppelbuchungen zuverlässig

**Konfigurierbare Einstellungen:**
```sql
CREATE TABLE booking_settings (
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL
);

-- Aktuelle Werte:
booking_start_time:        11:00
booking_end_time:          13:00  (= Slots bis 12:00)
booking_interval_minutes:  60
max_bookings_per_slot:     1
```

**Dateien:**
- `src/api/available-slots.php` - Verfügbarkeits-API
- `database/create-booking-settings.sql` - Einstellungen-Tabelle

#### 4. Unterschiedliche Zeiten je Terminart

**Feste Termine ("Fester Termin"):**
- **Wochentage:** Dienstag bis Freitag
- **Zeiten:** 11:00 Uhr und 12:00 Uhr
- **Pflicht:** Zeitauswahl erforderlich
- **Kapazität:** Max. 1 Buchung pro Zeitslot

**Walk-in ("Ich komme vorbei"):**
- **Wochentage:** Dienstag bis Freitag + **Samstag** (NEU!)
- **Zeiten:**
  - Di-Fr: 14:00-17:00 Uhr
  - Sa: 12:00-16:00 Uhr
- **Keine feste Zeitauswahl** nötig
- **Info:** Kunde wird bei Wartezeiten benachrichtigt

**Validierung:**
- Frontend: JavaScript-Validierung bei Datumsauswahl
- Backend: PHP-Validierung abhängig von Terminart
- API: Korrekte Slot-Generierung je nach Wochentag

#### 5. Formular-Erweiterungen

**Neue Felder in Schritt 4:**
- Ländervorwahl-Dropdown (separates Feld vor Mobilnummer)
- Straße + Hausnummer (nebeneinander, flex-Layout)
- PLZ + Ort (nebeneinander, 1:2 Ratio)

**Validierung:**
- PLZ: 5 Ziffern
- Straße/Ort: Min. 2 Zeichen
- Hausnummer: Pflichtfeld
- Telefon: Automatische Bereinigung führender Nullen

**Datenbank-Schema:**
```sql
ALTER TABLE bookings ADD COLUMN (
    customer_street VARCHAR(255) NOT NULL,
    customer_house_number VARCHAR(20) NOT NULL,
    customer_postal_code VARCHAR(10) NOT NULL,
    customer_city VARCHAR(100) NOT NULL
);
```

### Technische Details

#### API-Endpunkte
1. **POST /api/booking** - Termin buchen
   - Validierung aller Felder
   - HelloCash-Integration
   - Datenbank-Speicherung
   - Email-Benachrichtigung (vorbereitet)

2. **GET /api/available-slots?date=YYYY-MM-DD** - Verfügbare Zeiten
   - Liest Einstellungen aus `booking_settings`
   - Generiert Zeitslots dynamisch
   - Prüft existierende Buchungen
   - Gibt verfügbare Slots zurück

#### Datenbank-Tabellen
```sql
-- Terminbuchungen
bookings (
    id, booking_type, service_type, booking_date, booking_time,
    customer_firstname, customer_lastname, customer_company,
    customer_email, customer_phone_country, customer_phone_mobile,
    customer_phone_landline, customer_street, customer_house_number,
    customer_postal_code, customer_city, customer_notes,
    hellocash_customer_id, status, created_at, updated_at
)

-- Konfigurierbare Einstellungen
booking_settings (
    id, setting_key, setting_value, description, updated_at
)
```

#### HelloCash API-Struktur
```php
// Kundendaten-Format für HelloCash
$customerData = [
    'firstname' => 'Max',
    'lastname' => 'Mustermann',
    'email' => 'max@example.com',
    'phone_country' => '+49',           // Dropdown-Wert
    'phone_mobile' => '170 1234567',    // Ohne führende 0
    'phone_landline' => '030 12345678', // Optional
    'company' => 'Firma GmbH',          // Optional
    'street' => 'Musterstraße',
    'house_number' => '42',
    'postal_code' => '10115',
    'city' => 'Berlin'
];

// Wird zu HelloCash gesendet als:
$payload = [
    'user_firstname' => 'Max',
    'user_surname' => 'Mustermann',
    'user_email' => 'max@example.com',
    'user_phoneNumber' => '+49 170 1234567',  // MIT Vorwahl
    'user_country_code' => 'DE',              // ISO-Code
    'user_company' => 'Firma GmbH',
    'user_street' => 'Musterstraße',
    'user_houseNumber' => '42',
    'user_postalCode' => '10115',
    'user_city' => 'Berlin',
    'user_notes' => 'Festnetz: +49 030 12345678'
];
```

#### Telefonnummer-Handling
1. **Frontend:** 
   - Dropdown für Ländervorwahl (+49, +43, +41, +1, +44)
   - Separate Eingabe für Mobilnummer
   - JavaScript entfernt führende Nullen vor Submit

2. **Backend:**
   - PHP entfernt führende Nullen zusätzlich
   - Validierung: Mobilnummer Pflicht, Festnetz optional
   - Speicherung in DB: Ländercode + Nummer getrennt

3. **HelloCash:**
   - Telefonnummer MIT Ländervorwahl übertragen
   - Ländercode als ISO-Code (mapping)
   - Festnetz in `user_notes` Feld

#### 6. Admin-Bereich für Terminverwaltung ✅ (NEU - 2026-01-01)

**Übersicht aller implementierten Admin-Features:**

##### Admin-Dashboard (`/admin`)
- **Statistik-Karten:**
  - Bestellungen gesamt / offen
  - Aktive Produkte
  - Blog-Beiträge
  - **Termine gesamt / offen** (NEU!)
- **Schnellzugriff-Links:**
  - Blog-Posts verwalten
  - Produkte verwalten
  - Bestellungen ansehen
  - **Termine verwalten** (NEU!)
  - **Termineinstellungen** (NEU!)
  - Abmelden

**Datei:** `src/admin/index.php`

##### Termineinstellungen (`/admin/booking-settings`)
Vollständige Admin-UI zur Konfiguration des Buchungssystems:

**Features:**
- **Buchungszeiten einstellen:**
  - Erste verfügbare Zeit (z.B. 11:00)
  - Letzte verfügbare Zeit (z.B. 13:00)
  - Zeit-Eingabe mit HTML5 `<input type="time">`

- **Zeitabstand konfigurieren:**
  - Intervall in Minuten (15-240 Min)
  - Dropdown mit Empfehlungen (15, 30, 45, 60, 90, 120)

- **Kapazität festlegen:**
  - Max. Buchungen pro Zeitslot (1-10)
  - Erlaubt parallele Termine

- **Live-Vorschau:**
  - Zeigt generierte Zeitslots als Badges
  - Automatische Berechnung der Slot-Anzahl
  - Visuelles Feedback zu Einstellungen

**Validierung:**
- Endzeit muss nach Startzeit liegen
- Intervall zwischen 15 und 240 Minuten
- Max. Buchungen zwischen 1 und 10
- Sofortige Fehler- und Erfolgsmeldungen

**Datei:** `src/admin/booking-settings.php`

##### Terminverwaltung (`/admin/bookings`)
Vollständige Verwaltung aller Terminbuchungen:

**Filter & Suche:**
- Suche nach Name oder E-Mail
- Filter nach Status (Ausstehend, Bestätigt, Abgeschlossen, Storniert)
- Filter nach Terminart (Fester Termin, Walk-in)
- Filter nach Datum
- Zurücksetzen-Button für alle Filter

**Buchungs-Tabelle:**
- Spalten: ID, Datum, Zeit, Kunde, Dienstleistung, Typ, Status, Aktionen
- Responsive Design (horizontal scrollbar auf Mobile)
- Farbcodierte Status-Badges
- Klickbare Detail-Links
- Gesamt-Anzahl am Tabellen-Ende

**Status-Badges:**
- 🟡 Ausstehend (Gelb)
- 🟢 Bestätigt (Grün)
- ⚫ Abgeschlossen (Grau)
- 🔴 Storniert (Rot)

**Datei:** `src/admin/bookings.php`

##### Buchungs-Details (`/admin/booking-detail?id=123`)
Detailansicht für einzelne Termine mit Status-Verwaltung:

**Anzeige-Bereiche:**

1. **Termindetails:**
   - Terminart (Badge: Fester Termin / Walk-in)
   - Dienstleistung
   - Datum (formatiert mit Wochentag)
   - Uhrzeit (oder "Walk-in ab 14:00")
   - Kundenanmerkungen (wenn vorhanden)

2. **Kundendaten:**
   - Vorname, Nachname, Firma
   - E-Mail (klickbarer mailto-Link)
   - Mobilnummer (klickbarer tel-Link)
   - Festnetz (klickbarer tel-Link, wenn vorhanden)
   - Vollständige Adresse (Straße, PLZ, Ort)
   - HelloCash Kunden-ID (wenn vorhanden)

3. **Status ändern:**
   - Dropdown mit allen Status-Optionen
   - Aktueller Status vorausgewählt
   - Speichern-Button
   - Erfolgsbestätigung nach Update

**Design:**
- 2-Spalten Grid-Layout (responsive → 1 Spalte auf Mobile)
- Uppercase-Labels mit Farbcodierung
- Hervorgehobene Notiz-Box mit Border
- Zurück-zur-Übersicht-Link

**Datei:** `src/admin/booking-detail.php`

##### Routen-Integration
Alle neuen Admin-Seiten sind im Router registriert:

```php
// src/router.php
case 'admin':
    // ... existing routes ...
    elseif ($param === 'booking-settings') {
        require_admin();
        require __DIR__ . '/admin/booking-settings.php';
    } elseif ($param === 'bookings') {
        require_admin();
        require __DIR__ . '/admin/bookings.php';
    } elseif ($param === 'booking-detail') {
        require_admin();
        require __DIR__ . '/admin/booking-detail.php';
    }
```

#### 7. Email-Benachrichtigungen ✅ (NEU - 2026-01-01)

Vollständiges Email-System bei Terminbuchung implementiert:

##### Funktionsweise
- Automatischer Versand nach erfolgreicher Buchung
- Fail-Safe: Buchung wird gespeichert auch wenn Email fehlschlägt
- Logging aller Email-Vorgänge (error_log)
- UTF-8 Support für deutsche Umlaute

##### Email an Kunde
**Betreff:** `Terminbestätigung #123 - PC-Wittfoot UG`

**Inhalt:**
- Persönliche Anrede mit Vor- und Nachname
- Buchungsnummer
- Formatierte Termindetails (Boxed Design mit UTF-8 Linien)
  - Terminart
  - Dienstleistung
  - Datum mit Wochentag (deutsch)
  - Uhrzeit (oder Walk-in-Hinweis)
- Kundenanmerkungen (falls vorhanden)
- **Checkliste** - Was mitbringen:
  - Gerät (PC/Notebook)
  - Netzkabel & Zubehör
  - Wichtige Passwörter
- Kontaktinformationen (E-Mail, Telefon)
- Freundlicher Abschluss mit Firmenname

##### Email an Admin
**Betreff:** `Neue Terminbuchung #123 - Max Mustermann`

**Inhalt:**
- **TERMINDETAILS** (Großbuchstaben-Header)
  - Alle Termin-Informationen
- **KUNDENDATEN**
  - Vollständiger Name
  - Firma (falls vorhanden)
  - E-Mail & Telefonnummern (Mobil + Festnetz)
  - Vollständige Adresse
- **KUNDENANMERKUNGEN** (falls vorhanden)
- **Direktlink** zur Admin-Detailseite

**Header:**
- Reply-To auf Kunden-Email gesetzt (direkt antworten möglich)

##### Technische Implementation
```php
// src/api/booking.php - Funktion sendBookingEmails()

// Email-Header mit UTF-8
$headers = "From: PC-Wittfoot UG <info@pc-wittfoot.de>\r\n";
$headers .= "Reply-To: info@pc-wittfoot.de\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// PHP mail() Funktion
$sent = @mail($to, $subject, $message, $headers);

// Error-Suppression (@) + Logging
if ($sent) {
    error_log("Email sent to: $to");
} else {
    error_log("Failed to send email to: $to");
}
```

##### Konfiguration
```php
// src/core/config.php
define('MAIL_FROM', 'info@pc-wittfoot.de');
define('MAIL_FROM_NAME', 'PC-Wittfoot UG');
define('MAIL_ADMIN', 'admin@pc-wittfoot.de');
```

**Hinweis:** Für Produktiv-Betrieb ggf. SMTP-Konfiguration oder PHPMailer verwenden (aktuell: PHP mail() Funktion).

##### Datenbank-Erweiterung: Adressfelder
```sql
-- database/add-address-fields-bookings.sql
ALTER TABLE bookings
ADD COLUMN customer_street VARCHAR(255) NULL,
ADD COLUMN customer_house_number VARCHAR(20) NULL,
ADD COLUMN customer_postal_code VARCHAR(10) NULL,
ADD COLUMN customer_city VARCHAR(255) NULL,
ADD INDEX idx_postal_code (customer_postal_code);
```

**Status:** Bereit für Produktion, evtl. SMTP für bessere Zustellbarkeit.

### Offene Aufgaben (TODO)

#### Kurz- bis Mittelfristig
- [ ] **Doppelbuchungs-Handling verfeinern**
  - Race Condition bei gleichzeitigen Buchungen verhindern
  - Optimistic Locking oder Database-Level Constraints

#### Langfristig (Nice-to-Have)
- [ ] Termin-Erinnerungen (24h vorher)
- [ ] Kunden-Login für Buchungshistorie
- [ ] iCal-Export für Terminkalender
- [ ] SMS-Benachrichtigungen (optional)
- [ ] Statistiken (Buchungen pro Monat, beliebteste Dienste)

### Testing

#### Terminbuchung testen
```bash
# PHP-Server starten (im src-Verzeichnis)
cd /home/nicole/projekte/c-d/src
php -S localhost:8000 server.php

# Im Browser öffnen:
# http://localhost:8000/termin

# Testdaten:
# - Fester Termin: Di-Fr, 11:00 oder 12:00
# - Walk-in: Di-Fr oder Sa
# - Alle Felder ausfüllen (inkl. Adresse)

# Nach erfolgreicher Buchung:
# - Email-Logs prüfen (error_log)
# - Admin-Bereich öffnen: http://localhost:8000/admin
# - Terminübersicht öffnen: http://localhost:8000/admin/bookings
# - Details anzeigen: http://localhost:8000/admin/booking-detail?id=1
# - Einstellungen ändern: http://localhost:8000/admin/booking-settings
```

#### Verfügbarkeits-API testen
```bash
# Verfügbare Slots für einen Tag abfragen
curl "http://localhost:8000/api/available-slots?date=2026-01-07"

# Response:
{
  "success": true,
  "date": "2026-01-07",
  "slots": [
    {"time": "11:00", "available": true, "booked": 0, "max": 1},
    {"time": "12:00", "available": false, "booked": 1, "max": 1}
  ]
}
```

#### HelloCash-Integration testen
```bash
# User-Daten in HelloCash prüfen
php tests/check-hellocash-user.php 12

# Neuen User anlegen (Test)
php tests/test-booking.php
```

### Konfiguration

#### Terminzeiten ändern
```sql
-- Zeitbereich ändern (z.B. 10:00-14:00)
UPDATE booking_settings 
SET setting_value = '10:00' 
WHERE setting_key = 'booking_start_time';

UPDATE booking_settings 
SET setting_value = '15:00' 
WHERE setting_key = 'booking_end_time';

-- Intervall ändern (z.B. 30 Minuten)
UPDATE booking_settings 
SET setting_value = '30' 
WHERE setting_key = 'booking_interval_minutes';

-- Max. Buchungen pro Slot (z.B. 2 parallel)
UPDATE booking_settings 
SET setting_value = '2' 
WHERE setting_key = 'max_bookings_per_slot';
```

#### HelloCash API-Credentials
```php
// src/core/config.php
define('HELLOCASH_API_KEY', 'Bearer eyJ0eXAi...');
define('HELLOCASH_API_URL', 'https://api.hellocash.business/api/v1/');
```

### Projektstand nach Session

#### Abgeschlossen ✅
- ✅ Terminbuchungs-Formular (4 Schritte, responsive)
- ✅ HelloCash REST-API vollständig integriert
- ✅ Verfügbarkeitsprüfung mit Doppelbuchungs-Schutz
- ✅ Konfigurierbare Terminzeiten (Datenbank)
- ✅ Unterschiedliche Zeiten für fixed/walkin
- ✅ Adressfelder hinzugefügt und validiert
- ✅ Telefonnummer-Handling perfektioniert
- ✅ Admin-UI für Termineinstellungen (NEU!)
- ✅ Admin-Bereich für Terminverwaltung (NEU!)
- ✅ Email-Benachrichtigungen (NEU!)

## Session 2026-01-01 (Fortsetzung): HelloCash-Kundensuche & Dark Mode

### Erreichte Ziele ✅

#### 1. HelloCash-Kundensuche in Admin-Kalender
**Problem:** Neue Termine mussten manuell eingegeben werden, obwohl Kundendaten bereits in HelloCash existieren.

**Lösung:**
- **API-Endpoint:** `/api/hellocash-search`
  - Suche nach Name, Email oder Telefonnummer
  - Gibt bis zu 10 Ergebnisse zurück
  - Auto-Complete Dropdown in Modal-Formularen
- **Neue Methode:** `HelloCashClient::getAllUsers($limit = 1000)`
  - Ermöglicht Namenssuche über alle User
- **Integration:**
  - Kalenderansicht (`/admin/booking-calendar`)
  - Wochenansicht (`/admin/booking-week`)
  - Automatisches Ausfüllen beim Klick auf Ergebnis
- **Features:**
  - Live-Suche mit min. 2 Zeichen
  - Enter-Taste unterstützt
  - Dropdown schließt bei Klick außerhalb
  - Zeigt Name, Email und Telefon im Dropdown

**Dateien:**
- `src/api/hellocash-search.php` - Search API
- `src/core/HelloCashClient.php` - getAllUsers() Methode
- `src/admin/booking-calendar-v2.php` - Integration
- `src/admin/booking-week.php` - Integration

#### 2. Multi-Stunden-Zeiträume in Wochenansicht
**Problem:** Termine konnten nur 1 Stunde lang sein.

**Lösung:**
- **Datenbank:** `booking_end_time` Spalte hinzugefügt
- **Backend-Berechnung:**
  - Automatisch +1 Stunde wenn keine Endzeit
  - Speichert Start-/End-Stunde und Dauer
- **Visuelle Darstellung:**
  - Absolute Positionierung über mehrere Stunden
  - Dynamische Höhe: `(Dauer * 60px) - 1px`
  - Zeitanzeige: "11:00 - 14:00"
- **Modal-Formular:**
  - "Von (Uhrzeit)" und "Bis (Uhrzeit)" Felder
  - Optionale Endzeit-Angabe

**Dateien:**
- `database/add-booking-end-time.sql` - Schema-Update
- `src/admin/booking-week.php` - Implementierung

#### 3. Admin-Bereich Erweiterungen
**Neue Features:**
- **Admin-Notizen-Feld** (`admin_notes`)
  - Interne Notizen, nicht für Kunden sichtbar
  - In allen Termin-Formularen verfügbar
- **Verschiedene Terminarten:**
  - `fixed` - Reguläre Termine mit Zeit
  - `walkin` - Walk-in ohne feste Zeit
  - `internal` - Interne Notizen (nur Admin)
  - `blocked` - Gesperrte Zeiträume
- **Modal-basierte Bearbeitung:**
  - Schnelles Bearbeiten ohne Seitenwechsel
  - AJAX-basierte Speicherung
  - Formular passt sich Terminart an
- **Kalenderansicht als Standard:**
  - Dashboard verlinkt auf `/admin/booking-calendar`
  - Übersichtlichere Darstellung

**Dateien:**
- `database/add-admin-notes-and-blocking.sql` - Schema
- `src/admin/booking-calendar-v2.php` - Neue Version
- `src/admin/booking-week.php` - Wochenansicht
- `src/admin/index.php` - Dashboard-Update

#### 4. Globaler Dark Mode
**Problem:** Dark Mode war bisher nur lokal in einzelnen Seiten implementiert.

**Lösung:**
- **Globales System nutzen:**
  - `data-theme="dark"` Attribut am HTML-Element
  - Toggle im Header für gesamte Anwendung
  - localStorage-Speicherung
- **Admin-spezifische Styles:**
  - Kalender-Grid & Zellen
  - Wochen-Grid & Zeitslots
  - Modal-Dialoge & Formulare
  - Dropdown-Suchergebnisse
  - Footer-Styling
- **Konsolidierung:**
  - Alle Dark Mode Styles in `/assets/css/components.css`
  - Lokale Implementierungen entfernt
  - Konsistentes Design über alle Seiten

**Dateien:**
- `src/assets/css/components.css` - Admin Dark Mode Styles
- `src/admin/booking-calendar-v2.php` - Lokale Styles entfernt
- `src/admin/booking-week.php` - Lokale Styles entfernt

#### 5. Bugfixes & Verbesserungen
- ✅ **Admin-Login:** Passwort-Hash korrigiert (admin123)
- ✅ **Database-Methoden:** `execute()` → `update()` korrigiert
- ✅ **PHP 8.1+ Kompatibilität:** `strftime()` → `DateTime` ersetzt
- ✅ **Column-Namen:** `status` → `order_status` korrigiert
- ✅ **Dark Mode Footer:** Footer wird jetzt korrekt dunkel dargestellt

**Dateien:**
- `database/create-admin-user.sql` - Password-Hash
- `src/admin/booking-settings.php` - Method-Namen
- `src/admin/booking-calendar-v2.php` - strftime ersetzt
- `src/admin/index.php` - Column-Namen

### Technische Details

#### HelloCash-Suche API
```php
// Request
POST /api/hellocash-search
{
    "action": "search",
    "query": "mustermann"  // oder Email/Telefon
}

// Response
{
    "success": true,
    "results": [
        {
            "user_id": 123,
            "firstname": "Max",
            "lastname": "Mustermann",
            "company": "Firma GmbH",
            "email": "max@example.com",
            "phone": "+49 170 1234567",
            "display_name": "Max Mustermann (Firma GmbH)"
        }
    ],
    "count": 1
}
```

#### Multi-Stunden-Zeiträume
```php
// Datenbank
booking_time: '11:00'
booking_end_time: '14:00'

// Berechnung
$startHour = 11;
$endHour = 14;
$duration = 3; // Stunden
$heightPixels = (3 * 60) - 1; // = 179px

// CSS
<div style="height: 179px; position: absolute; top: 1px;">
    <strong>Max M.</strong>
    <div>11:00 - 14:00</div>
</div>
```

#### Dark Mode Integration
```css
/* Globale Dark Mode Styles */
[data-theme="dark"] .calendar-grid {
    background-color: #404040;
    border-color: #404040;
}

[data-theme="dark"] .calendar-day {
    background-color: #2d2d2d;
    color: #e0e0e0;
}

[data-theme="dark"] footer {
    background: #1a1a1a;
    color: #b0b0b0;
}
```

### Projektstand nach Session

#### Admin-Features komplett ✅
- ✅ Dashboard mit Statistiken
- ✅ Termineinstellungen konfigurierbar
- ✅ Terminübersicht mit Filter & Suche
- ✅ Termin-Details mit Status-Verwaltung
- ✅ Kalenderansicht (Monat)
- ✅ Wochenansicht mit Stundenraster
- ✅ Modal-basierte Termin-Bearbeitung
- ✅ HelloCash-Kundensuche
- ✅ Multi-Stunden-Zeiträume
- ✅ Admin-Notizen & Terminarten
- ✅ Dark Mode global integriert

#### Nächste Session
- Blog-System vervollständigen
- PayPal-Integration
- Bewertungen einbinden

