# Production Checklist - Webseite + Terminbuchung

> **Ziel:** Webseite mit Terminbuchung in den Produktivbetrieb überführen
> **Shop:** Wird zunächst ausgeblendet und später integriert
> **Stand:** 2026-01-03

---

## 📋 Phase 1: Testing & Finalisierung (master Branch)

### 🧪 Terminmodul testen

- [ ] **Buchungs-Workflow**
  - [ ] Fester Termin: Alle 8 Services durchspielen
  - [ ] Walk-in: Alle 8 Services durchspielen
  - [ ] Navigation Vor/Zurück zwischen Schritten funktioniert
  - [ ] Zusammenfassung zeigt korrekte Daten
  - [ ] Erfolgsseite mit Buchungsnummer

- [ ] **Formular-Validierung**
  - [ ] Leere Pflichtfelder werden abgefangen
  - [ ] E-Mail-Format wird validiert
  - [ ] PLZ: 5 Ziffern Validierung
  - [ ] Telefon: Ländervorwahl + Nummer korrekt
  - [ ] Führende Nullen werden entfernt
  - [ ] Straße/Hausnummer/Ort min. 2 Zeichen

- [ ] **Zeitslot-System**
  - [ ] Verfügbare Zeiten werden korrekt angezeigt
  - [ ] Doppelbuchung wird verhindert
  - [ ] Walk-in Zeiten: Di-Fr 14-17 Uhr, Sa 12-16 Uhr
  - [ ] Feste Termine: Nur 11:00 + 12:00 Uhr
  - [ ] Gebuchte Slots sind ausgegraut/deaktiviert
  - [ ] API `/api/available-slots` funktioniert

- [ ] **Email-Versand**
  - [ ] Kunde erhält Bestätigungs-Email
  - [ ] Admin erhält Benachrichtigung
  - [ ] Deutsche Umlaute korrekt (UTF-8)
  - [ ] Links in Email funktionieren
  - [ ] Admin-Detail-Link korrekt
  - [ ] Email-Layout ist lesbar

- [ ] **HelloCash-Integration**
  - [ ] Neuer Kunde wird in HelloCash angelegt
  - [ ] Bestehender Kunde wird gefunden (Email-Match)
  - [ ] Bestehender Kunde wird gefunden (Telefon-Match)
  - [ ] Adresse wird korrekt übertragen
  - [ ] Ländercode wird korrekt gemappt (DE aus +49)
  - [ ] Festnetz landet in `user_notes`
  - [ ] Keine Duplikate werden erstellt

- [ ] **Admin-Bereich: Termine**
  - [ ] Übersicht zeigt alle Buchungen
  - [ ] Filter nach Status funktioniert
  - [ ] Filter nach Terminart funktioniert
  - [ ] Filter nach Datum funktioniert
  - [ ] Suche nach Name/E-Mail funktioniert
  - [ ] Detail-Ansicht zeigt alle Daten
  - [ ] Status ändern funktioniert
  - [ ] Status-Badges korrekt farbcodiert

- [ ] **Admin-Bereich: Booking Settings**
  - [ ] Zeiten ändern funktioniert
  - [ ] Intervall ändern funktioniert
  - [ ] Max. Buchungen pro Slot ändern funktioniert
  - [ ] Live-Vorschau zeigt korrekte Slots
  - [ ] Validierung: Endzeit nach Startzeit
  - [ ] Änderungen werden gespeichert

- [ ] **Edge Cases**
  - [ ] Was passiert bei gleichzeitiger Buchung? (Race Condition)
  - [ ] Ungültige Datumsauswahl (Sonntag/Montag)
  - [ ] Sehr lange Eingaben in Textfeldern
  - [ ] SQL-Injection Versuche (Prepared Statements?)
  - [ ] CSRF-Token korrekt validiert

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
  - [ ] Bilder: Logo vorhanden
  - [ ] Bilder: Baileys-Foto? (optional)
  - [ ] Featured Products Sektion (später entfernen für Production)

