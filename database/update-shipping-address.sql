-- =============================================
-- Separate Lieferadresse-Felder hinzufügen
-- =============================================
-- Datum: 2025-12-31
-- Zweck: Lieferadresse kann von Kundendaten abweichen

ALTER TABLE orders
ADD COLUMN shipping_firstname VARCHAR(100) DEFAULT NULL AFTER customer_phone,
ADD COLUMN shipping_lastname VARCHAR(100) DEFAULT NULL AFTER shipping_firstname,
ADD COLUMN shipping_street VARCHAR(255) DEFAULT NULL AFTER shipping_lastname,
ADD COLUMN shipping_zip VARCHAR(20) DEFAULT NULL AFTER shipping_street,
ADD COLUMN shipping_city VARCHAR(100) DEFAULT NULL AFTER shipping_zip;

-- Hinweis:
-- - Wenn delivery_method = 'pickup': alle shipping_* Felder bleiben NULL
-- - Wenn delivery_method = 'shipping' und keine abweichende Adresse: alle shipping_* Felder bleiben NULL
-- - Wenn abweichende Lieferadresse: shipping_* Felder werden ausgefüllt
