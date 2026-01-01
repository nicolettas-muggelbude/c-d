<?php
/**
 * HelloCash Kundensuche API
 * PC-Wittfoot UG
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();

// Nur für eingeloggte Admins
if (!is_admin()) {
    http_response_code(403);
    echo json_encode(['error' => 'Keine Berechtigung']);
    exit;
}

header('Content-Type: application/json; charset=UTF-8');

// POST-Daten auslesen
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? $_POST['action'] ?? null;

$hellocash = new HelloCashClient();

if (!$hellocash->isConfigured()) {
    echo json_encode([
        'success' => false,
        'error' => 'HelloCash API nicht konfiguriert'
    ]);
    exit;
}

try {
    switch ($action) {
        case 'search':
            // Suchbegriff (Email oder Telefonnummer oder Name)
            $query = trim($input['query'] ?? $_POST['query'] ?? '');

            if (empty($query)) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Suchbegriff erforderlich'
                ]);
                exit;
            }

            $results = [];

            // 1. Nach Email suchen
            if (filter_var($query, FILTER_VALIDATE_EMAIL)) {
                $user = $hellocash->findUserByEmail($query);
                if ($user) {
                    $results[] = $user;
                }
            }

            // 2. Nach Telefonnummer suchen (wenn Zahlen enthalten)
            if (preg_match('/\d/', $query)) {
                $user = $hellocash->findUserByPhone($query);
                if ($user && !in_array($user, $results)) {
                    $results[] = $user;
                }
            }

            // 3. Nach Namen suchen (wenn keine exakten Treffer gefunden)
            if (count($results) === 0) {
                $allUsers = $hellocash->getAllUsers(1000);

                if ($allUsers && is_array($allUsers)) {
                    $queryLower = mb_strtolower($query);

                    foreach ($allUsers as $user) {
                        $firstname = mb_strtolower($user['user_firstname'] ?? '');
                        $surname = mb_strtolower($user['user_surname'] ?? '');
                        $company = mb_strtolower($user['user_company'] ?? '');
                        $fullname = $firstname . ' ' . $surname;

                        // Prüfen ob Suchbegriff in Name oder Firma enthalten ist
                        if (
                            str_contains($firstname, $queryLower) ||
                            str_contains($surname, $queryLower) ||
                            str_contains($fullname, $queryLower) ||
                            str_contains($company, $queryLower)
                        ) {
                            $results[] = $user;

                            // Maximal 10 Ergebnisse
                            if (count($results) >= 10) {
                                break;
                            }
                        }
                    }
                }
            }

            // Ergebnisse formatieren für Frontend
            $formatted = array_map(function($user) {
                return [
                    'user_id' => $user['user_id'] ?? null,
                    'firstname' => $user['user_firstname'] ?? '',
                    'lastname' => $user['user_surname'] ?? '',
                    'company' => $user['user_company'] ?? '',
                    'email' => $user['user_email'] ?? '',
                    'phone' => $user['user_phoneNumber'] ?? '',
                    'street' => $user['user_street'] ?? '',
                    'house_number' => $user['user_houseNumber'] ?? '',
                    'postal_code' => $user['user_postalCode'] ?? '',
                    'city' => $user['user_city'] ?? '',
                    'notes' => $user['user_notes'] ?? '',
                    // Display name für Dropdown
                    'display_name' => trim(
                        ($user['user_firstname'] ?? '') . ' ' .
                        ($user['user_surname'] ?? '') .
                        (!empty($user['user_company']) ? ' (' . $user['user_company'] . ')' : '')
                    )
                ];
            }, $results);

            echo json_encode([
                'success' => true,
                'results' => $formatted,
                'count' => count($formatted)
            ]);
            break;

        case 'get_user':
            // User-Details nach ID abrufen
            $userId = $input['user_id'] ?? $_POST['user_id'] ?? null;

            if (empty($userId)) {
                echo json_encode([
                    'success' => false,
                    'error' => 'User-ID erforderlich'
                ]);
                exit;
            }

            $user = $hellocash->getUser($userId);

            if ($user) {
                echo json_encode([
                    'success' => true,
                    'user' => [
                        'user_id' => $user['user_id'] ?? null,
                        'firstname' => $user['user_firstname'] ?? '',
                        'lastname' => $user['user_surname'] ?? '',
                        'company' => $user['user_company'] ?? '',
                        'email' => $user['user_email'] ?? '',
                        'phone' => $user['user_phoneNumber'] ?? '',
                        'street' => $user['user_street'] ?? '',
                        'house_number' => $user['user_houseNumber'] ?? '',
                        'postal_code' => $user['user_postalCode'] ?? '',
                        'city' => $user['user_city'] ?? '',
                        'notes' => $user['user_notes'] ?? ''
                    ]
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'User nicht gefunden'
                ]);
            }
            break;

        default:
            echo json_encode([
                'success' => false,
                'error' => 'Unbekannte Aktion'
            ]);
    }

} catch (Exception $e) {
    error_log('HelloCash Search Error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Fehler bei der Suche: ' . $e->getMessage()
    ]);
}
