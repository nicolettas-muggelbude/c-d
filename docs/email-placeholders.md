# E-Mail Template Platzhalter

## Terminbuchung (Booking)

### Verfügbare Platzhalter:
- `{customer_firstname}` - Vorname des Kunden
- `{customer_lastname}` - Nachname des Kunden
- `{booking_id}` - Buchungs-ID
- `{booking_date_formatted}` - Formatiertes Datum (z.B. "Montag, 15. Januar 2026")
- `{booking_time_formatted}` - Formatierte Uhrzeit (z.B. "14:30 Uhr")
- `{service_type_label}` - Service-Typ (z.B. "PC-Reparatur")
- `{booking_type_label}` - Buchungstyp (z.B. "Fester Termin")
- `{customer_notes_section}` - Kundenanmerkungen (nur wenn vorhanden)

## Shop-Bestellungen (Orders)

### Verfügbare Platzhalter:
- `{customer_firstname}` - Vorname des Kunden
- `{customer_lastname}` - Nachname des Kunden
- `{customer_email}` - E-Mail-Adresse
- `{customer_company_line}` - Firmenname (nur wenn vorhanden, mit "Firma: " Präfix)
- `{customer_phone_line}` - Telefonnummer (nur wenn vorhanden, mit "Telefon: " Präfix)
- `{customer_address}` - Vollständige Adresse (Straße, Hausnummer, PLZ, Stadt)
- `{order_number}` - Bestellnummer (z.B. "ORD-2026-1234")
- `{order_date}` - Bestelldatum (z.B. "01.01.2026 15:30")
- `{order_items}` - Liste aller Bestellpositionen (formatiert)
- `{order_subtotal}` - Zwischensumme (formatiert mit EUR)
- `{order_tax}` - MwSt-Betrag (formatiert mit EUR)
- `{order_total}` - Gesamtsumme (formatiert mit EUR)
- `{delivery_method}` - Lieferart ("Abholung im Laden" oder "Versand")
- `{payment_method}` - Zahlungsart (z.B. "Vorkasse / Überweisung")
- `{invoice_link_section}` - HelloCash Rechnungslink-Sektion (nur wenn Invoice erstellt)
- `{order_notes_section}` - Bestellnotizen (nur wenn vorhanden)
- `{admin_order_link}` - Link zur Bestellung im Admin-Bereich

## Hinweise

- Platzhalter mit `_section` oder `_line` Suffix werden nur eingefügt, wenn die entsprechenden Daten vorhanden sind
- Bei fehlenden Daten bleibt der Platzhalter leer (wird durch leeren String ersetzt)
- Die Signatur wird automatisch an alle E-Mails angehängt
