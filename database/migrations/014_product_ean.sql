-- Migration: EAN-Feld für Produkte
-- Barcode/EAN für Adminbereich und HelloCash-Integration

ALTER TABLE products
ADD COLUMN ean VARCHAR(20) DEFAULT NULL AFTER sku,
ADD INDEX idx_ean (ean);
