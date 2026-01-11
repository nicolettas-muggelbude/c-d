# PC-Wittfoot UG - Projekt-Dokumentation

> **Hinweis:** Diese Dokumentation wurde am 2026-01-10 reorganisiert.
> Session-Logs wurden in `docs/session-logs/` archiviert für bessere Übersichtlichkeit.

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
  - Cronjobs (HelloCash-Sync alle 5 Min)
  - Production-Server: www116.c.artfiles.de
  - Live-URL: https://pc-wittfoot.de

### Verlauf
- **[09 - Session-Log](docs/09-session-log.md)**
  - Chronologische Dokumentation aller Entwicklungs-Sessions
  - Detaillierte Implementierungsschritte
  - Git-Commits

- **Session-Logs (Archiv)**
  - [2026-01-04](docs/session-logs/2026-01-04.md) - Terminbuchungs-System komplett
  - [2026-01-05](docs/session-logs/2026-01-05.md) - WCAG 2.1 AA Compliance & Phase 1 Abschluss

---

## 🎯 Aktueller Stand (2026-01-11)

### ✅ Abgeschlossen

**Phase 1: Planung** (2025-12-31)
- Anforderungsanalyse
- Technische Architektur
- Design-System

**Phase 2: Entwicklung** (ABGESCHLOSSEN 2026-01-05)
- ✅ **Terminbuchungs-System (ABGESCHLOSSEN)**
  - Flatpickr Datepicker mit ausgegrauten ausgebuchten Tagen
  - API-Endpoint für vollständig ausgebuchte Tage
  - Server-seitige Doppelbuchungs-Prüfung
  - Verfügbare Slots Anzeige (X von Y frei)
  - Wochentag-Validierung (Di-Fr für fixed, Di-Sa für walkin)
  - Walk-in Slot-Zuweisung mit Rotation (14:00, 15:00, 16:00)
  - Samstags-Öffnungszeiten (12:00-16:00, 4 Slots)
  - Kunden-Self-Service (Magic Link, Ändern, Stornieren)
  - Walk-in Gruppierung in Admin-Kalender
  - Service-Filter (Fernwartung/Hausbesuch nur für feste Termine)
  - Zeitsperre nur für feste Termine (Walk-ins jederzeit änderbar)
  - Email-System mit HTML-Templates
  - Admin-Session 12h
  - Security-Testing dokumentiert
- ✅ HelloCash Integration (Kunden, Kassenanbindung)
  - Korrigierte Duplikaterkennung (nur bei Vorname UND Email identisch)
- ✅ Email-System mit PHPMailer
- ✅ Deployment-System mit Wartungsmodus
- ✅ Shop-System mit CSV-Import
- ✅ Produktverwaltung (Kategorien, Steuersätze, Details)
- ✅ Detaillierte Produktansicht mit Galerie
- ✅ Darkmode-Support (vollständig)
- ✅ **WCAG 2.1 Level AA Compliance** (Startseite, Leistungen, Blog, Termin, Kontakt)

**Phase 3: Production Deployment** (ABGESCHLOSSEN 2026-01-11)
- ✅ **Server-Setup & Deployment**
  - SSH-Zugang konfiguriert (www116.c.artfiles.de)
  - Git Repository auf Production geklont
  - Apache .htaccess mit Routing konfiguriert
  - HTTPS-Redirect eingerichtet
  - Content Security Policy für www/non-www Varianten
- ✅ **Datenbank-Migration**
  - Vollständige 1:1 Migration (22 Tabellen, 314 KB)
  - Export-Script via PHP (export-database-php.php)
  - Production-Konfiguration (config.production.php)
- ✅ **Performance-Optimierung**
  - HelloCash-Sync asynchron per Cronjob (statt blocking)
  - Buchungs-Response von 8-9s auf < 1s reduziert
  - Button-Disable Funktion gegen Doppelbuchungen
- ✅ **HelloCash Cronjob**
  - `/cronjobs/sync-hellocash.php` implementiert
  - Crontab eingerichtet (alle 5 Minuten)
  - Logging nach `/logs/cronjob.log`
  - Synchronisiert max. 50 Buchungen pro Lauf
- ✅ **Bug-Fixes Production**
  - Router-Fehler behoben (Navigation funktioniert)
  - CSS MIME-Type Fehler behoben
  - BASE_URL korrekt gesetzt (ohne www)
  - Email-Template Preview verfügbar
  - Admin-Login funktionsfähig

### 🚧 In Arbeit

- Vollständiges Production-Testing (nach Pause)

### 📋 Geplant

**Priorität Hoch:**
- **Datenschutzerklärung erstellen** (noch nicht vorhanden!)
- CSV-Import testen mit echten Lieferanten-Daten
- PayPal-Integration (Zahlung abwickeln)

**Priorität Mittel:**
- HelloCash-Sync für eigene Artikel (Phase 3)
- Dropshipping-API-Integration
- Bewertungen einbinden (Google Reviews API)
- Impressum erstellen/aktualisieren
- Cronjob-Script für CSV-Import

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

