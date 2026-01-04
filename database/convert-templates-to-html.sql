-- Email-Templates: Konvertierung von Plain-Text zu HTML
-- PC-Wittfoot UG
-- Alle Templates werden auf modernes HTML-Format aktualisiert

-- 1. Buchungsbestätigung (Kunde)
UPDATE email_templates
SET body = '<h2>Terminbestätigung</h2>

<p>Moin {customer_firstname} {customer_lastname},</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> {booking_number}</li>
    <li><strong>Terminart:</strong> {booking_type}</li>
    <li><strong>Dienstleistung:</strong> {service_type}</li>
    <li><strong>Datum:</strong> {booking_date}</li>
    <li><strong>Uhrzeit:</strong> {booking_time}</li>
</ul>

{customer_notes_section}

<hr>

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin jederzeit online verwalten:</p>
<p><a href="{manage_link}" style="background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;">Termin verwalten</a></p>

<p><small>
    Hier können Sie:<br>
    ✓ Ihre Buchungsdetails einsehen<br>
    ✓ Den Termin ändern (bis 48h vorher)<br>
    ✓ Den Termin stornieren (bis 24h vorher)
</small></p>

<hr>

<h3>Bitte mitbringen</h3>
<p><small>
    Evt. nach Absprache:<br>
    ✓ Ihr Gerät (PC/Notebook)<br>
    ✓ Netzteil<br>
    ✓ Wichtige Zugangsdaten/Passwörter aufschreiben
</small></p>

<hr>

<p>
    Bei Fragen erreichen Sie uns unter:<br>
    <strong>PC-Wittfoot UG</strong><br>
    E-Mail: info@pc-wittfoot.de<br>
    Telefon: +49 441 40576020
</p>

<p>Wir freuen uns auf Ihren Besuch!</p>',
    updated_at = NOW()
WHERE template_type = 'confirmation';

-- 2. Neue Buchung (Admin)
UPDATE email_templates
SET body = '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> {booking_number}</li>
    <li><strong>Terminart:</strong> {booking_type}</li>
    <li><strong>Dienstleistung:</strong> {service_type}</li>
    <li><strong>Datum:</strong> {booking_date}</li>
    <li><strong>Uhrzeit:</strong> {booking_time}</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> {customer_firstname} {customer_lastname}</li>
    <li><strong>E-Mail:</strong> {customer_email}</li>
    <li><strong>Telefon (Mobil):</strong> {customer_phone}</li>
    <li><strong>Telefon (Fest):</strong> {customer_phone_landline}</li>
    <li><strong>Firma:</strong> {customer_company}</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    {customer_street} {customer_house_number}<br>
    {customer_postal_code} {customer_city}
</p>

{customer_notes_section}

<hr>

<p><a href="{admin_link}" style="background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>',
    updated_at = NOW()
WHERE template_type = 'booking_notification';

-- 3. Stornierungsbestätigung (Kunde)
UPDATE email_templates
SET body = '<h2>Stornierung bestätigt</h2>

<p>Moin {customer_firstname} {customer_lastname},</p>

<p>Ihre Terminbuchung wurde erfolgreich storniert.</p>

<h3>Stornierte Buchung</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> {booking_number}</li>
    <li><strong>Terminart:</strong> {booking_type}</li>
    <li><strong>Dienstleistung:</strong> {service_type}</li>
    <li><strong>Datum:</strong> {booking_date}</li>
    <li><strong>Uhrzeit:</strong> {booking_time}</li>
</ul>

<p>Sie erhalten keine weitere Bestätigung.</p>

<hr>

<h3>Neuen Termin buchen</h3>
<p>Sie können jederzeit einen neuen Termin online buchen:</p>
<p><a href="http://localhost:8000/termin" style="background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;">Neuen Termin buchen</a></p>

<hr>

<p>
    Bei Fragen erreichen Sie uns unter:<br>
    <strong>PC-Wittfoot UG</strong><br>
    E-Mail: info@pc-wittfoot.de<br>
    Telefon: +49 441 40576020
</p>

<p>Wir hoffen, Sie bald wieder begrüßen zu dürfen!</p>',
    updated_at = NOW()
WHERE template_type = 'cancellation';

-- 4. Erinnerung 24h (Kunde)
UPDATE email_templates
SET body = '<h2>Erinnerung: Ihr Termin morgen</h2>

<p>Moin {customer_firstname} {customer_lastname},</p>

<p>dies ist eine Erinnerung an Ihren Termin bei uns!</p>

