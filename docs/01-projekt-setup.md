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
- **PHP:** Version 8.0+ verfügbar
  - **Empfehlung:** PHP 8.2 verwenden ✅
  - Aktive Sicherheitsupdates
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

