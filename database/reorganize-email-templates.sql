-- Email-Templates neu organisieren: Buchungssystem oben, Shop unten
-- Datum: 2026-01-03

-- BUCHUNGSSYSTEM (kommt vor Shop alphabetisch)
UPDATE email_templates SET template_name = 'Buchung - Bestätigung (Kunde)' WHERE template_type = 'confirmation';
UPDATE email_templates SET template_name = 'Buchung - Neue Buchung (Admin)' WHERE template_type = 'booking_notification';
UPDATE email_templates SET template_name = 'Buchung - Erinnerung 24h (Kunde)' WHERE template_type = 'reminder_24h';
UPDATE email_templates SET template_name = 'Buchung - Erinnerung 1h (Kunde)' WHERE template_type = 'reminder_1h';

-- SHOP-BESTELLUNGEN
UPDATE email_templates SET template_name = 'Shop - Bestätigung (Kunde)' WHERE template_type = 'order_confirmation';
UPDATE email_templates SET template_name = 'Shop - Neue Bestellung (Admin)' WHERE template_type = 'order_notification';

-- Optische Trennung zwischen Buchungen und Shop (inaktives Template)
INSERT INTO email_templates (template_type, template_name, subject, body, is_active, created_at)
VALUES ('separator', '────────── SHOP-BESTELLUNGEN ──────────', '', '', 0, NOW())
ON DUPLICATE KEY UPDATE template_name = template_name;
