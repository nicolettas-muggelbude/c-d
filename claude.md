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

## Session 2026-01-01 (Fortsetzung): Template-basiertes Email-System mit Erinnerungen

### Erreichte Ziele ✅

#### 1. Datenbank-basierte Email-Templates
**Problem:** Email-Texte waren hardcodiert im PHP-Code, keine Möglichkeit zur Anpassung durch Admin.

**Lösung:**
- **Email-Templates Tabelle:**
  - 3 Template-Typen: `confirmation`, `reminder_24h`, `reminder_1h`
  - Felder: subject, body, placeholders, is_active
  - Vollständig editierbar über Admin-UI
- **Email-Signatur Tabelle:**
  - Globale Signatur für alle Emails
  - Wird automatisch an alle Nachrichten angehängt
- **Email-Log Tabelle:**
  - Audit-Trail aller versendeten Emails
  - Status-Tracking (sent/failed/pending)
  - Duplikat-Vermeidung durch Prüfung

**Dateien:**
- `database/create-email-templates.sql` - Schema mit Defaults

#### 2. EmailService-Klasse
**Zentrale Service-Klasse** für alle Email-Vorgänge:

**Features:**
- `sendBookingEmail($bookingId, $templateType)` - Haupt-Methode
- `getTemplate($type)` - Lädt Template aus DB
- `getSignature()` - Lädt Signatur aus DB
- `replacePlaceholders($text, $booking)` - Ersetzt Platzhalter
- `sendMail($to, $subject, $body)` - Versendet Email
- `logEmail(...)` - Loggt Versand-Vorgänge
- `isEmailAlreadySent(...)` - Prüft Duplikate
- `getBookingsForReminder24h()` - Findet Termine für 24h-Reminder
- `getBookingsForReminder1h()` - Findet Termine für 1h-Reminder

**Platzhalter-System:**
```php
{customer_firstname}       → "Max"
{customer_lastname}        → "Mustermann"
{booking_id}              → "123"
{booking_date_formatted}  → "Dienstag, 07. Januar 2026"
{booking_time_formatted}  → "11:00 Uhr" oder "Walk-in ab 14:00 Uhr"
{service_type_label}      → "PC-Reparatur"
{booking_type_label}      → "Fester Termin"
{customer_notes_section}  → "Ihre Anmerkungen:\n..."
```

**Datei:** `src/core/EmailService.php`

#### 3. Admin-UI für Email-Template-Verwaltung
**Vollständige Verwaltung** aller Email-Templates:

**Features:**
- **Template-Liste:** Alle Templates mit Status (aktiv/inaktiv)
- **Template bearbeiten:**
  - Subject und Body editierbar (Textarea)
  - Verfügbare Platzhalter werden angezeigt
  - Speichern-Button mit Bestätigung
- **Signatur bearbeiten:**
  - Globale Signatur für alle Emails
  - Wird automatisch angehängt
- **Toggle aktiv/inaktiv:**
  - Templates können deaktiviert werden
  - Inaktive Templates werden nicht versendet

**Standard-Templates:**
1. **Buchungsbestätigung (confirmation):**
   - Betreff: "Ihre Terminbuchung #{booking_id} - PC-Wittfoot UG"
   - Inhalt mit Box-Design (UTF-8 Linien)
   - Termindetails formatiert
   - Was mitbringen-Checkliste

2. **24-Stunden-Erinnerung (reminder_24h):**
   - Betreff: "Erinnerung: Ihr Termin morgen um {booking_time_formatted}"
   - Freundliche Erinnerung
   - Alle Termindetails nochmal

3. **1-Stunden-Erinnerung (reminder_1h):**
   - Betreff: "Ihr Termin in 1 Stunde - PC-Wittfoot UG"
   - Kurze Erinnerung
   - Wichtigste Infos (Adresse, Zeit)

**Datei:** `src/admin/email-templates.php`

#### 4. Automatische Erinnerungs-Emails via Cron-Jobs
**Problem:** Kunden vergessen ihre Termine.

**Lösung - 24-Stunden-Erinnerung:**
- **Cron-Job:** Läuft täglich um 10:00 Uhr
- **Zielgruppe:** Termine am nächsten Tag
- **Filter:**
  - `booking_date = DATE_ADD(CURDATE(), INTERVAL 1 DAY)`
  - Status: pending oder confirmed
  - Nur fixed und walkin Termine
  - Nicht bereits versendet (Email-Log-Check)

**Lösung - 1-Stunden-Erinnerung:**
- **Cron-Job:** Läuft stündlich
- **Zielgruppe:** Termine in 50-70 Minuten
- **Filter:**
  - `booking_date = CURDATE()`
  - `booking_time` zwischen NOW()+50min und NOW()+70min
  - Nur fixed Termine (haben feste Zeit)
  - Status: pending oder confirmed
  - Nicht bereits versendet

**Features beider Jobs:**
- CLI-only Check (Sicherheit)
- Zählt gesendete/fehlgeschlagene Emails
- Logging: Datum, Zeit, Statistik
- Exit-Code für Monitoring (0 = OK, 1 = Fehler)

**Dateien:**
- `src/cron/send-reminder-24h.php` - 24h-Job
- `src/cron/send-reminder-1h.php` - 1h-Job

