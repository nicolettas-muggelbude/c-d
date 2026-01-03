-- Email-Templates thematisch umbenennen für bessere Sortierung
-- Datum: 2026-01-03

-- Terminbuchungen (Kunde)
UPDATE email_templates SET template_name = 'Terminbuchung: Bestätigung (Kunde)' WHERE template_type = 'confirmation';
UPDATE email_templates SET template_name = 'Terminbuchung: Erinnerung 24h (Kunde)' WHERE template_type = 'reminder_24h';
UPDATE email_templates SET template_name = 'Terminbuchung: Erinnerung 1h (Kunde)' WHERE template_type = 'reminder_1h';

-- Terminbuchungen (Admin)
UPDATE email_templates SET template_name = 'Terminbuchung: Neue Buchung (Admin)' WHERE template_type = 'booking_notification';

-- Bestellungen (Kunde)
UPDATE email_templates SET template_name = 'Bestellung: Bestätigung (Kunde)' WHERE template_type = 'order_confirmation';

-- Bestellungen (Admin)
UPDATE email_templates SET template_name = 'Bestellung: Neue Bestellung (Admin)' WHERE template_type = 'order_notification';
