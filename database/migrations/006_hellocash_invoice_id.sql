-- Migration: HelloCash Invoice ID für Bestellungen
-- Speichert die Invoice-ID aus HelloCash für jede Bestellung

ALTER TABLE orders
ADD COLUMN hellocash_invoice_id INT DEFAULT NULL AFTER hellocash_customer_id,
ADD COLUMN hellocash_invoice_number VARCHAR(50) DEFAULT NULL AFTER hellocash_invoice_id,
ADD INDEX idx_hellocash_invoice (hellocash_invoice_id);