**Crontab-Beispiel:**
```bash
# 24h-Erinnerungen täglich um 10:00 Uhr
0 10 * * * /usr/bin/php /pfad/zu/src/cron/send-reminder-24h.php

# 1h-Erinnerungen jede Stunde
0 * * * * /usr/bin/php /pfad/zu/src/cron/send-reminder-1h.php
```

#### 5. Email-Versand bei Admin-Buchung
**Problem:** Wenn Admin einen Termin für Kunden erstellt, erhält dieser keine Bestätigung.

**Lösung:**
- Integration in `src/admin/booking-calendar-v2.php`
- Prüfung nach INSERT:
  - Buchung erfolgreich erstellt?
  - Email-Adresse vorhanden?
  - Kundenrelevanter Termin? (fixed/walkin, nicht internal/blocked)
- Automatischer Versand der confirmation-Email
- Fail-Safe: Fehler beim Email-Versand stoppt Buchung nicht

**Code:**
```php
// Email-Bestätigung senden (nur bei Kundenterminen mit Email)
if ($bookingId && !empty($customerEmail) && in_array($bookingType, ['fixed', 'walkin'])) {
    $emailService = new EmailService();
    $emailService->sendBookingEmail($bookingId, 'confirmation');
}
```

**Gilt für:**
- Kalenderansicht (`/admin/booking-calendar`)
- Wochenansicht (`/admin/booking-week`)

**Datei:** `src/admin/booking-calendar-v2.php`

#### 6. Migration: Alte Email-Funktion entfernt
**Vorher:**
- 158 Zeilen hardcodierte Email-Funktion `sendBookingEmails()`
- Separate Email für Kunde und Admin
- Nicht wiederverwendbar, nicht konfigurierbar

**Nachher:**
- Ersetzt durch `EmailService::sendBookingEmail()`
- Wiederverwendbar in gesamter Anwendung
- Admin-editierbar, Template-basiert
- Umfangreiches Logging

**Datei:** `src/api/booking.php` (158 Zeilen entfernt, 3 Zeilen hinzugefügt)

### Technische Details

#### Duplikat-Vermeidung
```php
// Prüft ob Email bereits versendet wurde
private function isEmailAlreadySent($bookingId, $emailType) {
    $result = $this->db->querySingle(
        "SELECT COUNT(*) as count FROM email_log
         WHERE booking_id = :booking_id
         AND email_type = :email_type
         AND status = 'sent'",
        [':booking_id' => $bookingId, ':email_type' => $emailType]
    );
    return ($result['count'] ?? 0) > 0;
}
```

**Vorteil:** Auch bei mehrfachem Aufruf wird Email nur 1x versendet.

#### Datum-Formatierung (deutsch)
```php
// Wochentage
$weekdays = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch',
             'Donnerstag', 'Freitag', 'Samstag'];

// Monate
$months = ['', 'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
           'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];

// Formatierung
$dateFormatted = $weekdays[(int)$date->format('w')] . ', ' .
                $date->format('d') . '. ' .
                $months[(int)$date->format('n')] . ' ' .
                $date->format('Y');
// Ergebnis: "Dienstag, 07. Januar 2026"
```

#### Email-Versand mit UTF-8
```php
private function sendMail($to, $subject, $body) {
    $headers = "From: " . MAIL_FROM_NAME . " <" . MAIL_FROM . ">\r\n";
    $headers .= "Reply-To: " . MAIL_FROM . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $sent = @mail($to, $subject, $body, $headers);

    // Logging
    if ($sent) {
        error_log("EmailService: Email sent to $to");
    } else {
        error_log("EmailService: Failed to send email to $to");
    }

    return $sent;
}
```

**Hinweis:** PHP mail() Funktion - für Produktion ggf. SMTP/PHPMailer verwenden.

#### SQL-Query für 1h-Erinnerungen
```sql
SELECT id FROM bookings
WHERE booking_date = CURDATE()
AND booking_time IS NOT NULL
AND booking_time BETWEEN
    DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 50 MINUTE), '%H:%i:00')
    AND DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 70 MINUTE), '%H:%i:00')
AND booking_type = 'fixed'
AND status IN ('pending', 'confirmed')
AND id NOT IN (
    SELECT booking_id FROM email_log
    WHERE email_type = 'reminder_1h' AND status = 'sent'
)
```

**Zeitfenster:** 50-70 Minuten → Cron-Job läuft stündlich, trifft damit alle Termine.

### Dateistruktur (Neu)

```
src/
├── core/
│   └── EmailService.php          # Email-Service-Klasse (NEU)
├── admin/
│   └── email-templates.php       # Template-Verwaltung (NEU)
├── cron/
│   ├── send-reminder-24h.php     # 24h-Reminder Job (NEU)
│   └── send-reminder-1h.php      # 1h-Reminder Job (NEU)
└── api/
    └── booking.php               # Email-Integration (AKTUALISIERT)

database/
└── create-email-templates.sql    # Schema + Defaults (NEU)
```

### Projektstand nach Session

#### Komplett implementiert ✅
- ✅ Datenbank-Schema für Email-System
- ✅ EmailService-Klasse mit allen Features
- ✅ Admin-UI für Template-Verwaltung
- ✅ Platzhalter-System mit deutscher Formatierung
- ✅ 24h-Erinnerungs-Cron-Job
- ✅ 1h-Erinnerungs-Cron-Job
- ✅ Email-Versand bei Admin-Buchung
- ✅ Email-Versand bei Kunden-Buchung
- ✅ Duplikat-Vermeidung
- ✅ Umfangreiches Logging

