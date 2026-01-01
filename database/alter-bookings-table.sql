-- Erweitere bookings Tabelle für neue Kundenfelder

-- Alte customer_name Spalte entfernen und durch getrennte Felder ersetzen
ALTER TABLE bookings
DROP COLUMN customer_name;

ALTER TABLE bookings
ADD COLUMN customer_firstname VARCHAR(255) NOT NULL AFTER customer_notes,
ADD COLUMN customer_lastname VARCHAR(255) NOT NULL AFTER customer_firstname,
ADD COLUMN customer_company VARCHAR(255) NULL AFTER customer_lastname;

-- Telefon aufteilen in Mobil und Festnetz mit Ländervorwahl
ALTER TABLE bookings
DROP COLUMN customer_phone;

ALTER TABLE bookings
ADD COLUMN customer_phone_country VARCHAR(10) NOT NULL DEFAULT '+49' AFTER customer_company,
ADD COLUMN customer_phone_mobile VARCHAR(50) NOT NULL AFTER customer_phone_country,
ADD COLUMN customer_phone_landline VARCHAR(50) NULL AFTER customer_phone_mobile,
ADD COLUMN customer_landline_country VARCHAR(10) NULL DEFAULT '+49' AFTER customer_phone_landline;
