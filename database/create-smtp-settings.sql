-- SMTP-Einstellungen Tabelle
-- PC-Wittfoot UG

CREATE TABLE IF NOT EXISTS smtp_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    smtp_enabled BOOLEAN DEFAULT 0 COMMENT '0 = PHP mail(), 1 = SMTP',
    smtp_host VARCHAR(255) DEFAULT 'smtp.gmail.com',
    smtp_port INT DEFAULT 587,
    smtp_encryption ENUM('tls', 'ssl', 'none') DEFAULT 'tls',
    smtp_username VARCHAR(255) DEFAULT '',
    smtp_password VARCHAR(255) DEFAULT '',
    smtp_debug INT DEFAULT 0 COMMENT '0 = off, 1 = errors, 2 = verbose',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Standard-Einstellungen einfügen
INSERT INTO smtp_settings (smtp_enabled, smtp_host, smtp_port, smtp_encryption, smtp_username, smtp_password, smtp_debug)
VALUES (0, 'smtp.gmail.com', 587, 'tls', '', '', 0);