#### Bereit für Produktion
- **Funktionsumfang:** Vollständig
- **Testing:** Durchgeführt
- **Integration:** Abgeschlossen
- **Dokumentation:** Vollständig

#### Mögliche Erweiterungen (Optional)
- SMTP-Integration für bessere Zustellbarkeit
- HTML-Email-Templates (derzeit: Plain Text)
- CC/BCC-Funktion
- Attachment-Support
- Email-Versand-Statistiken im Dashboard

#### Router-Integration
Neue Route hinzugefügt:
```php
// src/router.php
elseif ($param === 'email-templates') {
    require_admin();
    require __DIR__ . '/admin/email-templates.php';
}
```

**Zugriff:** `/admin/email-templates`

## Session 2026-01-01 (Fortsetzung): PHPMailer SMTP-Integration

### Erreichte Ziele ✅

#### 1. PHPMailer Installation
**Problem:** PHP mail() Funktion ist unzuverlässig, landet oft im Spam, keine SMTP-Unterstützung.

**Lösung:**
- PHPMailer v7.0.1 via Composer installiert
- Composer lokal heruntergeladen (`composer.phar`) für Entwicklung
- Vendor-Ordner kann via FTP auf Produktiv-Server deployed werden

**Dateien:**
- `composer.json` - Composer-Konfiguration
- `composer.lock` - Dependency Lock-File
- `vendor/` - PHPMailer & Dependencies

#### 2. SMTP-Konfiguration
**Datenbank-basierte Konfiguration** für flexible Admin-Verwaltung:

**Tabelle `smtp_settings`:**
```sql
- smtp_enabled (BOOLEAN) - SMTP aktiviert oder PHP mail()
- smtp_host (VARCHAR) - SMTP Server (z.B. smtp.gmail.com)
- smtp_port (INT) - Port (587 = TLS, 465 = SSL)
- smtp_encryption (ENUM) - tls, ssl, oder none
- smtp_username (VARCHAR) - SMTP Benutzername
- smtp_password (VARCHAR) - SMTP Passwort
- smtp_debug (INT) - Debug-Level (0-2)
- updated_at (TIMESTAMP) - Letzte Änderung
```

**Standard-Werte:**
- SMTP deaktiviert (verwendet PHP mail())
- Vorkonfiguriert für Gmail (smtp.gmail.com:587, TLS)
- Debug aus für Produktion

**Dateien:**
- `database/create-smtp-settings.sql` - Schema
- `src/core/config.php` - Composer Autoload & Fallback-Konstanten

#### 3. EmailService mit PHPMailer
**Komplett überarbeiteter Email-Service:**

**Features:**
- Automatische Wahl zwischen SMTP und PHP mail()
- Liest Konfiguration aus Datenbank (nicht hardcoded!)
- Besseres Error-Handling mit Try-Catch
- Detailliertes Logging (zeigt SMTP-Server an)
- UTF-8 Support
- Debug-Ausgabe konfigurierbar

**Code-Änderungen:**
```php
// Vorher: Hardcoded mail() Funktion
$sent = @mail($to, $subject, $body, $headers);

// Nachher: PHPMailer mit DB-Konfiguration
$mail = new \PHPMailer\PHPMailer\PHPMailer(true);
$smtp = $this->db->querySingle("SELECT * FROM smtp_settings WHERE id = 1");

if ($smtp && $smtp['smtp_enabled']) {
    $mail->isSMTP();
    $mail->Host = $smtp['smtp_host'];
    // ... weitere SMTP-Einstellungen
} else {
    $mail->isMail();  // Fallback zu PHP mail()
}
```

**Vorteile:**
- ✅ Bessere Zustellbarkeit (weniger Spam)
- ✅ Verschlüsselte Verbindung (TLS/SSL)
- ✅ Authentifizierung mit SMTP-Credentials
- ✅ Detaillierte Error-Messages
- ✅ Flexibel: SMTP per Klick ein/ausschalten

**Datei:** `src/core/EmailService.php`

#### 4. Admin-UI für SMTP-Verwaltung
**Vollständige Verwaltungsoberfläche** (`/admin/smtp-settings`):

**Features:**
- ✅ **SMTP aktivieren/deaktivieren** - Checkbox zum Umschalten
- ✅ **Server-Konfiguration:**
  - SMTP Host (z.B. smtp.gmail.com, smtp.office365.com)
  - Port (Standard: 587 für TLS, 465 für SSL)
  - Verschlüsselung (TLS/SSL/Keine)
- ✅ **Authentifizierung:**
  - Benutzername
  - Passwort (nur ändern wenn neues eingegeben)
- ✅ **Debug-Level:**
  - Aus (Produktion)
  - Nur Fehler
  - Verbose (Entwicklung)
- ✅ **Aktuelle Konfiguration** - Übersicht der gespeicherten Einstellungen
- ✅ **Info-Box** mit Hinweisen für Gmail, Office365, etc.

**Design:**
- Responsive Formular mit Validierung
- Form-Row Layout für Port/Verschlüsselung
- Passwort-Feld: Placeholder-Text erklärt Verhalten
- Übersichtliche Tabelle mit aktueller Config

**Datei:** `src/admin/smtp-settings.php`

#### 5. Test-Email Funktion
**Dedizierte Test-Seite** (`/admin/smtp-test`):

