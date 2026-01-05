# Production Checklist - Webseite + Terminbuchung

> **Ziel:** Webseite mit Terminbuchung in den Produktivbetrieb überführen
> **Shop:** Wird zunächst ausgeblendet und später integriert
> **Stand:** 2026-01-03

---

## 📋 Phase 1: Testing & Finalisierung (master Branch)

### 🧪 Terminmodul testen

- [x] **Buchungs-Workflow**
  - [x] Fester Termin: Alle 8 Services durchspielen
  - [x] Walk-in: Alle 8 Services durchspielen
  - [x] Navigation Vor/Zurück zwischen Schritten funktioniert
  - [x] Zusammenfassung zeigt korrekte Daten
  - [x] Erfolgsseite mit Buchungsnummer
  - [x] WCAG 2.1 Level AA konform (16 Emojis mit aria-hidden, Keyboard-Navigation)

- [x] **Formular-Validierung**
  - [x] Leere Pflichtfelder werden abgefangen
  - [x] E-Mail-Format wird validiert
  - [x] PLZ: 5 Ziffern Validierung
  - [x] Telefon: Ländervorwahl + Nummer korrekt
  - [x] Führende Nullen werden entfernt
  - [x] Straße/Hausnummer/Ort min. 2 Zeichen

- [x] **Zeitslot-System**
  - [x] Verfügbare Zeiten werden korrekt angezeigt
  - [x] Doppelbuchung wird verhindert
  - [x] Walk-in Zeiten: Di-Fr 14-17 Uhr, Sa 12-16 Uhr
  - [x] Feste Termine: Nur 11:00 + 12:00 Uhr
  - [x] Gebuchte Slots sind ausgegraut/deaktiviert
  - [x] API `/api/available-slots` funktioniert

- [x] **Email-Versand**
  - [x] Kunde erhält Bestätigungs-Email
  - [x] Admin erhält Benachrichtigung
  - [x] Deutsche Umlaute korrekt (UTF-8)
  - [x] Links in Email funktionieren
  - [x] Admin-Detail-Link korrekt
  - [x] Email-Layout ist lesbar

- [x] **HelloCash-Integration**
  - [x] Neuer Kunde wird in HelloCash angelegt
  - [x] Bestehender Kunde wird gefunden (Email-Match)
  - [x] Bestehender Kunde wird gefunden (Vorname-Match)
  - [x] Adresse wird korrekt übertragen
  - [x] Ländercode wird korrekt gemappt (DE aus +49)
  - [x] Festnetz landet in `user_notes`
  - [x] Keine Duplikate werden erstellt

- [x] **Admin-Bereich: Termine**
  - [x] Übersicht zeigt alle Buchungen
  - [x] Filter nach Status funktioniert
  - [x] Filter nach Terminart funktioniert
  - [x] Filter nach Datum funktioniert
  - [x] Suche nach Name/E-Mail funktioniert
  - [x] Detail-Ansicht zeigt alle Daten
  - [x] Status ändern funktioniert
  - [x] Status-Badges korrekt farbcodiert

- [x] **Admin-Bereich: Booking Settings**
  - [x] Zeiten ändern funktioniert
  - [x] Intervall ändern funktioniert
  - [x] Max. Buchungen pro Slot ändern funktioniert
  - [x] Live-Vorschau zeigt korrekte Slots
  - [x] Validierung: Endzeit nach Startzeit
  - [x] Änderungen werden gespeichert

- [x] **Edge Cases**
  - [x] Was passiert bei gleichzeitiger Buchung? (Race Condition)
  - [x] Ungültige Datumsauswahl (Sonntag/Montag)
  - [x] Sehr lange Eingaben in Textfeldern
  - [x] SQL-Injection Versuche (Prepared Statements getestet)
  - [x] CSRF-Token korrekt validiert (Security-Tests durchgeführt)

---

### 📝 Blog-System überarbeiten & testen

- [ ] **Blog-Übersicht (blog.php)**
  - [ ] Seite existiert und ist erreichbar
  - [ ] Alle Blog-Posts werden angezeigt
  - [ ] Pagination funktioniert (falls implementiert)
  - [ ] Post-Vorschau mit Titel, Datum, Excerpt
  - [ ] "Weiterlesen"-Links funktionieren
  - [ ] Kategorien/Tags angezeigt (falls implementiert)
  - [ ] Responsive Design (Mobile, Tablet, Desktop)
  - [ ] Darkmode funktioniert

