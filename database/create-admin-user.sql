-- Test-Admin-User erstellen
-- Passwort: admin123

INSERT INTO users (username, password_hash, email, full_name, role, is_active)
VALUES (
    'admin',
    '$2y$10$NEkz7BVra8wQaJgssrTu3uL.lC9kFIhaJ22gmonto2AymiaroybYi', -- admin123
    'admin@pc-wittfoot.de',
    'Admin User',
    'admin',
    1
);
