-- Migration: Shop Email-Templates
-- Fügt konfigurierbare E-Mail-Templates für Shop-Bestellungen hinzu

INSERT INTO email_templates (template_name, template_type, subject, body, is_active, created_at) VALUES
(
    'Bestellbestätigung (Kunde)',
    'order_confirmation',
    'Bestellbestätigung #{order_number} - PC-Wittfoot UG',
    'Hallo {customer_firstname} {customer_lastname},

vielen Dank für Ihre Bestellung bei PC-Wittfoot UG!

Bestellnummer: {order_number}
Bestelldatum: {order_date}

--- Ihre Bestellung ---

{order_items}

Zwischensumme: {order_subtotal}
MwSt (19%): {order_tax}
Gesamt: {order_total}

Lieferart: {delivery_method}
Zahlungsart: {payment_method}

{invoice_link_section}

Wir melden uns in Kürze bei Ihnen mit weiteren Details.

Mit freundlichen Grüßen
Ihr Team von PC-Wittfoot UG',
    1,
    NOW()
),
(
    'Bestellbenachrichtigung (Admin)',
    'order_notification',
    'Neue Bestellung #{order_number} im Shop',
    'Eine neue Bestellung ist eingegangen:

Bestellnummer: {order_number}
Bestelldatum: {order_date}

--- Kunde ---
Name: {customer_firstname} {customer_lastname}
{customer_company_line}E-Mail: {customer_email}
{customer_phone_line}Adresse: {customer_address}

--- Bestellpositionen ---

{order_items}

Gesamt: {order_total}

Lieferart: {delivery_method}
Zahlungsart: {payment_method}

{order_notes_section}
{invoice_link_section}

Bestellung im Admin-Bereich ansehen:
{admin_order_link}',
    1,
    NOW()
);