- [ ] **Blog-Post-Detail (blog-post.php)**
  - [ ] Post wird vollständig angezeigt
  - [ ] Titel, Datum, Autor korrekt
  - [ ] Bilder werden angezeigt (falls vorhanden)
  - [ ] Formatierung korrekt (Markdown/HTML)
  - [ ] Navigation zu vorherigen/nächsten Posts (optional)
  - [ ] Zurück-zur-Übersicht Link
  - [ ] Responsive Design
  - [ ] Darkmode funktioniert

- [ ] **Admin-Bereich: Blog-Verwaltung**
  - [ ] Übersicht aller Blog-Posts
  - [ ] Neuen Post erstellen funktioniert
  - [ ] Post bearbeiten funktioniert
  - [ ] Post löschen funktioniert
  - [ ] Post veröffentlichen/Entwurf speichern
  - [ ] Markdown/Editor funktioniert
  - [ ] Bild-Upload (falls implementiert)
  - [ ] Kategorien/Tags verwalten (falls implementiert)

- [ ] **Content & SEO**
  - [ ] Mindestens 2-3 Blog-Posts als Beispiel-Content
  - [ ] Meta-Tags für Blog-Posts (Title, Description)
  - [ ] Canonical URLs korrekt
  - [ ] Open Graph Tags (optional)

- [ ] **Integration**
  - [ ] Blog-Link in Navigation vorhanden
  - [ ] Blog-Link im Footer (optional)
  - [ ] Neueste Posts auf Startseite (optional)

---

### 🌐 Webseiten-Anpassungen

- [ ] **Startseite (index.php)**
  - [ ] Hero-Section: Text finalisiert
  - [ ] "Über uns" Bereich: Text vorhanden
  - [ ] Call-to-Action Buttons funktionieren
  - [ ] Leistungen-Preview vorhanden
  - [x] Bilder: Logo vorhanden (modernes SVG-Logo mit Monitor/Laptop/Handy)
  - [ ] Bilder: Baileys-Foto? (optional)
  - [ ] Featured Products Sektion (später entfernen für Production)

- [x] **Leistungen (leistungen.php)**
  - [x] Alle Dienstleistungen beschrieben
  - [x] Preise angegeben? (optional)
  - [x] Icons/Bilder vorhanden?
  - [x] Links zu Terminbuchung funktionieren

- [x] **Kontaktformular (kontakt.php)**
  - [x] Formular durchspielen
  - [x] Validierung funktioniert
  - [x] Daten werden in DB gespeichert
  - [x] Erfolgsmeldung wird angezeigt
  - [x] CSRF-Schutz aktiv
  - [x] Kontaktdaten korrekt angezeigt (Adresse, Telefon, Email)
  - [x] WCAG 2.1 Level AA konform (Emojis, Alert-Boxen, Formular-Labels)

- [x] **Rechtliche Seiten**
  - [x] **Impressum:** Vollständige Adresse, Telefon, Email
  - [x] **Impressum:** USt-ID vorhanden (DE331470711)
  - [x] **Impressum:** Geschäftsführer/Inhaber genannt (Nicole Wittfoot)
  - [x] **Datenschutz:** Vollständig für Terminbuchung
  - [x] **Datenschutz:** HelloCash-Integration erwähnt
  - [x] **Datenschutz:** Email-Versand (PHPMailer/SMTP) erwähnt
  - [x] **Datenschutz:** Cookie-Banner nötig? NEIN (nur sessionStorage, keine Cookies)
  - [x] **AGB:** Vollständig (B2C + B2B mit Stornoregelungen)
  - [x] **Widerruf:** Vollständig (B2C + B2B Ausschluss, Ausnahmen)

- [x] **Navigation & Footer**
  - [x] Alle Links funktionieren (13 interne + 3 externe Links getestet)
  - [x] Mobile Navigation (Hamburger Menu) funktioniert
  - [x] Darkmode-Toggle funktioniert (localStorage-Persistenz)
  - [x] Footer: Social Media Links gesetzt (Facebook, Instagram, WhatsApp)
  - [x] Footer: Kontaktdaten korrekt (Melkbrink 61, 26121 Oldenburg)
  - [x] Footer: Öffnungszeiten korrekt (Mo geschlossen, Di-Fr 14-17, Sa 12-16)

---

### 🎨 Design & UX Testing

