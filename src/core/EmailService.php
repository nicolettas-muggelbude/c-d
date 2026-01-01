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
     * Email versenden (mit PHPMailer)
     */
    private function sendMail($to, $subject, $body) {
        try {
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

            // SMTP-Einstellungen aus Datenbank laden
            $smtp = $this->db->querySingle("SELECT * FROM smtp_settings WHERE id = 1");

            // SMTP oder PHP mail()
            if ($smtp && $smtp['smtp_enabled']) {
                // SMTP-Konfiguration
                $mail->isSMTP();
                $mail->Host       = $smtp['smtp_host'];
                $mail->SMTPAuth   = !empty($smtp['smtp_username']);
                $mail->Username   = $smtp['smtp_username'];
                $mail->Password   = $smtp['smtp_password'];
                $mail->SMTPSecure = $smtp['smtp_encryption'] !== 'none' ? $smtp['smtp_encryption'] : '';
                $mail->Port       = $smtp['smtp_port'];
                $mail->SMTPDebug  = $smtp['smtp_debug'];
            } else {
                // Standard PHP mail()
                $mail->isMail();
            }

            // Absender
            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addReplyTo(MAIL_FROM, MAIL_FROM_NAME);

            // Empfänger
            $mail->addAddress($to);

            // Inhalt
            $mail->CharSet = 'UTF-8';
            $mail->isHTML(false); // Plain Text
            $mail->Subject = $subject;
            $mail->Body    = $body;

            // Versenden
            $sent = $mail->send();

            if ($sent) {
                $method = ($smtp && $smtp['smtp_enabled']) ? "via SMTP ({$smtp['smtp_host']})" : "via PHP mail()";
                error_log("EmailService: Email sent to $to {$method}");
            }

            return $sent;

        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log("EmailService: Failed to send email to $to - Error: {$mail->ErrorInfo}");
            return false;
        } catch (\Exception $e) {
            error_log("EmailService: Failed to send email to $to - Error: {$e->getMessage()}");
            return false;
        }
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

    /**
     * Bestellbestätigung an Kunden senden
     *
     * @param int $orderId Bestellungs-ID
     * @return bool Erfolg
     */
    public function sendOrderConfirmation($orderId) {
        // Bestelldaten laden
        $order = $this->db->querySingle("SELECT * FROM orders WHERE id = :id", [':id' => $orderId]);

        if (!$order) {
            error_log("EmailService: Order $orderId not found");
            return false;
        }

        // Bestellpositionen laden
        $items = $this->db->query("
            SELECT * FROM order_items WHERE order_id = :id
        ", [':id' => $orderId]);

        // E-Mail-Text erstellen
        $subject = "Bestellbestätigung #" . $order['order_number'] . " - PC-Wittfoot UG";

        $body = "Hallo " . $order['customer_firstname'] . " " . $order['customer_lastname'] . ",\n\n";
        $body .= "vielen Dank für Ihre Bestellung bei PC-Wittfoot UG!\n\n";
        $body .= "Bestellnummer: " . $order['order_number'] . "\n";
        $body .= "Bestelldatum: " . date('d.m.Y H:i', strtotime($order['created_at'])) . "\n\n";

        $body .= "--- Ihre Bestellung ---\n\n";
        foreach ($items as $item) {
            $body .= sprintf("%dx %s - %.2f EUR\n",
                $item['quantity'],
                $item['product_name'],
                $item['total_price']
            );
        }

        $body .= "\n";
        $body .= sprintf("Zwischensumme: %.2f EUR\n", $order['subtotal']);
        $body .= sprintf("MwSt (19%%): %.2f EUR\n", $order['tax']);
        $body .= sprintf("Gesamt: %.2f EUR\n\n", $order['total']);

        // Lieferart
        $deliveryMethod = $order['delivery_method'] === 'pickup' ? 'Abholung im Laden' : 'Versand';
        $body .= "Lieferart: $deliveryMethod\n";

        // Zahlungsart
        $paymentMethods = [
            'prepayment' => 'Vorkasse / Überweisung',
            'cash' => 'Barzahlung bei Abholung',
            'paypal' => 'PayPal'
        ];
        $paymentMethod = $paymentMethods[$order['payment_method']] ?? $order['payment_method'];
        $body .= "Zahlungsart: $paymentMethod\n\n";

        // HelloCash Rechnungslink (falls vorhanden)
        if (!empty($order['hellocash_invoice_link'])) {
            $body .= "--- Ihre Rechnung ---\n\n";
            $body .= "Ihre Rechnung können Sie hier einsehen und herunterladen:\n";
            $body .= $order['hellocash_invoice_link'] . "\n\n";
        }

        $body .= "Wir melden uns in Kürze bei Ihnen mit weiteren Details.\n\n";
        $body .= "Mit freundlichen Grüßen\n";
        $body .= "Ihr Team von PC-Wittfoot UG\n\n";
        $body .= "---\n";
        $body .= "PC-Wittfoot UG\n";
        $body .= "E-Mail: " . MAIL_FROM . "\n";

        // E-Mail versenden
        return $this->sendMail($order['customer_email'], $subject, $body);
    }

    /**
     * Benachrichtigung an Admin über neue Bestellung
     *
     * @param int $orderId Bestellungs-ID
     * @return bool Erfolg
     */
    public function sendOrderNotification($orderId) {
        // Bestelldaten laden
        $order = $this->db->querySingle("SELECT * FROM orders WHERE id = :id", [':id' => $orderId]);

        if (!$order) {
            error_log("EmailService: Order $orderId not found");
            return false;
        }

        // Bestellpositionen laden
        $items = $this->db->query("
            SELECT * FROM order_items WHERE order_id = :id
        ", [':id' => $orderId]);

        // E-Mail-Text erstellen
        $subject = "Neue Bestellung #" . $order['order_number'] . " im Shop";

        $body = "Eine neue Bestellung ist eingegangen:\n\n";
        $body .= "Bestellnummer: " . $order['order_number'] . "\n";
        $body .= "Bestelldatum: " . date('d.m.Y H:i', strtotime($order['created_at'])) . "\n\n";

        $body .= "--- Kunde ---\n";
        $body .= "Name: " . $order['customer_firstname'] . " " . $order['customer_lastname'] . "\n";
        if ($order['customer_company']) {
            $body .= "Firma: " . $order['customer_company'] . "\n";
        }
        $body .= "E-Mail: " . $order['customer_email'] . "\n";
        if ($order['customer_phone']) {
            $body .= "Telefon: " . $order['customer_phone'] . "\n";
        }
        $body .= "Adresse: " . $order['customer_street'] . " " . $order['customer_housenumber'] . ", " . $order['customer_zip'] . " " . $order['customer_city'] . "\n\n";

        $body .= "--- Bestellpositionen ---\n\n";
        foreach ($items as $item) {
            $body .= sprintf("%dx %s (ID: %d) - %.2f EUR\n",
                $item['quantity'],
                $item['product_name'],
                $item['product_id'],
                $item['total_price']
            );
        }

        $body .= "\n";
        $body .= sprintf("Gesamt: %.2f EUR\n\n", $order['total']);

        // Lieferart
        $deliveryMethod = $order['delivery_method'] === 'pickup' ? 'Abholung im Laden' : 'Versand';
        $body .= "Lieferart: $deliveryMethod\n";

        // Zahlungsart
        $paymentMethods = [
            'prepayment' => 'Vorkasse / Überweisung',
            'cash' => 'Barzahlung bei Abholung',
            'paypal' => 'PayPal'
        ];
        $paymentMethod = $paymentMethods[$order['payment_method']] ?? $order['payment_method'];
        $body .= "Zahlungsart: $paymentMethod\n\n";

        if ($order['order_notes']) {
            $body .= "--- Anmerkungen ---\n";
            $body .= $order['order_notes'] . "\n\n";
        }

        // HelloCash Rechnungslink (für Buchhaltung)
        if (!empty($order['hellocash_invoice_link'])) {
            $body .= "--- HelloCash Rechnung ---\n";
            $body .= "Link zur Originalrechnung (für Buchhaltung):\n";
            $body .= $order['hellocash_invoice_link'] . "\n";
            if (!empty($order['hellocash_invoice_id'])) {
                $body .= "Invoice-ID: " . $order['hellocash_invoice_id'] . "\n";
            }
            $body .= "\n";
        }

        $body .= "Bestellung im Admin-Bereich ansehen:\n";
        $body .= BASE_URL . "/admin/orders/" . $order['id'] . "\n";

        // E-Mail an Admin versenden
        return $this->sendMail(MAIL_ADMIN, $subject, $body);
    }
}