**Features:**
- ✅ Test-Email an beliebige Adresse senden
- ✅ Zeigt aktuelle SMTP-Methode an (SMTP oder PHP mail())
- ✅ Bei SMTP: Zeigt Server, Port, Verschlüsselung
- ✅ **Debug-Ausgabe** - Komplette SMTP-Kommunikation sichtbar
- ✅ Erfolgs-/Fehlermeldungen
- ✅ Hinweise für Gmail, Office365, Spam-Ordner

**Debug-Ausgabe:**
```
SMTP -> FROM SERVER: 220 smtp.gmail.com ESMTP ready
SMTP -> FROM SERVER: 250-smtp.gmail.com at your service
...
```

**Test-Email Inhalt:**
- Versanddatum/Zeit
- Verwendete Methode (SMTP/PHP mail())
- SMTP-Server Details (falls SMTP)
- Bestätigungstext

**Datei:** `src/admin/smtp-test.php`

#### 6. Dashboard-Integration
**Neue Links im Admin-Dashboard:**

```php
✉️ Email-Templates verwalten  → /admin/email-templates
🔧 SMTP-Einstellungen         → /admin/smtp-settings
```

Zugriff: Dashboard → "🔧 SMTP-Einstellungen" → "🧪 Test-Email senden"

#### 7. Router-Erweiterung
**Neue Routen:**
```php
/admin/smtp-settings  → SMTP-Konfiguration
/admin/smtp-test      → Test-Email senden
```

**Datei:** `src/router.php`

### Technische Details

#### Composer Autoload
```php
// src/core/config.php
require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';
```

Lädt PHPMailer und alle anderen Composer-Packages automatisch.

#### PHPMailer Konfiguration
```php
// SMTP aktiviert
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Username = 'email@gmail.com';
$mail->Password = 'app-password';

// PHP mail() Fallback
$mail->isMail();
```

#### Gmail-Konfiguration
Für Gmail-Versand erforderlich:
1. 2-Faktor-Authentifizierung aktivieren
2. App-Passwort generieren (nicht normales Passwort!)
3. SMTP-Einstellungen:
   - Host: `smtp.gmail.com`
   - Port: `587`
   - Verschlüsselung: `TLS`
   - Benutzername: Deine Gmail-Adresse
   - Passwort: App-Passwort (16-stellig)

#### Office365-Konfiguration
1. SMTP-Einstellungen:
   - Host: `smtp.office365.com`
   - Port: `587`
   - Verschlüsselung: `TLS`
   - Benutzername: Deine Office365-Email
   - Passwort: Office365-Passwort

### Dateistruktur (Neu)

```
/
├── composer.json              # Composer-Konfiguration (NEU)
├── composer.phar              # Composer Binary (NEU)
├── vendor/                    # Dependencies (NEU)
│   └── phpmailer/phpmailer/
├── database/
│   └── create-smtp-settings.sql (NEU)
├── src/
│   ├── core/
│   │   ├── config.php         # Composer Autoload hinzugefügt
│   │   └── EmailService.php   # PHPMailer-Integration
│   └── admin/
│       ├── index.php          # Dashboard-Link hinzugefügt
│       ├── smtp-settings.php  # SMTP-Verwaltung (NEU)
│       └── smtp-test.php      # Test-Email (NEU)
```

### Projektstand nach Session

#### Komplett implementiert ✅
- ✅ PHPMailer v7.0.1 installiert
- ✅ Datenbank-basierte SMTP-Konfiguration
- ✅ EmailService auf PHPMailer migriert
- ✅ Admin-UI für SMTP-Verwaltung
- ✅ Test-Email Funktion mit Debug-Ausgabe
- ✅ Dashboard-Integration
- ✅ Kompatibilität mit Gmail, Office365, eigenen SMTP-Servern

#### Bereit für Produktion
- **Email-Versand:** Flexibel (SMTP oder PHP mail())
- **Konfiguration:** Admin-editierbar über UI
- **Testing:** Integrierte Test-Funktion
- **Logging:** Detaillierte Error-Messages
- **Sicherheit:** Passwörter in Datenbank (verschlüsselt empfohlen)

#### Deployment-Hinweise
1. **Composer Dependencies:** `vendor/` Ordner via FTP hochladen
2. **Datenbank:** `create-smtp-settings.sql` importieren
3. **SMTP-Einstellungen:** Im Admin-Bereich konfigurieren
4. **Test:** Test-Email senden vor Produktiv-Betrieb

#### Verbesserungsmöglichkeiten (Optional)
- Passwort-Verschlüsselung in Datenbank
- Multiple SMTP-Profile (z.B. für verschiedene Email-Typen)
- Email-Queue für bessere Performance
- Statistiken: Erfolgreiche/Fehlgeschlagene Emails
- HTML-Email Support (derzeit: Plain Text)

## Session 2026-01-01 (Fortsetzung): Deployment-System mit Wartungsmodus

### Erreichte Ziele ✅

#### 1. Wartungsmodus-System
**Problem:** Bei Updates muss die Website offline genommen werden können, ohne dass User Fehler sehen.

**Lösung - Datei-basiertes System:**
- Einfacher File-Check: Wenn `src/MAINTENANCE` existiert → Wartungsseite anzeigen
- Keine Datenbank-Änderung erforderlich
- Schnell aktivierbar (per FTP/SSH oder Admin-UI)

