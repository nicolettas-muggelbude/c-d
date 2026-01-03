# Email & Kommunikation

## Inhaltsverzeichnis
- Template-basiertes Email-System
- PHPMailer SMTP-Integration
- Email-Erinnerungen
- Placeholders & Variablen
- Email-Templates

Siehe auch: `docs/email-placeholders.md`

## Session 2026-01-01 (Fortsetzung): Template-basiertes Email-System mit Erinnerungen

### Erreichte Ziele ✅

#### 1. Datenbank-basierte Email-Templates
**Problem:** Email-Texte waren hardcodiert im PHP-Code, keine Möglichkeit zur Anpassung durch Admin.

**Lösung:**
- **Email-Templates Tabelle:**
  - 3 Template-Typen: `confirmation`, `reminder_24h`, `reminder_1h`
  - Felder: subject, body, placeholders, is_active
  - Vollständig editierbar über Admin-UI
- **Email-Signatur Tabelle:**
  - Globale Signatur für alle Emails
  - Wird automatisch an alle Nachrichten angehängt
- **Email-Log Tabelle:**
  - Audit-Trail aller versendeten Emails
  - Status-Tracking (sent/failed/pending)
  - Duplikat-Vermeidung durch Prüfung

**Dateien:**
- `database/create-email-templates.sql` - Schema mit Defaults

#### 2. EmailService-Klasse
**Zentrale Service-Klasse** für alle Email-Vorgänge:

**Features:**
- `sendBookingEmail($bookingId, $templateType)` - Haupt-Methode
- `getTemplate($type)` - Lädt Template aus DB
- `getSignature()` - Lädt Signatur aus DB
- `replacePlaceholders($text, $booking)` - Ersetzt Platzhalter
- `sendMail($to, $subject, $body)` - Versendet Email
- `logEmail(...)` - Loggt Versand-Vorgänge
- `isEmailAlreadySent(...)` - Prüft Duplikate
- `getBookingsForReminder24h()` - Findet Termine für 24h-Reminder
- `getBookingsForReminder1h()` - Findet Termine für 1h-Reminder

**Platzhalter-System:**
```php
{customer_firstname}       → "Max"
{customer_lastname}        → "Mustermann"
{booking_id}              → "123"
{booking_date_formatted}  → "Dienstag, 07. Januar 2026"
{booking_time_formatted}  → "11:00 Uhr" oder "Walk-in ab 14:00 Uhr"
{service_type_label}      → "PC-Reparatur"
{booking_type_label}      → "Fester Termin"
{customer_notes_section}  → "Ihre Anmerkungen:\n..."
```

**Datei:** `src/core/EmailService.php`

#### 3. Admin-UI für Email-Template-Verwaltung
**Vollständige Verwaltung** aller Email-Templates:

**Features:**
- **Template-Liste:** Alle Templates mit Status (aktiv/inaktiv)
- **Template bearbeiten:**
  - Subject und Body editierbar (Textarea)
  - Verfügbare Platzhalter werden angezeigt
  - Speichern-Button mit Bestätigung
- **Signatur bearbeiten:**
  - Globale Signatur für alle Emails
  - Wird automatisch angehängt
- **Toggle aktiv/inaktiv:**
  - Templates können deaktiviert werden
  - Inaktive Templates werden nicht versendet

**Standard-Templates:**
1. **Buchungsbestätigung (confirmation):**
   - Betreff: "Ihre Terminbuchung #{booking_id} - PC-Wittfoot UG"
   - Inhalt mit Box-Design (UTF-8 Linien)
   - Termindetails formatiert
   - Was mitbringen-Checkliste

2. **24-Stunden-Erinnerung (reminder_24h):**
   - Betreff: "Erinnerung: Ihr Termin morgen um {booking_time_formatted}"
   - Freundliche Erinnerung
   - Alle Termindetails nochmal

3. **1-Stunden-Erinnerung (reminder_1h):**
   - Betreff: "Ihr Termin in 1 Stunde - PC-Wittfoot UG"
   - Kurze Erinnerung
   - Wichtigste Infos (Adresse, Zeit)

**Datei:** `src/admin/email-templates.php`

#### 4. Automatische Erinnerungs-Emails via Cron-Jobs
**Problem:** Kunden vergessen ihre Termine.

