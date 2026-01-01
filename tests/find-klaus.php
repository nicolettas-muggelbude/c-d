<?php
require_once __DIR__ . '/../src/core/config.php';

$client = new HelloCashClient();

echo "=== Suche Klaus Klausen ===\n\n";

$response = $client->request('GET', '/users', ['limit' => 100]);

if (isset($response['users'])) {
    foreach ($response['users'] as $user) {
        $firstname = $user['user_firstname'] ?? '';
        $surname = $user['user_surname'] ?? '';

        if (stripos($firstname, 'Klaus') !== false || stripos($surname, 'Klausen') !== false) {
            echo "Gefunden: {$firstname} {$surname} (ID: {$user['user_id']})\n\n";
            echo json_encode($user, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
        }
    }
} else {
    echo "Keine Users gefunden oder Fehler\n";
}
