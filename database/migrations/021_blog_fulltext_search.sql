-- Migration: Blog Fulltext-Suche
-- Datum: 2026-01-18
-- Beschreibung: FULLTEXT-Index für schnelle Blog-Suche

-- FULLTEXT-Index für bessere Suchperformance
ALTER TABLE blog_posts
ADD FULLTEXT INDEX idx_blog_search (title, excerpt, content, keywords);

-- Index für author_name (normale Suche)
ALTER TABLE blog_posts
ADD INDEX idx_author_name (author_name);
