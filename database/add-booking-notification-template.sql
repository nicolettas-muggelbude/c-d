-- Template für Admin-Benachrichtigung bei neuen Buchungen
-- Datum: 2026-01-03

INSERT INTO email_templates (template_type, template_name, subject, body, is_active, created_at) VALUES (
    'booking_notification',
    'Admin-Benachrichtigung: Neue Buchung',
    'Neue Terminbuchung #{booking_id} - {customer_firstname} {customer_lastname}',
    'NEUE TERMINBUCHUNG

Es wurde ein neuer Termin gebucht:

--- TERMINDETAILS ---

Buchungs-ID:      #{booking_id}
Terminart:        {booking_type_label}
Dienstleistung:   {service_type_label}
Datum:            {booking_date_formatted}
Uhrzeit:          {booking_time_formatted}

--- KUNDENDATEN ---

Name:             {customer_firstname} {customer_lastname}
E-Mail:           {customer_email}
Telefon (Mobil):  {customer_phone_country} {customer_phone_mobile}
Telefon (Fest):   {customer_phone_landline}
Firma:            {customer_company}

Adresse:
{customer_street} {customer_house_number}
{customer_postal_code} {customer_city}

{customer_notes_section}

--- ADMIN-BEREICH ---

Details ansehen: {admin_booking_link}

',
    1,
    NOW()
);
