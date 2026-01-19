-- Migration 023: Hero Image Alt-Text für Barrierefreiheit
-- Fügt separates Alt-Feld für Hero-Bilder hinzu

ALTER TABLE blog_posts
ADD COLUMN hero_image_alt VARCHAR(255) DEFAULT NULL COMMENT 'Alt-Text für Hero-Bild (Barrierefreiheit)' AFTER hero_image;
