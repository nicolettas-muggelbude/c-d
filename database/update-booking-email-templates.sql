-- Update Buchungsbestätigung mit Management-Link
UPDATE email_templates
SET body = 'Moin {customer_firstname} {customer_lastname},

vielen Dank für Ihre Terminbuchung!

╔════════════════════════════════════╗
║         TERMINDETAILS              ║
╚════════════════════════════════════╝

Buchungsnummer: #{booking_id}
Terminart:      {booking_type_label}
Dienstleistung: {service_type_label}
Datum:          {booking_date_formatted}
Uhrzeit:        {booking_time_formatted}

{customer_notes_section}

╔════════════════════════════════════╗
║  TERMIN VERWALTEN                  ║
╚════════════════════════════════════╝

Sie können Ihren Termin jederzeit online verwalten:
{manage_link}

Hier können Sie:
✓ Ihre Buchungsdetails einsehen
✓ Den Termin ändern (bis 48h vorher)
✓ Den Termin stornieren (bis 24h vorher)

╔════════════════════════════════════╗
║     BITTE MITBRINGEN               ║
╚════════════════════════════════════╝

Evt. nach Absprache:
✓ Ihr Gerät (PC/Notebook)
✓ Netzteil
✓ Wichtige Zugangsdaten/Passwörter aufschreiben

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789

Wir freuen uns auf Ihren Besuch!'
WHERE template_type = 'confirmation';

-- Neue Template für Stornierungsbestätigung
INSERT INTO email_templates (template_type, template_name, subject, body, is_active)
VALUES (
    'cancellation',
    'Terminbuchung - Stornierungsbestätigung',
    'Stornierung bestätigt - Buchung #{booking_id}',
    'Moin {customer_firstname} {customer_lastname},

Ihre Terminbuchung wurde erfolgreich storniert.

╔════════════════════════════════════╗
║    STORNIERTE BUCHUNG              ║
╚════════════════════════════════════╝

Buchungsnummer: #{booking_id}
Terminart:      {booking_type_label}
Dienstleistung: {service_type_label}
Datum:          {booking_date_formatted}
Uhrzeit:        {booking_time_formatted}

Sie erhalten keine weitere Bestätigung.

╔════════════════════════════════════╗
║    NEUEN TERMIN BUCHEN             ║
╚════════════════════════════════════╝

Sie können jederzeit einen neuen Termin online buchen unter:
http://localhost:8000/termin

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789

Wir hoffen, Sie bald wieder begrüßen zu dürfen!',
    1
);
