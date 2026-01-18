-- Migration: Blog Markdown Features
-- Datum: 2026-01-18
-- Beschreibung: Fügt Emoji, Hero-Image, Author-Name und SEO Keywords zum Blog hinzu

-- Neue Spalten hinzufügen
ALTER TABLE blog_posts
ADD COLUMN emoji VARCHAR(10) DEFAULT '📝' COMMENT 'GitHub-Style Emoji für Blog-Post' AFTER id,
ADD COLUMN hero_image VARCHAR(255) DEFAULT NULL COMMENT 'Hero-Image URL oder Upload-Pfad' AFTER emoji,
ADD COLUMN author_name VARCHAR(100) DEFAULT 'PC-Wittfoot Team' COMMENT 'Autor als Freitext (flexibel)' AFTER author_id,
ADD COLUMN keywords VARCHAR(255) DEFAULT NULL COMMENT 'SEO Keywords (comma-separated)' AFTER content;

-- Bestehende Posts: Author-Namen aus users-Tabelle migrieren
UPDATE blog_posts bp
LEFT JOIN users u ON bp.author_id = u.id
SET bp.author_name = COALESCE(u.full_name, 'PC-Wittfoot Team')
WHERE bp.author_id IS NOT NULL;

-- Index für Keywords-Suche (optional, für Performance)
ALTER TABLE blog_posts
ADD INDEX idx_keywords (keywords);
