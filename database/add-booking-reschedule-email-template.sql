-- Email-Template für Terminänderung
-- PC-Wittfoot UG

-- Template für Kunden-Benachrichtigung bei Terminänderung
INSERT INTO email_templates (template_type, template_name, subject, body, is_active, created_at)
VALUES (
    'reschedule',
    'Terminbuchung - Terminänderung (Kunde)',
    'Terminänderung bestätigt - {booking_number}',
    '<h2>Terminänderung bestätigt</h2>

<p>Hallo {customer_firstname} {customer_lastname},</p>

<p>Ihr Termin wurde erfolgreich geändert.</p>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> {old_date}</li>
    <li><strong>Uhrzeit:</strong> {old_time}</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> {booking_number}</li>
    <li><strong>Terminart:</strong> {booking_type}</li>
    <li><strong>Dienstleistung:</strong> {service_type}</li>
    <li><strong>Datum:</strong> {booking_date}</li>
    <li><strong>Uhrzeit:</strong> {booking_time}</li>
</ul>

<h3>Ihre Kontaktdaten:</h3>
<p>
    {customer_firstname} {customer_lastname}<br>
    {customer_email}<br>
    {customer_phone}
</p>

<hr>

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin jederzeit einsehen, ändern oder stornieren:</p>
<p><a href="{manage_link}" style="background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;">Termin verwalten</a></p>

<p><small>
    <strong>Wichtig:</strong><br>
    • Terminänderungen sind bis 48 Stunden vor dem Termin möglich<br>
    • Stornierungen sind bis 24 Stunden vor dem Termin möglich<br>
    • Bei späteren Änderungen kontaktieren Sie uns bitte telefonisch
</small></p>

<hr>

<p>
    Bei Fragen stehen wir Ihnen gerne zur Verfügung:<br>
    <strong>PC-Wittfoot UG</strong><br>
    Telefon: +49 123 456789<br>
    E-Mail: info@pc-wittfoot.de
</p>

<p>Mit freundlichen Grüßen<br>
Ihr Team von PC-Wittfoot</p>',
    1,
    NOW()
)
ON DUPLICATE KEY UPDATE
    template_name = VALUES(template_name),
    subject = VALUES(subject),
    body = VALUES(body),
    updated_at = NOW();
