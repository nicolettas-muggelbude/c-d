-- Migration: Kategorie-Mapping für CSV-Import
-- Ermöglicht Zuordnung von CSV-Kategorien zu Shop-Kategorien

ALTER TABLE suppliers
ADD COLUMN category_mapping JSON DEFAULT NULL AFTER description_filter;