### Widerrufsrecht & Rechtliches (Shop)

**B2B-Ausschluss:**
- Widerrufsrecht gilt NUR für Verbraucher (§ 312g BGB)
- Geschäftskunden haben KEIN gesetzliches Widerrufsrecht
- Muss deutlich kommuniziert werden (Warnbox auf Widerrufsbelehrung)

**Ausnahmen vom Widerrufsrecht (§ 312g Abs. 2 BGB):**
1. **Geschäftskunden (B2B):** Kauf für gewerbliche/freiberufliche Zwecke
2. **Individuell konfigurierte Systeme:** Nach Kundenspezifikation zusammengestellt
3. **Versiegelte Software:** Wenn Versiegelung nach Lieferung entfernt wurde
4. **Entsiegelte Datenträger:** Audio/Video/Software mit entfernter Versiegelung
5. **Vollständig erbrachte Dienstleistungen:** Mit ausdrücklicher Zustimmung vor Fristablauf

**ESD-Keys (ESET Sicherheitssoftware):**
- Refurbished-Geräte werden mit ESET-Lizenz (ESD-Key) ausgeliefert
- Bei Aktivierung ist Lizenz verbraucht → hoher Aufwand bei Rücksendung
- **Lösung:** Deutlicher Hinweis in Bestellbestätigungs-Email
- **Wichtig:** Kunde MUSS informiert werden, BEVOR er ESET aktiviert

**Email-Hinweis bei Bestellung (TODO):**
```
⚠️ WICHTIGER HINWEIS ZUR ESET-SICHERHEITSSOFTWARE

Ihr Gerät wird mit einer ESET Security Lizenz ausgeliefert.

BITTE BEACHTEN:
- Aktivieren Sie ESET NICHT sofort nach Erhalt
- Testen Sie das Gerät zunächst ohne ESET-Aktivierung
- Windows Defender bietet während der Testphase Grundschutz
- Bei Aktivierung der ESET-Lizenz erlischt das Widerrufsrecht
  für die Software (§ 312g Abs. 2 Nr. 6 BGB)

Das Widerrufsrecht für das Gerät selbst bleibt davon unberührt.
```

**Checkout-Implementation (TODO):**
```php
// Pflicht-Checkboxen vor Bestellung:

☑ Ich bin Unternehmer und kaufe für gewerbliche Zwecke.
   Mir ist bekannt, dass kein Widerrufsrecht besteht. (B2B-Käufe)

☑ Mir ist bekannt, dass bei individuell konfigurierten
   Systemen das Widerrufsrecht ausgeschlossen ist. (Custom Builds)

☑ Ich stimme der sofortigen Leistungserbringung zu und bin mir
   bewusst, dass mein Widerrufsrecht erlischt. (Dienstleistungen)

☑ Mir ist bekannt, dass bei Aktivierung der mitgelieferten ESET-Lizenz
   das Widerrufsrecht für die Software erlischt. (Refurbished mit ESET)
```

**Technische Umsetzung:**
- Checkboxen im Checkout-Formular (vor "Jetzt kaufen")
- Validierung: Erforderliche Checkboxen müssen angehakt sein
- Speicherung der Zustimmung mit Bestellung
- Ausschlüsse VOR Vertragsabschluss kommunizieren
- In Bestellbestätigung erwähnen

**Dateien:**
- `/src/pages/widerruf.php` - Vollständige Widerrufsbelehrung
- B2B-Hinweis in Warnbox (Orange)
- Ausnahmen-Liste detailliert aufgeführt
- Muster-Widerrufsformular enthalten

**Rechtlicher Hinweis:**
Für wasserdichte Formulierungen rechtliche Beratung empfohlen!

### Stornierung & AGB (B2B vs. B2C)

**B2C (Verbraucher):**
- Gesetzliches Widerrufsrecht 14 Tage (kann NICHT ausgeschlossen werden)
- Kunde trägt nur Rücksendekosten
- Keine Stornogebühren erlaubt

**B2B (Geschäftskunden):**
- KEIN gesetzliches Widerrufsrecht
- Stornierung grundsätzlich ausgeschlossen (zulässig)
- Kulanz-Regelung optional

**AGB-Formulierung für B2B-Stornierung:**
```
§X Stornierung und Rücktritt (Geschäftskunden)

1. Geschäftskunden haben kein gesetzliches Widerrufsrecht.
   Stornierungen nach Vertragsabschluss sind grundsätzlich
   ausgeschlossen.

2. Kulanz-Stornierung:
   Auf Kulanz kann eine Bestellung bis zum Versand storniert werden.

   Bei Stornierung fallen an:
   - Bearbeitungsgebühr: 2% des Brutto-Warenwertes
   - Bereits angefallene Versandkosten
   - Transaktionsgebühren des Zahlungsdienstleisters

3. Nach Versand:
   Eine Stornierung ist nur noch nach Rücksprache möglich.
   Zusätzlich zu den o.g. Gebühren fallen die Rücksendekosten an.

4. Die Erstattung erfolgt abzüglich aller angefallenen Kosten.
```

