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

- **[10 - Git-Workflow](docs/10-git-workflow.md)**
  - master vs. production Branch
  - Standard-Workflow (Entwicklung → Production)
  - Häufige Fehler und Lösungen
  - Cherry-pick vs. Merge
  - Deployment-Checkliste
  - Rollback-Strategie

### Verlauf
- **[09 - Session-Log](docs/09-session-log.md)**
  - Chronologische Dokumentation aller Entwicklungs-Sessions
  - Detaillierte Implementierungsschritte
  - Git-Commits

- **Session-Logs (Archiv)**
  - [2026-01-04](docs/session-logs/2026-01-04.md) - Terminbuchungs-System komplett
  - [2026-01-05](docs/session-logs/2026-01-05.md) - WCAG 2.1 AA Compliance & Phase 1 Abschluss
  - [2026-01-11](docs/session-logs/2026-01-11.md) - Production Deployment & Performance-Optimierung
  - [2026-01-12](docs/session-logs/2026-01-12.md) - Kritische Bugfixes & Admin-Login
  - [2026-01-17](docs/session-logs/2026-01-17.md) - Termintyp-abhängige Kalenderanzeige
  - [2026-01-18](docs/session-logs/2026-01-18.md) - SEO, Google Maps, Blog Markdown & Suche

---

## 🎯 Aktueller Stand (2026-01-18)

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

**Phase 4: SEO & UX-Optimierungen** (2026-01-18)
- ✅ **Bing Sitemap-Fix**
  - 404-URLs aus Sitemap entfernt (`/ueber-uns`)
  - Datumsangaben aktualisiert (2026-01-18)
  - Timeout-Problem behoben
- ✅ **Google Maps Integration**
  - Eingebettete Karte auf Kontaktseite
  - "Route planen" Button (iOS & Android kompatibel)
  - Footer-Link auf allen Seiten
- ✅ **Barrierefreiheit (WCAG 2.1 Level AA)**
  - aria-labels für alle externen Links
  - Messenger-Links (Telegram, Signal, WhatsApp)
  - Semantische Verbesserungen (Footer H2 statt H3)
  - Map-Section als Landmark
- ✅ **Content-Security-Policy**
  - Google Fonts (Noto Color Emoji) erlaubt
  - Google Maps iframe erlaubt
  - Minimale Security-Erweiterung
- ✅ **Git-Workflow Dokumentation**
  - Deployment-Befehle korrigiert
  - Best Practices aktualisiert
- ✅ **Blog-System: Markdown & Suche**
  - Markdown-Editor mit Live-Vorschau im Admin
  - FULLTEXT-Suche mit Relevanz-Scoring
  - RSS-Feed (`/blog/feed.xml`) und XML-Sitemap
  - Parsedown-Bibliothek für Markdown-Rendering
  - Schema.org BlogPosting Markup für SEO
  - Responsive Suchfeld mit Keine-Ergebnisse Hinweisen

### 🚧 In Arbeit

- Bing Sitemap-Indexierung (eingereicht, warte auf Crawl)
- Blog Migration 021 auf Production ausführen (FULLTEXT-Index)

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

1. **Blog-Migration auf Production** (FULLTEXT-Index für Suche)
2. **Blog lokal testen** (Suche, Markdown, RSS-Feed)
3. **Datenschutzerklärung erstellen** (rechtlich erforderlich!)
4. **PayPal-Integration fertigstellen** (Zahlungsabwicklung)
5. **Cronjob für CSV-Import** (Automatisierung)
6. **Shop für Production vorbereiten** (Phase 4)

---

## 📞 Kontakt & Support

- **Projekt:** PC-Wittfoot UG Online-Shop & Terminbuchung
- **Dokumentation:** Stand 2026-01-18
- **Session-Logs:** Archiviert in `docs/session-logs/`

---

## 📅 Session-Logs

Detaillierte Entwicklungs-Logs wurden in separate Dateien ausgelagert:

- **[2026-01-04](docs/session-logs/2026-01-04.md)** - Terminbuchungs-System vollständig implementiert
- **[2026-01-05](docs/session-logs/2026-01-05.md)** - WCAG 2.1 Level AA Compliance & Phase 1 Abschluss
- **[2026-01-11](docs/session-logs/2026-01-11.md)** - Production Deployment & Performance-Optimierung

---

## 📡 Production-Server Details