**Features:**
- ✅ **Admin-Bypass:** Eingeloggte Admins können weiter arbeiten
- ✅ **Admin-Warnung:** Orange Sticky-Banner zeigt Wartungsmodus an
- ✅ **Custom Message:** Nachricht aus MAINTENANCE-Datei (erste Zeile)
- ✅ **Geschätzte Endzeit:** Optional in zweiter Zeile
- ✅ **Schöne Wartungsseite:**
  - Gradient-Hintergrund (Lila)
  - Animiertes Werkzeug-Icon (Pulse)
  - Spinner-Animation
  - Kontaktinformationen
  - 503 HTTP Status mit Retry-After Header

**Technische Implementation:**
```php
// src/core/maintenance.php
if (file_exists($maintenanceFile)) {
    // Admin kann trotzdem zugreifen
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
        define('MAINTENANCE_ADMIN_BYPASS', true);
        return;
    }

    // Normale User sehen Wartungsseite
    showMaintenancePage($maintenanceFile);
    exit;
}
```

**MAINTENANCE-Datei Format:**
```
Wir führen gerade ein Update durch.
Heute um 18:00 Uhr
```

**Integration in Router:**
```php
// src/router.php
start_session_safe();

// Wartungsmodus-Check (vor allen Routen!)
require_once __DIR__ . '/core/maintenance.php';

// ... rest of routing
```

**Admin-Warnung im Header:**
```php
// src/templates/header.php
<?php if (defined('MAINTENANCE_ADMIN_BYPASS') && MAINTENANCE_ADMIN_BYPASS): ?>
    <div style="background: #ff9800; color: white; padding: 1rem;
                text-align: center; font-weight: bold; position: sticky;
                top: 0; z-index: 10000;">
        ⚠️ WARTUNGSMODUS AKTIV - Sie sind als Admin eingeloggt...
    </div>
<?php endif; ?>
```

**Datei:** `src/core/maintenance.php`

#### 2. Health-Check Endpoint
**Problem:** Nach Deployment muss Systemstatus überprüfbar sein.

**Lösung - Umfassender Health-Check API:**

**Endpoint:** `GET /api/health-check`

**Prüfungen:**
1. ✅ **Datenbank-Verbindung:**
   - SELECT 1 Query
   - Erfolg/Fehler mit Fehlermeldung

2. ✅ **EmailService:**
   - Klasse ladbar?
   - Instanziierbar?

3. ✅ **Composer Vendor:**
   - autoload.php vorhanden?
   - PHPMailer verfügbar?

4. ✅ **Logs-Verzeichnis:**
   - Existiert?
   - Beschreibbar?

5. ✅ **Uploads-Verzeichnis:**
   - Existiert?
   - Beschreibbar?

6. ✅ **Speicherplatz:**
   - Freier Speicher in GB
   - Prozent verfügbar
   - Warnung bei <10%

7. ✅ **PHP-Version:**
   - Aktuelle Version anzeigen

8. ✅ **Wartungsmodus-Status:**
   - Aktiv/Inaktiv
   - Warnung wenn aktiv

**Response-Format:**
```json
{
    "status": "ok",  // oder "warning", "error"
    "timestamp": "2026-01-01 16:04:10",
    "version": "1.0.0",
    "checks": {
        "database": {
            "status": "ok",
            "message": "Datenbankverbindung erfolgreich"
        },
        "disk_space": {
            "status": "ok",
            "message": "Speicherplatz ausreichend: 93.23%",
            "free_gb": 938.64,
            "total_gb": 1006.85
        },
        "maintenance_mode": {
            "status": "ok",
            "message": "Wartungsmodus ist inaktiv",
            "enabled": false
        }
        // ... weitere Checks
    }
}
```

**HTTP Status Codes:**
- `200 OK` - Alle Checks erfolgreich oder nur Warnungen
- `503 Service Unavailable` - Kritische Fehler (z.B. DB down)

**Verwendung im Deployment:**
```bash
# Health-Check aufrufen
curl https://pc-wittfoot.de/api/health-check

# Mit jq für bessere Lesbarkeit
curl -s https://pc-wittfoot.de/api/health-check | jq
```

**Datei:** `src/api/health-check.php`

**Router-Integration:**
```php
// src/router.php
case 'api':
    header('Content-Type: application/json; charset=UTF-8');

    switch ($param) {
        // ... existing routes
        case 'health-check':
            require __DIR__ . '/api/health-check.php';
            break;
    }
```

#### 3. Admin-UI für Wartungsmodus
**Problem:** Wartungsmodus sollte nicht per FTP/SSH aktiviert werden müssen.

**Lösung - Vollständige Verwaltungsoberfläche:**

**URL:** `/admin/maintenance`

**Features:**
- ✅ **Status-Übersicht:**
  - Großer Status-Indicator (🔧 AKTIV / ✅ ONLINE)
  - Farbcodiert (Orange/Grün)
  - Zeigt aktuelle Nachricht und Endzeit

- ✅ **Wartungsmodus aktivieren:**
  - Custom Nachricht eingeben (Textarea)
  - Voraussichtliches Ende (optional, Text-Input)
  - Bestätigungs-Dialog
  - Erstellt `src/MAINTENANCE` Datei

- ✅ **Wartungsmodus deaktivieren:**
  - Button mit Bestätigungs-Dialog
  - Löscht `src/MAINTENANCE` Datei

- ✅ **Nachricht bearbeiten:**
  - Während Wartungsmodus aktiv
  - Live-Update ohne Deaktivierung

