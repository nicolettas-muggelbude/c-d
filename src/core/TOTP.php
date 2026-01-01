<?php
/**
 * TOTP (Time-based One-Time Password) Implementation
 * RFC 6238 kompatibel
 * Funktioniert mit Google Authenticator, Authy, etc.
 */

class TOTP {
    const PERIOD = 30; // 30 Sekunden pro Code
    const DIGITS = 6;  // 6-stelliger Code
    const ALGORITHM = 'sha1';

    /**
     * Generiert ein neues Secret (Base32 encoded)
     */
    public static function generateSecret($length = 16) {
        $secret = '';
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567'; // Base32 Alphabet

        for ($i = 0; $i < $length; $i++) {
            $secret .= $chars[random_int(0, 31)];
        }

        return $secret;
    }

    /**
     * Generiert aktuellen TOTP-Code
     *
     * @param string $secret Base32-encoded Secret
     * @param int $timestamp Unix-Timestamp (optional, default: now)
     * @return string 6-stelliger Code
     */
    public static function generateCode($secret, $timestamp = null) {
        if ($timestamp === null) {
            $timestamp = time();
        }

        // Zeitschritt berechnen
        $timeCounter = floor($timestamp / self::PERIOD);

        // Secret von Base32 zu Binary
        $secretBinary = self::base32Decode($secret);

        // HMAC-SHA1
        $hash = hash_hmac(self::ALGORITHM, pack('N*', 0, $timeCounter), $secretBinary, true);

        // Dynamic truncation (RFC 4226)
        $offset = ord($hash[strlen($hash) - 1]) & 0x0F;
        $code = (
            ((ord($hash[$offset]) & 0x7F) << 24) |
            ((ord($hash[$offset + 1]) & 0xFF) << 16) |
            ((ord($hash[$offset + 2]) & 0xFF) << 8) |
            (ord($hash[$offset + 3]) & 0xFF)
        ) % pow(10, self::DIGITS);

        return str_pad($code, self::DIGITS, '0', STR_PAD_LEFT);
    }

    /**
     * Verifiziert einen TOTP-Code
     *
     * @param string $secret Base32-encoded Secret
     * @param string $code User-eingabe (6 Ziffern)
     * @param int $window Zeitfenster (±N Perioden, default: 1)
     * @return bool True wenn Code gültig
     */
    public static function verify($secret, $code, $window = 1) {
        $timestamp = time();

        // Code normalisieren (Leerzeichen entfernen, nur Ziffern)
        $code = preg_replace('/[^0-9]/', '', $code);

        // Prüfe aktuellen Zeitpunkt und ±window Perioden
        for ($i = -$window; $i <= $window; $i++) {
            $testTime = $timestamp + ($i * self::PERIOD);
            $validCode = self::generateCode($secret, $testTime);

            if (hash_equals($validCode, $code)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generiert QR-Code URL für Google Authenticator
     *
     * @param string $secret Base32-encoded Secret
     * @param string $accountName Benutzername/E-Mail
     * @param string $issuer Name der Anwendung
     * @return string otpauth:// URL
     */
    public static function getQRCodeUrl($secret, $accountName, $issuer = 'PC-Wittfoot') {
        $params = http_build_query([
            'secret' => $secret,
            'issuer' => $issuer,
            'algorithm' => strtoupper(self::ALGORITHM),
            'digits' => self::DIGITS,
            'period' => self::PERIOD
        ]);

        $label = rawurlencode($issuer) . ':' . rawurlencode($accountName);
        return "otpauth://totp/{$label}?{$params}";
    }

    /**
     * Generiert QR-Code als Data-URL (via Google Charts API)
     *
     * @param string $otpauthUrl otpauth:// URL
     * @return string Data-URL für <img src="">
     */
    public static function getQRCodeDataUrl($otpauthUrl) {
        $size = 200;
        $url = 'https://chart.googleapis.com/chart?' . http_build_query([
            'chs' => "{$size}x{$size}",
            'chld' => 'M|0',
            'cht' => 'qr',
            'chl' => $otpauthUrl
        ]);

        return $url;
    }

    /**
     * Generiert Backup-Codes
     *
     * @param int $count Anzahl der Codes
     * @return array Array mit Backup-Codes
     */
    public static function generateBackupCodes($count = 8) {
        $codes = [];

        for ($i = 0; $i < $count; $i++) {
            // 8-stelliger Code: XXXX-XXXX
            $code = sprintf(
                '%04d-%04d',
                random_int(0, 9999),
                random_int(0, 9999)
            );
            $codes[] = $code;
        }

        return $codes;
    }

    /**
     * Base32 Decode (RFC 4648)
     */
    private static function base32Decode($input) {
        $base32Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $input = strtoupper($input);
        $output = '';

        $v = 0;
        $vbits = 0;

        for ($i = 0, $j = strlen($input); $i < $j; $i++) {
            $v <<= 5;
            $char = $input[$i];
            $pos = strpos($base32Chars, $char);

            if ($pos === false) {
                continue; // Ungültiges Zeichen ignorieren
            }

            $v += $pos;
            $vbits += 5;

            while ($vbits >= 8) {
                $vbits -= 8;
                $output .= chr(($v >> $vbits) & 0xFF);
            }
        }

        return $output;
    }

    /**
     * Timing-Safe String Comparison (PHP < 5.6 Fallback)
     */
    private static function timingSafeEquals($a, $b) {
        if (function_exists('hash_equals')) {
            return hash_equals($a, $b);
        }

        if (strlen($a) !== strlen($b)) {
            return false;
        }

        $result = 0;
        for ($i = 0; $i < strlen($a); $i++) {
            $result |= ord($a[$i]) ^ ord($b[$i]);
        }

        return $result === 0;
    }
}