- [x] **Responsive Design**
  - [x] Mobile (< 768px): Layout korrekt
  - [x] Tablet (768px - 1024px): Layout korrekt
  - [x] Desktop (> 1024px): Layout korrekt
  - [x] Touch-Targets min. 44x44px (Mobile)
  - [x] Hamburger Menu auf Mobile funktioniert

- [x] **Darkmode**
  - [x] Alle Seiten im Darkmode testen
  - [x] Kontraste ausreichend (WCAG 2.1 AA)
  - [x] Toggle speichert Präferenz (localStorage)
  - [x] System-Präferenz wird erkannt
  - [x] Formulare im Darkmode lesbar
  - [x] Admin-Bereich im Darkmode funktioniert

- [x] **Cross-Browser Testing**
  - [x] Chrome (Desktop + Mobile)
  - [x] Firefox (Desktop)
  - [x] Safari (Desktop + iOS)
  - [x] Edge (Desktop)
  - [x] Keine Console-Errors
  - [x] JavaScript funktioniert überall
  - ⚠️ **Hinweis:** IE11 wird NICHT unterstützt (CSS Variables, async/await, fetch fehlen)

- [x] **Barrierefreiheit (WCAG 2.1 Level AA)**
  - [x] Keyboard-Navigation funktioniert (Tab, Enter, Escape)
  - [x] Fokus-Indikatoren sichtbar
  - [x] Alt-Texte für alle Bilder
  - [x] Formular-Labels korrekt zugeordnet
  - [x] aria-hidden für dekorative Emojis (Startseite, Leistungen, Blog, Termin, Kontakt)
  - [x] role="alert" für Fehler-/Erfolgsmeldungen
  - [x] Farbkontraste WCAG AA konform (--color-primary-dark, --color-secondary-dark)
  - [ ] Screen-Reader Test (optional)
  - [x] Skip-Links vorhanden

---

### 🐛 Bug-Fixing

- [ ] **Alle gefundenen Bugs dokumentieren**
  - [ ] Bug-Beschreibung
  - [ ] Reproduktions-Schritte
  - [ ] Erwartetes Verhalten
  - [ ] Tatsächliches Verhalten

- [ ] **Bugs beheben**
  - [ ] Fix implementieren
  - [ ] Testen ob Bug behoben
  - [ ] Keine neuen Bugs eingeführt (Regression Test)

- [ ] **Finale Tests nach Bug-Fixes**
  - [ ] Gesamter Workflow nochmal durchspielen
  - [ ] Cross-Browser nochmal testen
  - [ ] Mobile nochmal testen

---

## 📦 Phase 2: Production Branch erstellen

- [ ] **Git-Branch erstellen**
  ```bash
  git checkout -b production
  ```

- [ ] **Shop ausblenden**
  - [ ] Navigation: Shop-Links entfernen/auskommentieren (`templates/header.php`)
  - [ ] Startseite: Featured Products Sektion entfernen (`index.php`)
  - [ ] Router: Shop-Routen auf Coming-Soon umleiten (optional)
  - [ ] Footer: Shop-Links entfernen (falls vorhanden)

- [ ] **Production Config erstellen**
  - [ ] `config.production.php` Template erstellen
  - [ ] Platzhalter für DB-Credentials
  - [ ] Platzhalter für SMTP-Settings
  - [ ] Platzhalter für HelloCash API-Keys
  - [ ] `ENVIRONMENT = 'production'`
  - [ ] Error-Display ausschalten
  - [ ] Logging aktivieren

- [ ] **Production-spezifische Anpassungen**
  - [ ] Fehlerbehandlung: Nutzerfreundliche Meldungen
  - [ ] Debug-Outputs entfernen
  - [ ] Test-Daten entfernen (falls vorhanden)
  - [ ] `.htaccess` prüfen (URL-Rewriting)

- [ ] **Commit**
  ```bash
  git commit -m "Production: Shop hidden, production config template"
  ```

---

## 🚀 Phase 3: Deployment vorbereiten (SSH/Git)