- [ ] **Leistungen (leistungen.php)**
  - [ ] Alle Dienstleistungen beschrieben
  - [ ] Preise angegeben? (optional)
  - [ ] Icons/Bilder vorhanden?
  - [ ] Links zu Terminbuchung funktionieren

- [ ] **Kontaktformular (kontakt.php)**
  - [ ] Formular durchspielen
  - [ ] Validierung funktioniert
  - [ ] Daten werden in DB gespeichert
  - [ ] Erfolgsmeldung wird angezeigt
  - [ ] CSRF-Schutz aktiv
  - [ ] Kontaktdaten korrekt angezeigt (Adresse, Telefon, Email)

- [ ] **Rechtliche Seiten**
  - [ ] **Impressum:** Vollständige Adresse, Telefon, Email
  - [ ] **Impressum:** USt-ID vorhanden? (falls vorhanden)
  - [ ] **Impressum:** Geschäftsführer/Inhaber genannt
  - [ ] **Datenschutz:** Vollständig für Terminbuchung
  - [ ] **Datenschutz:** HelloCash-Integration erwähnt
  - [ ] **Datenschutz:** Email-Versand (PHPMailer/SMTP) erwähnt
  - [ ] **Datenschutz:** Cookie-Banner nötig? (wenn ja: implementieren)
  - [ ] **AGB:** Vollständig für Terminbuchung
  - [ ] **Widerruf:** Relevant für Terminbuchung?

- [ ] **Navigation & Footer**
  - [ ] Alle Links funktionieren
  - [ ] Mobile Navigation (Hamburger Menu) funktioniert
  - [ ] Darkmode-Toggle funktioniert
  - [ ] Footer: Social Media Links setzen (Facebook, Instagram)
  - [ ] Footer: Kontaktdaten korrekt
  - [ ] Footer: Öffnungszeiten korrekt

---

### 🎨 Design & UX Testing

- [ ] **Responsive Design**
  - [ ] Mobile (< 768px): Layout korrekt
  - [ ] Tablet (768px - 1024px): Layout korrekt
  - [ ] Desktop (> 1024px): Layout korrekt
  - [ ] Touch-Targets min. 44x44px (Mobile)
  - [ ] Hamburger Menu auf Mobile funktioniert

- [ ] **Darkmode**
  - [ ] Alle Seiten im Darkmode testen
  - [ ] Kontraste ausreichend (WCAG 2.1 AA)
  - [ ] Toggle speichert Präferenz (localStorage)
  - [ ] System-Präferenz wird erkannt
  - [ ] Formulare im Darkmode lesbar
  - [ ] Admin-Bereich im Darkmode funktioniert

- [ ] **Cross-Browser Testing**
  - [ ] Chrome (Desktop + Mobile)
  - [ ] Firefox (Desktop)
  - [ ] Safari (Desktop + iOS)
  - [ ] Edge (Desktop)
  - [ ] Keine Console-Errors
  - [ ] JavaScript funktioniert überall

- [ ] **Barrierefreiheit (WCAG 2.1 Level AA)**
  - [ ] Keyboard-Navigation funktioniert (Tab, Enter, Escape)
  - [ ] Fokus-Indikatoren sichtbar
  - [ ] Alt-Texte für alle Bilder
  - [ ] Formular-Labels korrekt zugeordnet
  - [ ] Screen-Reader Test (optional)
  - [ ] Skip-Links vorhanden

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

- [ ] **SEO & Meta**
  - [ ] Meta-Tags: Title, Description für alle Seiten
  - [ ] `robots.txt` erstellen
  - [ ] `sitemap.xml` erstellen (optional)
  - [ ] Google Search Console registrieren
  - [ ] Favicon vorhanden?

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

**Letzte Aktualisierung:** 2026-01-03
**Status:** Phase 1 - Testing läuft
