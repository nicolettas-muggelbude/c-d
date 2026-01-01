<?php
/**
 * Helper-Funktionen
 * PC-Wittfoot
 *
 * Globale Hilfsfunktionen für die gesamte Anwendung
 */

/**
 * Sicheres Escaping für HTML-Ausgabe (XSS-Schutz)
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect zu einer URL
 */
function redirect($url, $statusCode = 303) {
    header('Location: ' . $url, true, $statusCode);
    exit;
}

/**
 * JSON-Response senden
 */
function json_response($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * CSRF-Token generieren
 */
function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * CSRF-Token validieren
 */
function csrf_verify($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Preis formatieren
 */
function format_price($price, $withCurrency = true) {
    // NULL-Werte auf 0 setzen (PHP 8+ Kompatibilität)
    $price = $price ?? 0.0;
    $formatted = number_format($price, 2, ',', '.');
    return $withCurrency ? $formatted . ' ' . CURRENCY : $formatted;
}

/**
 * Datum formatieren (deutsch)
 */
function format_date($date, $format = 'd.m.Y') {
    return date($format, strtotime($date));
}

/**
 * Datum und Zeit formatieren (deutsch)
 */
function format_datetime($datetime, $format = 'd.m.Y H:i') {
    return date($format, strtotime($datetime));
}

/**
 * Slug aus String erstellen
 */
function create_slug($string) {
    $string = mb_strtolower($string, 'UTF-8');

    // Umlaute ersetzen
    $replacements = [
        'ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue',
        'ß' => 'ss', 'Ä' => 'Ae', 'Ö' => 'Oe', 'Ü' => 'Ue'
    ];
    $string = str_replace(array_keys($replacements), array_values($replacements), $string);

    // Nur Buchstaben, Zahlen und Bindestriche
    $string = preg_replace('/[^a-z0-9\-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    $string = trim($string, '-');

    return $string;
}

/**
 * Text kürzen
 */
function truncate($text, $length = 100, $suffix = '...') {
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . $suffix;
}

/**
 * Asset-URL generieren
 */
function asset($path) {
    $url = ASSETS_URL . '/' . ltrim($path, '/');
    // Cache-Busting: Version-Parameter hinzufügen
    $version = '30'; // Bei CSS-Änderungen erhöhen
    return $url . '?v=' . $version;
}

/**
 * Upload-URL generieren
 */
function upload($path) {
    return UPLOADS_URL . '/' . ltrim($path, '/');
}

/**
 * Flash-Message setzen
 */
function set_flash($type, $message) {
    $_SESSION['flash'][$type] = $message;
}

/**
 * Flash-Message holen und löschen
 */
function get_flash($type) {
    if (isset($_SESSION['flash'][$type])) {
        $message = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $message;
    }
    return null;
}

/**
 * Prüfen ob User eingeloggt ist
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Prüfen ob User Admin ist
 */
function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Login erforderlich (redirect wenn nicht eingeloggt)
 */
function require_login() {
    if (!is_logged_in()) {
        set_flash('error', 'Bitte melden Sie sich an.');
        redirect(BASE_URL . '/admin/login.php');
    }
}

/**
 * Admin-Rechte erforderlich
 */
function require_admin() {
    require_login();
    if (!is_admin()) {
        set_flash('error', 'Keine Berechtigung.');
        redirect(BASE_URL);
    }
}

/**
 * E-Mail validieren
 */
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * E-Mail versenden (mit PHPMailer oder Fallback zu Logging)
 *
 * @param string $to Empfänger-E-Mail
 * @param string $subject Betreff
 * @param string $body Nachricht (Plain Text)
 * @return bool Erfolg
 */
function send_email($to, $subject, $body) {
    try {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        // Prüfe ob SMTP konfiguriert ist
        if (defined('SMTP_ENABLED') && SMTP_ENABLED) {
            // SMTP verwenden
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = !empty(SMTP_USERNAME);
            $mail->Username   = SMTP_USERNAME;
            $mail->Password   = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_ENCRYPTION !== 'none' ? SMTP_ENCRYPTION : '';
            $mail->Port       = SMTP_PORT;
            $mail->SMTPDebug  = SMTP_DEBUG;
        } else {
            // Fallback: PHP mail() - in Entwicklung nur loggen
            if (DEBUG_MODE) {
                error_log("=== E-MAIL (DEBUG) ===");
                error_log("An: $to");
                error_log("Betreff: $subject");
                error_log("Nachricht:\n$body");
                error_log("======================");
                return true; // In Debug-Modus immer erfolgreich
            }
            $mail->isMail();
        }

        // Absender
        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addReplyTo(MAIL_FROM, MAIL_FROM_NAME);

        // Empfänger
        $mail->addAddress($to);

        // Inhalt
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Versenden
        return $mail->send();

    } catch (\Exception $e) {
        error_log("send_email() failed: " . $e->getMessage());

        // In Debug-Modus trotzdem als erfolgreich markieren (für Tests)
        if (DEBUG_MODE) {
            error_log("DEBUG MODE: E-Mail wurde geloggt statt versendet");
            return true;
        }

        return false;
    }
}

/**
 * Sicheres Passwort-Hash erstellen
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Passwort verifizieren
 */
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Session starten (wenn noch nicht gestartet)
 */
function start_session_safe() {
    if (session_status() === PHP_SESSION_NONE) {
        session_name(SESSION_NAME);
        session_start();
    }
}

/**
 * Sanitize Input (Basis-Bereinigung)
 */
function sanitize($input) {
    return trim(strip_tags($input));
}

/**
 * Debug-Ausgabe (nur im Debug-Modus)
 */
function dd($var) {
    if (DEBUG_MODE) {
        echo '<pre style="background: #2C3E50; color: #8BC34A; padding: 20px; margin: 20px; border-radius: 8px; overflow: auto;">';
        var_dump($var);
        echo '</pre>';
        die();
    }
}

/**
 * Pagination-Daten berechnen
 */
function paginate($totalItems, $perPage, $currentPage = 1) {
    $totalPages = ceil($totalItems / $perPage);
    $currentPage = max(1, min($currentPage, $totalPages));
    $offset = ($currentPage - 1) * $perPage;

    return [
        'total_items' => $totalItems,
        'per_page' => $perPage,
        'current_page' => $currentPage,
        'total_pages' => $totalPages,
        'offset' => $offset,
        'has_prev' => $currentPage > 1,
        'has_next' => $currentPage < $totalPages
    ];
}
