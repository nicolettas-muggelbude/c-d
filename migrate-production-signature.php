#!/usr/bin/env php
<?php
/**
 * Production Migration: Email-Signatur HTML
 * Fügt HTML-Signatur, Plaintext-Fallback und Logo-Support hinzu
 */

require_once __DIR__ . '/src/core/config.php';

echo "===========================================\n";
echo "Production Migration: Email-Signatur HTML\n";
echo "===========================================\n\n";

$db = Database::getInstance();

try {
    // Schritt 1: Spalten hinzufügen
    echo "Schritt 1: Spalten zur email_signature Tabelle hinzufügen...\n";

    $db->query("ALTER TABLE email_signature ADD COLUMN signature_html TEXT NULL COMMENT 'HTML-Version der Signatur mit Logo'");
    echo "  ✓ signature_html hinzugefügt\n";

    $db->query("ALTER TABLE email_signature ADD COLUMN signature_plaintext TEXT NULL COMMENT 'Plaintext-Fallback (ohne Logo)'");
    echo "  ✓ signature_plaintext hinzugefügt\n";

    $db->query("ALTER TABLE email_signature ADD COLUMN logo_filename VARCHAR(255) NULL COMMENT 'Dateiname des Logos'");
    echo "  ✓ logo_filename hinzugefügt\n";

    // Schritt 2: Alte Signatur migrieren
    echo "\nSchritt 2: Alte Signatur in Plaintext-Feld migrieren...\n";
    $db->query("UPDATE email_signature SET signature_plaintext = signature_text WHERE id = 1");
    echo "  ✓ Alte Signatur migriert\n";

    // Schritt 3: Standard HTML-Signatur einfügen
    echo "\nSchritt 3: Standard HTML-Signatur einfügen...\n";

    $htmlSignature = '<div style="font-family: Arial, sans-serif; color: #333; margin-top: 30px; padding-top: 20px; border-top: 2px solid #0066cc;">
    <table cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td style="padding-right: 20px; vertical-align: top;">
                <img src="{logo_url}" alt="PC-Wittfoot UG Logo" style="max-width: 120px; height: auto;" />
            </td>
            <td style="vertical-align: top;">
                <p style="margin: 0; font-weight: bold; font-size: 16px; color: #0066cc;">PC-Wittfoot UG</p>
                <p style="margin: 5px 0 0 0; font-size: 13px; color: #666; font-style: italic;">IT-Fachbetrieb mit Herz</p>
                <p style="margin: 15px 0 0 0; font-size: 13px; line-height: 1.6;">
                    <strong>Telefon:</strong> +49 (0) 123 456789<br>
                    <strong>E-Mail:</strong> <a href="mailto:info@pc-wittfoot.de" style="color: #0066cc; text-decoration: none;">info@pc-wittfoot.de</a><br>
                    <strong>Web:</strong> <a href="https://pc-wittfoot.de" style="color: #0066cc; text-decoration: none;">www.pc-wittfoot.de</a>
                </p>
            </td>
        </tr>
    </table>
</div>';

    $db->update("UPDATE email_signature SET signature_html = :html, logo_filename = 'logo-modern.svg' WHERE id = 1", [
        ':html' => $htmlSignature
    ]);
    echo "  ✓ HTML-Signatur eingefügt\n";
    echo "  ✓ Logo-Dateiname gesetzt: logo-modern.svg\n";

    // Schritt 4: Verifizieren
    echo "\nSchritt 4: Verifikation...\n";
    $signature = $db->querySingle("SELECT * FROM email_signature WHERE id = 1");

    echo "  ✓ HTML-Signatur: " . (!empty($signature['signature_html']) ? 'Gesetzt' : 'FEHLT') . "\n";
    echo "  ✓ Plaintext-Signatur: " . (!empty($signature['signature_plaintext']) ? 'Gesetzt' : 'FEHLT') . "\n";
    echo "  ✓ Logo: " . ($signature['logo_filename'] ?? 'FEHLT') . "\n";

    echo "\n===========================================\n";
    echo "✅ Migration erfolgreich abgeschlossen!\n";
    echo "===========================================\n";

} catch (Exception $e) {
    echo "\n===========================================\n";
    echo "❌ Migration fehlgeschlagen!\n";
    echo "===========================================\n";
    echo "Fehler: " . $e->getMessage() . "\n";
    exit(1);
}
