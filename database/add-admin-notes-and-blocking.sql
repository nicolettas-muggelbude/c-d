-- Admin-Notizen und Sperrung von Zeiträumen

-- Admin-Notizen Feld hinzufügen (intern, für Admin-Team)
ALTER TABLE bookings
ADD COLUMN admin_notes TEXT NULL AFTER customer_notes;

-- Neuer Buchungstyp für Sperrungen und interne Notizen
-- Erweitere booking_type ENUM
-- Alte Werte: 'fixed', 'walkin'
-- Neue Werte: 'fixed', 'walkin', 'blocked', 'internal'

-- Zuerst die Spalte ändern
ALTER TABLE bookings
MODIFY COLUMN booking_type VARCHAR(20) NOT NULL DEFAULT 'fixed';

-- Optional: Index für schnellere Admin-Suche
ALTER TABLE bookings
ADD INDEX idx_booking_type_date (booking_type, booking_date);