<h3>Ihr Termin morgen</h3>
<ul>
    <li><strong>Datum:</strong> {booking_date}</li>
    <li><strong>Uhrzeit:</strong> {booking_time}</li>
    <li><strong>Dienstleistung:</strong> {service_type}</li>
</ul>

<hr>

<h3>Bitte nicht vergessen</h3>
<p><small>
    Evt. nach Absprache:<br>
    ✓ Ihr Gerät (PC/Notebook)<br>
    ✓ Netzteil<br>
    ✓ Wichtige Zugangsdaten/Passwörter aufschreiben
</small></p>

<p><small>Falls Sie den Termin nicht wahrnehmen können, bitten wir Sie, uns rechtzeitig zu informieren.</small></p>

<hr>

<p>
    Bei Fragen erreichen Sie uns unter:<br>
    <strong>PC-Wittfoot UG</strong><br>
    E-Mail: info@pc-wittfoot.de<br>
    Telefon: +49 441 40576020
</p>',
    updated_at = NOW()
WHERE template_type = 'reminder_24h';

-- 5. Erinnerung 1h (Kunde)
UPDATE email_templates
SET body = '<h2>Erinnerung: Ihr Termin heute</h2>

<p>Moin {customer_firstname} {customer_lastname},</p>

<p>Ihr Termin bei uns findet heute statt!</p>

<h3>Ihr Termin heute</h3>
<ul>
    <li><strong>Uhrzeit:</strong> {booking_time}</li>
    <li><strong>Dienstleistung:</strong> {service_type}</li>
</ul>

<p><strong>Wir freuen uns auf Sie!</strong></p>

<hr>

<p>
    Bei Fragen erreichen Sie uns unter:<br>
    <strong>PC-Wittfoot UG</strong><br>
    E-Mail: info@pc-wittfoot.de<br>
    Telefon: +49 441 40576020
</p>',
    updated_at = NOW()
WHERE template_type = 'reminder_1h';

-- 6. Bestellbestätigung (Kunde)
UPDATE email_templates
SET body = '<h2>Bestellbestätigung</h2>

<p>Hallo {customer_firstname} {customer_lastname},</p>

<p>vielen Dank für Ihre Bestellung bei PC-Wittfoot UG!</p>

<h3>Bestelldetails</h3>
<ul>
    <li><strong>Bestellnummer:</strong> {order_number}</li>
    <li><strong>Bestelldatum:</strong> {order_date}</li>
</ul>

<hr>

<h3>Ihre Bestellung</h3>
{order_items}

<p>
    <strong>Zwischensumme:</strong> {order_subtotal}<br>
    <strong>MwSt (19%):</strong> {order_tax}<br>
    <strong>Gesamt:</strong> {order_total}
</p>

<p>
    <strong>Lieferart:</strong> {delivery_method}<br>
    <strong>Zahlungsart:</strong> {payment_method}
</p>

{invoice_link_section}

<hr>

<p>Wir melden uns in Kürze bei Ihnen mit weiteren Details.</p>

<p>
    Bei Fragen erreichen Sie uns unter:<br>
    <strong>PC-Wittfoot UG</strong><br>
    E-Mail: info@pc-wittfoot.de<br>
    Telefon: +49 441 40576020
</p>

<p>Mit freundlichen Grüßen<br>
Ihr Team von PC-Wittfoot UG</p>',
    updated_at = NOW()
WHERE template_type = 'order_confirmation';

-- 7. Neue Bestellung (Admin)
UPDATE email_templates
SET body = '<h2>Neue Bestellung</h2>

<p>Eine neue Bestellung ist eingegangen.</p>

<h3>Bestelldetails</h3>
<ul>
    <li><strong>Bestellnummer:</strong> {order_number}</li>
    <li><strong>Bestelldatum:</strong> {order_date}</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> {customer_firstname} {customer_lastname}</li>
    {customer_company_line}
    <li><strong>E-Mail:</strong> {customer_email}</li>
    {customer_phone_line}
    <li><strong>Adresse:</strong> {customer_address}</li>
</ul>

<hr>

<h3>Bestellpositionen</h3>
{order_items}

<p><strong>Gesamt:</strong> {order_total}</p>

<p>
    <strong>Lieferart:</strong> {delivery_method}<br>
    <strong>Zahlungsart:</strong> {payment_method}
</p>

{order_notes_section}
{invoice_link_section}

<hr>

<p><a href="{admin_order_link}" style="background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;">Bestellung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>',
    updated_at = NOW()
WHERE template_type = 'order_notification';
