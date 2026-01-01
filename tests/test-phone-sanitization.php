<?php
/**
 * Test: Telefonnummer-Bereinigung (führende Nullen)
 */

echo "=== Telefonnummer-Bereinigung Test ===\n\n";

$testCases = [
    '170 1234567',      // Ohne führende 0
    '0170 1234567',     // Mit führender 0
    '00170 1234567',    // Mit zwei führenden 0en
    '030 98765432',     // Festnetz mit führender 0
    '0',                // Nur Null
    '000',              // Nur Nullen
    '1234567',          // Normale Nummer
];

foreach ($testCases as $input) {
    $sanitized = ltrim(trim($input), '0');
    $isEmpty = empty($sanitized);

    echo "Input:      '$input'\n";
    echo "Sanitized:  '$sanitized'\n";
    echo "Is empty:   " . ($isEmpty ? 'JA ⚠️' : 'NEIN ✅') . "\n";
    echo "---\n";
}

echo "\n=== Test abgeschlossen ===\n";
