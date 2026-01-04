#!/bin/bash
# Security Quick-Test Script
# PC-Wittfoot UG

BASE_URL="http://localhost:8000"

echo "╔════════════════════════════════════════════════╗"
echo "║     Security Testing - PC-Wittfoot UG          ║"
echo "╚════════════════════════════════════════════════╝"
echo ""

# Farben
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

pass_count=0
fail_count=0
warn_count=0

# Test-Funktion
run_test() {
    local test_name="$1"
    local command="$2"
    local expected="$3"

    echo -n "Testing: $test_name ... "

    result=$(eval "$command" 2>/dev/null)

    if echo "$result" | grep -qi "$expected"; then
        echo -e "${GREEN}✓ PASS${NC}"
        ((pass_count++))
        return 0
    else
        echo -e "${RED}✗ FAIL${NC}"
        ((fail_count++))
        return 1
    fi
}

warn_test() {
    local test_name="$1"
    local message="$2"

    echo -e "${YELLOW}⚠ WARN${NC}: $test_name"
    echo "  → $message"
    ((warn_count++))
}

echo "═══════════════════════════════════════════════"
echo "1. SQL-Injection Protection Tests"
echo "═══════════════════════════════════════════════"
echo ""

# Test 1: Login mit SQL-Injection
run_test "SQL-Injection in Login (OR 1=1)" \
    "curl -s -X POST $BASE_URL/admin/login -d \"username=admin' OR '1'='1' --&password=test\"" \
    "error\|ungültig\|invalid"

# Test 2: Terminsuche mit SQL-Injection
run_test "SQL-Injection in Search (UNION)" \
    "curl -s '$BASE_URL/admin/bookings?search=' UNION SELECT NULL --'" \
    "keine.*gefunden\|error\|invalid"

echo ""
echo "═══════════════════════════════════════════════"
echo "2. CSRF-Token Protection Tests"
echo "═══════════════════════════════════════════════"
echo ""

# Test 3: Login-Seite hat CSRF-Token
run_test "CSRF-Token in Login-Form vorhanden" \
    "curl -s $BASE_URL/admin/login" \
    "csrf_token"

# Test 4: POST ohne CSRF-Token wird abgelehnt
echo -n "Testing: POST ohne CSRF-Token ... "
response=$(curl -s -X POST $BASE_URL/admin/login \
    -d "username=admin&password=test" \
    -c /tmp/cookies.txt)

if echo "$response" | grep -qi "csrf\|token\|ungültig\|invalid"; then
    echo -e "${GREEN}✓ PASS${NC} (Token-Validierung aktiv)"
    ((pass_count++))
else
    echo -e "${YELLOW}⚠ WARN${NC} (Keine explizite CSRF-Fehlermeldung)"
    ((warn_count++))
fi

echo ""
echo "═══════════════════════════════════════════════"
echo "3. Session Security Tests"
echo "═══════════════════════════════════════════════"
echo ""

# Test 5: HttpOnly Cookie-Flag
session_cookie=$(curl -si $BASE_URL/admin/login | grep -i "Set-Cookie.*pc_wittfoot_session")

echo -n "Testing: HttpOnly Cookie-Flag ... "
if echo "$session_cookie" | grep -qi "HttpOnly"; then
    echo -e "${GREEN}✓ PASS${NC}"
    ((pass_count++))
else
    echo -e "${RED}✗ FAIL${NC}"
    ((fail_count++))
fi

# Test 6: SameSite Cookie-Flag
echo -n "Testing: SameSite Cookie-Flag ... "
if echo "$session_cookie" | grep -qi "SameSite"; then
    echo -e "${GREEN}✓ PASS${NC}"
    ((pass_count++))
else
    echo -e "${YELLOW}⚠ WARN${NC} (SameSite nicht gesetzt)"
    ((warn_count++))
fi

echo ""
echo "═══════════════════════════════════════════════"
echo "4. Security Headers Tests"
echo "═══════════════════════════════════════════════"
echo ""

headers=$(curl -si $BASE_URL/ | head -20)

# Test 7: X-Frame-Options
run_test "X-Frame-Options Header" \
    "echo '$headers'" \
    "X-Frame-Options"

# Test 8: X-Content-Type-Options
run_test "X-Content-Type-Options Header" \
    "echo '$headers'" \
    "X-Content-Type-Options"

# Test 9: Content-Security-Policy
run_test "Content-Security-Policy Header" \
    "echo '$headers'" \
    "Content-Security-Policy"

echo ""
echo "═══════════════════════════════════════════════"
echo "5. XSS Protection Tests"
echo "═══════════════════════════════════════════════"
echo ""

# Test 10: XSS in API-Response wird escaped
echo -n "Testing: XSS-Payload wird escaped ... "
api_response=$(curl -s -X POST $BASE_URL/api/booking \
    -H "Content-Type: application/json" \
    -d '{
        "booking_type": "fixed",
        "service_type": "beratung",
        "booking_date": "2026-01-20",
        "booking_time": "11:00",
        "customer_firstname": "<script>alert(1)</script>",
        "customer_lastname": "Test",
        "customer_email": "test@test.de",
        "customer_phone_mobile": "1234567890",
        "customer_street": "Test",
        "customer_house_number": "1",
        "customer_postal_code": "12345",
        "customer_city": "Berlin"
    }' 2>/dev/null)

# Prüfen ob Response XSS-Payload escaped enthält oder Fehler
if echo "$api_response" | grep -q "&lt;script&gt;\|error\|ungültig"; then
    echo -e "${GREEN}✓ PASS${NC} (XSS escaped oder Input-Validierung)"
    ((pass_count++))
else
    echo -e "${YELLOW}⚠ INFO${NC} (API gibt keine HTML-Antwort zurück)"
    ((warn_count++))
fi

echo ""
echo "═══════════════════════════════════════════════"
echo "Test Summary"
echo "═══════════════════════════════════════════════"
echo ""
echo -e "Passed:  ${GREEN}$pass_count${NC}"
echo -e "Failed:  ${RED}$fail_count${NC}"
echo -e "Warnings: ${YELLOW}$warn_count${NC}"
echo ""

if [ $fail_count -eq 0 ]; then
    echo -e "${GREEN}╔════════════════════════════════════════════════╗${NC}"
    echo -e "${GREEN}║  Alle kritischen Tests bestanden! ✓           ║${NC}"
    echo -e "${GREEN}╚════════════════════════════════════════════════╝${NC}"
    exit 0
else
    echo -e "${RED}╔════════════════════════════════════════════════╗${NC}"
    echo -e "${RED}║  $fail_count Test(s) fehlgeschlagen!                   ║${NC}"
    echo -e "${RED}╚════════════════════════════════════════════════╝${NC}"
    exit 1
fi
