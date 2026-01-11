-- Migration: HTML-Signatur für Emails mit Logo-Support
-- Datum: 2026-01-11

ALTER TABLE email_signature
ADD COLUMN signature_html TEXT NULL COMMENT 'HTML-Version der Signatur mit Logo',
ADD COLUMN signature_plaintext TEXT NULL COMMENT 'Plaintext-Fallback (ohne Logo)',
ADD COLUMN logo_filename VARCHAR(255) NULL COMMENT 'Dateiname des Logos in /assets/images/email/';
