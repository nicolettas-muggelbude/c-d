-- Migration: Versandkosten-Option für Produkte und Lieferanten
-- Ermöglicht Markierung als "Versandkostenfrei Deutschland"
-- Lieferanten-Einstellung wird bei CSV-Import auf Produkte übertragen

-- Versandkosten-Option für Lieferanten
ALTER TABLE suppliers
ADD COLUMN free_shipping BOOLEAN DEFAULT 0 AFTER price_markup,
ADD INDEX idx_free_shipping (free_shipping);

-- Versandkosten-Option für Produkte
ALTER TABLE products
ADD COLUMN free_shipping BOOLEAN DEFAULT 0 AFTER in_showroom,
ADD INDEX idx_free_shipping_products (free_shipping);
