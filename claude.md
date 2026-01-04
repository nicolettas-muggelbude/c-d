# PC-Wittfoot UG - Projekt-Dokumentation

> **Hinweis:** Diese Dokumentation wurde am 2026-01-02 thematisch reorganisiert.
> Die vollständige alte Version ist unter `docs/claude-backup-full.md` archiviert.

## 📚 Dokumentations-Index

### Grundlagen
- **[01 - Projekt-Setup](docs/01-projekt-setup.md)**
  - Projektübersicht & Anforderungen
  - Server-Spezifikationen (Reseller-PlanB)
  - Technischer Stack
  - Kernmerkmale des Unternehmens

- **[02 - Architektur](docs/02-architektur.md)**
  - Technische Architektur-Entscheidungen
  - Router-System (Dual-Mode)
  - Sicherheit (CSRF, XSS-Schutz)
  - Formular-Validierung
  - Barrierefreiheit (WCAG 2.1 Level AA)

- **[03 - Design-System](docs/03-design-system.md)**
  - Farbpalette (Option B - Neutral mit Akzenten)
  - Darkmode (Hybrid: automatisch + umschaltbar)
  - Design-Prinzipien
  - Typografie & Spacing

### Features
- **[04 - Terminbuchung](docs/04-terminbuchung.md)**
  - Booking-System Implementierung
  - Kalender-Integration
  - Service-Verwaltung
  - Zeitslot-Logik
  - Conditional Fields (JavaScript)

- **[05 - Shop & Produkte](docs/05-shop-produkte.md)**
  - Shop-System Implementierung
  - Produktverwaltung
  - CSV-Import System
  - Kategorienverwaltung mit Löschschutz
  - Steuersatz-Verwaltung (19%, 7%, 0%)
  - Detaillierte Produktinformationen (Zustand, Garantie, Bilder)
  - Produktdetailseite mit Galerie
  - Warenkorb & Bestellabwicklung

### Integrationen
- **[06 - HelloCash Integration](docs/06-hellocash-integration.md)**
  - API-Integration
  - Kundensuche & Synchronisation
  - Produkt-Sync
  - Kassenanbindung bei Bestellungen
  - Steuersätze & HelloCash
  - Siehe auch: [HELLOCASH_INTEGRATION.md](docs/HELLOCASH_INTEGRATION.md)

- **[07 - Email & Kommunikation](docs/07-email-kommunikation.md)**
  - Template-basiertes Email-System
  - PHPMailer SMTP-Integration
  - Email-Erinnerungen
  - Placeholders & Variablen
  - Siehe auch: [email-placeholders.md](docs/email-placeholders.md)

### Operations
- **[08 - Deployment & Operations](docs/08-deployment-ops.md)**
  - Deployment-System
  - Wartungsmodus
  - Update-Workflow
  - Git-basierte Deployments
  - Cronjobs

### Verlauf
- **[09 - Session-Log](docs/09-session-log.md)**
  - Chronologische Dokumentation aller Entwicklungs-Sessions
  - Detaillierte Implementierungsschritte
  - Git-Commits

---

## 🎯 Aktueller Stand (2026-01-04)

### ✅ Abgeschlossen

**Phase 1: Planung** (2025-12-31)
- Anforderungsanalyse
- Technische Architektur
- Design-System

**Phase 2: Entwicklung** (laufend)
- ✅ Terminbuchungs-System (vollständig)
  - Flatpickr Datepicker mit ausgegrauten ausgebuchten Tagen
  - API-Endpoint für vollständig ausgebuchte Tage
  - Server-seitige Doppelbuchungs-Prüfung
  - Verfügbare Slots Anzeige (X von Y frei)
  - Wochentag-Validierung (Di-Fr für fixed, Di-Sa für walkin)
- ✅ HelloCash Integration (Kunden, Kassenanbindung)
  - Korrigierte Duplikaterkennung (nur bei Vorname UND Email identisch)
- ✅ Email-System mit PHPMailer
- ✅ Deployment-System mit Wartungsmodus
- ✅ Shop-System mit CSV-Import
- ✅ Produktverwaltung (Kategorien, Steuersätze, Details)
- ✅ Detaillierte Produktansicht mit Galerie
- ✅ Darkmode-Support (vollständig)

### 🚧 In Arbeit

