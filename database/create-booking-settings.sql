-- Terminbuchungs-Einstellungen
-- PC-Wittfoot UG

CREATE TABLE IF NOT EXISTS booking_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL,
    description VARCHAR(255) NULL,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,

    KEY idx_setting_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Standard-Einstellungen einfügen
INSERT INTO booking_settings (setting_key, setting_value, description) VALUES
('booking_start_time', '11:00', 'Erste verfügbare Buchungszeit (Format: HH:MM)'),
('booking_end_time', '17:00', 'Letzte verfügbare Buchungszeit (Format: HH:MM)'),
('booking_interval_minutes', '60', 'Zeitabstand zwischen Terminen in Minuten'),
('max_bookings_per_slot', '1', 'Maximale Anzahl Buchungen pro Zeitslot')
ON DUPLICATE KEY UPDATE
    setting_value = VALUES(setting_value),
    description = VALUES(description);