**Host:** www116.c.artfiles.de
**User:** dcp285520007
**Web Root:** `/home/www/doc/28552/dcp285520007/pc-wittfoot.de/www`
**Database:** sql116.c.artfiles.de / db285520001
**Live URL:** https://pc-wittfoot.de
**PHP-Pfad:** `/usr/local/bin/php`

**Git Workflow:**
> **⚠️ WICHTIG:** Siehe **[Git-Workflow Dokumentation](docs/10-git-workflow.md)** für detaillierte Anweisungen!

Kurzversion:
```bash
# 1. Auf master entwickeln und committen
git checkout master
git add src/pfad/zur/datei.php
git commit -m "Feature: Beschreibung"

# 2. Nach production übertragen
git checkout production
git merge master  # oder: git cherry-pick COMMIT_HASH

# 3. Beide Branches pushen
git push origin master
git push origin production

# 4. Auf Production-Server deployen
ssh dcp285520007@www116.c.artfiles.de
cd /home/www/doc/28552/dcp285520007/pc-wittfoot.de/www
git stash push -m "Production-Config"
git pull --no-rebase --no-edit origin production
git stash pop
```

---

## 🚨 SICHERES DEPLOYMENT-KONZEPT (nach Incident 2026-01-11)

### Was ist schiefgelaufen?

**Incident:** HTML-Signatur-Feature-Deployment hat Production zerstört:
- ❌ `config.php` wurde überschrieben → DB-Verbindung verloren
- ❌ `.htaccess` wurde überschrieben → Internal Server Error
- ❌ `Security.php` wurde überschrieben → CSP-Probleme
- ❌ Mehrere Stunden Downtime
- ❌ Mehrfache Rollback-Versuche fehlgeschlagen

**Root Cause:** Production-spezifische Konfigurationsdateien wurden nicht von Code getrennt.

---

### ✅ NEUE DEPLOYMENT-STRATEGIE

## 1. Trennung: Code vs. Konfiguration

**Prinzip:** Production-spezifische Dateien dürfen NIE in Git committed werden!

### Production-spezifische Dateien (NICHT in Git):

```
.htaccess                           # Apache-Konfiguration (Server-spezifisch)
src/core/config.php                 # DB-Credentials & URLs (Environment-spezifisch)
src/core/Security.php (teilweise)   # CSP mit Domain-Namen
logs/                              # Log-Dateien
```

### Code-Dateien (IN Git):

```
src/**/*.php                        # Alle Logik-Dateien
database/migrations/                # DB-Migrationen
assets/                            # CSS, JS, Images
```

---

## 2. .gitignore einrichten

**Datei:** `/.gitignore`

```gitignore
# Production-spezifische Konfiguration
.htaccess
src/core/config.php
src/core/Security.php

# Logs
logs/*.log
*.log

# Temporary files
.server.pid
*.tmp
*.cache

# Session files
/tmp/

# Backups
*.backup
*.bak
```

---

## 3. Separate Config-Dateien

### Struktur:

```
src/core/
├── config.php              # NICHT in Git (Production-spezifisch)
├── config.example.php      # IN Git (Template für neue Umgebungen)
├── config.local.php        # NICHT in Git (Lokale Entwicklung)
└── config.template.php     # IN Git (Dokumentation aller Optionen)
```

### config.example.php (Template):

```php
<?php
// ===================================
// BEISPIEL-KONFIGURATION
// Diese Datei kopieren nach config.php
// und mit echten Credentials füllen
// ===================================

// Datenbank
define('DB_HOST', 'localhost');        // Production: sql116.c.artfiles.de
define('DB_NAME', 'your_database');
define('DB_USER', 'your_user');
define('DB_PASS', 'your_password');

// URLs
define('BASE_URL', 'http://localhost:8000');  // Production: https://pc-wittfoot.de

// Email
define('MAIL_FROM', 'noreply@example.com');
define('MAIL_ADMIN', 'admin@example.com');
```

---

## 4. SICHERER DEPLOYMENT-WORKFLOW

### Phase 1: Lokale Entwicklung

```bash
# 1. Feature entwickeln
git checkout -b feature/neue-funktion

# 2. Testen (WICHTIG!)
php -S localhost:8000 server.php
# → Manuell testen im Browser
# → Alle Funktionen durchklicken

# 3. Commit
git add src/
git commit -m "Feature: Beschreibung"

# 4. Merge zu master
git checkout master
git merge feature/neue-funktion
```