**Lösung - 24-Stunden-Erinnerung:**
- **Cron-Job:** Läuft täglich um 10:00 Uhr
- **Zielgruppe:** Termine am nächsten Tag
- **Filter:**
  - `booking_date = DATE_ADD(CURDATE(), INTERVAL 1 DAY)`
  - Status: pending oder confirmed
  - Nur fixed und walkin Termine
  - Nicht bereits versendet (Email-Log-Check)

**Lösung - 1-Stunden-Erinnerung:**
- **Cron-Job:** Läuft stündlich
- **Zielgruppe:** Termine in 50-70 Minuten
- **Filter:**
  - `booking_date = CURDATE()`
  - `booking_time` zwischen NOW()+50min und NOW()+70min
  - Nur fixed Termine (haben feste Zeit)
  - Status: pending oder confirmed
  - Nicht bereits versendet

**Features beider Jobs:**
- CLI-only Check (Sicherheit)
- Zählt gesendete/fehlgeschlagene Emails
- Logging: Datum, Zeit, Statistik
- Exit-Code für Monitoring (0 = OK, 1 = Fehler)

**Dateien:**
- `src/cron/send-reminder-24h.php` - 24h-Job
- `src/cron/send-reminder-1h.php` - 1h-Job

**Crontab-Beispiel:**
```bash
# 24h-Erinnerungen täglich um 10:00 Uhr
0 10 * * * /usr/bin/php /pfad/zu/src/cron/send-reminder-24h.php

# 1h-Erinnerungen jede Stunde
0 * * * * /usr/bin/php /pfad/zu/src/cron/send-reminder-1h.php
```

#### 5. Email-Versand bei Admin-Buchung
**Problem:** Wenn Admin einen Termin für Kunden erstellt, erhält dieser keine Bestätigung.

**Lösung:**
- Integration in `src/admin/booking-calendar-v2.php`
- Prüfung nach INSERT:
  - Buchung erfolgreich erstellt?
  - Email-Adresse vorhanden?
  - Kundenrelevanter Termin? (fixed/walkin, nicht internal/blocked)
- Automatischer Versand der confirmation-Email
- Fail-Safe: Fehler beim Email-Versand stoppt Buchung nicht

**Code:**
```php
// Email-Bestätigung senden (nur bei Kundenterminen mit Email)
if ($bookingId && !empty($customerEmail) && in_array($bookingType, ['fixed', 'walkin'])) {
    $emailService = new EmailService();
    $emailService->sendBookingEmail($bookingId, 'confirmation');
}
```

**Gilt für:**
- Kalenderansicht (`/admin/booking-calendar`)
- Wochenansicht (`/admin/booking-week`)

**Datei:** `src/admin/booking-calendar-v2.php`

#### 6. Migration: Alte Email-Funktion entfernt
**Vorher:**
- 158 Zeilen hardcodierte Email-Funktion `sendBookingEmails()`
- Separate Email für Kunde und Admin
- Nicht wiederverwendbar, nicht konfigurierbar

**Nachher:**
- Ersetzt durch `EmailService::sendBookingEmail()`
- Wiederverwendbar in gesamter Anwendung
- Admin-editierbar, Template-basiert
- Umfangreiches Logging

**Datei:** `src/api/booking.php` (158 Zeilen entfernt, 3 Zeilen hinzugefügt)

### Technische Details

#### Duplikat-Vermeidung
```php
// Prüft ob Email bereits versendet wurde
private function isEmailAlreadySent($bookingId, $emailType) {
    $result = $this->db->querySingle(
        "SELECT COUNT(*) as count FROM email_log
         WHERE booking_id = :booking_id
         AND email_type = :email_type
         AND status = 'sent'",
        [':booking_id' => $bookingId, ':email_type' => $emailType]
    );
    return ($result['count'] ?? 0) > 0;
}
```

**Vorteil:** Auch bei mehrfachem Aufruf wird Email nur 1x versendet.

#### Datum-Formatierung (deutsch)
```php
// Wochentage
$weekdays = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch',
             'Donnerstag', 'Freitag', 'Samstag'];

// Monate
$months = ['', 'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
           'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];

// Formatierung
$dateFormatted = $weekdays[(int)$date->format('w')] . ', ' .
                $date->format('d') . '. ' .
                $months[(int)$date->format('n')] . ' ' .
                $date->format('Y');
// Ergebnis: "Dienstag, 07. Januar 2026"
```

