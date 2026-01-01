-- =============================================
-- Kundenadresse und separate Hausnummer-Felder
-- =============================================
-- Datum: 2025-12-31
-- Zweck: Kundendaten bekommen vollständige Adresse, Straße und Hausnummer sind getrennt

ALTER TABLE orders
ADD COLUMN customer_street VARCHAR(255) DEFAULT NULL AFTER customer_phone,
ADD COLUMN customer_housenumber VARCHAR(20) DEFAULT NULL AFTER customer_street,
ADD COLUMN customer_zip VARCHAR(20) DEFAULT NULL AFTER customer_housenumber,
ADD COLUMN customer_city VARCHAR(100) DEFAULT NULL AFTER customer_zip,
ADD COLUMN shipping_housenumber VARCHAR(20) DEFAULT NULL AFTER shipping_street;

-- Hinweis:
-- - Kundendaten enthalten immer eine Adresse (Straße, Hausnummer, PLZ, Ort)
-- - Lieferadresse ist optional und nur bei delivery_method = 'shipping' ausgefüllt
-- - Straße und Hausnummer sind jetzt separate Felder