### Phase 2: Vorbereitung für Production

```bash
# 1. Production Branch aktualisieren
git checkout production
git merge master

# 2. STOPP - Nicht sofort pushen!

# 3. Prüfen welche Dateien sich geändert haben
git diff origin/production --name-only

# 4. WICHTIG: Falls config.php, .htaccess oder Security.php dabei sind:
git reset HEAD src/core/config.php
git reset HEAD .htaccess
git reset HEAD src/core/Security.php

# 5. Erst JETZT pushen
git push origin production
git checkout master
```

### Phase 3: Production Deployment

```bash
# SSH auf Production-Server
ssh dcp285520007@www116.c.artfiles.de
cd /home/www/doc/28552/dcp285520007/pc-wittfoot.de/www

# BACKUP ERSTELLEN (WICHTIG!)
cp src/core/config.php ../backups/config.php.$(date +%Y%m%d_%H%M%S)
cp .htaccess ../backups/.htaccess.$(date +%Y%m%d_%H%M%S)

# Git Status prüfen
git status

# Falls lokale Änderungen an Production-Dateien:
git stash push -m "Production-Config vor Pull $(date)"

# Code pullen
git pull origin production

# Production-Config wiederherstellen
git stash pop

# Falls Konflikte: Manuell lösen (Production-Werte behalten!)

# Testen
curl https://pc-wittfoot.de/src/router.php | head -20
```

### Phase 4: Datenbank-Migration (falls erforderlich)

```bash
# NUR wenn DB-Schema-Änderungen:

# 1. Migration-Script hochladen (bereits in Git)
ls -la database/migrations/

# 2. BACKUP der Production-DB erstellen
# (über phpMyAdmin oder Hosting-Panel)

# 3. Migration ausführen
/usr/local/bin/php migrate-production-XXX.php

# 4. Verifizieren
/usr/local/bin/php -r "
require_once 'src/core/config.php';
\$db = Database::getInstance();
\$tables = \$db->query('SHOW TABLES');
print_r(\$tables);
"
```

---

## 5. ROLLBACK-STRATEGIE

### Wenn etwas schiefgeht:

```bash
# AUF DEM PRODUCTION-SERVER:

# Schritt 1: Letzten funktionierenden Commit identifizieren
git log --oneline -10

# Schritt 2: Rollback (Hard Reset)
git reset --hard COMMIT_HASH  # z.B. fef6dae

# Schritt 3: Production-Config aus Backup wiederherstellen
cp ../backups/config.php.TIMESTAMP src/core/config.php
cp ../backups/.htaccess.TIMESTAMP .htaccess

# Schritt 4: Testen
curl https://pc-wittfoot.de | head -20

# Schritt 5: Falls DB-Migration durchgeführt wurde:
# → DB-Backup wiederherstellen (über Hosting-Panel)
```

---

## 6. DEPLOYMENT-CHECKLISTE

### VOR jedem Production-Deployment:

- [ ] **Lokale Tests:** Feature vollständig getestet?
- [ ] **Git Status:** Nur relevante Dateien staged?
- [ ] **Production-Dateien:** config.php, .htaccess NICHT in Commit?
- [ ] **Backup erstellt:** Production-Config gesichert?
- [ ] **Migration vorbereitet:** DB-Änderungen dokumentiert?
- [ ] **Rollback-Plan:** Letzter funktionierender Commit bekannt?

### NACH jedem Production-Deployment:

- [ ] **Website lädt:** https://pc-wittfoot.de erreichbar?
- [ ] **CSS/JS laden:** Assets werden korrekt ausgeliefert?
- [ ] **Login funktioniert:** Admin-Bereich erreichbar?
- [ ] **DB-Verbindung:** Keine Datenbankfehler?
- [ ] **Logs prüfen:** `tail -20 logs/error.log` - neue Fehler?

---

## 7. LESSONS LEARNED

### Was NICHT funktioniert hat:

❌ **Direktes `git pull` ohne Stash/Backup**
- Überschreibt Production-Config
- Verlust von DB-Credentials
- Website-Downtime

❌ **Experimentieren direkt auf Production**
- Mehrfache .htaccess-Änderungen ohne Test
- Keine Möglichkeit zurückzugehen
- Zeitverschwendung

❌ **Keine Trennung Code/Config**
- Production-spezifische Dateien in Git
- Merge-Konflikte bei jedem Deployment

