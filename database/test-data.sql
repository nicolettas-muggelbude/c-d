-- =============================================
-- PC-Wittfoot Test-Daten
-- Version: 1.0
-- Erstellt: 2025-12-31
-- =============================================

USE pc_wittfoot;

-- =============================================
-- Admin-Benutzer
-- =============================================
-- Passwort: admin123 (ACHTUNG: In Produktion ändern!)
INSERT INTO users (username, password_hash, email, full_name, role, is_active) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@pc-wittfoot.de', 'Administrator', 'admin', 1);

-- =============================================
-- Kategorien
-- =============================================
INSERT INTO categories (name, slug, description, parent_id, sort_order, is_active) VALUES
('Laptops & Notebooks', 'laptops-notebooks', 'Tragbare Computer für unterwegs', NULL, 1, 1),
('Desktop-PCs', 'desktop-pcs', 'Standcomputer für Büro und Zuhause', NULL, 2, 1),
('Monitore', 'monitore', 'Bildschirme in verschiedenen Größen', NULL, 3, 1),
('Tablets & Handys', 'tablets-handys', 'Mobile Geräte', NULL, 4, 1),
('Peripherie', 'peripherie', 'Mäuse, Tastaturen, Headsets', NULL, 5, 1),
('Drucker', 'drucker', 'Drucker und Multifunktionsgeräte', NULL, 6, 1),
('Netzwerk', 'netzwerk', 'Router, Switches, Access Points', NULL, 7, 1),
('Zubehör', 'zubehoer', 'Kabel, Adapter, Tinte, Toner', NULL, 8, 1);

-- =============================================
-- Produkte (Laptops)
-- =============================================
INSERT INTO products (sku, name, slug, short_description, description, price, stock, category_id, brand, condition_type, is_active, is_featured) VALUES
('DELL-E7470-001', 'Dell Latitude E7470', 'dell-latitude-e7470', 'Business-Laptop mit SSD', 'Dell Latitude E7470 - Professioneller Business-Laptop. Intel Core i5-6300U, 8GB RAM, 256GB SSD, 14" Full HD Display. Perfekt für Büroarbeit und mobiles Arbeiten.', 449.00, 8, 1, 'Dell', 'refurbished', 1, 1),
('HP-840-G5-001', 'HP EliteBook 840 G5', 'hp-elitebook-840-g5', 'Premium Business-Notebook', 'HP EliteBook 840 G5 - Top Business-Notebook mit Intel Core i7-8550U, 16GB RAM, 512GB SSD, 14" Full HD IPS Display. Sehr guter Zustand.', 599.00, 4, 1, 'HP', 'refurbished', 1, 1),
('LENOVO-T470-001', 'Lenovo ThinkPad T470', 'lenovo-thinkpad-t470', 'Robustes Arbeitsgerät', 'Lenovo ThinkPad T470 - Zuverlässiges Business-Notebook. Intel Core i5-7200U, 8GB RAM, 256GB SSD, 14" Full HD, legendäre ThinkPad-Tastatur.', 379.00, 6, 1, 'Lenovo', 'refurbished', 1, 0),
('DELL-P5520-001', 'Dell Precision 5520', 'dell-precision-5520', 'Workstation-Laptop', 'Dell Precision 5520 - Mobile Workstation für anspruchsvolle Aufgaben. Intel Core i7-7820HQ, 16GB RAM, 512GB SSD, 15.6" 4K Touch Display, NVIDIA Quadro.', 749.00, 2, 1, 'Dell', 'refurbished', 1, 0);

-- =============================================
-- Produkte (Desktop-PCs)
-- =============================================
INSERT INTO products (sku, name, slug, short_description, description, price, stock, category_id, brand, condition_type, is_active, is_featured) VALUES
('HP-800-G3-001', 'HP EliteDesk 800 G3 SFF', 'hp-elitedesk-800-g3-sff', 'Kompakter Desktop-PC', 'HP EliteDesk 800 G3 Small Form Factor - Platzsparender Büro-PC. Intel Core i5-7500, 8GB RAM, 256GB SSD, Windows 10 Pro. Ideal für Büroarbeit.', 329.00, 12, 2, 'HP', 'refurbished', 1, 1),
('EXONE-BIZ-3000', 'exone Business 3000', 'exone-business-3000', 'Neuer Office-PC', 'exone Business 3000 - Brandneuer Desktop-PC von Extracomputer. Intel Core i5-13400, 16GB RAM, 512GB NVMe SSD, Windows 11 Pro. Made in Germany.', 799.00, 5, 2, 'exone', 'neu', 1, 1),
('DELL-3060-001', 'Dell OptiPlex 3060 MT', 'dell-optiplex-3060-mt', 'Zuverlässiger Tower-PC', 'Dell OptiPlex 3060 MiniTower - Solider Office-PC. Intel Core i3-8100, 8GB RAM, 256GB SSD, DVD-RW, Windows 10 Pro.', 279.00, 8, 2, 'Dell', 'refurbished', 1, 0);

