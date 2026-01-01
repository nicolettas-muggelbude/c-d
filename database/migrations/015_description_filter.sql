-- Migration: Beschreibungs-Filter für Lieferanten
-- Ermöglicht das Entfernen bestimmter Texte/Wörter aus Produktbeschreibungen beim CSV-Import

ALTER TABLE suppliers
ADD COLUMN description_filter TEXT DEFAULT NULL AFTER column_mapping;
