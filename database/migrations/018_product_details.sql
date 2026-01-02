-- Migration 018: Detaillierte Produktinformationen
-- Datum: 2026-01-02
-- Beschreibung: Garantie und Multiple Bilder
-- Hinweis: condition_type existiert bereits

ALTER TABLE products
ADD COLUMN warranty_months INT DEFAULT 24 COMMENT 'Garantie in Monaten' AFTER condition_type,
ADD COLUMN images JSON DEFAULT NULL COMMENT 'Zusätzliche Produktbilder (Array mit bis zu 5 URLs)' AFTER image_url;
