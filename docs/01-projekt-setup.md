# Projekt-Setup & Übersicht

## Inhaltsverzeichnis
- Projektübersicht
- Kernmerkmale des Unternehmens
- Online-Präsenz aktuell
- Projektstand & Anforderungen
- Server-Spezifikationen
- Technischer Stack

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

## Server-Spezifikationen (Professional Web Hosting)

### Hosting-Plan
- **Tarif:** Professional Web (Shared Hosting)
- **Monatliche Kosten:** 19,99 €
- **Vertragslaufzeit:** 1 Monat (flexibel)
- **Einrichtungsgebühr:** 0,00 €

### Verfügbare Ressourcen
- **Webspace:** 300 GB
- **Traffic:** Flatrate (unbegrenzt)
- **MySQL-Datenbanken:** 500 Datenbanken ✅
- **Domains inklusive:** 5
- **Zusatzdomains:** Unbegrenzt
- **Subdomains:** Unlimited
- **Mailbox Speicherplatz:** 300 GB
- **FTP-Zugänge:** 50
- **Cronjobs:** 100 ✅ (für CSV-Import, Email-Erinnerungen, Backups)
- **SSL:** Let's Encrypt / Shared SSL verfügbar

### Wichtige Features
- ✅ **SSH-Zugang vorhanden!** 🎉
  - Git-basiertes Deployment möglich
  - CLI-Tools nutzbar (php, mysql, composer)
  - Automatisierte Deployment-Scripts
  - Direkte Datenbank-Migration via SSH
- ✅ **Cronjobs:** 100 verfügbar für Automatisierung
- ✅ **Website Builder:** Verfügbar (wird aber nicht genutzt)
- ✅ **Kundenlimit pro CPU:** 10 (Shared Hosting)

### PHP & Datenbank
- **PHP:** Versionen bis PHP 8.5 verfügbar
  - **Empfehlung:** PHP 8.3 oder 8.2 verwenden ✅
  - PHP 8.5 möglich, aber sehr aktuell (ggf. Kompatibilitätsprüfung)
  - Aktive Sicherheitsupdates für 8.2 und 8.3
- **MySQL:** Version verfügbar (ausreichend für Projekt)
- **PHP Memory Limit:** Shared Hosting Standard (ausreichend)
- **Max Execution Time:** Standard (ausreichend)

### Deployment-Möglichkeiten mit SSH
**Mit SSH-Zugang können wir:**
1. **Git-basiertes Deployment**
   ```bash
   # Auf Server via SSH
   git clone <repo-url>
   git pull  # Für Updates
   ```

2. **Composer nutzen**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. **Automatisierte Deployment-Scripts**
   - SSH-basierte Deployment-Automation
   - Wartungsmodus per Script aktivieren/deaktivieren
   - Datenbank-Migrationen direkt ausführen

4. **Cronjobs einrichten** (100 verfügbar!)
   - CSV-Import automatisieren
   - Email-Erinnerungen
   - Automatische Backups
   - Cache-Bereinigung

### Konsequenzen für Entwicklung
- ✅ Entwicklung & Build lokal
- ✅ **Deployment via SSH/Git** (bevorzugt) oder FTP
- ✅ **CLI-Tools verfügbar** (php, mysql, git, composer)
- ✅ **Server-seitige Cronjobs möglich** (100 Slots)
- ✅ Schnellere Updates durch `git pull`
- ⚠️ Shared Hosting Einschränkungen (keine Root-Rechte, Ressourcen-Limits)

---

## Production Server-Details (artfiles.de)

### Server-Pfade
```bash
# Wichtigste Pfade für Deployment
Homeverzeichnis:      /home/www/doc/28552/
Web-Root:             /home/www/doc/28552/  # (vermutlich + subdomain)
PHP-CLI:              /usr/local/bin/php
Perl:                 /usr/bin/perl
MySQL-Tools:          /usr/bin/
Sendmail:             /usr/sbin/sendmail
```

### Webserver-Informationen
```
Webserver:            www116.c.artfiles.de
Webserver-IP (IPv6):  2a00:1f78:cd:4:0:0:0:180
Webserver-IP (IPv4):  212.53.215.101
Webserver-Software:   Apache HTTPD 2.4.x
Zeitzone:             CET (UTC+0100)
```

### Datenbank-Server
```
Datenbankserver:      sql116.c.artfiles.de
Datenbank-Software:   MariaDB 10.11.14
MySQL-Tools:          /usr/bin/ (mysql, mysqldump, etc.)
```

### Verfügbare Software-Versionen

**PHP:**
- Verfügbare Versionen: 7.4, 8.0, 8.1, 8.2, 8.3, 8.4, 8.5
- **Empfehlung für Projekt:** PHP 8.3 ✅
- CLI-Pfad: `/usr/local/bin/php`

**Python:**
- Version: 3.11.2
- Für Deployment-Scripts nutzbar

**Perl:**
- Version: 5.36.0
- Pfad: `/usr/bin/perl`

**Bildverarbeitung:**
- ImageMagick: 6.9.11-60 (in `/usr/bin/`)
- GraphicsMagick: Verfügbar (in `/usr/bin/`)
- Nutzbar für Thumbnails, Bildoptimierung

**Email:**
- Sendmail: `/usr/sbin/sendmail`
- PHPMailer kann Sendmail oder SMTP nutzen

### SSH-Verbindung
```bash
# SSH-Login (Zugangsdaten separat)
ssh username@www116.c.artfiles.de

# Alternativer Hostname (falls vorhanden)
ssh username@artfiles.de -p 22
```

### Deployment-Pfade für Git
```bash
# Nach SSH-Login:
cd /home/www/doc/28552/

# Repository hier clonen
git clone <repo-url> .

# Oder in Subdomain/Subdirectory:
# cd /home/www/doc/28552/subdomain/
```

### Wichtige Hinweise
- ⚠️ **Homeverzeichnis-Pfad merken:** `/home/www/doc/28552/`
- ✅ MariaDB 10.11.14 ist kompatibel mit MySQL 5.7+ Code
- ✅ Apache 2.4.x unterstützt `.htaccess` vollständig
- ✅ ImageMagick verfügbar für Produktbilder/Thumbnails
- ✅ Python 3.11 verfügbar für Deployment-Scripts

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

