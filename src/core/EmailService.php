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
    public function sendBookingEmail($bookingId, $templateType, $extraPlaceholders = [], $skipDuplicateCheck = false) {
        // Buchungsdaten laden
        $booking = $this->db->querySingle("SELECT * FROM bookings WHERE id = :id", [':id' => $bookingId]);

        if (!$booking) {
            error_log("EmailService: Booking $bookingId not found");
            return false;
        }

        // Zusätzliche Platzhalter zu Booking-Daten hinzufügen
        if (!empty($extraPlaceholders)) {
            $booking = array_merge($booking, $extraPlaceholders);
        }

        // Prüfen ob Email bereits versendet wurde (außer bei skipDuplicateCheck)
        if (!$skipDuplicateCheck && $this->isEmailAlreadySent($bookingId, $templateType)) {
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

        // Signatur für HTML konvertieren (Zeilenumbrüche → <br>)
        $signatureHtml = nl2br($signature);

        // HTML-Body mit Signatur
        $fullBodyHtml = $body . "\n\n" . $signatureHtml;

        // Plaintext-Fallback (HTML-Tags entfernen)
        $fullBodyPlain = strip_tags($body) . "\n\n" . $signature;

        // Email versenden
        $success = $this->sendMail($booking['customer_email'], $subject, $fullBodyHtml, $fullBodyPlain);

        // Log-Eintrag erstellen
        $this->logEmail($bookingId, $templateType, $booking['customer_email'], $subject, $fullBodyHtml, $success);

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
            'beratung' => 'Beratung',
            'verkauf' => 'Verkauf',
            'fernwartung' => 'Fernwartung',
            'hausbesuch' => 'Hausbesuch',
            'installation' => 'Installation',
            'diagnose' => 'Diagnose',
            'reparatur' => 'Reparatur',
            'sonstiges' => 'Sonstiges',
            // Alt (für Kompatibilität)
            'pc-reparatur' => 'PC-Reparatur',
            'notebook-reparatur' => 'Notebook-Reparatur',
            'software' => 'Software-Installation',
            'datenrettung' => 'Datenrettung',
            'virus-entfernung' => 'Virus-Entfernung',
            'upgrade' => 'Hardware-Upgrade'
        ];

        // Booking-Type Labels
        $bookingTypeLabels = [
            'fixed' => 'Fester Termin',
            'walkin' => 'Ich komme vorbei',
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
        if ($booking['booking_type'] === 'walkin') {
            // Prüfe ob Samstag (andere Öffnungszeiten)
            $date = new DateTime($booking['booking_date']);
            $isSaturday = $date->format('N') == 6;
            $timeRange = $isSaturday ? '12:00-16:00' : '14:00-17:00';

            if ($booking['booking_time']) {
                $timeFormatted = 'Empfohlene Ankunftszeit: ' . substr($booking['booking_time'], 0, 5) . ' Uhr';
            } else {
                $timeFormatted = "Flexible Ankunft zwischen {$timeRange} Uhr";
            }
        } else {
            $timeFormatted = $booking['booking_time'] ? substr($booking['booking_time'], 0, 5) . ' Uhr' : '-';
        }

        // Kundenanmerkungen-Sektion
        $notesSection = '';
        if (!empty($booking['customer_notes'])) {
            $notesSection = "Ihre Anmerkungen:\n" . $booking['customer_notes'] . "\n";
        }

        // Flexibilitäts-Hinweis für "Ich komme vorbei"
        $flexibilityNote = '';
        if ($booking['booking_type'] === 'walkin') {
            // Prüfe ob Samstag (andere Öffnungszeiten)
            $date = new DateTime($booking['booking_date']);
            $isSaturday = $date->format('N') == 6;
            $timeRange = $isSaturday ? '12:00-16:00' : '14:00-17:00';
            $flexibilityNote = "Sie können flexibel zwischen {$timeRange} Uhr vorbeikommen. Die empfohlene Zeit hilft uns, Wartezeiten zu minimieren.";
        }

        // Optionale Felder
        $customerCompany = !empty($booking['customer_company']) ? $booking['customer_company'] : '-';
        $customerPhoneLandline = !empty($booking['customer_phone_landline']) ?
            $booking['customer_phone_country'] . ' ' . $booking['customer_phone_landline'] : '-';

        // Admin-Link
        $adminBookingLink = BASE_URL . '/admin/booking-detail?id=' . $booking['id'];

        // Kunden-Verwaltungs-Link (Magic Link)
        $manageLink = '';
        if (!empty($booking['manage_token'])) {
            $manageLink = BASE_URL . '/termin/verwalten?token=' . $booking['manage_token'];
        }

        // Telefonnummer kombiniert
        $customerPhone = $booking['customer_phone_country'] . ' ' . $booking['customer_phone_mobile'];

        // Platzhalter-Map
        $placeholders = [
            '{customer_firstname}' => $booking['customer_firstname'],
            '{customer_lastname}' => $booking['customer_lastname'],
            '{customer_email}' => $booking['customer_email'],
            '{customer_company}' => $customerCompany,
            '{customer_phone_country}' => $booking['customer_phone_country'],
            '{customer_phone_mobile}' => $booking['customer_phone_mobile'],
            '{customer_phone_landline}' => $customerPhoneLandline,
            '{customer_phone}' => $customerPhone,
            '{customer_street}' => $booking['customer_street'],
            '{customer_house_number}' => $booking['customer_house_number'],
            '{customer_postal_code}' => $booking['customer_postal_code'],
            '{customer_city}' => $booking['customer_city'],
            '{booking_id}' => $booking['id'],
            '{booking_number}' => str_pad($booking['id'], 6, '0', STR_PAD_LEFT),
            '{booking_date}' => $dateFormatted,
            '{booking_date_formatted}' => $dateFormatted,
            '{booking_time}' => $timeFormatted,
            '{booking_time_formatted}' => $timeFormatted,
            '{service_type}' => $serviceLabels[$booking['service_type']] ?? $booking['service_type'],
            '{service_type_label}' => $serviceLabels[$booking['service_type']] ?? $booking['service_type'],
            '{booking_type}' => $bookingTypeLabels[$booking['booking_type']] ?? $booking['booking_type'],
            '{booking_type_label}' => $bookingTypeLabels[$booking['booking_type']] ?? $booking['booking_type'],
            '{customer_notes_section}' => $notesSection,
            '{flexibility_note}' => $flexibilityNote,
            '{admin_booking_link}' => $adminBookingLink,
            '{admin_link}' => $adminBookingLink,
            '{manage_link}' => $manageLink
        ];

        // Zusätzliche Platzhalter aus Booking-Daten (z.B. old_date, old_time)
        foreach ($booking as $key => $value) {
            $placeholder = '{' . $key . '}';
            if (!isset($placeholders[$placeholder]) && is_scalar($value)) {
                $placeholders[$placeholder] = $value;
            }
        }

        return str_replace(array_keys($placeholders), array_values($placeholders), $text);
    }

    /**
     * Admin-Benachrichtigung über neue Buchung senden
     *
     * @param int $bookingId Buchungs-ID
     * @return bool Erfolg
     */
    public function sendBookingNotification($bookingId, $templateType = 'booking_notification', $extraPlaceholders = [], $skipDuplicateCheck = false) {
        // Buchungsdaten laden
        $booking = $this->db->querySingle("SELECT * FROM bookings WHERE id = :id", [':id' => $bookingId]);

        if (!$booking) {
            error_log("EmailService: Booking $bookingId not found");
            return false;
        }

        // Zusätzliche Platzhalter zu Booking-Daten hinzufügen
        if (!empty($extraPlaceholders)) {
            $booking = array_merge($booking, $extraPlaceholders);
        }

        // Prüfen ob Email bereits versendet wurde (außer bei skipDuplicateCheck)
        if (!$skipDuplicateCheck && $this->isEmailAlreadySent($bookingId, $templateType)) {
            error_log("EmailService: $templateType already sent for booking $bookingId");
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

        // Signatur für HTML konvertieren (Zeilenumbrüche → <br>)
        $signatureHtml = nl2br($signature);

        // HTML-Body mit Signatur
        $fullBodyHtml = $body . "\n\n" . $signatureHtml;

        // Plaintext-Fallback (HTML-Tags entfernen)
        $fullBodyPlain = strip_tags($body) . "\n\n" . $signature;

        // Email an Admin versenden
        $success = $this->sendMail(MAIL_ADMIN, $subject, $fullBodyHtml, $fullBodyPlain);

        // Log-Eintrag erstellen
        $this->logEmail($bookingId, $templateType, MAIL_ADMIN, $subject, $fullBodyHtml, $success);

        return $success;
    }

    /**
     * Email versenden (mit PHPMailer)
     */
    private function sendMail($to, $subject, $bodyHtml, $bodyPlain = null) {
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
            $mail->isHTML(true); // HTML-Emails
            $mail->Subject = $subject;
            $mail->Body    = $bodyHtml;

            // Plaintext-Fallback für Email-Clients ohne HTML-Unterstützung
            if ($bodyPlain !== null) {
                $mail->AltBody = $bodyPlain;
            }

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
     * Platzhalter im Order-Template ersetzen
     */
    private function replaceOrderPlaceholders($text, $order, $items) {
        // Bestellpositionen formatieren
        $itemsList = '';
        foreach ($items as $item) {
            $itemsList .= sprintf("%dx %s - %.2f EUR\n",
                $item['quantity'],
                $item['product_name'],
                $item['total_price']
            );
        }

        // Datum formatieren
        $orderDate = date('d.m.Y H:i', strtotime($order['created_at']));

        // Lieferart
        $deliveryMethods = [
            'billing' => 'Versand an Rechnungsadresse',
            'pickup' => 'Abholung im Laden',
            'shipping' => 'Versand an andere Adresse'
        ];
        $deliveryMethod = $deliveryMethods[$order['delivery_method']] ?? $order['delivery_method'];

        // Zahlungsart
        $paymentMethods = [
            'prepayment' => 'Vorkasse / Überweisung',
            'paypal' => 'PayPal'
        ];
        $paymentMethod = $paymentMethods[$order['payment_method']] ?? $order['payment_method'];

        // Invoice-Link Sektion
        $invoiceLinkSection = '';
        if (!empty($order['hellocash_invoice_link'])) {
            $invoiceLinkSection = "--- Ihre Rechnung ---\n\n";
            $invoiceLinkSection .= "Ihre Rechnung können Sie hier einsehen und herunterladen:\n";
            $invoiceLinkSection .= $order['hellocash_invoice_link'] . "\n";
        }

        // Order Notes Sektion
        $orderNotesSection = '';
        if (!empty($order['order_notes'])) {
            $orderNotesSection = "--- Anmerkungen ---\n" . $order['order_notes'] . "\n";
        }

        // Kunden-Firma Zeile
        $customerCompanyLine = '';
        if (!empty($order['customer_company'])) {
            $customerCompanyLine = "Firma: " . $order['customer_company'] . "\n";
        }

        // Kunden-Telefon Zeile
        $customerPhoneLine = '';
        if (!empty($order['customer_phone'])) {
            $customerPhoneLine = "Telefon: " . $order['customer_phone'] . "\n";
        }

        // Kunden-Adresse
        $customerAddress = $order['customer_street'] . " " . $order['customer_housenumber'] . ", " .
                          $order['customer_zip'] . " " . $order['customer_city'];

        // Admin Order Link
        $adminOrderLink = BASE_URL . "/admin/orders/" . $order['id'];

        // Platzhalter-Map
        $placeholders = [
            '{customer_firstname}' => $order['customer_firstname'],
            '{customer_lastname}' => $order['customer_lastname'],
            '{customer_email}' => $order['customer_email'],
            '{customer_company_line}' => $customerCompanyLine,
            '{customer_phone_line}' => $customerPhoneLine,
            '{customer_address}' => $customerAddress,
            '{order_number}' => $order['order_number'],
            '{order_date}' => $orderDate,
            '{order_items}' => $itemsList,
            '{order_subtotal}' => sprintf("%.2f EUR", $order['subtotal']),
            '{order_tax}' => sprintf("%.2f EUR", $order['tax']),
            '{order_total}' => sprintf("%.2f EUR", $order['total']),
            '{delivery_method}' => $deliveryMethod,
            '{payment_method}' => $paymentMethod,
            '{invoice_link_section}' => $invoiceLinkSection,
            '{order_notes_section}' => $orderNotesSection,
            '{admin_order_link}' => $adminOrderLink
        ];

        return str_replace(array_keys($placeholders), array_values($placeholders), $text);
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

        // Prüfen ob Email bereits versendet wurde
        if ($this->isEmailAlreadySent($orderId, 'order_confirmation')) {
            error_log("EmailService: order_confirmation already sent for order $orderId");
            return false;
        }

        // Template laden
        $template = $this->getTemplate('order_confirmation');

        if (!$template || !$template['is_active']) {
            error_log("EmailService: Template order_confirmation not found or inactive");
            return false;
        }

        // Bestellpositionen laden
        $items = $this->db->query("
            SELECT * FROM order_items WHERE order_id = :id
        ", [':id' => $orderId]);

        // Signatur laden
        $signature = $this->getSignature();

        // Platzhalter ersetzen
        $subject = $this->replaceOrderPlaceholders($template['subject'], $order, $items);
        $body = $this->replaceOrderPlaceholders($template['body'], $order, $items);

        // Signatur anhängen
        $fullBody = $body . "\n\n" . $signature;

        // Email versenden
        $success = $this->sendMail($order['customer_email'], $subject, $fullBody);

        // Log-Eintrag erstellen
        $this->logEmail($orderId, 'order_confirmation', $order['customer_email'], $subject, $fullBody, $success);

        return $success;
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

        // Prüfen ob Email bereits versendet wurde
        if ($this->isEmailAlreadySent($orderId, 'order_notification')) {
            error_log("EmailService: order_notification already sent for order $orderId");
            return false;
        }

        // Template laden
        $template = $this->getTemplate('order_notification');

        if (!$template || !$template['is_active']) {
            error_log("EmailService: Template order_notification not found or inactive");
            return false;
        }

        // Bestellpositionen laden
        $items = $this->db->query("
            SELECT * FROM order_items WHERE order_id = :id
        ", [':id' => $orderId]);

        // Signatur laden
        $signature = $this->getSignature();

        // Platzhalter ersetzen
        $subject = $this->replaceOrderPlaceholders($template['subject'], $order, $items);
        $body = $this->replaceOrderPlaceholders($template['body'], $order, $items);

        // Signatur anhängen
        $fullBody = $body . "\n\n" . $signature;

        // Email versenden
        $success = $this->sendMail(MAIL_ADMIN, $subject, $fullBody);

        // Log-Eintrag erstellen
        $this->logEmail($orderId, 'order_notification', MAIL_ADMIN, $subject, $fullBody, $success);

        return $success;
    }
}
