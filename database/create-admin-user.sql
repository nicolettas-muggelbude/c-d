-- Test-Admin-User erstellen
-- Passwort: admin123

INSERT INTO users (username, password_hash, email, full_name, role, is_active)
VALUES (
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- admin123
    'admin@pc-wittfoot.de',
    'Admin User',
    'admin',
    1
);