-- =============================================
-- Produkte (Monitore)
-- =============================================
INSERT INTO products (sku, name, slug, short_description, description, price, stock, category_id, brand, condition_type, is_active, is_featured) VALUES
('BENQ-24-001', 'BenQ GW2480 24"', 'benq-gw2480-24', 'Full HD Monitor', 'BenQ GW2480 - 24" Full HD Monitor (1920x1080), IPS-Panel, HDMI, DisplayPort, VGA. Augenschonende Technologie, perfekt für Büro.', 159.00, 6, 3, 'BenQ', 'refurbished', 1, 0),
('HP-27-001', 'HP E273q 27"', 'hp-e273q-27', 'QHD Business-Monitor', 'HP E273q - 27" QHD Monitor (2560x1440), IPS-Panel, höhenverstellbar, HDMI, DisplayPort, USB-Hub. Professioneller Business-Monitor.', 249.00, 3, 3, 'HP', 'refurbished', 1, 1);

-- =============================================
-- Produkte (Peripherie)
-- =============================================
INSERT INTO products (sku, name, slug, short_description, description, price, stock, category_id, brand, condition_type, is_active, is_featured) VALUES
('LOGI-MX-001', 'Logitech MX Master 3', 'logitech-mx-master-3', 'Premium Maus', 'Logitech MX Master 3 - Ergonomische Premium-Maus mit präzisem Sensor, mehreren programmierbaren Tasten und USB-C Schnellladung.', 89.00, 15, 5, 'Logitech', 'neu', 1, 0),
('CHERRY-KC6000', 'Cherry KC 6000 Slim', 'cherry-kc-6000-slim', 'Flache Tastatur', 'Cherry KC 6000 Slim - Hochwertige flache Tastatur mit leisen Tasten, USB-Anschluss, deutsches Layout (QWERTZ).', 39.00, 10, 5, 'Cherry', 'neu', 1, 0);

-- =============================================
-- Blog-Posts
-- =============================================
INSERT INTO blog_posts (slug, title, excerpt, content, author_id, published, published_at) VALUES
('neue-exone-pcs-eingetroffen', 'Neue exone Business-PCs eingetroffen', 'Frische Lieferung von Extracomputer ist da!', '<p>Wir haben eine neue Lieferung der beliebten <strong>exone Business-PCs</strong> erhalten!</p><p>Die Serie Business 3000 überzeugt mit aktueller Intel-Technologie, schnellen NVMe-SSDs und wird komplett in Deutschland gefertigt.</p><p>Perfekt für Büro, Home-Office und kleine Unternehmen. Jetzt im Shop verfügbar!</p>', 1, 1, NOW()),
('refurbished-laptops-warum', 'Warum refurbished Laptops?', 'Hochwertig, günstig, nachhaltig', '<p><strong>Refurbished Hardware</strong> ist generalüberholte, professionell aufbereitete Technik.</p><p>Unsere Vorteile:</p><ul><li>Bis zu 70% günstiger als Neuware</li><li>Professionell getestet und gereinigt</li><li>12 Monate Gewährleistung</li><li>Nachhaltig und umweltfreundlich</li></ul><p>Alle Geräte werden in unserer Werkstatt geprüft und mit frischer Windows-Installation ausgeliefert.</p>', 1, 1, NOW());

-- =============================================
-- Test-Bestellung
-- =============================================
INSERT INTO orders (order_number, customer_name, customer_email, customer_phone, shipping_address, billing_address, total_amount, payment_method, payment_status, order_status) VALUES
('ORD-2025-0001', 'Max Mustermann', 'max@example.com', '0123456789', 'Musterstraße 123\n12345 Musterstadt', 'Musterstraße 123\n12345 Musterstadt', 778.00, 'vorkasse', 'paid', 'completed');

INSERT INTO order_items (order_id, product_id, product_name, product_sku, quantity, unit_price, total_price) VALUES
(1, 1, 'Dell Latitude E7470', 'DELL-E7470-001', 1, 449.00, 449.00),
(1, 9, 'HP E273q 27"', 'HP-27-001', 1, 249.00, 249.00),
(1, 10, 'Logitech MX Master 3', 'LOGI-MX-001', 1, 89.00, 89.00);

-- =============================================
-- ENDE TEST-DATEN
-- =============================================

-- Zusammenfassung
SELECT 'Datenbank erfolgreich mit Test-Daten gefüllt!' AS Status;
SELECT COUNT(*) AS Kategorien FROM categories;
SELECT COUNT(*) AS Produkte FROM products;
SELECT COUNT(*) AS Blog_Posts FROM blog_posts;
SELECT COUNT(*) AS Bestellungen FROM orders;