### Was FUNKTIONIERT:

✅ **Separate Config-Dateien**
- `config.example.php` in Git
- `config.php` auf Server (nicht in Git)
- Klar dokumentierte Unterschiede

✅ **Git Stash vor Pull**
- Production-Änderungen sichern
- Pull durchführen
- Production-Werte wiederherstellen

✅ **Backup vor Änderungen**
- Immer Kopie der funktionierenden Version
- Schneller Rollback möglich

✅ **Lokales Testing**
- Alle Features lokal testen
- Production nur für fertige Features

---

## 8. PRODUCTION-DATEIEN DOKUMENTATION

### .htaccess (funktionierende Version)

```apache
RewriteEngine On

# HTTPS Redirect
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Statische Assets ZUERST
RewriteRule ^assets/(.*)$ src/assets/$1 [L]
RewriteRule ^favicon\.(.*)$ src/favicon.$1 [L]

# Router nur für nicht-existierende Dateien
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ src/router.php?route=$1 [L,QSA]

DirectoryIndex src/router.php
Options -Indexes
```

### config.php (Production-Template)

```php
<?php
// Production Database
define('DB_HOST', 'sql116.c.artfiles.de');
define('DB_NAME', 'db285520001');
define('DB_USER', 'dcp285520007');
define('DB_PASS', 'SECRET');  // Aus Hosting-Panel

// Production URLs
define('BASE_URL', 'https://pc-wittfoot.de');
define('MAIL_FROM', 'noreply@pc-wittfoot.de');
define('MAIL_ADMIN', 'admin@pc-wittfoot.de');

// Rest aus config.example.php kopieren
```

---

## 9. ZUKÜNFTIGE DEPLOYMENTS

### Neue Features implementieren:

1. **Lokal entwickeln** (auf Feature-Branch)
2. **Lokal testen** (alle Funktionen prüfen)
3. **Zu master mergen**
4. **Auf production mergen** (Production-Dateien ausschließen!)
5. **GitHub pushen**
6. **Production-Backup erstellen**
7. **Git stash auf Production**
8. **Git pull auf Production**
9. **Production-Config wiederherstellen**
10. **Testen**
11. **Bei Fehler: Rollback mit git reset**

### HTML-Signatur Feature (Retry nach Fix):

**NICHT mehr direkt deployen!**

Stattdessen:
1. Lokal vollständig testen
2. Migration-Script lokal testen
3. Backup auf Production
4. Code deployen (OHNE config.php zu überschreiben!)
5. Migration auf Production ausführen
6. Testen
7. Bei Fehler: Rollback + DB-Restore

---

**NIEMALS WIEDER:**
- ❌ Direkt auf Production experimentieren
- ❌ Mehrfache Änderungen ohne Backup
- ❌ Production-Config überschreiben
- ❌ Deployment ohne lokale Tests

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

## 🔧 Session 2026-01-12: Kritische Bugfixes nach Terminal-Absturz

### Behobene Fehler

**1. booking_end_time: Leerer String zu NULL konvertiert**
- **Problem:** Leere Strings ('') für `booking_end_time` verursachten MySQL-Fehler
- **Fehler:** `SQLSTATE[22007]: Invalid datetime format: 1292 Incorrect time value: ''`
- **Lösung:** `!empty()` Check in `src/admin/admin/booking-calendar-v2.php` (Zeile 24-25)
- **Datei:** `src/admin/admin/booking-calendar-v2.php`

**2. hellocash_user_id zu hellocash_customer_id umbenannt**
- **Problem:** Code verwendete falsche Spaltenbezeichnung (`hellocash_user_id` statt `hellocash_customer_id`)
- **Fehler:** `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'hellocash_user_id'`
- **Betroffene Dateien:**
  - `src/admin/booking-calendar-v2.php` (SQL + HTML + JavaScript)
  - `src/admin/booking-week.php` (HTML + JavaScript)
  - `src/admin/admin/booking-calendar-v2.php` (HTML + JavaScript)
  - `src/admin/admin/booking-week.php` (HTML + JavaScript)

**3. HelloCash Cronjob: Fallback für fehlende Nachnamen**
- **Problem:** Cronjob schlug alle 5 Minuten fehl bei Kunden ohne Nachname
- **Fehler:** `HelloCash createUser Error: user_surname oder user_company ist erforderlich`
- **Lösung:** Fallback-Platzhalter '.' wenn weder Nachname noch Firma vorhanden
- **Datei:** `cronjobs/sync-hellocash.php` (Zeile 50-57)
- **Auswirkung:** Alle ausstehenden HelloCash-Synchronisationen werden nun erfolgreich durchgeführt

