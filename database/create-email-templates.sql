-- Email-Templates für Terminbuchungen
-- PC-Wittfoot UG

CREATE TABLE IF NOT EXISTS email_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    template_type VARCHAR(50) NOT NULL UNIQUE,
    template_name VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    placeholders TEXT NULL COMMENT 'JSON array of available placeholders',
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type (template_type),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Email-Signatur (global)
CREATE TABLE IF NOT EXISTS email_signature (
    id INT AUTO_INCREMENT PRIMARY KEY,
    signature_text TEXT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Email-Versand-Log
CREATE TABLE IF NOT EXISTS email_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    email_type VARCHAR(50) NOT NULL COMMENT 'confirmation, reminder_24h, reminder_1h',
    recipient_email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('sent', 'failed', 'pending') DEFAULT 'sent',
    error_message TEXT NULL,
    INDEX idx_booking (booking_id),
    INDEX idx_type (email_type),
    INDEX idx_sent (sent_at),
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Standard-Templates einfügen
INSERT INTO email_templates (template_type, template_name, subject, body, placeholders) VALUES
('confirmation', 'Buchungsbestätigung', 'Terminbestätigung #{booking_id} - PC-Wittfoot UG',
'Hallo {customer_firstname} {customer_lastname},

vielen Dank für Ihre Terminbuchung!

╔════════════════════════════════════════╗
║         IHRE TERMINDETAILS             ║
╚════════════════════════════════════════╝

Buchungsnummer: #{booking_id}
Terminart:      {booking_type_label}
Dienstleistung: {service_type_label}
Datum:          {booking_date_formatted}
Uhrzeit:        {booking_time_formatted}

{customer_notes_section}

╔════════════════════════════════════════╗
║     BITTE MITBRINGEN                   ║
╚════════════════════════════════════════╝

✓ Ihr Gerät (PC/Notebook)
✓ Netzkabel und Zubehör
✓ Wichtige Passwörter aufschreiben

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789

Wir freuen uns auf Ihren Besuch!',
''["customer_firstname", "customer_lastname", "booking_id", "booking_type_label", "service_type_label", "booking_date_formatted", "booking_time_formatted", "customer_notes_section"]''),

('reminder_24h', 'Erinnerung 24 Stunden', 'Erinnerung: Ihr Termin morgen - PC-Wittfoot UG',
'Hallo {customer_firstname} {customer_lastname},

dies ist eine Erinnerung an Ihren Termin bei uns!

╔════════════════════════════════════════╗
║         IHR TERMIN MORGEN              ║
╚════════════════════════════════════════╝

Datum:          {booking_date_formatted}
Uhrzeit:        {booking_time_formatted}
Dienstleistung: {service_type_label}

╔════════════════════════════════════════╗
║     BITTE NICHT VERGESSEN              ║
╚════════════════════════════════════════╝

✓ Ihr Gerät (PC/Notebook)
✓ Netzkabel und Zubehör
✓ Wichtige Passwörter aufschreiben

Falls Sie den Termin nicht wahrnehmen können,
bitten wir Sie, uns rechtzeitig zu informieren.

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789',
''["customer_firstname", "customer_lastname", "booking_date_formatted", "booking_time_formatted", "service_type_label"]''),

('reminder_1h', 'Erinnerung 1 Stunde', 'Erinnerung: Ihr Termin heute - PC-Wittfoot UG',
'Hallo {customer_firstname} {customer_lastname},

Ihr Termin bei uns findet heute statt!

╔════════════════════════════════════════╗
║         IHR TERMIN HEUTE               ║
╚════════════════════════════════════════╝

Uhrzeit:        {booking_time_formatted}
Dienstleistung: {service_type_label}

Wir freuen uns auf Sie!

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789',
''["customer_firstname", "customer_lastname", "booking_time_formatted", "service_type_label"]'');

-- Standard-Signatur einfügen
INSERT INTO email_signature (signature_text) VALUES
('Mit freundlichen Grüßen

PC-Wittfoot UG
IT-Fachbetrieb mit Herz

Musterstraße 123
12345 Musterstadt

E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789
Web: www.pc-wittfoot.de

Öffnungszeiten:
Mo-Fr: 09:00 - 18:00 Uhr
Sa:    10:00 - 14:00 Uhr');