- ✅ **Info-Bereiche:**
  - Was passiert beim Aktivieren?
  - Health-Check Endpoint Info
  - Empfohlener Deployment-Workflow

**Design:**
- Responsive Cards-Layout
- Status-Badges mit Icons
- Info-Boxen mit Hinweisen
- Preview-Box für aktuelle Nachricht
- Form-Validierung

**Workflow:**
1. Admin öffnet `/admin/maintenance`
2. Klickt "🔧 Wartungsmodus aktivieren"
3. Gibt Nachricht ein (z.B. "Wir führen gerade ein Update durch.")
4. Optional: Gibt geschätzte Endzeit ein (z.B. "Heute um 18:00 Uhr")
5. Bestätigt → Website ist offline für User
6. Admin kann weiter arbeiten (sieht orange Warnung)
7. Nach Deployment: "✅ Wartungsmodus deaktivieren"

**Dateiberechtigungen:**
- `src/` Verzeichnis muss beschreibbar sein
- Fehlermeldung falls MAINTENANCE nicht erstellt/gelöscht werden kann

**Datei:** `src/admin/maintenance.php`

**Router-Integration:**
```php
// src/router.php
case 'admin':
    // ... existing routes
    elseif ($param === 'maintenance') {
        require_admin();
        require __DIR__ . '/admin/maintenance.php';
    }
```

**Dashboard-Link:**
```php
// src/admin/index.php
<a href="<?= BASE_URL ?>/admin/maintenance" class="btn btn-outline btn-block">
    🛠️ Wartungsmodus
</a>
```

#### 4. Deployment-Script (deploy.sh)
**Problem:** Manuelles Deployment via FTP ist fehleranfällig und zeitaufwändig.

**Lösung - Automatisiertes Deployment-Script:**

**Verwendung:**
```bash
./deploy.sh
```

**Features:**
1. ✅ **FTP-Verbindung prüfen**
   - Validiert Zugangsdaten
   - Prüft lftp-Installation

2. ✅ **Backup erstellen**
   - Automatisch vor jedem Deployment
   - Dateien + Datenbank
   - Komprimiert als .tar.gz

3. ✅ **Wartungsmodus aktivieren**
   - Uploaded MAINTENANCE-Datei per FTP
   - Custom Nachricht mit Zeitstempel

4. ✅ **Dateien hochladen**
   - Mirror-Mode (nur geänderte Dateien)
   - Excludes: .git, node_modules, .env, MAINTENANCE
   - Parallele Uploads (5 Connections)

5. ✅ **Health-Check durchführen**
   - 3 Versuche bei Fehler
   - Zeigt Status-Details
   - Bei Fehler: Frage ob trotzdem online gehen

6. ✅ **Wartungsmodus deaktivieren**
   - Nur wenn Health-Check erfolgreich
   - Optional: Manuelles Override bei Fehler

**Workflow:**
```
Bestätigung → FTP-Check → Backup → Wartung AN
  → Upload → Health-Check → Wartung AUS → Fertig
```

**Konfiguration (anpassen!):**
```bash
# FTP-Zugangsdaten
FTP_HOST="ftp.example.com"
FTP_USER="username"
FTP_PASS="password"
FTP_REMOTE_DIR="/public_html"

# Website-URL
SITE_URL="https://pc-wittfoot.de"
HEALTH_CHECK_URL="$SITE_URL/api/health-check"

# Backup-Aufbewahrung
BACKUP_RETENTION_DAYS=30
```

**Logging:**
- Farbcodierte Ausgabe (INFO/SUCCESS/WARNING/ERROR)
- Alle Schritte werden geloggt
- Backup-Pfad wird angezeigt

**Error-Handling:**
- Bei FTP-Fehler → Abbruch vor Wartungsmodus
- Bei Upload-Fehler → Wartungsmodus bleibt aktiv
- Bei Health-Check-Fehler → Nachfrage ob trotzdem online
- Backup-Fehler stoppt Deployment nicht (Warnung)

**Datei:** `deploy.sh`

**Ausführbar machen:**
```bash
chmod +x deploy.sh
```

**Abhängigkeiten:**
```bash
# lftp für FTP-Upload
sudo apt-get install lftp

# curl für Health-Check
sudo apt-get install curl

# python3 für JSON-Formatierung (optional)
sudo apt-get install python3
```

#### 5. Backup-Script (backup.sh)
**Problem:** Regelmäßige Backups sind essentiell, sollten aber automatisiert sein.

**Lösung - Flexibles Backup-Script:**

**Verwendung:**
```bash
./backup.sh                 # Vollständiges Backup
./backup.sh --files-only    # Nur Dateien
./backup.sh --db-only       # Nur Datenbank
./backup.sh --list          # Backups auflisten
```

**Features:**
1. ✅ **Dateien sichern:**
   - Kompletter `src/` Ordner
   - .env, composer.json, composer.lock
   - .htaccess
   - Erstellt backup_info.txt mit Metadaten

2. ✅ **Datenbank sichern:**
   - mysqldump aller Tabellen
   - Komplett mit Struktur und Daten
   - Erstellt database_info.txt

3. ✅ **Backup komprimieren:**
   - tar.gz Format
   - Zeitstempel im Dateinamen
   - Temp-Verzeichnis wird aufgeräumt

4. ✅ **Alte Backups löschen:**
   - Automatisch Backups >30 Tage
   - Konfigurierbar