### Commit
```
Fix: HelloCash-Synchronisation - Drei kritische Fehler behoben

1. booking_end_time: Leere Strings werden nun zu NULL konvertiert
2. hellocash_user_id zu hellocash_customer_id umbenannt
3. HelloCash Cronjob: Fallback für fehlende Nachnamen

Co-Authored-By: Claude Sonnet 4.5 <noreply@anthropic.com>
```

### Nächste Schritte
- ✅ Production-Deployment der Fixes (erfolgreich)
- ✅ HelloCash-Synchronisation funktioniert (62 Buchungen synchronisiert)
- ⚠️ Admin-Login-Problem entdeckt und behoben

---

## 🔧 Session 2026-01-12 (Fortsetzung): Admin-Login Session-Problem

### Problem
Nach dem HelloCash-Bugfix-Deployment funktionierte der Admin-Login nicht mehr:
- Redirect-Loop: `/admin` → `/admin/login.php` → `/admin/login.php` → ...
- "Ungültiger Sicherheitstoken" bei Login-Versuchen
- Sessions wurden nicht gespeichert

### Root Cause Analysis

**Problem 1: Redirect-Loop**
- `/admin/login.php` wurde vom Router als `param='login.php'` geparst
- Router matched nur auf `param === 'login'` (ohne .php)
- Landete im else-Block → lud `index.php` → `require_admin()` → redirect zu `/admin/login.php`
- Loop!

**Problem 2: Sessions nicht gespeichert**
- `session.save_path` war NICHT gesetzt in config.php
- PHP versuchte in `/var/lib/php/sessions` zu schreiben → Permission denied
- CSRF-Token konnte nicht in Session gespeichert werden
- Login schlug fehl auch mit korrektem Passwort

### Lösung

**1. Session Save Path konfiguriert** (`src/core/config.php`)
```php
// Zeile 64: Absoluter Pfad zu logs/
ini_set('session.save_path', '/home/www/doc/28552/dcp285520007/pc-wittfoot.de/www/logs');
```

**2. Redirect-Loop behoben** (`src/core/helpers.php`)
```php
// Vorher: redirect(BASE_URL . '/admin/login.php');
// Nachher: redirect(BASE_URL . '/admin/login');
```

**3. logs/ Permissions gesetzt**
```bash
chmod 777 logs/
```

**4. Admin-Passwort zurückgesetzt**
```bash
# Neues Passwort: admin123
password_hash('admin123', PASSWORD_DEFAULT)
```

### Ergebnis

✅ **Admin-Login funktioniert:**
- Sessions werden in `logs/sess_*` gespeichert
- CSRF-Token funktioniert korrekt
- Login erfolgreich
- Kein Redirect-Loop mehr

✅ **Production-System vollständig funktionsfähig:**
- HelloCash-Sync läuft (62 Buchungen synchronisiert)
- Admin-Login funktioniert
- Alle Bugfixes deployed

### Offene Punkte

⚠️ **Fehlende Datenbank-Tabelle:**
- `rate_limits` Tabelle existiert nicht
- Rate-Limiting funktioniert nicht
- TODO: Tabelle erstellen oder Feature deaktivieren

### Commits
```
a4f58b3 Fix: Admin-Login Redirect zu /admin/login statt /admin/login.php
f9a8b88 Fix: Admin-Login Session-Problem behoben (Production)
```

---

## 🔧 Session 2026-01-13: Git-Workflow Fixes & Social Media Meta-Tags

### Behobene Probleme

