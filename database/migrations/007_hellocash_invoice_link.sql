-- Migration: HelloCash Invoice Link für digitale Rechnungen
-- Speichert den öffentlichen Link zur Rechnung in HelloCash

ALTER TABLE orders
ADD COLUMN hellocash_invoice_link TEXT DEFAULT NULL AFTER hellocash_invoice_number;
