-- Migration: Adress-Felder zur bookings-Tabelle hinzufügen
-- Datum: 2026-01-03
-- Beschreibung: Fügt customer_street, customer_house_number, customer_postal_code, customer_city hinzu

ALTER TABLE bookings
    ADD COLUMN customer_street VARCHAR(255) NOT NULL AFTER customer_phone_landline,
    ADD COLUMN customer_house_number VARCHAR(20) NOT NULL AFTER customer_street,
    ADD COLUMN customer_postal_code VARCHAR(10) NOT NULL AFTER customer_house_number,
    ADD COLUMN customer_city VARCHAR(100) NOT NULL AFTER customer_postal_code;

-- Hinweis: Wenn bereits Daten in der Tabelle sind, setze zunächst DEFAULT-Werte:
-- ALTER TABLE bookings
--     ADD COLUMN customer_street VARCHAR(255) DEFAULT '' NOT NULL,
--     ADD COLUMN customer_house_number VARCHAR(20) DEFAULT '' NOT NULL,
--     ADD COLUMN customer_postal_code VARCHAR(10) DEFAULT '' NOT NULL,
--     ADD COLUMN customer_city VARCHAR(100) DEFAULT '' NOT NULL;
