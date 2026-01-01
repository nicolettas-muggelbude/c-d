-- Füge HelloCash Customer ID zur Bookings-Tabelle hinzu
-- PC-Wittfoot UG

ALTER TABLE bookings
ADD COLUMN hellocash_customer_id VARCHAR(100) NULL AFTER customer_phone_landline,
ADD INDEX idx_hellocash_customer_id (hellocash_customer_id);