- Terminmodul: Umfassende Tests (Workflow, Email, HelloCash)
- PayPal-Integration (Zahlung abwickeln)
- Cronjob-Script für CSV-Import

### 📋 Geplant

**Priorität Hoch:**
- CSV-Import testen mit echten Lieferanten-Daten
- Produktions-Deployment vorbereiten
- **Datenschutzerklärung erstellen** (noch nicht vorhanden!)

**Priorität Mittel:**
- HelloCash-Sync für eigene Artikel (Phase 3)
- Dropshipping-API-Integration
- Bewertungen einbinden (Google Reviews API)
- Impressum erstellen/aktualisieren

**Priorität Niedrig:**
- Newsletter-System
- Statistiken im Dashboard
- CSV-Export für Bestellungen

---

## 🔧 Technischer Stack

**Backend:**
- PHP 8.2+ (ohne Framework)
- MySQL (Datenbank)
- Native Sessions (filesystembasiert)

**Frontend:**
- Vanilla JavaScript (ES6+)
- Custom CSS mit CSS-Variablen
- Responsive Design (Mobile-First)

**Integrationen:**
- HelloCash API (Kassensystem)
- PHPMailer (SMTP Email-Versand)
- PayPal API (geplant)

**Deployment:**
- Git-basiert via SSH
- Wartungsmodus-System
- Dateibasierte Konfiguration

---

## 📝 Wichtige Konzepte

### Hybrid-Produktsystem
1. **CSV-Import (Dropshipping):** Stündlicher Import, dynamisch zu HelloCash
2. **Ausstellungs-Artikel:** In HelloCash (Kategorie "Showroom"), mit Lagerbestand
3. **HelloCash-Artikel:** Manuell ausgewählte Artikel für Shop

### Preissystem
- Brutto-basiert (B2C)
- Flexible Steuersätze (19%, 7%, 0%)
- CSV-Import mit Aufschlag-Berechnung

### Sicherheit
- CSRF-Token-basiert
- XSS-Schutz durch Escaping
- Server-seitige + Client-seitige Validierung
- Prepared Statements

### Design
- Darkmode (automatisch + umschaltbar)
- Barrierefreiheit (WCAG 2.1 Level AA)
- Farbpalette: Neutral mit grünen Akzenten
- Mobile-First, responsive

---

## 🚀 Nächste Session: Prioritäten

1. **Terminmodul testen** (Kompletter Workflow, Email-Versand, HelloCash-Integration)
2. **Blog-System überarbeiten** (Übersicht, Post-Detail, Admin-Verwaltung)
3. **Production Branch erstellen** (Shop ausblenden, nur Terminbuchung live)
4. **PayPal-Integration fertigstellen** (Zahlungsabwicklung)
5. **Cronjob für CSV-Import** (Automatisierung)

---

## 📞 Kontakt & Support

- **Projekt:** PC-Wittfoot UG Online-Shop & Terminbuchung
- **Dokumentation:** Stand 2026-01-04
- **Backup:** `docs/claude-backup-full.md` (85KB, 2934 Zeilen)

---

## 📅 Session-Log 2026-01-04

### Terminbuchungs-System: Flatpickr & Verfügbarkeits-Validierung

**Aufgabenstellung:**
- Doppelbuchungen verhindern (zwei Buchungen auf gleichen Slot waren möglich)
- HelloCash: Duplikate bei gleicher Adresse, aber unterschiedlichen Namen/Emails vermeiden
- UX verbessern: Kunde soll VORHER sehen, welche Tage ausgebucht sind

**Implementierte Lösungen:**

1. **API-Endpoint für ausgebuchte Tage** (`src/api/fully-booked-dates.php`)
   - Berechnet slots_per_day × max_bookings_per_slot
   - Gibt alle vollständig ausgebuchten Tage zurück
   - Route in `router.php` registriert

