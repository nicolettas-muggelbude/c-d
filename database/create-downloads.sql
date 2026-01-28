-- Downloads-System für Dateien und Software
-- PC-Wittfoot UG

CREATE TABLE IF NOT EXISTS downloads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL COMMENT 'Anzeigename des Downloads',
    slug VARCHAR(255) UNIQUE NOT NULL COMMENT 'URL-freundlicher Slug',
    description TEXT COMMENT 'Beschreibung des Downloads',
    version VARCHAR(50) NULL COMMENT 'Versionsnummer (z.B. 2.4.1, Jan 2026)',
    category ENUM('tools', 'drivers', 'documentation', 'updates', 'other') DEFAULT 'other' COMMENT 'Kategorie des Downloads',
    filename VARCHAR(255) NOT NULL COMMENT 'Dateiname auf dem Server (in /uploads/downloads/)',
    file_size BIGINT NULL COMMENT 'Dateigröße in Bytes',
    file_type VARCHAR(50) NULL COMMENT 'MIME-Type (z.B. application/pdf, application/zip)',
    download_count INT DEFAULT 0 COMMENT 'Anzahl der Downloads',
    is_active BOOLEAN DEFAULT 1 COMMENT 'Ist der Download verfügbar?',
    sort_order INT DEFAULT 0 COMMENT 'Sortierreihenfolge (niedrigere Werte zuerst)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_active (is_active),
    INDEX idx_category (category),
    INDEX idx_sort (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Beispiel-Daten einfügen
INSERT INTO downloads (title, slug, description, version, category, filename, file_size, file_type, sort_order) VALUES
('Backup-Tool Pro', 'backup-tool-pro', 'Automatisches Backup-Tool für Windows-Systeme. Erstellt regelmäßige Sicherungen Ihrer wichtigen Dateien.', 'v2.4.1', 'tools', 'backup-tool-pro-2.4.1.exe', 15932416, 'application/x-msdownload', 10),
('System-Diagnose-Tool', 'system-diagnose-tool', 'Analysiert Ihr System und findet Probleme mit Hardware, Treibern und Software.', 'v1.8.0', 'tools', 'system-diagnose-1.8.0.exe', 9114624, 'application/x-msdownload', 20),
('IT-Service Preisliste', 'preisliste-2026', 'Aktuelle Preisübersicht unserer Dienstleistungen und Reparatur-Services.', 'Januar 2026', 'documentation', 'pc-wittfoot-preisliste-2026.pdf', 2411520, 'application/pdf', 30);
