-- Migration: Update trusted_devices Tabelle für Device Fingerprinting
-- Ändert device_token zu device_fingerprint

ALTER TABLE trusted_devices
    CHANGE COLUMN device_token device_fingerprint VARCHAR(64) NOT NULL UNIQUE;
