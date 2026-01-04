-- Füge manage_token Spalte zur bookings Tabelle hinzu
-- Dieser Token ermöglicht Kunden die Verwaltung ihrer Buchung ohne Login

ALTER TABLE bookings
ADD COLUMN manage_token VARCHAR(64) NULL AFTER customer_city;

-- Index für schnelle Token-Suche
ALTER TABLE bookings
ADD UNIQUE INDEX idx_manage_token (manage_token);

-- Generiere Tokens für bestehende Buchungen
UPDATE bookings
SET manage_token = SHA2(CONCAT(id, customer_email, created_at, RAND()), 256)
WHERE manage_token IS NULL;
