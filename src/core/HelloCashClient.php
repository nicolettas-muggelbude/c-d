<?php
/**
 * HelloCash API Client
 * PC-Wittfoot UG
 *
 * Wrapper für die HelloCash REST API
 * API Blueprint: https://api.hellocash.business/api/v1
 * Dokumentation: /docs/hellocash.apib
 */

class HelloCashClient {
    private $apiUrl;
    private $apiKey;
    private $rateLimit = 60; // Anfragen pro Minute

    public function __construct() {
        $this->apiUrl = rtrim(HELLOCASH_API_URL, '/');
        $this->apiKey = HELLOCASH_API_KEY;
    }

    /**
     * Prüfen ob API konfiguriert ist
     */
    public function isConfigured() {
        return !empty($this->apiKey);
    }

    /**
     * User nach E-Mail suchen
     *
     * @param string $email
     * @return array|null User-Daten oder null wenn nicht gefunden
     */
    public function findUserByEmail($email) {
        if (!$this->isConfigured()) {
            error_log('HelloCash API nicht konfiguriert');
            return null;
        }

        try {
            // API bietet keine direkte Email-Suche, daher alle User abrufen und filtern
            $response = $this->request('GET', '/users', ['limit' => 1000]);

            if (isset($response['users']) && is_array($response['users'])) {
                foreach ($response['users'] as $user) {
                    if (isset($user['user_email']) &&
                        strtolower($user['user_email']) === strtolower($email)) {
                        return $user;
                    }
                }
            }

            return null;
        } catch (Exception $e) {
            error_log('HelloCash findUserByEmail Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * User nach Telefonnummer suchen
     *
     * @param string $phone
     * @return array|null User-Daten oder null wenn nicht gefunden
     */
    public function findUserByPhone($phone) {
        if (!$this->isConfigured()) {
            error_log('HelloCash API nicht konfiguriert');
            return null;
        }

        try {
            // Telefonnummer normalisieren (Leerzeichen entfernen)
            $phoneNormalized = preg_replace('/\s+/', '', $phone);

            $response = $this->request('GET', '/users', ['limit' => 1000]);

            if (isset($response['users']) && is_array($response['users'])) {
                foreach ($response['users'] as $user) {
                    if (isset($user['user_phoneNumber'])) {
                        $userPhoneNormalized = preg_replace('/\s+/', '', $user['user_phoneNumber']);
                        if ($userPhoneNormalized === $phoneNormalized) {
                            return $user;
                        }
                    }
                }
            }

            return null;
        } catch (Exception $e) {
            error_log('HelloCash findUserByPhone Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Neuen User erstellen
     *
     * @param array $userData
     * @return array|null Erstellter User oder null bei Fehler
     */
    public function createUser($userData) {
        if (!$this->isConfigured()) {
            error_log('HelloCash API nicht konfiguriert');
            return null;
        }

        try {
            // API Blueprint: CreateUser Structure
            $payload = [];

            // Required: Surname OR Company
            if (!empty($userData['lastname'])) {
                $payload['user_surname'] = $userData['lastname'];
            }
            if (!empty($userData['company'])) {
                $payload['user_company'] = $userData['company'];
            }

            // Optional fields
            if (!empty($userData['firstname'])) {
                $payload['user_firstname'] = $userData['firstname'];
            }
            if (!empty($userData['email'])) {
                $payload['user_email'] = $userData['email'];
            }

            // Telefonnummer OHNE Ländervorwahl
            if (!empty($userData['phone_number'])) {
                $payload['user_phoneNumber'] = $userData['phone_number'];
            }

            // Ländercode separat (ISO 3166-1 alpha-2)
            if (!empty($userData['country_code'])) {
                $payload['user_country_code'] = $userData['country_code'];
            }

            // Notes (für Festnetznummer)
            if (!empty($userData['notes'])) {
                $payload['user_notes'] = $userData['notes'];
            }

            // Adresse
            if (!empty($userData['street'])) {
                $payload['user_street'] = $userData['street'];
            }
            if (!empty($userData['house_number'])) {
                $payload['user_houseNumber'] = $userData['house_number'];
            }
            if (!empty($userData['postal_code'])) {
                $payload['user_postalCode'] = $userData['postal_code'];
            }
            if (!empty($userData['city'])) {
                $payload['user_city'] = $userData['city'];
            }

            // Validation: Surname OR Company required
            if (empty($payload['user_surname']) && empty($payload['user_company'])) {
                throw new Exception('user_surname oder user_company ist erforderlich');
            }

            $response = $this->request('POST', '/users', [], $payload);

            return $response;
        } catch (Exception $e) {
            error_log('HelloCash createUser Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * User aktualisieren
     *
     * @param int $userId
     * @param array $userData
     * @return array|null Aktualisierter User oder null bei Fehler
     */
    public function updateUser($userId, $userData) {
        if (!$this->isConfigured()) {
            error_log('HelloCash API nicht konfiguriert');
            return null;
        }

        try {
            // Map to HelloCash field names
            $payload = [];

            if (isset($userData['firstname'])) {
                $payload['user_firstname'] = $userData['firstname'];
            }
            if (isset($userData['lastname'])) {
                $payload['user_surname'] = $userData['lastname'];
            }
            if (isset($userData['company'])) {
                $payload['user_company'] = $userData['company'];
            }
            if (isset($userData['email'])) {
                $payload['user_email'] = $userData['email'];
            }
            if (isset($userData['phone'])) {
                $payload['user_phoneNumber'] = $userData['phone'];
            }

            $response = $this->request('POST', "/users/{$userId}", [], $payload);

            return $response;
        } catch (Exception $e) {
            error_log('HelloCash updateUser Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * User nach ID abrufen
     *
     * @param int $userId
     * @return array|null User-Daten oder null bei Fehler
     */
    public function getUser($userId) {
        if (!$this->isConfigured()) {
            error_log('HelloCash API nicht konfiguriert');
            return null;
        }

        try {
            $response = $this->request('GET', "/users/{$userId}");
            return $response;
        } catch (Exception $e) {
            error_log('HelloCash getUser Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Alle User abrufen
     *
     * @param int $limit Maximale Anzahl User (Standard: 1000)
     * @return array|null Array mit allen Usern oder null bei Fehler
     */
    public function getAllUsers($limit = 1000) {
        if (!$this->isConfigured()) {
            error_log('HelloCash API nicht konfiguriert');
            return null;
        }

        try {
            $response = $this->request('GET', '/users', ['limit' => $limit]);
            return $response['users'] ?? [];
        } catch (Exception $e) {
            error_log('HelloCash getAllUsers Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * HTTP-Request an HelloCash API senden
     *
     * @param string $method HTTP-Methode (GET, POST, PUT, DELETE)
     * @param string $endpoint API-Endpoint
     * @param array $queryParams Query-Parameter
     * @param array $body Request-Body (für POST/PUT)
     * @return array API-Response
     * @throws Exception Bei API-Fehlern
     */
    private function request($method, $endpoint, $queryParams = [], $body = null) {
        $url = $this->apiUrl . $endpoint;

        // Query-Parameter hinzufügen
        if (!empty($queryParams)) {
            $url .= '?' . http_build_query($queryParams);
        }

        // cURL initialisieren
        $ch = curl_init();

        // Headers
        $headers = [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        // HTTP-Methode setzen
        switch ($method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if ($body) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
                }
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                if ($body) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
                }
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            case 'GET':
            default:
                // Keine zusätzlichen Optionen nötig
                break;
        }

        // Request ausführen
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        // Fehlerbehandlung
        if ($error) {
            throw new Exception("cURL Error: $error");
        }

        // Response dekodieren
        $data = json_decode($response, true);

        // HTTP-Fehler prüfen
        if ($httpCode >= 400) {
            $errorMsg = isset($data['error']) ? $data['error'] : 'Unknown error';
            if (isset($data['message'])) {
                $errorMsg = $data['message'];
            }
            throw new Exception("API Error (HTTP $httpCode): $errorMsg");
        }

        return $data;
    }

    /**
     * Konvertiere Telefonvorwahl (+49) zu ISO-Ländercode (DE)
     */
    private function phoneCountryToIsoCode($phoneCountry) {
        $mapping = [
            '+49' => 'DE',  // Deutschland
            '+43' => 'AT',  // Österreich
            '+41' => 'CH',  // Schweiz
            '+1'  => 'US',  // USA
            '+44' => 'GB',  // Großbritannien
        ];

        return $mapping[$phoneCountry] ?? 'DE'; // Default: Deutschland
    }

    /**
     * User in HelloCash suchen oder erstellen
     *
     * @param array $customerData Kundendaten aus Buchungsformular
     * @return array ['user_id' => int|null, 'is_new' => bool, 'error' => string|null]
     */
    public function findOrCreateUser($customerData) {
        if (!$this->isConfigured()) {
            return [
                'user_id' => null,
                'is_new' => false,
                'error' => 'HelloCash API nicht konfiguriert'
            ];
        }

        // User nur als Duplikat erkennen wenn VORNAME UND EMAIL zusammen übereinstimmen
        // Unterschiedliche Personen an gleicher Adresse sollen separate HelloCash-Einträge erhalten
        if (!empty($customerData['email'])) {
            $user = $this->findUserByEmail($customerData['email']);

            if ($user) {
                // Vorname vergleichen (case-insensitive)
                $existingFirstname = strtolower(trim($user['user_firstname'] ?? ''));
                $newFirstname = strtolower(trim($customerData['firstname'] ?? ''));

                // Nur wenn BEIDE übereinstimmen, existierenden User verwenden
                if ($existingFirstname === $newFirstname) {
                    return [
                        'user_id' => $user['user_id'] ?? null,
                        'is_new' => false,
                        'error' => null
                    ];
                }

                // Sonst: Email stimmt überein, aber anderer Vorname -> neuen User erstellen
                error_log("HelloCash: Gleiche Email, anderer Vorname - erstelle neuen User (Email: {$customerData['email']}, Alt: $existingFirstname, Neu: $newFirstname)");
            }
        }

        // KEIN Check mehr auf Telefonnummer alleine
        // Unterschiedliche Personen im selben Haushalt dürfen unterschiedliche HelloCash-Einträge haben

        // 3. Neuen User erstellen
        // Ländervorwahl (+49) zu ISO-Code (DE) konvertieren
        $phoneCountry = $customerData['phone_country'] ?? '+49';
        $isoCountryCode = $this->phoneCountryToIsoCode($phoneCountry);

        // Telefonnummer MIT Ländervorwahl (z.B. "+49 170 1234567")
        // HelloCash-UI hat separates Pflichtfeld für Ländervorwahl
        $phoneNumber = $phoneCountry . ' ' . ($customerData['phone_mobile'] ?? '');

        // Notes-Feld für Festnetznummer vorbereiten (auch mit Vorwahl)
        $notes = null;
        if (!empty($customerData['phone_landline'])) {
            $notes = 'Festnetz: ' . $phoneCountry . ' ' . $customerData['phone_landline'];
        }

        $newUser = $this->createUser([
            'firstname' => $customerData['firstname'] ?? '',
            'lastname' => $customerData['lastname'] ?? '',
            'email' => $customerData['email'] ?? '',
            'phone_number' => $phoneNumber,  // OHNE Ländervorwahl
            'country_code' => $isoCountryCode,  // ISO Code (DE, AT, etc.)
            'company' => $customerData['company'] ?? null,
            'notes' => $notes,  // Festnetznummer im Notes-Feld
            // Adresse
            'street' => $customerData['street'] ?? null,
            'house_number' => $customerData['house_number'] ?? null,
            'postal_code' => $customerData['postal_code'] ?? null,
            'city' => $customerData['city'] ?? null
        ]);

        if ($newUser) {
            return [
                'user_id' => $newUser['user_id'] ?? null,
                'is_new' => true,
                'error' => null
            ];
        }

        return [
            'user_id' => null,
            'is_new' => false,
            'error' => 'User konnte nicht erstellt werden'
        ];
    }

    /**
     * Erstellt eine Invoice (Rechnung) in HelloCash
     *
     * @param array $invoiceData Invoice-Daten
     *   - user_id: HelloCash User-ID (required)
     *   - items: Array mit Artikeln (required)
     *       - name: Artikelname
     *       - quantity: Menge
     *       - price: Bruttopreis
     *       - tax_rate: Steuersatz (z.B. 19)
     *   - payment_method: Zahlungsmethode (optional, default: 'Vorkasse')
     *   - notes: Notizen (optional)
     * @return array ['invoice_id' => int, 'invoice_link' => string, 'invoice_text' => string, 'error' => string|null]
     */
    public function createInvoice($invoiceData) {
        if (!$this->isConfigured()) {
            return [
                'invoice_id' => null,
                'invoice_link' => null,
                'invoice_text' => null,
                'error' => 'HelloCash API nicht konfiguriert'
            ];
        }

        try {
            // Items vorbereiten
            $items = [];
            foreach ($invoiceData['items'] as $item) {
                $itemData = [
                    'item_name' => $item['name'],
                    'item_quantity' => (string)$item['quantity'],
                    'item_price' => (string)$item['price'],
                    'item_taxRate' => (string)($item['tax_rate'] ?? 19),
                    'item_type' => 'article'
                ];

                // EAN/Barcode hinzufügen falls vorhanden
                if (!empty($item['ean'])) {
                    $itemData['item_ean'] = $item['ean'];
                }

                $items[] = $itemData;
            }

            // Request-Payload
            $payload = [
                'invoice_user_id' => (int)$invoiceData['user_id'],
                'items' => $items,
                'invoice_paymentMethod' => $invoiceData['payment_method'] ?? 'Vorkasse',
                'invoice_type' => 'digital',  // Digital-Link für Kunde & Buchhaltung
                'locale' => 'de_DE'  // Deutsche Rechnung
            ];

            // Notizen hinzufügen falls vorhanden
            if (!empty($invoiceData['notes'])) {
                $payload['invoice_text'] = $invoiceData['notes'];
            }

            // API-Request
            error_log("HelloCash Invoice Request: " . json_encode($payload));
            $response = $this->request('POST', '/invoices', [], $payload);
            error_log("HelloCash Invoice Response: " . json_encode($response));

            // Response für invoice_type: digital enthält link, text, qr, invoice_id
            if (isset($response['invoice_id']) && isset($response['link'])) {
                return [
                    'invoice_id' => $response['invoice_id'],
                    'invoice_link' => $response['link'],
                    'invoice_text' => $response['text'] ?? null,
                    'error' => null
                ];
            }

            // Detaillierte Fehlerinformation
            $errorMsg = 'Invoice konnte nicht erstellt werden';
            if (isset($response['error'])) {
                $errorMsg .= ': ' . (is_array($response['error']) ? json_encode($response['error']) : $response['error']);
            } elseif (isset($response['message'])) {
                $errorMsg .= ': ' . $response['message'];
            }

            return [
                'invoice_id' => null,
                'invoice_link' => null,
                'invoice_text' => null,
                'error' => $errorMsg
            ];

        } catch (Exception $e) {
            error_log('HelloCash createInvoice Error: ' . $e->getMessage());
            return [
                'invoice_id' => null,
                'invoice_link' => null,
                'invoice_text' => null,
                'error' => $e->getMessage()
            ];
        }
    }
}
