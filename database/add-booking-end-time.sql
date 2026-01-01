-- Endzeit für Termine hinzufügen (für Zeiträume über mehrere Stunden)

ALTER TABLE bookings
ADD COLUMN booking_end_time TIME NULL AFTER booking_time;

-- Index für Performance
ALTER TABLE bookings
ADD INDEX idx_booking_times (booking_date, booking_time, booking_end_time);
