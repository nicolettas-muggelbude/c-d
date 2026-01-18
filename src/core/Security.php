<?php
/**
 * Security Class
 * PC-Wittfoot UG
 *
 * Erweiterte Sicherheitsfunktionen:
 * - Login-Rate-Limiting
 * - Audit-Logging
 * - Session-Security
 */

class Security {
    private $db;

    // Rate-Limiting Konfiguration
    const MAX_LOGIN_ATTEMPTS = 5;
    const LOCKOUT_DURATION = 900; // 15 Minuten in Sekunden
    const ATTEMPT_WINDOW = 900; // 15 Minuten

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Prüft ob Login-Rate-Limit erreicht ist
     *
     * @param string $identifier IP oder Username
     * @param string $type 'ip' oder 'username'
     * @return array ['allowed' => bool, 'remaining' => int, 'retry_after' => int]
     */
    public function checkRateLimit($identifier, $type = 'ip') {
        $column = $type === 'ip' ? 'ip_address' : 'username';

        // Alte Versuche aufräumen
        $this->cleanupOldAttempts();

        // Zähle Versuche im Zeitfenster
        $attempts = $this->db->querySingle("
            SELECT COUNT(*) as count, MAX(attempted_at) as last_attempt
            FROM login_attempts
            WHERE $column = :identifier
                AND attempted_at > DATE_SUB(NOW(), INTERVAL :window SECOND)
                AND success = FALSE
        ", [
            ':identifier' => $identifier,
            ':window' => self::ATTEMPT_WINDOW
        ]);

        $attemptCount = $attempts['count'] ?? 0;
        $lastAttempt = $attempts['last_attempt'] ?? null;

        // Wenn Limit erreicht
        if ($attemptCount >= self::MAX_LOGIN_ATTEMPTS) {
            $retryAfter = 0;
            if ($lastAttempt) {
                $lastAttemptTime = strtotime($lastAttempt);
                $unlockTime = $lastAttemptTime + self::LOCKOUT_DURATION;
                $retryAfter = max(0, $unlockTime - time());
            }

            return [
                'allowed' => false,
                'remaining' => 0,
                'retry_after' => $retryAfter,
                'message' => sprintf(
                    'Zu viele Loginversuche. Bitte versuchen Sie es in %d Minuten erneut.',
                    ceil($retryAfter / 60)
                )
            ];
        }

        return [
            'allowed' => true,
            'remaining' => self::MAX_LOGIN_ATTEMPTS - $attemptCount,
            'retry_after' => 0
        ];
    }

    /**
     * Login-Versuch protokollieren
     */
    public function logLoginAttempt($username, $success = false) {
        $ipAddress = $this->getClientIP();

        $this->db->insert("
            INSERT INTO login_attempts (ip_address, username, success, attempted_at)
            VALUES (:ip, :username, :success, NOW())
        ", [
            ':ip' => $ipAddress,
            ':username' => $username,
            ':success' => $success ? 1 : 0
        ]);

        // Bei erfolgreichem Login: Alte Failed-Attempts löschen
        if ($success) {
            $this->clearFailedAttempts($username, $ipAddress);
        }
    }

    /**
     * Fehlgeschlagene Versuche löschen nach erfolgreichem Login
     */
    private function clearFailedAttempts($username, $ipAddress) {
        $this->db->delete("
            DELETE FROM login_attempts
            WHERE (username = :username OR ip_address = :ip)
                AND success = FALSE
        ", [
            ':username' => $username,
            ':ip' => $ipAddress
        ]);
    }

    /**
     * Alte Login-Versuche aufräumen (älter als 24h)
     */
    private function cleanupOldAttempts() {
        $this->db->delete("
            DELETE FROM login_attempts
            WHERE attempted_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)
        ");
    }

    /**
     * Session regenerieren (gegen Session Fixation)
     */
    public function regenerateSession() {
        // Alte Session-Daten sichern
        $oldSessionData = $_SESSION;

        // Session-ID neu generieren
        session_regenerate_id(true);

        // Session-Daten wiederherstellen
        $_SESSION = $oldSessionData;
    }

    /**
     * Client-IP ermitteln (auch hinter Proxies)
     */
    public function getClientIP() {
        $ipKeys = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',  // Standard Proxy
            'HTTP_X_REAL_IP',        // Nginx
            'REMOTE_ADDR'            // Direkte Verbindung
        ];

        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];

                // Bei X-Forwarded-For: Erste IP nehmen
                if (strpos($ip, ',') !== false) {
                    $ips = explode(',', $ip);
                    $ip = trim($ips[0]);
                }

                // IP validieren
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return '0.0.0.0';
    }

    /**
     * Audit-Log Eintrag erstellen
     */
    public function logAudit($action, $entityType = null, $entityId = null, $details = null) {
        $userId = $_SESSION['user_id'] ?? null;
        $ipAddress = $this->getClientIP();
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        // Details als JSON wenn Array
        if (is_array($details)) {
            $details = json_encode($details, JSON_UNESCAPED_UNICODE);
        }

        $this->db->insert("
            INSERT INTO audit_log (user_id, action, entity_type, entity_id, details, ip_address, user_agent, created_at)
            VALUES (:user_id, :action, :entity_type, :entity_id, :details, :ip, :user_agent, NOW())
        ", [
            ':user_id' => $userId,
            ':action' => $action,
            ':entity_type' => $entityType,
            ':entity_id' => $entityId,
            ':details' => $details,
            ':ip' => $ipAddress,
            ':user_agent' => substr($userAgent, 0, 255)
        ]);
    }

    /**
     * Security-Headers setzen
     */
    public function setSecurityHeaders() {
        // Bereits gesendete Headers prüfen
        if (headers_sent()) {
            return;
        }

        // Clickjacking verhindern
        header('X-Frame-Options: SAMEORIGIN');

        // MIME-Sniffing verhindern
        header('X-Content-Type-Options: nosniff');

        // XSS-Filter aktivieren
        header('X-XSS-Protection: 1; mode=block');

        // Referrer-Policy
        header('Referrer-Policy: strict-origin-when-cross-origin');

        // Content Security Policy (Basis)
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; img-src 'self' data: https://chart.googleapis.com; font-src 'self' https://fonts.gstatic.com; frame-src https://www.google.com;");

        // HTTPS in Produktion erzwingen
        if (!empty($_SERVER['HTTPS']) || ($_SERVER['SERVER_PORT'] ?? 80) == 443) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
    }
}
