-- Migration: Produktverwaltung - Erweiterte Felder für CSV-Import und HelloCash-Sync
-- Ermöglicht Unterscheidung zwischen Lieferanten-Produkten, HelloCash-Artikeln und manuellen Einträgen

ALTER TABLE products
ADD COLUMN source ENUM('csv_import', 'hellocash', 'manual') DEFAULT 'manual' AFTER is_active,
ADD COLUMN supplier_id INT DEFAULT NULL AFTER source,
ADD COLUMN supplier_name VARCHAR(100) DEFAULT NULL AFTER supplier_id,
ADD COLUMN supplier_stock INT DEFAULT 0 AFTER supplier_name,
ADD COLUMN in_showroom BOOLEAN DEFAULT 0 AFTER supplier_stock,
ADD COLUMN sync_with_hellocash BOOLEAN DEFAULT 0 AFTER in_showroom,
ADD COLUMN last_csv_sync DATETIME DEFAULT NULL AFTER sync_with_hellocash,
ADD INDEX idx_source (source),
ADD INDEX idx_supplier (supplier_id),
ADD INDEX idx_showroom (in_showroom);
