-- Migration: Passwort-Reset-System
-- Datum: 2025-01-XX
-- Beschreibung: Tabelle für Passwort-Reset-Tokens

CREATE TABLE IF NOT EXISTS password_reset_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    expires_at TIMESTAMP NOT NULL,
    used BOOLEAN DEFAULT FALSE,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    used_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_token (token),
    INDEX idx_expires (expires_at)
);

-- Alte/abgelaufene Tokens automatisch löschen (über Cron-Job oder manuell)
-- DELETE FROM password_reset_tokens WHERE expires_at < NOW() OR used = TRUE;
