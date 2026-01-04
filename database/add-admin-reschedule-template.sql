-- Admin-Email-Template für Terminänderungen
-- PC-Wittfoot UG

INSERT INTO email_templates (template_type, template_name, subject, body, is_active, created_at)
VALUES (
    'admin_reschedule',
    'Admin - Terminänderung (Benachrichtigung)',
    'Terminänderung: Buchung {booking_number}',
    '<h2>Terminänderung</h2>

<p>Ein Kunde hat seinen Termin geändert.</p>

<h3>Buchungsdetails:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> {booking_number}</li>
    <li><strong>Kunde:</strong> {customer_firstname} {customer_lastname}</li>
    <li><strong>Email:</strong> {customer_email}</li>
    <li><strong>Telefon:</strong> {customer_phone}</li>
</ul>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> {old_date}</li>
    <li><strong>Uhrzeit:</strong> {old_time}</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Terminart:</strong> {booking_type}</li>
    <li><strong>Dienstleistung:</strong> {service_type}</li>
    <li><strong>Datum:</strong> {booking_date}</li>
    <li><strong>Uhrzeit:</strong> {booking_time}</li>
</ul>

<h3>Kundenadresse:</h3>
<p>
    {customer_street} {customer_house_number}<br>
    {customer_postal_code} {customer_city}
</p>

<p><a href="{admin_link}" style="background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;">Buchung im Admin-Bereich ansehen</a></p>

<hr>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>',
    1,
    NOW()
)
ON DUPLICATE KEY UPDATE
    template_name = VALUES(template_name),
    subject = VALUES(subject),
    body = VALUES(body),
    updated_at = NOW();
