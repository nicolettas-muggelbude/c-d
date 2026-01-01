-- Migration: CSV-Import-System für Lieferanten-Produkte
-- Ermöglicht automatischen Import von Produkten aus CSV-Dateien verschiedener Lieferanten

-- Lieferanten-Tabelle
CREATE TABLE IF NOT EXISTS suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    csv_url VARCHAR(255) DEFAULT NULL COMMENT 'URL oder Pfad zur CSV-Datei',
    csv_delimiter CHAR(1) DEFAULT ',' COMMENT 'CSV-Trennzeichen',
    csv_encoding VARCHAR(20) DEFAULT 'UTF-8' COMMENT 'CSV-Encoding',
    column_mapping JSON DEFAULT NULL COMMENT 'Mapping CSV-Spalten zu Produkt-Feldern',
    price_markup DECIMAL(5,2) DEFAULT 0.00 COMMENT 'Aufschlag in % auf Lieferanten-Preis',
    is_active BOOLEAN DEFAULT 1,
    last_import_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Import-Log-Tabelle
CREATE TABLE IF NOT EXISTS product_import_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT NOT NULL,
    status ENUM('running', 'completed', 'failed') DEFAULT 'running',
    imported_count INT DEFAULT 0 COMMENT 'Neue Produkte',
    updated_count INT DEFAULT 0 COMMENT 'Aktualisierte Produkte',
    skipped_count INT DEFAULT 0 COMMENT 'Übersprungene Zeilen',
    error_count INT DEFAULT 0 COMMENT 'Fehlerhafte Zeilen',
    log_details JSON DEFAULT NULL COMMENT 'Detaillierte Fehler und Warnungen',
    duration_seconds INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at DATETIME DEFAULT NULL,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE,
    INDEX idx_supplier (supplier_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Produkt-Tabelle: supplier_id-Spalte mit Foreign Key
ALTER TABLE products
ADD CONSTRAINT fk_products_supplier
FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL;
