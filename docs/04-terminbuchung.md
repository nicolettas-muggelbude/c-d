# Terminbuchung & Werkstatt-System

## Inhaltsverzeichnis
- Booking-System Implementierung
- Kalender-Integration
- Service-Verwaltung
- Zeitslot-Logik
- Conditional Fields (JavaScript)

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