2. **Flatpickr Datepicker Integration**
   - Ersetzt HTML5 `<input type="date">` durch Flatpickr
   - Lokale CSS-Datei (CSP-konform, kein CDN-Blocking)
   - Custom Styling in PC-Wittfoot Grün (#8BC34A)
   - Vollständiger Darkmode-Support (automatisch + manuell)
   - Deaktiviert ungültige Wochentage (Mo/So/Sa bei fixed, Mo/So bei walkin)
   - Deaktiviert vollständig ausgebuchte Tage
   - UX-Verbesserungen: Kalender-Icon, klarer Placeholder-Text, cursor: pointer

3. **Server-seitige Doppelbuchungs-Prüfung** (`src/api/booking.php`)
   - Prüft vor INSERT ob Slot noch verfügbar
   - Verwendet TIME_FORMAT() für korrekte Zeit-Vergleiche
   - HTTP 409 Conflict bei ausgebuchtem Slot

4. **HelloCash Duplikaterkennung korrigiert** (`src/core/HelloCashClient.php`)
   - Alt: Skip bei Email ODER Telefon
   - Neu: Skip nur bei Vorname UND Email identisch
   - Erlaubt unterschiedliche Personen im selben Haushalt

5. **Verfügbare Slots Anzeige** (`src/api/available-slots.php`)
   - Zeigt "X von Y frei" für jeden Zeitslot
   - TIME_FORMAT() Fix für korrekte Buchungszählung

**Technische Details:**
- Flatpickr v4.6.13 von cdnjs.cloudflare.com
- CSS lokal gespeichert in `src/assets/css/flatpickr.min.css`
- Deutsche Lokalisierung (l10n/de.js)
- Custom CSS für Corporate Design Integration

**Debugging-Erkenntnisse:**
- CSP blockierte externe Stylesheets → Lösung: lokale CSS-Datei
- HTML5 date input: keine Möglichkeit Tage zu deaktivieren
- Flatpickr: type="text" statt type="date" erforderlich
- Router: neue API-Endpoints müssen explizit registriert werden

**Git-Commit:**
- Alle Debug-Logs noch aktiv (für kommende Tests)

---

### Kunden-Self-Service: Terminverwaltung mit Magic Link

**Aufgabenstellung:**
- Kunden sollen ihre Buchungen eigenständig verwalten können
- Stornierung und Änderung ohne Admin-Eingriff ermöglichen
- Sicherer Zugriff ohne Login-System

**Implementierte Lösung: Magic Link (Option A)**

1. **Datenbank-Erweiterung** (`database/add-booking-manage-token.sql`)
   - Neue Spalte `manage_token` (VARCHAR 64) in `bookings` Tabelle
   - Unique Index für schnelle Token-Suche
   - Automatische Token-Generierung für bestehende Buchungen

2. **Token-Generierung bei Buchung** (`src/api/booking.php`)
   - `bin2hex(random_bytes(32))` für kryptographisch sicheren Token
   - Token wird bei jeder Buchung automatisch generiert
   - Token wird in API-Response zurückgegeben für Email-Versand

3. **Kunden-Verwaltungsseite** (`src/pages/termin-verwalten.php`)
   - Route: `/termin/verwalten?token=...`
   - Token-Validierung aus Query-String
   - Anzeige aller Buchungsdetails
   - Zeitbasierte Berechtigungsprüfung:
     - Änderung: >= 48h vor Termin
     - Stornierung: >= 24h vor Termin
   - Vollständiger Darkmode-Support
   - Responsive Design

4. **API-Endpoint Stornierung** (`src/api/booking-cancel.php`)
   - POST `/api/booking-cancel` mit Token
   - Validierung: Token, Status, Zeitlimit (24h)
   - Status-Update auf 'cancelled'
   - Email-Bestätigung an Kunde + Admin-Benachrichtigung
   - HTTP 409 bei Regelverletzung

5. **Router-Integration** (`src/router.php`)
   - Route `termin/verwalten` registriert
   - API-Route `booking-cancel` registriert

6. **Email-System erweitert** (`src/core/EmailService.php`)
   - Neue Service-Kategorien in Platzhalter-Map:
     - beratung, verkauf, fernwartung, hausbesuch
     - installation, diagnose, reparatur, sonstiges
   - Neuer Platzhalter `{manage_link}` für Magic Link
   - Automatische Link-Generierung aus Token

7. **Email-Templates aktualisiert** (`database/update-booking-email-templates.sql`)
   - Bestätigungs-Email: Management-Link-Sektion hinzugefügt
   - Neue Template: Stornierungsbestätigung (`cancellation`)
   - Klarstellung über Änderungs- und Stornierungsfristen

**Geschäftsregeln:**
- **Stornierung:** Bis 24 Stunden vor Termin online möglich
- **Änderung:** Bis 48 Stunden vor Termin online möglich (Placeholder, noch nicht implementiert)
- **Nach Fristablauf:** Kunde muss telefonisch/per Email kontaktieren

**Sicherheit:**
- 64 Zeichen Hex-Token (256 Bit Entropy)
- Token-basierte Authentifizierung ohne Session
- Unique Index verhindert Token-Kollisionen
- Server-seitige Zeitvalidierung

**Technische Details:**
- Magic Link Format: `http://localhost:8000/termin/verwalten?token={64-char-hex}`
- Token-Generierung: `bin2hex(random_bytes(32))`
- Zeitberechnung: DateTime-Differenz in Stunden
- Status-Werte: pending, confirmed, cancelled, completed

**Noch nicht implementiert:**
- Terminänderung (Datum/Zeit neu wählen)
- Fallback-Seite mit Buchungsnummer + Email-Suche
- QR-Code in Email für mobilen Zugriff

**Debugging-Erkenntnisse:**
- Email-Template benötigt `template_name` (NOT NULL)
- Token muss vor Email-Versand in DB gespeichert sein
- Router benötigt explizite Registrierung für neue Routes
- EmailService lädt Booking-Daten neu → Token muss in DB sein

**Git-Commit:**
- Bereit für Tests der kompletten Kunden-Self-Service Funktionalität

---

### Bugfixes & Verbesserungen: Terminverwaltung & sessionStorage

**Bugfix: Stornierungsbestätigung**
- **Problem:** Nach Stornierung wurde "Fehler: Keine Terminbuchung vorhanden" angezeigt
- **Lösung:** Separate Variable `$cancelled` eingeführt (Zeile 13)
- **Änderung:** Bei storniertem Termin wird Info-Box angezeigt statt Fehler-Box
- **Ergebnis:** "Kein Termin gebucht" in blauer Info-Box (ohne "Fehler:" Präfix)
- **Datei:** `src/pages/termin-verwalten.php:13,31,76-98`

**Feature: sessionStorage für Kontaktdaten (DSGVO-konform)**
- **Anforderung:** Kunde soll Daten nicht erneut eingeben müssen bei Reload/Tab-Wechsel
- **Lösung:** sessionStorage statt Cookie (keine Einwilligung erforderlich)
- **Implementierung:**
  - Automatisches Speichern bei jeder Eingabe (live während Tippens)
  - Automatisches Wiederherstellen beim Laden der Seite
  - Automatisches Löschen nach erfolgreicher Buchung
  - 12 Kontaktfelder werden gespeichert
- **Datenschutz:**
  - ✅ Keine Cookie-Einwilligung erforderlich (kein Cookie)
  - ✅ Daten nur lokal im Browser, keine Server-Übermittlung
  - ✅ Automatische Löschung bei Browser-Schließen
- **Datei:** `src/pages/termin.php:836-913,945`
- **Storage-Key:** `booking_customer_data`

**Gespeicherte Felder:**
- Vorname, Nachname, Firma (optional)
- E-Mail, Ländervorwahl, Mobilnummer, Festnetz (optional)
- Straße, Hausnummer, PLZ, Ort
- Bemerkungen (optional)

**UX-Verbesserung: Daten bei Neubuchung nach Stornierung**
- **Problem:** Nach Stornierung mussten Daten bei Neubuchung erneut eingegeben werden
- **Lösung:** Kundendaten werden beim Klick auf "Neuen Termin buchen" in sessionStorage gespeichert
- **Implementierung:**
  - JavaScript-Funktion `saveCustomerDataToStorage()` beim Button-Klick
  - Speichert 11 Kontaktfelder aus stornierter Buchung
  - Notizen werden absichtlich nicht übernommen (neue Buchung = neue Notizen)
  - Automatisches Vorausfüllen auf Terminbuchungs-Seite
- **User-Flow:** Stornierung → "Neuen Termin buchen" → Formular vorausgefüllt
- **Datei:** `src/pages/termin-verwalten.php:83,88-108`

**Vorgemerkt für künftige Entwicklung:**
- 📋 **Datenschutzerklärung erstellen** (aktuell nicht vorhanden)
  - Hinweis auf sessionStorage-Nutzung
  - Allgemeine DSGVO-Anforderungen
  - Cookie-Richtlinie (falls künftig Cookies verwendet werden)
  - Kontaktformular & Terminbuchungs-Daten
  - HelloCash-Integration (Kundendaten-Verarbeitung)
  - PHPMailer SMTP (Email-Versand)
