# HelloCash API Integration

## Übersicht

Das Terminbuchungssystem ist mit der HelloCash REST API integriert. Bei jeder Terminbuchung wird automatisch geprüft, ob der Kunde bereits in HelloCash existiert. Falls nicht, wird ein neuer Kunde angelegt.

## Konfiguration

### 1. API-Schlüssel erhalten

1. Melden Sie sich im HelloCash Business Portal an
2. Gehen Sie zu Einstellungen → API
3. Generieren Sie einen neuen API-Token (Bearer Token)
4. Kopieren Sie den Token

**Hinweis:** Die API ist nur ab dem Premium-Plan verfügbar.

### 2. Konfiguration in config.php

Tragen Sie Ihren API-Schlüssel in `/src/core/config.php` ein:

```php
// hellocash API
define('HELLOCASH_API_KEY', 'ihr-api-token-hier');
define('HELLOCASH_API_URL', 'https://api.hellocash.business/api/v1/');
define('HELLOCASH_LANDLINE_FIELD', 'Zusatzfeld 1'); // Name des Custom Fields für Festnetznummer
```

**Wichtig:**
- Die API-URL ist immer `https://api.hellocash.business/api/v1/` (für alle Länder)
- Falls Ihr Custom Field für die Festnetznummer anders heißt, passen Sie `HELLOCASH_LANDLINE_FIELD` an

### 3. Datenbank-Migration ausführen

```bash
mysql -u pc_wittfoot -pdev123 pc_wittfoot < database/add-hellocash-customer-id.sql
```

## Funktionsweise

### Automatischer Workflow

Wenn ein Kunde einen Termin bucht:

1. **Suche nach E-Mail:** System prüft ob Kunde mit dieser E-Mail bereits existiert
2. **Suche nach Telefon:** Falls nicht gefunden, Suche nach Telefonnummer
3. **Kunde erstellen:** Falls immer noch nicht gefunden, neuen Kunden in HelloCash anlegen
4. **Speichern:** Termin mit HelloCash Customer ID in Datenbank speichern

### Datenfelder

Folgende Daten werden an HelloCash übermittelt:

- **Vorname** (`user_firstname`)
- **Nachname** (`user_surname`)
- **E-Mail** (`user_email`)
- **Mobilnummer** (`user_phoneNumber`) - **OHNE** Ländervorwahl (z.B. "170 1234567")
- **Ländercode** (`user_country_code`) - ISO-Code (z.B. "DE", "AT")
- **Firma** (`user_company`) - optional
- **Festnetznummer** (Custom Field "Zusatzfeld 1") - optional

### Fehlerbehandlung

Die Integration ist **fail-safe**:
- Wenn HelloCash API nicht konfiguriert ist → Termin wird trotzdem gespeichert
- Wenn API-Anfrage fehlschlägt → Termin wird trotzdem gespeichert
- Fehler werden im Error-Log dokumentiert

## API-Klasse: HelloCashClient

### Verfügbare Methoden

```php
$client = new HelloCashClient();

// Prüfen ob API konfiguriert ist
$client->isConfigured();

// Kunde suchen
$customer = $client->findCustomerByEmail('kunde@example.com');
$customer = $client->findCustomerByPhone('+491701234567');

// Kunde erstellen
$customer = $client->createCustomer([
    'firstname' => 'Max',
    'lastname' => 'Mustermann',
    'email' => 'max@example.com',
    'phone' => '+491701234567',
    'company' => 'Musterfirma GmbH'
]);

// Kunde aktualisieren
$customer = $client->updateCustomer('customer-id', [
    'email' => 'neue-email@example.com'
]);

// Kunde suchen oder erstellen (verwendet in booking.php)
$result = $client->findOrCreateCustomer([
    'firstname' => 'Max',
    'lastname' => 'Mustermann',
    'email' => 'max@example.com',
    'phone_country' => '+49',
    'phone_mobile' => '170 1234567',
    'company' => null
]);

// $result = [
//     'customer_id' => 'hc-12345' oder null,
//     'is_new' => true/false,
//     'error' => 'Fehlermeldung' oder null
// ]
```

### Rate Limiting

Die HelloCash API erlaubt **60 Anfragen pro Minute**. Dies ist für die Terminbuchung mehr als ausreichend.

## Testen der Integration

### 1. Ohne API-Token (Fail-Safe Test)

Lassen Sie `HELLOCASH_API_KEY` leer. Termine sollten trotzdem gespeichert werden.

```bash
# Logs prüfen
tail -f /var/log/apache2/error.log | grep HelloCash
```

Erwartete Ausgabe: `HelloCash API nicht konfiguriert`

### 2. Mit API-Token

1. Tragen Sie Ihren API-Token in `config.php` ein
2. Buchen Sie einen Testtermin
3. Prüfen Sie die Logs:

```bash
tail -f /var/log/apache2/error.log | grep HelloCash
```

Erwartete Ausgabe bei neuem Kunden:
```
HelloCash Customer: Neu erstellt - ID: hc-12345
```

Erwartete Ausgabe bei bestehendem Kunden:
```
HelloCash Customer: Gefunden - ID: hc-12345
```

### 3. In HelloCash prüfen

1. Öffnen Sie das HelloCash Business Portal
2. Gehen Sie zu Kunden
3. Suchen Sie nach dem neu angelegten Kunden

## Datenbank-Schema

### bookings Tabelle

```sql
hellocash_customer_id VARCHAR(100) NULL
```

Dieses Feld speichert die Customer ID aus HelloCash und ermöglicht die Verknüpfung zwischen Termin und HelloCash-Kunde.

## API-Dokumentation

- **Offizielle Dokumentation:** https://api.hellocash.net/docs/
- **Authentifizierung:** Bearer Token (seit November 2023)
- **Base URL Deutschland:** https://api.hellocash.de/
- **Base URL Österreich:** https://api.hellocash.at/

## Fehlerbehebung

### "HelloCash API nicht konfiguriert"

→ Prüfen Sie ob `HELLOCASH_API_KEY` in `config.php` gesetzt ist

### "API Error (HTTP 401): Unauthorized"

→ API-Token ist ungültig oder abgelaufen. Generieren Sie einen neuen Token im HelloCash Portal

### "API Error (HTTP 429): Too Many Requests"

→ Rate Limit (60/min) überschritten. Warten Sie eine Minute und versuchen Sie es erneut

### "cURL Error: Could not resolve host"

→ Server kann HelloCash API nicht erreichen. Prüfen Sie Netzwerkverbindung und Firewall

## Sicherheit

- API-Token wird **niemals** im Frontend exponiert
- Alle API-Calls erfolgen serverseitig über `HelloCashClient`
- Sensible Daten werden nur über HTTPS übertragen
- `config.php` darf **nicht** in Git committed werden

## Zukünftige Erweiterungen

Mögliche Erweiterungen der Integration:

1. **Rechnungen erstellen:** Nach Terminabschluss automatisch Rechnung in HelloCash generieren
2. **Zahlungen verknüpfen:** Anzahlungen mit HelloCash-Zahlungssystem verknüpfen
3. **Artikel-Synchronisation:** Verkaufte Produkte aus Shop in HelloCash übertragen
4. **Webhooks:** HelloCash-Events empfangen (z.B. Zahlungsbestätigung)

## Support

Bei Fragen zur HelloCash API:
- **HelloCash Support:** https://hellocash.at/kontakt
- **API-Dokumentation:** https://api.hellocash.net/docs/
- **Entwickler-Portal:** https://developer.hellocash.net/