- [ ] **Server-Vorbereitung via SSH**
  - [ ] SSH-Zugang testen: `ssh username@server-address`
  - [ ] Git auf Server verfügbar prüfen: `git --version`
  - [ ] Composer auf Server verfügbar prüfen: `composer --version`
  - [ ] Web-Root identifizieren (z.B. `~/public_html`, `/var/www/html`)
  - [ ] Datenbank anlegen (MySQL)
  - [ ] Datenbank-User mit Rechten anlegen
  - [ ] PHP 8.2 aktivieren (falls nicht Standard)
  - [ ] SSL-Zertifikat (Let's Encrypt oder Shared SSL)

- [ ] **Repository auf Server clonen**
  - [ ] SSH-Key für GitHub/GitLab hinterlegen (optional, aber empfohlen)
  - [ ] Repository clonen: `git clone <repo-url> .`
  - [ ] Production Branch auschecken: `git checkout production`
  - [ ] Composer Dependencies installieren: `composer install --no-dev`

- [ ] **Config auf Server erstellen**
  - [ ] `config.production.php` auf Server kopieren zu `config.php`
  - [ ] `config.php` mit echten Daten füllen:
    - [ ] DB: Host, Name, User, Passwort
    - [ ] SMTP: Host, Port, User, Passwort
    - [ ] HelloCash: API-Key (Produktiv-Umgebung!)
    - [ ] BASE_URL auf echte Domain setzen
  - [ ] `.env` Datei erstellen (falls genutzt)

- [ ] **Berechtigungen setzen**
  - [ ] `chmod -R 755 .` (alle Dateien)
  - [ ] `chmod -R 777 logs/` (Log-Verzeichnis beschreibbar)
  - [ ] `chmod -R 777 uploads/` (Upload-Verzeichnis beschreibbar)
  - [ ] `chmod 644 config.php` (Config lesbar, nicht ausführbar)

- [ ] **Datenbank-Setup**
  - [ ] Schema importieren: `mysql -u user -p dbname < database/schema.sql`
  - [ ] Test-Daten importieren (optional): `mysql -u user -p dbname < database/test-data.sql`
  - [ ] Datenbank-Verbindung testen

- [ ] **Deployment-Script erstellen (optional)**
  - [ ] `deploy-ssh.sh` Script lokal erstellen
  - [ ] SSH-Credentials konfigurieren
  - [ ] Testen mit Dry-Run

- [x] **SEO & Meta**
  - [x] Meta-Tags: Title, Description für alle Seiten (inkl. Open Graph & Twitter Cards)
  - [x] `robots.txt` erstellt (mit Sitemap-Referenz)
  - [x] `sitemap.xml` erstellt (alle öffentlichen Seiten)
  - [ ] Google Search Console registrieren
  - [x] Favicon vorhanden (SVG + PNG Fallback)

---

## ✅ Phase 4: Go-Live Testing

- [ ] **Nach Deployment auf Production-Server:**
  - [ ] Terminbuchung durchspielen (Live!)
  - [ ] Email-Versand testen (echte Emails!)
  - [ ] HelloCash-Integration testen (echter API-Call!)
  - [ ] Kontaktformular testen
  - [ ] Alle Links prüfen
  - [ ] SSL-Zertifikat funktioniert (https://)
  - [ ] Mobile Testing (echtes Gerät)
  - [ ] Admin-Login funktioniert

- [ ] **Monitoring einrichten**
  - [ ] Error-Log prüfen (Cronjob für tägliche Benachrichtigung?)
  - [ ] Backup-Strategie (Datenbank + Dateien)
  - [ ] Uptime-Monitoring (optional)

---

## 🎯 Danach: Shop-Entwicklung (master Branch)

- [ ] **Zurück zu master Branch**
  ```bash
  git checkout master
  ```

- [ ] **Shop weiterentwickeln (ungestört)**
  - [ ] PayPal-Integration finalisieren
  - [ ] CSV-Import testen mit echten Daten
  - [ ] Produktverwaltung finalisieren
  - [ ] Warenkorb/Checkout testen
  - [ ] Blog-System implementieren

- [ ] **Später: Shop aktivieren**
  ```bash
  git checkout production
  git merge master  # Shop-Features übernehmen
  # Shop-Links in Navigation aktivieren
  # Deployment auf Production
  ```

---

## 📝 Notizen & Offene Fragen

- Cookie-Banner nötig? (DSGVO-Prüfung)
- USt-ID vorhanden?
- Baileys-Foto verwenden? Wo platzieren?
- Social Media Links vollständig?
- Öffnungszeiten korrekt?
- Google Reviews API später integrieren?

---

**Letzte Aktualisierung:** 2026-01-05
**Status:** Phase 1 - Testing läuft (WCAG 2.1 Level AA Audits abgeschlossen)
