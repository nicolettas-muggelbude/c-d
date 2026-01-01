-- Migration: Shop HelloCash Integration
-- Fehlende Spalten für kasse.php und HelloCash-Integration

-- Kundendaten aufteilen in Vor-/Nachname/Firma
ALTER TABLE orders
ADD COLUMN customer_firstname VARCHAR(100) DEFAULT NULL AFTER customer_email,
ADD COLUMN customer_lastname VARCHAR(100) DEFAULT NULL AFTER customer_firstname,
ADD COLUMN customer_company VARCHAR(255) DEFAULT NULL AFTER customer_lastname;

-- Lieferart hinzufügen
ALTER TABLE orders
ADD COLUMN delivery_method ENUM('pickup', 'shipping') DEFAULT NULL AFTER shipping_city;

-- Bestellnotizen umbenennen zu order_notes (konsistent mit bookings)
ALTER TABLE orders
CHANGE COLUMN notes order_notes TEXT DEFAULT NULL;

-- Preis-Felder hinzufügen
ALTER TABLE orders
ADD COLUMN subtotal DECIMAL(10,2) DEFAULT NULL AFTER order_notes,
ADD COLUMN tax DECIMAL(10,2) DEFAULT NULL AFTER subtotal,
ADD COLUMN total DECIMAL(10,2) DEFAULT NULL AFTER tax;

-- HelloCash Customer ID
ALTER TABLE orders
ADD COLUMN hellocash_customer_id INT DEFAULT NULL AFTER delivery_method,
ADD INDEX idx_hellocash_customer (hellocash_customer_id);

-- Status-Spalte anpassen (konsistent mit kasse.php)
ALTER TABLE orders
MODIFY COLUMN order_status ENUM('pending', 'new', 'processing', 'shipped', 'completed', 'cancelled') DEFAULT 'pending';

-- Zahlungsmethoden erweitern (konsistent mit kasse.php)
ALTER TABLE orders
MODIFY COLUMN payment_method ENUM('paypal', 'sumup', 'vorkasse', 'prepayment', 'cash') DEFAULT NULL;

-- Kommentar
ALTER TABLE orders COMMENT = 'Shop-Bestellungen mit HelloCash-Integration';
