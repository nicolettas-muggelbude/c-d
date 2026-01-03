# Architektur & Technische Entscheidungen

## Inhaltsverzeichnis
- Technische Architektur-Entscheidungen
- Router-System
- Sicherheit (CSRF, XSS-Schutz)
- Formular-Validierung
- Barrierefreiheit
- Wichtige technische Entscheidungen

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

