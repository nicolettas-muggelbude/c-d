<?php
/**
 * Email Service
 * Template-basiertes Email-System für Terminbuchungen
 * PC-Wittfoot UG
 */

class EmailService {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Email versenden mit Template
     *
     * @param int $bookingId Buchungs-ID
     * @param string $templateType Template-Typ (confirmation, reminder_24h, reminder_1h)
     * @return bool Erfolg
     */
    public function sendBookingEmail($bookingId, $templateType) {
        // Buchungsdaten laden
        $booking = $this->db->querySingle("SELECT * FROM bookings WHERE id = :id", [':id' => $bookingId]);

        if (!$booking) {
            error_log("EmailService: Booking $bookingId not found");
            return false;
        }

        // Prüfen ob Email bereits versendet wurde
        if ($this->isEmailAlreadySent($bookingId, $templateType)) {
            error_log("EmailService: Email type $templateType already sent for booking $bookingId");
            return false;
        }

        // Template laden
        $template = $this->getTemplate($templateType);

        if (!$template || !$template['is_active']) {
            error_log("EmailService: Template $templateType not found or inactive");
            return false;
        }

        // Signatur laden
        $signature = $this->getSignature();

        // Platzhalter ersetzen
        $subject = $this->replacePlaceholders($template['subject'], $booking);
        $body = $this->replacePlaceholders($template['body'], $booking);

        // Signatur anhängen
        $fullBody = $body . "\n\n" . $signature;

        // Email versenden
        $success = $this->sendMail($booking['customer_email'], $subject, $fullBody);

        // Log-Eintrag erstellen
        $this->logEmail($bookingId, $templateType, $booking['customer_email'], $subject, $fullBody, $success);

        return $success;
    }

    /**
     * Template aus Datenbank laden
     */
    private function getTemplate($templateType) {
        return $this->db->querySingle(
            "SELECT * FROM email_templates WHERE template_type = :type",
            [':type' => $templateType]
        );
    }

    /**
     * Signatur aus Datenbank laden
     */
    private function getSignature() {
        $sig = $this->db->querySingle("SELECT signature_text FROM email_signature WHERE id = 1");
        return $sig['signature_text'] ?? '';
    }

    /**
     * Platzhalter im Template ersetzen
     */
    private function replacePlaceholders($text, $booking) {
        // Service-Type Labels
        $serviceLabels = [
            'pc-reparatur' => 'PC-Reparatur',
            'notebook-reparatur' => 'Notebook-Reparatur',
            'beratung' => 'Beratung',
            'software' => 'Software-Installation',
            'datenrettung' => 'Datenrettung',
            'virus-entfernung' => 'Virus-Entfernung',
            'upgrade' => 'Hardware-Upgrade',
            'sonstiges' => 'Sonstiges'
        ];

        // Booking-Type Labels
        $bookingTypeLabels = [
            'fixed' => 'Fester Termin',
            'walkin' => 'Walk-in',
            'internal' => 'Interne Notiz',
            'blocked' => 'Gesperrt'
        ];

        // Datum formatieren
        $date = new DateTime($booking['booking_date']);
        $weekdays = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
        $months = ['', 'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
                   'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];

        $dateFormatted = $weekdays[(int)$date->format('w')] . ', ' .
                        $date->format('d') . '. ' .
                        $months[(int)$date->format('n')] . ' ' .
                        $date->format('Y');

        // Uhrzeit formatieren
        $timeFormatted = 'Walk-in ab 14:00 Uhr';
        if ($booking['booking_time']) {
            $timeFormatted = substr($booking['booking_time'], 0, 5) . ' Uhr';
        }

        // Kundenanmerkungen-Sektion
        $notesSection = '';
        if (!empty($booking['customer_notes'])) {
            $notesSection = "Ihre Anmerkungen:\n" . $booking['customer_notes'] . "\n";
        }

        // Platzhalter-Map
        $placeholders = [
            '{customer_firstname}' => $booking['customer_firstname'],
            '{customer_lastname}' => $booking['customer_lastname'],
            '{booking_id}' => $booking['id'],
            '{booking_date_formatted}' => $dateFormatted,
            '{booking_time_formatted}' => $timeFormatted,
            '{service_type_label}' => $serviceLabels[$booking['service_type']] ?? $booking['service_type'],
            '{booking_type_label}' => $bookingTypeLabels[$booking['booking_type']] ?? $booking['booking_type'],
            '{customer_notes_section}' => $notesSection
        ];

        return str_replace(array_keys($placeholders), array_values($placeholders), $text);
    }

    /**
     * Email versenden
     */
    private function sendMail($to, $subject, $body) {
        $headers = "From: " . MAIL_FROM_NAME . " <" . MAIL_FROM . ">\r\n";
        $headers .= "Reply-To: " . MAIL_FROM . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        $sent = @mail($to, $subject, $body, $headers);

        if ($sent) {
            error_log("EmailService: Email sent to $to");
        } else {
            error_log("EmailService: Failed to send email to $to");
        }

        return $sent;
    }

    /**
     * Email-Versand loggen
     */
    private function logEmail($bookingId, $emailType, $recipient, $subject, $body, $success) {
        $sql = "INSERT INTO email_log (booking_id, email_type, recipient_email, subject, body, status, sent_at)
                VALUES (:booking_id, :email_type, :recipient, :subject, :body, :status, NOW())";

        $this->db->insert($sql, [
            ':booking_id' => $bookingId,
            ':email_type' => $emailType,
            ':recipient' => $recipient,
            ':subject' => $subject,
            ':body' => $body,
            ':status' => $success ? 'sent' : 'failed'
        ]);
    }

    /**
     * Prüfen ob Email bereits versendet wurde
     */
    private function isEmailAlreadySent($bookingId, $emailType) {
        $result = $this->db->querySingle(
            "SELECT COUNT(*) as count FROM email_log
             WHERE booking_id = :booking_id AND email_type = :email_type AND status = 'sent'",
            [':booking_id' => $bookingId, ':email_type' => $emailType]
        );

        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Alle Buchungen für 24h-Erinnerung holen
     */
    public function getBookingsForReminder24h() {
        $sql = "SELECT id FROM bookings
                WHERE booking_date = DATE_ADD(CURDATE(), INTERVAL 1 DAY)
                AND booking_type IN ('fixed', 'walkin')
                AND status IN ('pending', 'confirmed')
                AND id NOT IN (
                    SELECT booking_id FROM email_log
                    WHERE email_type = 'reminder_24h' AND status = 'sent'
                )";

        return $this->db->query($sql);
    }

    /**
     * Alle Buchungen für 1h-Erinnerung holen
     */
    public function getBookingsForReminder1h() {
        $sql = "SELECT id FROM bookings
                WHERE booking_date = CURDATE()
                AND booking_time IS NOT NULL
                AND booking_time BETWEEN
                    DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 50 MINUTE), '%H:%i:00')
                    AND DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 70 MINUTE), '%H:%i:00')
                AND booking_type = 'fixed'
                AND status IN ('pending', 'confirmed')
                AND id NOT IN (
                    SELECT booking_id FROM email_log
                    WHERE email_type = 'reminder_1h' AND status = 'sent'
                )";

        return $this->db->query($sql);
    }
}