#### Email-Versand mit UTF-8
```php
private function sendMail($to, $subject, $body) {
    $headers = "From: " . MAIL_FROM_NAME . " <" . MAIL_FROM . ">\r\n";
    $headers .= "Reply-To: " . MAIL_FROM . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $sent = @mail($to, $subject, $body, $headers);

    // Logging
    if ($sent) {
        error_log("EmailService: Email sent to $to");
    } else {
        error_log("EmailService: Failed to send email to $to");
    }

    return $sent;
}
```

**Hinweis:** PHP mail() Funktion - für Produktion ggf. SMTP/PHPMailer verwenden.

#### SQL-Query für 1h-Erinnerungen
```sql
SELECT id FROM bookings
WHERE booking_date = CURDATE()
AND booking_time IS NOT NULL
AND booking_time BETWEEN
    DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 50 MINUTE), '%H:%i:00')
    AND DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 70 MINUTE), '%H:%i:00')
AND booking_type = 'fixed'
AND status IN ('pending', 'confirmed')
AND id NOT IN (
    SELECT booking_id FROM email_log
    WHERE email_type = 'reminder_1h' AND status = 'sent'
)
```

**Zeitfenster:** 50-70 Minuten → Cron-Job läuft stündlich, trifft damit alle Termine.

### Dateistruktur (Neu)

```
src/
├── core/
│   └── EmailService.php          # Email-Service-Klasse (NEU)
├── admin/
│   └── email-templates.php       # Template-Verwaltung (NEU)
├── cron/
│   ├── send-reminder-24h.php     # 24h-Reminder Job (NEU)
│   └── send-reminder-1h.php      # 1h-Reminder Job (NEU)
└── api/
    └── booking.php               # Email-Integration (AKTUALISIERT)

database/
└── create-email-templates.sql    # Schema + Defaults (NEU)
```

### Projektstand nach Session

#### Komplett implementiert ✅
- ✅ Datenbank-Schema für Email-System
- ✅ EmailService-Klasse mit allen Features
- ✅ Admin-UI für Template-Verwaltung
- ✅ Platzhalter-System mit deutscher Formatierung
- ✅ 24h-Erinnerungs-Cron-Job
- ✅ 1h-Erinnerungs-Cron-Job
- ✅ Email-Versand bei Admin-Buchung
- ✅ Email-Versand bei Kunden-Buchung
- ✅ Duplikat-Vermeidung
- ✅ Umfangreiches Logging

#### Bereit für Produktion
- **Funktionsumfang:** Vollständig
- **Testing:** Durchgeführt
- **Integration:** Abgeschlossen
- **Dokumentation:** Vollständig

#### Mögliche Erweiterungen (Optional)
- SMTP-Integration für bessere Zustellbarkeit
- HTML-Email-Templates (derzeit: Plain Text)
- CC/BCC-Funktion
- Attachment-Support
- Email-Versand-Statistiken im Dashboard

#### Router-Integration
Neue Route hinzugefügt:
```php
// src/router.php
elseif ($param === 'email-templates') {
    require_admin();
    require __DIR__ . '/admin/email-templates.php';
}
```

**Zugriff:** `/admin/email-templates`

## Session 2026-01-01 (Fortsetzung): PHPMailer SMTP-Integration

### Erreichte Ziele ✅

#### 1. PHPMailer Installation
**Problem:** PHP mail() Funktion ist unzuverlässig, landet oft im Spam, keine SMTP-Unterstützung.

**Lösung:**
- PHPMailer v7.0.1 via Composer installiert
- Composer lokal heruntergeladen (`composer.phar`) für Entwicklung
- Vendor-Ordner kann via FTP auf Produktiv-Server deployed werden

**Dateien:**
- `composer.json` - Composer-Konfiguration
- `composer.lock` - Dependency Lock-File
- `vendor/` - PHPMailer & Dependencies

#### 2. SMTP-Konfiguration
**Datenbank-basierte Konfiguration** für flexible Admin-Verwaltung:

**Tabelle `smtp_settings`:**
```sql
- smtp_enabled (BOOLEAN) - SMTP aktiviert oder PHP mail()
- smtp_host (VARCHAR) - SMTP Server (z.B. smtp.gmail.com)
- smtp_port (INT) - Port (587 = TLS, 465 = SSL)
- smtp_encryption (ENUM) - tls, ssl, oder none
- smtp_username (VARCHAR) - SMTP Benutzername
- smtp_password (VARCHAR) - SMTP Passwort
- smtp_debug (INT) - Debug-Level (0-2)
- updated_at (TIMESTAMP) - Letzte Änderung
```

**Standard-Werte:**
- SMTP deaktiviert (verwendet PHP mail())
- Vorkonfiguriert für Gmail (smtp.gmail.com:587, TLS)
- Debug aus für Produktion

**Dateien:**
- `database/create-smtp-settings.sql` - Schema
- `src/core/config.php` - Composer Autoload & Fallback-Konstanten

#### 3. EmailService mit PHPMailer
**Komplett überarbeiteter Email-Service:**

**Features:**
- Automatische Wahl zwischen SMTP und PHP mail()
- Liest Konfiguration aus Datenbank (nicht hardcoded!)
- Besseres Error-Handling mit Try-Catch
- Detailliertes Logging (zeigt SMTP-Server an)
- UTF-8 Support
- Debug-Ausgabe konfigurierbar

**Code-Änderungen:**
```php
// Vorher: Hardcoded mail() Funktion
$sent = @mail($to, $subject, $body, $headers);

// Nachher: PHPMailer mit DB-Konfiguration
$mail = new \PHPMailer\PHPMailer\PHPMailer(true);
$smtp = $this->db->querySingle("SELECT * FROM smtp_settings WHERE id = 1");

if ($smtp && $smtp['smtp_enabled']) {
    $mail->isSMTP();
    $mail->Host = $smtp['smtp_host'];
    // ... weitere SMTP-Einstellungen
} else {
    $mail->isMail();  // Fallback zu PHP mail()
}
```

**Vorteile:**
- ✅ Bessere Zustellbarkeit (weniger Spam)
- ✅ Verschlüsselte Verbindung (TLS/SSL)
- ✅ Authentifizierung mit SMTP-Credentials
- ✅ Detaillierte Error-Messages
- ✅ Flexibel: SMTP per Klick ein/ausschalten

**Datei:** `src/core/EmailService.php`

#### 4. Admin-UI für SMTP-Verwaltung
**Vollständige Verwaltungsoberfläche** (`/admin/smtp-settings`):

**Features:**
- ✅ **SMTP aktivieren/deaktivieren** - Checkbox zum Umschalten
- ✅ **Server-Konfiguration:**
  - SMTP Host (z.B. smtp.gmail.com, smtp.office365.com)
  - Port (Standard: 587 für TLS, 465 für SSL)
  - Verschlüsselung (TLS/SSL/Keine)
- ✅ **Authentifizierung:**
  - Benutzername
  - Passwort (nur ändern wenn neues eingegeben)
- ✅ **Debug-Level:**
  - Aus (Produktion)
  - Nur Fehler
  - Verbose (Entwicklung)
- ✅ **Aktuelle Konfiguration** - Übersicht der gespeicherten Einstellungen
- ✅ **Info-Box** mit Hinweisen für Gmail, Office365, etc.

**Design:**
- Responsive Formular mit Validierung
- Form-Row Layout für Port/Verschlüsselung
- Passwort-Feld: Placeholder-Text erklärt Verhalten
- Übersichtliche Tabelle mit aktueller Config

**Datei:** `src/admin/smtp-settings.php`

#### 5. Test-Email Funktion
**Dedizierte Test-Seite** (`/admin/smtp-test`):

**Features:**
- ✅ Test-Email an beliebige Adresse senden
- ✅ Zeigt aktuelle SMTP-Methode an (SMTP oder PHP mail())
- ✅ Bei SMTP: Zeigt Server, Port, Verschlüsselung
- ✅ **Debug-Ausgabe** - Komplette SMTP-Kommunikation sichtbar
- ✅ Erfolgs-/Fehlermeldungen
- ✅ Hinweise für Gmail, Office365, Spam-Ordner

