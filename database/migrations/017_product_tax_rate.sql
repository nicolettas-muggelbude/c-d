-- Migration 017: Steuersatz für Produkte
-- Datum: 2026-01-02
-- Beschreibung: Fügt Steuersatz-Feld hinzu (Standard 19%, optional 7% oder 0%)

ALTER TABLE products
ADD COLUMN tax_rate DECIMAL(5,2) DEFAULT 19.00 AFTER price,
ADD INDEX idx_tax_rate (tax_rate);

-- Kommentar
COMMENT ON COLUMN products.tax_rate IS 'Steuersatz in Prozent (Standard: 19.00, optional: 7.00 oder 0.00)';
