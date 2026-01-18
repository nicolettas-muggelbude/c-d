-- Migration: Blog Kategorien & Such-Tracking
-- Datum: 2026-01-18
-- Beschreibung: Kategorien für Blog-Posts + Such-Tracking für "Häufig gesucht"

-- Kategorien-Spalte für Blog-Posts
ALTER TABLE blog_posts
ADD COLUMN category VARCHAR(50) DEFAULT 'Allgemein' COMMENT 'Kategorie: Allgemein, Hardware, Software, Tipps, News' AFTER keywords;

-- Index für Kategorie-Filter
ALTER TABLE blog_posts
ADD INDEX idx_category (category, published);

-- Such-Tracking Tabelle
CREATE TABLE IF NOT EXISTS blog_search_queries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    query VARCHAR(255) NOT NULL,
    results_count INT DEFAULT 0,
    searched_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_query (query),
    INDEX idx_searched_at (searched_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Kategorien für bestehende Posts setzen (basierend auf Keywords)
UPDATE blog_posts
SET category = CASE
    WHEN keywords LIKE '%Hardware%' OR keywords LIKE '%Laptop%' OR keywords LIKE '%PC%' THEN 'Hardware'
    WHEN keywords LIKE '%Software%' OR keywords LIKE '%Windows%' OR keywords LIKE '%Programm%' THEN 'Software'
    WHEN keywords LIKE '%Tipp%' OR keywords LIKE '%Anleitung%' OR keywords LIKE '%Tutorial%' THEN 'Tipps'
    WHEN keywords LIKE '%News%' OR keywords LIKE '%Neuigkeiten%' THEN 'News'
    ELSE 'Allgemein'
END
WHERE published = 1;
