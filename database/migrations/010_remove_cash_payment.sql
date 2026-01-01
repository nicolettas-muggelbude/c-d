-- Migration: Entfernung Barzahlung bei Abholung
-- "cash" als Zahlungsart entfernt wegen Spaßbestellungen

ALTER TABLE orders
MODIFY COLUMN payment_method ENUM('paypal', 'sumup', 'vorkasse', 'prepayment') DEFAULT NULL;