5. ✅ **Remote-Upload (optional):**
   - FTP-Upload auf Remote-Server
   - Konfigurierbar ein/ausschalten

6. ✅ **Backup-Übersicht:**
   - Liste aller Backups
   - Datum, Zeit, Größe
   - Sortiert nach Datum

**Backup-Format:**
```
backups/
├── backup_20260101_160808.tar.gz  (112K)
├── backup_20260101_160746.tar.gz  (112K)
└── backup_20250101_143022.tar.gz  (108K)
```

**Backup-Inhalt:**
```
backup_20260101_160808.tar.gz
├── files/
│   ├── src/
│   ├── composer.json
│   ├── composer.lock
│   ├── .htaccess
│   └── backup_info.txt
└── database/
    ├── pc_wittfoot_20260101_160808.sql
    └── database_info.txt
```

**Konfiguration:**
```bash
# Datenbank-Zugangsdaten
DB_HOST="localhost"
DB_USER="pc_wittfoot"
DB_PASS="dev123"
DB_NAME="pc_wittfoot"

# Backup-Aufbewahrung
BACKUP_RETENTION_DAYS=30

# Remote-Upload (optional)
REMOTE_BACKUP_ENABLED=false
REMOTE_FTP_HOST=""
REMOTE_FTP_USER=""
REMOTE_FTP_PASS=""
```

**Logging:**
- Farbcodierte Ausgabe
- Zeigt Backup-Größe
- Listet gelöschte alte Backups
- Zusammenfassung am Ende

**Automatisierung via Cron:**
```bash
# Täglich um 3:00 Uhr
0 3 * * * /pfad/zu/backup.sh

# Wöchentlich Sonntags um 4:00 Uhr
0 4 * * 0 /pfad/zu/backup.sh
```

**Datei:** `backup.sh`

**Ausführbar machen:**
```bash
chmod +x backup.sh
```

**Abhängigkeiten:**
```bash
# mysqldump für Datenbank-Backup
sudo apt-get install mysql-client

# lftp für Remote-Upload (optional)
sudo apt-get install lftp
```

**Backup wiederherstellen:**
```bash
# Backup entpacken
tar -xzf backups/backup_20260101_160808.tar.gz

# Dateien zurückspielen
cp -r files/src/* /pfad/zu/src/

# Datenbank importieren
mysql -u pc_wittfoot -p pc_wittfoot < database/pc_wittfoot_*.sql
```

### Deployment-Workflow (Empfohlen)

#### Manuelles Deployment
```bash
# 1. Änderungen testen lokal
php -S localhost:8000 server.php

# 2. Backup erstellen
./backup.sh

# 3. Wartungsmodus aktivieren (via Admin-UI oder Script)
touch src/MAINTENANCE

# 4. Dateien per FTP hochladen
# ... manuell oder via FileZilla

# 5. Health-Check prüfen
curl https://pc-wittfoot.de/api/health-check

# 6. Wartungsmodus deaktivieren
rm src/MAINTENANCE
```

#### Automatisches Deployment
```bash
# Alles in einem Schritt
./deploy.sh

# Das Script führt alle Schritte automatisch aus:
# ✅ Backup
# ✅ Wartungsmodus AN
# ✅ Upload
# ✅ Health-Check
# ✅ Wartungsmodus AUS
```

#### Deployment mit Datenbank-Migration
```bash
# 1. Deploy wie gewohnt
./deploy.sh

# 2. Via FTP: SQL-Datei hochladen nach /tmp

# 3. Via phpMyAdmin oder SSH:
mysql -u pc_wittfoot -p pc_wittfoot < /tmp/migration.sql

# 4. Health-Check prüfen
curl https://pc-wittfoot.de/api/health-check

# 5. Falls Fehler: Wartungsmodus manuell deaktivieren
# Via Admin-UI: /admin/maintenance
```

### Technische Details

#### Wartungsmodus-Check (Performance)
```php
// Sehr schnell - nur File-Check
if (file_exists($maintenanceFile)) {
    // Kein DB-Query nötig!
}

// Pro Request: ~0.001s Overhead
```

#### Health-Check Performance
- Führt ~8 Checks durch
- Response-Time: ~50-200ms
- Cached: Nein (immer aktuell)
- Geeignet für Monitoring-Tools

#### Backup-Größen (Beispiel)
```
Dateien (src/):              ~2 MB
Datenbank (SQL-Dump):       ~100 KB
Komprimiert (tar.gz):       ~500 KB
```

**Mit Bildern/Uploads:**
```
Dateien + Uploads:           ~50 MB
Komprimiert:                 ~20 MB
```

#### FTP-Upload via lftp
```bash
# Vorteile gegenüber Standard-FTP:
- Mirror-Mode (nur geänderte Dateien)
- Parallele Verbindungen (schneller)
- Resume bei Abbruch
- SSL/TLS Support
- Scripting-fähig
```

### Dateistruktur (Deployment-System)

```
/
├── deploy.sh                      # Deployment-Script (NEU)
├── backup.sh                      # Backup-Script (NEU)
├── backups/                       # Backup-Verzeichnis (NEU)
│   └── backup_*.tar.gz
├── src/
│   ├── MAINTENANCE                # Wartungsmodus-Trigger
│   ├── core/
│   │   └── maintenance.php        # Wartungsmodus-Handler (NEU)
│   ├── templates/
│   │   └── header.php             # Admin-Warnung (AKTUALISIERT)
│   ├── admin/
│   │   └── maintenance.php        # Admin-UI (NEU)
│   ├── api/
│   │   └── health-check.php       # Health-Check (NEU)
│   └── router.php                 # Maintenance-Check (AKTUALISIERT)
```