**Wichtig:**
- PayPal-Gebühren NICHT als separate Position ausweisen
- Stattdessen: "Transaktionsgebühren des Zahlungsdienstleisters"
- Nur tatsächlich angefallene Kosten berechnen
- Bei Vorauskasse/Rechnung: keine Transaktionsgebühren

**TODO für Shop-Entwicklung:**
- Kundenstatus (B2B/B2C) bei Bestellung erfassen
- AGB-Checkbox mit korrektem AGB-Link (B2B vs. B2C)
- Stornogebühren automatisch berechnen
- In Bestellbestätigung auf Storno-Regelung hinweisen

---

## 🚀 Nächste Session: Prioritäten

1. **Production-System vollständig testen** (Buchung, Email, HelloCash, Mobile)
2. **Datenschutzerklärung erstellen** (rechtlich erforderlich!)
3. **Blog-System überarbeiten** (Übersicht, Post-Detail, Admin-Verwaltung)
4. **PayPal-Integration fertigstellen** (Zahlungsabwicklung)
5. **Cronjob für CSV-Import** (Automatisierung)
6. **Shop für Production vorbereiten** (Phase 4)

---

## 📞 Kontakt & Support

- **Projekt:** PC-Wittfoot UG Online-Shop & Terminbuchung
- **Dokumentation:** Stand 2026-01-10
- **Session-Logs:** Archiviert in `docs/session-logs/` (2026-01-04, 2026-01-05)

---

## 📅 Session-Logs

Detaillierte Entwicklungs-Logs wurden in separate Dateien ausgelagert:

- **[2026-01-04](docs/session-logs/2026-01-04.md)** - Terminbuchungs-System vollständig implementiert
- **[2026-01-05](docs/session-logs/2026-01-05.md)** - WCAG 2.1 Level AA Compliance & Phase 1 Abschluss
- **[2026-01-11](docs/session-logs/2026-01-11.md)** - Production Deployment & Performance-Optimierung

---

## 🔧 Session 2026-01-11 (Fortsetzung): Cronjob-Fixes Production

### Problem
Nach Production-Deployment funktionierten die Cronjobs nicht:
- ❌ HelloCash-Sync lief nicht (Kunden wurden nicht synchronisiert)
- ❌ 24h-Erinnerungs-Mails kamen nicht an
- ❌ 1h-Erinnerungs-Mails kamen nicht an
- ✅ Bestätigungs-Mails funktionierten (werden sofort versendet)

### Ursache
**Falscher PHP-Pfad im Cronjob:**
- Konfiguriert: `/usr/bin/php` ❌
- Korrekt: `/usr/local/bin/php` ✅

**Fehlende Cronjobs:**
- 24h-Erinnerungs-Cronjob nicht eingerichtet
- 1h-Erinnerungs-Cronjob nicht eingerichtet

### Lösung

**Cronjob-Konfiguration auf Production (korrekt):**

```cron
# HelloCash-Sync (alle 5 Minuten)
*/5 * * * * /usr/local/bin/php /home/www/doc/28552/dcp285520007/pc-wittfoot.de/www/cronjobs/sync-hellocash.php >> /home/www/doc/28552/dcp285520007/pc-wittfoot.de/www/logs/cronjob.log 2>&1

# 24-Stunden Erinnerung (täglich um 10:00 Uhr)
0 10 * * * /usr/local/bin/php /home/www/doc/28552/dcp285520007/pc-wittfoot.de/www/src/cron/send-reminder-24h.php >> /home/www/doc/28552/dcp285520007/pc-wittfoot.de/www/logs/cronjob.log 2>&1

# 1-Stunde Erinnerung (stündlich zur vollen Stunde)
0 * * * * /usr/local/bin/php /home/www/doc/28552/dcp285520007/pc-wittfoot.de/www/src/cron/send-reminder-1h.php >> /home/www/doc/28552/dcp285520007/pc-wittfoot.de/www/logs/cronjob.log 2>&1
```

### Ergebnis

✅ **Alle Cronjobs funktionieren:**
1. HelloCash-Sync läuft alle 5 Minuten
2. 24h-Erinnerungen werden täglich um 10:00 Uhr versendet
3. 1h-Erinnerungen werden stündlich versendet

✅ **Mail-System funktioniert korrekt:**
- Bestätigungs-Mails (Kunde + Admin) → **sofort**
- HelloCash-Sync → **verzögert (max. 5 Min.)**
- Erinnerungs-Mails → **automatisch per Cronjob**

### Testing
- ✅ Manuelle Tests aller 3 Cronjobs erfolgreich
- ✅ User-Tests für Terminbuchung erfolgreich
- ✅ Production-System vollständig funktionsfähig

---
