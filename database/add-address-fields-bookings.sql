-- Füge Adressfelder zur bookings Tabelle hinzu

ALTER TABLE bookings
ADD COLUMN customer_street VARCHAR(255) NULL AFTER customer_phone_landline,
ADD COLUMN customer_house_number VARCHAR(20) NULL AFTER customer_street,
ADD COLUMN customer_postal_code VARCHAR(10) NULL AFTER customer_house_number,
ADD COLUMN customer_city VARCHAR(255) NULL AFTER customer_postal_code;

-- Index für PLZ für schnellere Suchen
ALTER TABLE bookings
ADD INDEX idx_postal_code (customer_postal_code);