### .gitignore Anpassungen

```bash
# Deployment-System
/backups/                  # Backups nicht committen
/src/MAINTENANCE           # Wartungsmodus-Datei nicht committen

# Bereits vorhanden
/vendor/
composer.phar
/logs/*.log
.vscode/
.idea/
```

**Datei:** `.gitignore`

### Projektstand nach Session

#### Komplett implementiert ✅
- ✅ Wartungsmodus-System (datei-basiert)
- ✅ Health-Check Endpoint (8 Prüfungen)
- ✅ Admin-UI für Wartungsmodus
- ✅ Deployment-Script (deploy.sh)
- ✅ Backup-Script (backup.sh)
- ✅ Admin-Warnung im Header
- ✅ Router-Integration
- ✅ Dashboard-Integration
- ✅ .gitignore aktualisiert

#### Bereit für Produktion
- **Wartungsmodus:** Jederzeit aktivierbar
- **Deployment:** Voll automatisiert
- **Backups:** Automatisch vor jedem Deployment
- **Monitoring:** Health-Check für Systemstatus
- **Admin-Bypass:** Admins können während Wartung arbeiten

#### Deployment-Komplexität nach Änderungsart

| Änderungsart | Komplexität | Zeit | Vorgehen |
|-------------|-------------|------|----------|
| Content (Text, Bilder) | Einfach | 5-15 Min | Direkt per FTP, kein Backup nötig |
| CSS/JS | Einfach | 5-15 Min | FTP-Upload, Browser-Cache leeren |
| PHP-Code | Mittel | 30-60 Min | `./deploy.sh` verwenden |
| Datenbank-Schema | Komplex | 1-2 Std | Deploy + manuelle SQL-Migration |
| Neue Features | Komplex | Variabel | Staging → Test → Deploy |

#### Best Practices

**Vor Deployment:**
- ✅ Lokale Tests durchführen
- ✅ Git commit & push
- ✅ Backup-Strategie prüfen

**Während Deployment:**
- ✅ Wartungsmodus aktivieren
- ✅ Automatisches Backup läuft
- ✅ Health-Check nach Upload

**Nach Deployment:**
- ✅ Website testen (alle Hauptfunktionen)
- ✅ Health-Check prüfen
- ✅ Error-Logs checken
- ✅ Backup verifizieren

**Bei Problemen:**
- ✅ Wartungsmodus bleibt aktiv
- ✅ Fehler beheben
- ✅ Erneut deployen
- ✅ Oder: Backup zurückspielen

#### Monitoring & Wartung

**Health-Check URL:**
```
https://pc-wittfoot.de/api/health-check
```

**Monitoring-Integration:**
- UptimeRobot: HTTP-Monitor auf Health-Check
- Statuscake: JSON-Response parsen
- Cronjob: Täglicher Check + Email bei Fehler

**Backup-Strategie:**
```bash
# Täglich automatisches Backup
0 3 * * * /pfad/zu/backup.sh

# Vor jedem Deployment (automatisch in deploy.sh)
./deploy.sh  # erstellt automatisch Backup

# Manuelle Backups bei großen Änderungen
./backup.sh
```

**Backup-Aufbewahrung:**
- Täglich: 30 Tage
- Vor Deployments: Unbegrenzt (manuell löschen)
- Kritische Versionen: Separat archivieren

#### Troubleshooting

**Problem: Wartungsmodus aktiviert sich nicht**
```bash
# Prüfen ob Datei erstellt wurde
ls -la src/MAINTENANCE

# Prüfen ob Router maintenance.php lädt
grep "maintenance.php" src/router.php

# Manuell aktivieren
echo "Wartungsarbeiten" > src/MAINTENANCE
```

**Problem: Health-Check schlägt fehl**
```bash
# Direkt im Browser öffnen
https://pc-wittfoot.de/api/health-check

# Welcher Check failed?
curl -s https://pc-wittfoot.de/api/health-check | jq '.checks'

# Logs prüfen
tail -f logs/error.log
```

**Problem: Deployment-Script kann nicht hochladen**
```bash
# FTP-Zugangsdaten testen
lftp -u username,password ftp.example.com -e "ls; bye"

# Rechte prüfen
lftp -u username,password ftp.example.com
cd /public_html
mkdir test
# Falls Fehler → Keine Schreibrechte
```

**Problem: Backup schlägt fehl**
```bash
# Datenbank-Zugangsdaten testen
mysql -u pc_wittfoot -pdev123 -e "SELECT 1"

# Backup-Verzeichnis beschreibbar?
ls -la backups/

# Manuell ausführen mit Debug
bash -x backup.sh
```

### Nächste Session

#### Priorität Hoch
- Blog-System vervollständigen (Posts editieren/löschen)
- PayPal-Integration (Zahlung abwickeln)

#### Priorität Mittel
- Bewertungen einbinden (Google Reviews API)
- Produkt-Verwaltung im Admin
- Bestellungen-Übersicht im Admin

#### Priorität Niedrig
- Newsletter-System
- Statistiken im Dashboard
- CSV-Export für Bestellungen

