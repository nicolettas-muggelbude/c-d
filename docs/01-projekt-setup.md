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

