-- Terminbuchungen Tabelle
-- Unterstützt zwei Terminarten: Feste Termine und Walk-in Anmeldungen

CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,

    -- Terminart: 'fixed' = Fester Termin, 'walkin' = Ich komme vorbei
    booking_type VARCHAR(20) NOT NULL DEFAULT 'fixed',

    -- Dienstleistung/Grund
    service_type VARCHAR(100) NOT NULL,

    -- Datum und Zeit
    booking_date DATE NOT NULL,
    booking_time TIME NULL, -- NULL bei walk-in

    -- Kundendaten
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50) NOT NULL,
    customer_notes TEXT NULL,

    -- Status: 'pending', 'confirmed', 'cancelled', 'completed'
    status VARCHAR(20) NOT NULL DEFAULT 'pending',

    -- Zeitstempel
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,

    -- Indizes für schnelle Abfragen
    KEY idx_booking_date (booking_date),
    KEY idx_booking_type (booking_type),
    KEY idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Beispiel-Dienstleistungen als Referenz:
-- 'pc-reparatur' = PC-Reparatur
-- 'beratung' = Beratung
-- 'software' = Software-Installation
-- 'datenrettung' = Datenrettung
-- 'sonstiges' = Sonstiges
