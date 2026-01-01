<?php
/**
 * Device Fingerprint Class
 * Erstellt eindeutige Geräte-Identifikationen für Trusted Devices
 */

class DeviceFingerprint {

    /**
     * Generiert einen eindeutigen Device-Fingerprint
     * Basiert auf User-Agent, IP und Accept-Sprache
     *
     * @return string Device-Fingerprint Hash
     */
    public static function generate() {
        $components = [
            $_SERVER['HTTP_USER_AGENT'] ?? '',
            $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '',
            $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '',
            // IP-Subnetz (nicht volle IP für Mobilgeräte mit wechselnder IP)
            self::getIpSubnet()
        ];

        $fingerprint = implode('|', $components);
        return hash('sha256', $fingerprint);
    }

    /**
     * Gibt das IP-Subnetz zurück (erste 3 Oktette bei IPv4)
     * Damit funktioniert es auch bei dynamischen IPs im gleichen Netz
     */
    private static function getIpSubnet() {
        $security = new Security();
        $ip = $security->getClientIP();

        // IPv4: Erste 3 Oktette
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip);
            return implode('.', array_slice($parts, 0, 3)) . '.0';
        }

        // IPv6: Erste 4 Hextets
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $parts = explode(':', $ip);
            return implode(':', array_slice($parts, 0, 4)) . '::';
        }

        return $ip;
    }

    /**
     * Prüft, ob ein Gerät als vertrauenswürdig markiert ist
     *
     * @param int $userId User-ID
     * @return bool|array False wenn nicht vertrauenswürdig, sonst Device-Daten
     */
    public static function isTrusted($userId) {
        $fingerprint = self::generate();
        $db = Database::getInstance();

        $device = $db->querySingle("
            SELECT * FROM trusted_devices
            WHERE user_id = :user_id
                AND device_fingerprint = :fingerprint
                AND expires_at > NOW()
        ", [
            ':user_id' => $userId,
            ':fingerprint' => $fingerprint
        ]);

        return $device ?: false;
    }

    /**
     * Markiert aktuelles Gerät als vertrauenswürdig
     *
     * @param int $userId User-ID
     * @param int $days Gültigkeitsdauer in Tagen (Standard: 30)
     * @return bool Erfolg
     */
    public static function trust($userId, $days = 30) {
        $fingerprint = self::generate();
        $db = Database::getInstance();
        $security = new Security();

        $deviceName = self::getDeviceName();
        $expiresAt = date('Y-m-d H:i:s', strtotime("+{$days} days"));

        try {
            $db->insert("
                INSERT INTO trusted_devices (
                    user_id,
                    device_fingerprint,
                    device_name,
                    ip_address,
                    user_agent,
                    expires_at,
                    created_at
                ) VALUES (
                    :user_id,
                    :fingerprint,
                    :device_name,
                    :ip,
                    :user_agent,
                    :expires_at,
                    NOW()
                )
                ON DUPLICATE KEY UPDATE
                    expires_at = :expires_at2,
                    last_used_at = NOW()
            ", [
                ':user_id' => $userId,
                ':fingerprint' => $fingerprint,
                ':device_name' => $deviceName,
                ':ip' => $security->getClientIP(),
                ':user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
                ':expires_at' => $expiresAt,
                ':expires_at2' => $expiresAt
            ]);

            return true;
        } catch (Exception $e) {
            error_log("DeviceFingerprint::trust() failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Entfernt ein vertrauenswürdiges Gerät
     *
     * @param int $userId User-ID
     * @param int $deviceId Device-ID
     * @return bool Erfolg
     */
    public static function revoke($userId, $deviceId) {
        $db = Database::getInstance();

        try {
            $db->delete("
                DELETE FROM trusted_devices
                WHERE id = :device_id AND user_id = :user_id
            ", [
                ':device_id' => $deviceId,
                ':user_id' => $userId
            ]);

            return true;
        } catch (Exception $e) {
            error_log("DeviceFingerprint::revoke() failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Gibt einen lesbaren Gerätenamen zurück
     *
     * @return string Device-Name
     */
    private static function getDeviceName() {
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

        // Browser erkennen
        if (strpos($ua, 'Firefox') !== false) {
            $browser = 'Firefox';
        } elseif (strpos($ua, 'Chrome') !== false) {
            $browser = 'Chrome';
        } elseif (strpos($ua, 'Safari') !== false) {
            $browser = 'Safari';
        } elseif (strpos($ua, 'Edge') !== false) {
            $browser = 'Edge';
        } else {
            $browser = 'Browser';
        }

        // OS erkennen
        if (strpos($ua, 'Windows') !== false) {
            $os = 'Windows';
        } elseif (strpos($ua, 'Mac') !== false) {
            $os = 'macOS';
        } elseif (strpos($ua, 'Linux') !== false) {
            $os = 'Linux';
        } elseif (strpos($ua, 'Android') !== false) {
            $os = 'Android';
        } elseif (strpos($ua, 'iOS') !== false || strpos($ua, 'iPhone') !== false || strpos($ua, 'iPad') !== false) {
            $os = 'iOS';
        } else {
            $os = 'Unknown OS';
        }

        return $browser . ' auf ' . $os;
    }

    /**
     * Aktualisiert last_used_at für ein vertrauenswürdiges Gerät
     *
     * @param int $userId User-ID
     */
    public static function updateLastUsed($userId) {
        $fingerprint = self::generate();
        $db = Database::getInstance();

        try {
            $db->update("
                UPDATE trusted_devices
                SET last_used_at = NOW()
                WHERE user_id = :user_id
                    AND device_fingerprint = :fingerprint
            ", [
                ':user_id' => $userId,
                ':fingerprint' => $fingerprint
            ]);
        } catch (Exception $e) {
            error_log("DeviceFingerprint::updateLastUsed() failed: " . $e->getMessage());
        }
    }
}