**1. Health-Check während Wartungsmodus**
- **Problem:** Health-Check Endpoint `/api/health-check` wurde im Wartungsmodus blockiert
- **Symptom:** Pre-Push-Hook Tests schlugen fehl (16/17, Test #12 failed)
- **Ursache:** `/api/health-check` war nicht in der Wartungsmodus-Whitelist
- **Lösung:** `/api/health-check` zu `src/core/maintenance.php` Whitelist hinzugefügt (Zeile 18)
- **Commit:** `b8d7eda` - Fix: Health-Check Endpoint während Wartungsmodus ermöglichen
- **Ergebnis:** ✅ Alle 17 Tests bestehen (100%)

**2. Fehlende Commits auf production Branch**
- **Problem:** OG-Image und LinkedIn Meta-Tags waren auf `master`, fehlten aber auf `production`
- **Symptom:**
  - `og-image.png` nicht auf Produktionsserver
  - `article:published_time` Tags fehlten im HTML
  - Doppelte Domain in og:image URL
- **Fehlende Commits:**
  - `b4ca840` - Add: Open Graph Image für Social Media Previews
  - `ea9a72b` - Add: LinkedIn Open Graph Meta-Tags
- **Ursache:** Commits wurden nur auf `master` erstellt, nicht nach `production` übertragen
- **Lösung:** `git cherry-pick` auf production Branch
- **Ergebnis:** ✅ Alle Features auf production verfügbar

**3. Doppelte Domain in OG-Image URL**
- **Problem:** `og:image` URL war `https://pc-wittfoot.dehttps://pc-wittfoot.de/assets/images/og-image.png`
- **Ursache:** `asset()` Funktion gibt bereits vollständige URL zurück, wurde aber mit Domain konkateniert
- **Lösung:** `'https://pc-wittfoot.de' . asset(...)` zu `asset(...)` geändert
- **Betroffene Dateien:**
  - `src/templates/header.php` (Zeile 16: og:image)
  - `src/templates/header.php` (Zeile 30: twitter:image)
- **Commit:** `c73fb6a` - Fix: Doppelte Domain in OG-Image URL entfernt
- **Ergebnis:** ✅ Korrekte URLs in Meta-Tags

### Deployment-Workflow

**Lokales System:**
```bash
# 1. Health-Check Fix auf production cherry-picken
git checkout production
git cherry-pick 2b368c0

# 2. OG-Image und LinkedIn Commits cherry-picken
git cherry-pick b4ca840  # OG-Image
git cherry-pick ea9a72b  # LinkedIn Meta-Tags

# 3. Doppelte Domain fixen
# ... Edit src/templates/header.php ...
git commit -m "Fix: Doppelte Domain in OG-Image URL entfernt"

# 4. Push
git push origin production
```

**Produktionsserver:**
```bash
cd /home/www/doc/28552/dcp285520007/pc-wittfoot.de/www
git pull --no-rebase --no-edit origin production
```

### Verifizierung auf Production

**og-image.png:**
```bash
$ ls -lh src/assets/images/og-image.png
-rw-r--r-- 1 dcp285520007 a28552 430K Jan 13 09:52 src/assets/images/og-image.png
```

**Meta-Tags:**
```bash
$ curl -s https://pc-wittfoot.de | grep -E "og:image|article:published_time"
<meta property="og:image" content="https://pc-wittfoot.de/assets/images/og-image.png?v=30">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="article:published_time" content="2024-01-01T00:00:00+01:00">
<meta property="article:modified_time" content="2026-01-13T10:46:04+01:00">
<meta property="article:author" content="PC-Wittfoot UG">
```

✅ **Alle Features funktionieren korrekt!**

### Neue Dokumentation

**[docs/10-git-workflow.md](docs/10-git-workflow.md)** erstellt:
- Standard-Workflow (master → production → deploy)
- Häufige Fehler und Lösungen
- Cherry-pick vs. Merge Strategien
- Deployment-Checkliste
- Rollback-Strategie
- Troubleshooting-Guide

### Commits
```
b8d7eda Fix: Health-Check Endpoint während Wartungsmodus ermöglichen
9ff7179 Add: Open Graph Image für Social Media Previews (cherry-picked)
85acdef Add: LinkedIn Open Graph Meta-Tags (cherry-picked)
c73fb6a Fix: Doppelte Domain in OG-Image URL entfernt
```

### Lessons Learned

⚠️ **Wichtige Erkenntnisse:**
1. **Beide Branches müssen aktuell gehalten werden** - Commits auf `master` automatisch auch auf `production` übertragen (merge oder cherry-pick)
2. **Pre-Push-Hook ist essentiell** - Fängt Fehler vor dem Deployment
3. **Whitelist für Wartungsmodus beachten** - Monitoring-Endpoints müssen auch im Wartungsmodus funktionieren
4. **URL-Helpers verstehen** - `asset()` gibt bereits vollständige URLs zurück
5. **Git-Workflow dokumentieren** - Vermeidet zukünftige Verwirrung

---
