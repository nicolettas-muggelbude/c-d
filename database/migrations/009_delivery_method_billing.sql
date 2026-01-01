-- Migration: Delivery Method - Billing Option
-- Fügt "billing" (Versand an Rechnungsadresse) als Lieferart hinzu

ALTER TABLE orders
MODIFY COLUMN delivery_method ENUM('billing', 'pickup', 'shipping') DEFAULT NULL;
