#!/bin/bash
# Test: Booking API via cURL aufrufen

echo "=== Booking API cURL Test ==="
echo ""

# Test-Daten
JSON_DATA='{
  "booking_type": "fixed",
  "service_type": "pc-reparatur",
  "booking_date": "2026-01-15",
  "booking_time": "11:00",
  "customer_firstname": "Max",
  "customer_lastname": "Tester",
  "customer_email": "max.tester@example.com",
  "customer_phone_country": "+49",
  "customer_phone_mobile": "0171 9999999",
  "customer_phone_landline": "030 88888888",
  "customer_company": "",
  "customer_notes": "cURL Test"
}'

echo "Sende Request an API..."
echo ""

# Request senden
curl -X POST \
  -H "Content-Type: application/json" \
  -d "$JSON_DATA" \
  http://localhost/api/booking \
  -w "\n\nHTTP Status: %{http_code}\n" \
  -s

echo ""
echo "=== Test abgeschlossen ==="
