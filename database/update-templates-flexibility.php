<?php
/**
 * Email-Templates aktualisieren: Flexibilitäts-Hinweis hinzufügen
 * PC-Wittfoot UG
 */

require_once __DIR__ . '/../src/core/config.php';

$db = Database::getInstance();

// Buchungsbestätigung
$confirmationBody = '<h2>Terminbestätigung</h2>

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

<p>{flexibility_note}</p>

{customer_notes_section}

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin online verwalten (umbuchen oder stornieren):</p>
<p><a href="{manage_link}" style="background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;">Termin verwalten</a></p>

<p><strong>Wichtig:</strong></p>
<ul>
    <li>Terminänderungen sind bis 48 Stunden vorher möglich</li>
    <li>Stornierungen sind bis 24 Stunden vorher möglich</li>
    <li>Bei kurzfristigeren Änderungen kontaktieren Sie uns bitte telefonisch</li>
</ul>

<p>Wir freuen uns auf Ihren Besuch!</p>';

$db->update(
    "UPDATE email_templates SET body = :body, updated_at = NOW() WHERE template_type = 'confirmation'",
    [':body' => $confirmationBody]
);
echo "✓ Buchungsbestätigung aktualisiert\n";

// Terminänderung
$rescheduleBody = '<h2>Terminänderung bestätigt</h2>

<p>Moin {customer_firstname} {customer_lastname},</p>

<p>Ihre Terminänderung wurde erfolgreich gespeichert.</p>

<h3>Alte Termindaten</h3>
<ul>
    <li><strong>Datum:</strong> {old_date}</li>
    <li><strong>Uhrzeit:</strong> {old_time}</li>
</ul>

<h3>Neue Termindaten</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> {booking_number}</li>
    <li><strong>Terminart:</strong> {booking_type}</li>
    <li><strong>Dienstleistung:</strong> {service_type}</li>
    <li><strong>Datum:</strong> {booking_date}</li>
    <li><strong>Uhrzeit:</strong> {booking_time}</li>
</ul>

<p>{flexibility_note}</p>

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin weiterhin online verwalten:</p>
<p><a href="{manage_link}" style="background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;">Termin verwalten</a></p>

<p>Wir freuen uns auf Ihren Besuch!</p>';

$db->update(
    "UPDATE email_templates SET body = :body, updated_at = NOW() WHERE template_type = 'reschedule'",
    [':body' => $rescheduleBody]
);
echo "✓ Terminänderung aktualisiert\n";

// 24h Erinnerung
$reminder24hBody = '<h2>Terminerinnerung</h2>

<p>Moin {customer_firstname} {customer_lastname},</p>

<p>dies ist eine freundliche Erinnerung an Ihren morgigen Termin:</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> {booking_number}</li>
    <li><strong>Terminart:</strong> {booking_type}</li>
    <li><strong>Dienstleistung:</strong> {service_type}</li>
    <li><strong>Datum:</strong> {booking_date}</li>
    <li><strong>Uhrzeit:</strong> {booking_time}</li>
</ul>

<p>{flexibility_note}</p>

<p><strong>Hinweis:</strong> Stornierungen sind nur noch bis heute Abend möglich. Bei kurzfristigen Änderungen kontaktieren Sie uns bitte telefonisch.</p>

<p>Wir freuen uns auf Ihren Besuch!</p>';

$db->update(
    "UPDATE email_templates SET body = :body, updated_at = NOW() WHERE template_type = 'reminder_24h'",
    [':body' => $reminder24hBody]
);
echo "✓ 24h Erinnerung aktualisiert\n";

// 1h Erinnerung
$reminder1hBody = '<h2>Ihr Termin steht bevor</h2>

<p>Moin {customer_firstname} {customer_lastname},</p>

<p>Ihr Termin bei PC-Wittfoot findet in Kürze statt:</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> {booking_number}</li>
    <li><strong>Terminart:</strong> {booking_type}</li>
    <li><strong>Dienstleistung:</strong> {service_type}</li>
    <li><strong>Datum:</strong> {booking_date}</li>
    <li><strong>Uhrzeit:</strong> {booking_time}</li>
</ul>

<p>{flexibility_note}</p>

<p>Wir freuen uns, Sie gleich bei uns begrüßen zu dürfen!</p>';

$db->update(
    "UPDATE email_templates SET body = :body, updated_at = NOW() WHERE template_type = 'reminder_1h'",
    [':body' => $reminder1hBody]
);
echo "✓ 1h Erinnerung aktualisiert\n";

echo "\n✅ Alle Templates erfolgreich aktualisiert!\n";
