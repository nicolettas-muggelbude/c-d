<?php
/**
 * HelloCash API Connection Test
 * PC-Wittfoot UG
 *
 * Testet die Verbindung zur HelloCash API
 */

require_once __DIR__ . '/../src/core/config.php';

echo "=== HelloCash API Verbindungstest ===\n\n";

// 1. Konfiguration prüfen
echo "1. Konfiguration prüfen...\n";
echo "   API URL: " . HELLOCASH_API_URL . "\n";
echo "   API Key: " . (empty(HELLOCASH_API_KEY) ? "❌ NICHT GESETZT" : "✅ Gesetzt (Länge: " . strlen(HELLOCASH_API_KEY) . " Zeichen)") . "\n\n";

if (empty(HELLOCASH_API_KEY)) {
    echo "❌ FEHLER: Bitte HELLOCASH_API_KEY in config.php setzen\n";
    exit(1);
}

// 2. HelloCash Client initialisieren
echo "2. HelloCash Client initialisieren...\n";
$client = new HelloCashClient();

if (!$client->isConfigured()) {
    echo "❌ FEHLER: HelloCash Client nicht konfiguriert\n";
    exit(1);
}
echo "   ✅ Client konfiguriert\n\n";

// 3. API-Verbindung testen - User abfragen
echo "3. API-Verbindung testen...\n";
echo "   Suche nach Test-User mit E-Mail: test@pc-wittfoot.de\n";

try {
    $customer = $client->findUserByEmail('test@pc-wittfoot.de');

    if ($customer === null) {
        echo "   ℹ️  Kunde nicht gefunden (das ist OK für ersten Test)\n";
        echo "   ✅ API-Verbindung funktioniert!\n\n";
    } else {
        echo "   ✅ Kunde gefunden!\n";
        echo "   ✅ API-Verbindung funktioniert!\n";
        echo "   Kunde-ID: " . ($customer['id'] ?? 'N/A') . "\n\n";
    }
} catch (Exception $e) {
    echo "   ❌ FEHLER: " . $e->getMessage() . "\n\n";
    echo "Mögliche Ursachen:\n";
    echo "- API-Token ist ungültig oder abgelaufen\n";
    echo "- Account hat keine API-Berechtigung (Premium-Plan erforderlich)\n";
    echo "- API-URL ist falsch (.de vs .at)\n";
    echo "- Netzwerkproblem / Firewall\n\n";
    exit(1);
}

// 4. Test-User erstellen (optional)
echo "4. Test-User erstellen?\n";
echo "   Möchten Sie einen Test-User in HelloCash erstellen? (j/n): ";
$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));

if (strtolower($line) === 'j') {
    echo "\n   Erstelle Test-User...\n";

    $testUser = [
        'firstname' => 'Test',
        'lastname' => 'User',
        'email' => 'test-' . time() . '@pc-wittfoot.de',
        'phone_number' => '170' . rand(1000000, 9999999),  // Ohne Ländervorwahl
        'company' => 'Test GmbH',
        'country_code' => 'DE'  // ISO Code
    ];

    try {
        $result = $client->createUser($testUser);

        if ($result) {
            echo "   ✅ Test-User erfolgreich erstellt!\n";
            echo "   User-ID: " . ($result['user_id'] ?? 'N/A') . "\n";
            echo "   E-Mail: " . $testUser['email'] . "\n";
            echo "   \n   ℹ️  Bitte im HelloCash Portal prüfen und ggf. löschen\n\n";
        } else {
            echo "   ❌ User konnte nicht erstellt werden\n\n";
        }
    } catch (Exception $e) {
        echo "   ❌ FEHLER: " . $e->getMessage() . "\n\n";
    }
} else {
    echo "   Test übersprungen\n\n";
}

echo "=== Test abgeschlossen ===\n";
echo "\nNächste Schritte:\n";
echo "1. Falls alles funktioniert: Terminbuchung testen\n";
echo "2. Logs überwachen: tail -f /var/log/apache2/error.log | grep HelloCash\n";
echo "3. Im HelloCash Portal prüfen ob Kunden ankommen\n\n";