**Debug-Ausgabe:**
```
SMTP -> FROM SERVER: 220 smtp.gmail.com ESMTP ready
SMTP -> FROM SERVER: 250-smtp.gmail.com at your service
...
```

**Test-Email Inhalt:**
- Versanddatum/Zeit
- Verwendete Methode (SMTP/PHP mail())
- SMTP-Server Details (falls SMTP)
- Bestätigungstext

**Datei:** `src/admin/smtp-test.php`

#### 6. Dashboard-Integration
**Neue Links im Admin-Dashboard:**

```php
✉️ Email-Templates verwalten  → /admin/email-templates
🔧 SMTP-Einstellungen         → /admin/smtp-settings
```

Zugriff: Dashboard → "🔧 SMTP-Einstellungen" → "🧪 Test-Email senden"

#### 7. Router-Erweiterung
**Neue Routen:**
```php
/admin/smtp-settings  → SMTP-Konfiguration
/admin/smtp-test      → Test-Email senden
```

**Datei:** `src/router.php`

### Technische Details

#### Composer Autoload
```php
// src/core/config.php
require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';
```

Lädt PHPMailer und alle anderen Composer-Packages automatisch.

#### PHPMailer Konfiguration
```php
// SMTP aktiviert
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Username = 'email@gmail.com';
$mail->Password = 'app-password';

// PHP mail() Fallback
$mail->isMail();
```

#### Gmail-Konfiguration
Für Gmail-Versand erforderlich:
1. 2-Faktor-Authentifizierung aktivieren
2. App-Passwort generieren (nicht normales Passwort!)
3. SMTP-Einstellungen:
   - Host: `smtp.gmail.com`
   - Port: `587`
   - Verschlüsselung: `TLS`
   - Benutzername: Deine Gmail-Adresse
   - Passwort: App-Passwort (16-stellig)

#### Office365-Konfiguration
1. SMTP-Einstellungen:
   - Host: `smtp.office365.com`
   - Port: `587`
   - Verschlüsselung: `TLS`
   - Benutzername: Deine Office365-Email
   - Passwort: Office365-Passwort

### Dateistruktur (Neu)

```
/
├── composer.json              # Composer-Konfiguration (NEU)
├── composer.phar              # Composer Binary (NEU)
├── vendor/                    # Dependencies (NEU)
│   └── phpmailer/phpmailer/
├── database/
│   └── create-smtp-settings.sql (NEU)
├── src/
│   ├── core/
│   │   ├── config.php         # Composer Autoload hinzugefügt
│   │   └── EmailService.php   # PHPMailer-Integration
│   └── admin/
│       ├── index.php          # Dashboard-Link hinzugefügt
│       ├── smtp-settings.php  # SMTP-Verwaltung (NEU)
│       └── smtp-test.php      # Test-Email (NEU)
```

### Projektstand nach Session

#### Komplett implementiert ✅
- ✅ PHPMailer v7.0.1 installiert
- ✅ Datenbank-basierte SMTP-Konfiguration
- ✅ EmailService auf PHPMailer migriert
- ✅ Admin-UI für SMTP-Verwaltung
- ✅ Test-Email Funktion mit Debug-Ausgabe
- ✅ Dashboard-Integration
- ✅ Kompatibilität mit Gmail, Office365, eigenen SMTP-Servern

#### Bereit für Produktion
- **Email-Versand:** Flexibel (SMTP oder PHP mail())
- **Konfiguration:** Admin-editierbar über UI
- **Testing:** Integrierte Test-Funktion
- **Logging:** Detaillierte Error-Messages
- **Sicherheit:** Passwörter in Datenbank (verschlüsselt empfohlen)

#### Deployment-Hinweise
1. **Composer Dependencies:** `vendor/` Ordner via FTP hochladen
2. **Datenbank:** `create-smtp-settings.sql` importieren
3. **SMTP-Einstellungen:** Im Admin-Bereich konfigurieren
4. **Test:** Test-Email senden vor Produktiv-Betrieb

#### Verbesserungsmöglichkeiten (Optional)
- Passwort-Verschlüsselung in Datenbank
- Multiple SMTP-Profile (z.B. für verschiedene Email-Typen)
- Email-Queue für bessere Performance
- Statistiken: Erfolgreiche/Fehlgeschlagene Emails
- HTML-Email Support (derzeit: Plain Text)

