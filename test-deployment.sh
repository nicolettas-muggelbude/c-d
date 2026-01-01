#!/bin/bash
#
# Test-Script für Deployment-System
# PC-Wittfoot UG
#
# Testet alle Komponenten des Deployment-Systems:
# - Wartungsmodus
# - Health-Check
# - Backup-Script
# - Admin-UI
#

set -e  # Bei kritischen Fehlern abbrechen

# ===================================
# KONFIGURATION
# ===================================

PROJECT_DIR="$(cd "$(dirname "$0")" && pwd)"
SRC_DIR="$PROJECT_DIR/src"
BACKUP_DIR="$PROJECT_DIR/backups"
MAINTENANCE_FILE="$SRC_DIR/MAINTENANCE"

# Test-Server
TEST_HOST="localhost"
TEST_PORT="8000"
TEST_URL="http://$TEST_HOST:$TEST_PORT"

# Farben für Ausgabe
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Test-Zähler
TESTS_TOTAL=0
TESTS_PASSED=0
TESTS_FAILED=0
TESTS_SKIPPED=0

# Server-PID
SERVER_PID=""

# ===================================
# FUNKTIONEN
# ===================================

log_info() {
    echo -e "${BLUE}[INFO]${NC} $1" >&2
}

log_success() {
    echo -e "${GREEN}[✓]${NC} $1" >&2
}

log_error() {
    echo -e "${RED}[✗]${NC} $1" >&2
}

log_warning() {
    echo -e "${YELLOW}[!]${NC} $1" >&2
}

log_test() {
    echo -e "${CYAN}[TEST]${NC} $1" >&2
}

# Test starten
start_test() {
    TESTS_TOTAL=$((TESTS_TOTAL + 1))
    log_test "$1"
}

# Test erfolgreich
pass_test() {
    TESTS_PASSED=$((TESTS_PASSED + 1))
    log_success "$1"
    echo ""
}

# Test fehlgeschlagen
fail_test() {
    TESTS_FAILED=$((TESTS_FAILED + 1))
    log_error "$1"
    echo ""
}

# Test übersprungen
skip_test() {
    TESTS_SKIPPED=$((TESTS_SKIPPED + 1))
    log_warning "ÜBERSPRUNGEN: $1"
    echo ""
}

# PHP-Server starten
start_server() {
    log_info "Starte PHP-Entwicklungsserver auf $TEST_URL..."

    cd "$SRC_DIR"
    php -S $TEST_HOST:$TEST_PORT server.php > /dev/null 2>&1 &
    SERVER_PID=$!
    cd "$PROJECT_DIR"

    # Warten bis Server bereit
    sleep 2

    # Prüfen ob Server läuft
    if kill -0 $SERVER_PID 2>/dev/null; then
        log_success "Server gestartet (PID: $SERVER_PID)"
        return 0
    else
        log_error "Server konnte nicht gestartet werden"
        return 1
    fi
}

# PHP-Server stoppen
stop_server() {
    if [ -n "$SERVER_PID" ] && kill -0 $SERVER_PID 2>/dev/null; then
        log_info "Stoppe Server (PID: $SERVER_PID)..."
        kill $SERVER_PID 2>/dev/null || true
        wait $SERVER_PID 2>/dev/null || true
        log_success "Server gestoppt"
    fi
}

# Cleanup bei Abbruch
cleanup() {
    echo ""
    log_warning "Test abgebrochen, räume auf..."

    # Server stoppen
    stop_server

    # Wartungsmodus deaktivieren
    if [ -f "$MAINTENANCE_FILE" ]; then
        rm -f "$MAINTENANCE_FILE"
        log_info "Wartungsmodus deaktiviert"
    fi

    echo ""
    log_info "Cleanup abgeschlossen"
}

# Trap für SIGINT (Ctrl+C) und EXIT
trap cleanup EXIT INT TERM

# ===================================
# TESTS
# ===================================

# Test 1: Verzeichnisstruktur
test_directory_structure() {
    start_test "Verzeichnisstruktur prüfen"

    local errors=0

    # Erforderliche Verzeichnisse
    if [ ! -d "$SRC_DIR" ]; then
        log_error "src/ Verzeichnis nicht gefunden"
        errors=$((errors + 1))
    fi

    if [ ! -d "$SRC_DIR/core" ]; then
        log_error "src/core/ Verzeichnis nicht gefunden"
        errors=$((errors + 1))
    fi

    if [ ! -d "$SRC_DIR/admin" ]; then
        log_error "src/admin/ Verzeichnis nicht gefunden"
        errors=$((errors + 1))
    fi

    if [ ! -d "$SRC_DIR/api" ]; then
        log_error "src/api/ Verzeichnis nicht gefunden"
        errors=$((errors + 1))
    fi

    # Erforderliche Dateien
    if [ ! -f "$SRC_DIR/core/maintenance.php" ]; then
        log_error "src/core/maintenance.php nicht gefunden"
        errors=$((errors + 1))
    fi

    if [ ! -f "$SRC_DIR/api/health-check.php" ]; then
        log_error "src/api/health-check.php nicht gefunden"
        errors=$((errors + 1))
    fi

    if [ ! -f "$SRC_DIR/admin/maintenance.php" ]; then
        log_error "src/admin/maintenance.php nicht gefunden"
        errors=$((errors + 1))
    fi

    if [ ! -f "$PROJECT_DIR/backup.sh" ]; then
        log_error "backup.sh nicht gefunden"
        errors=$((errors + 1))
    fi

    if [ ! -f "$PROJECT_DIR/deploy.sh" ]; then
        log_error "deploy.sh nicht gefunden"
        errors=$((errors + 1))
    fi

    if [ $errors -eq 0 ]; then
        pass_test "Alle erforderlichen Dateien und Verzeichnisse vorhanden"
    else
        fail_test "$errors Datei(en) oder Verzeichnis(se) fehlen"
    fi
}

# Test 2: Dateiberechtigungen
test_permissions() {
    start_test "Dateiberechtigungen prüfen"

    local errors=0

    # Scripts müssen ausführbar sein
    if [ ! -x "$PROJECT_DIR/backup.sh" ]; then
        log_error "backup.sh ist nicht ausführbar"
        errors=$((errors + 1))
    fi

    if [ ! -x "$PROJECT_DIR/deploy.sh" ]; then
        log_error "deploy.sh ist nicht ausführbar"
        errors=$((errors + 1))
    fi

    # src/ muss beschreibbar sein (für MAINTENANCE)
    if [ ! -w "$SRC_DIR" ]; then
        log_error "src/ Verzeichnis ist nicht beschreibbar"
        errors=$((errors + 1))
    fi

    if [ $errors -eq 0 ]; then
        pass_test "Alle Dateiberechtigungen korrekt"
    else
        fail_test "$errors Berechtigungsproblem(e) gefunden"
    fi
}

# Test 3: Backup-Script Syntax
test_backup_syntax() {
    start_test "Backup-Script Syntax prüfen"

    if bash -n "$PROJECT_DIR/backup.sh" 2>/dev/null; then
        pass_test "Backup-Script Syntax OK"
    else
        fail_test "Backup-Script hat Syntax-Fehler"
    fi
}

# Test 4: Deployment-Script Syntax
test_deploy_syntax() {
    start_test "Deployment-Script Syntax prüfen"

    if bash -n "$PROJECT_DIR/deploy.sh" 2>/dev/null; then
        pass_test "Deployment-Script Syntax OK"
    else
        fail_test "Deployment-Script hat Syntax-Fehler"
    fi
}

# Test 5: Backup erstellen
test_backup_creation() {
    start_test "Backup-Erstellung testen"

    # Altes Test-Backup löschen falls vorhanden
    rm -f "$BACKUP_DIR"/backup_test_*.tar.gz 2>/dev/null || true

    # Backup erstellen (nur Dateien, schneller)
    if "$PROJECT_DIR/backup.sh" --files-only > /dev/null 2>&1; then
        # Prüfen ob Backup erstellt wurde
        local backup_count=$(find "$BACKUP_DIR" -name "backup_*.tar.gz" -mmin -1 | wc -l)

        if [ $backup_count -gt 0 ]; then
            local backup_file=$(find "$BACKUP_DIR" -name "backup_*.tar.gz" -mmin -1 | head -1)
            local backup_size=$(du -sh "$backup_file" | cut -f1)
            pass_test "Backup erstellt: $(basename "$backup_file") ($backup_size)"
        else
            fail_test "Backup-Script lief, aber keine Datei erstellt"
        fi
    else
        fail_test "Backup-Script fehlgeschlagen"
    fi
}

# Test 6: Server starten
test_server_start() {
    start_test "PHP-Server starten"

    if start_server; then
        pass_test "Server läuft auf $TEST_URL"
    else
        fail_test "Server konnte nicht gestartet werden"
        return 1
    fi
}

# Test 7: Normale Seite erreichbar
test_normal_page() {
    start_test "Normale Seite erreichbar (ohne Wartungsmodus)"

    # Sicherstellen dass Wartungsmodus aus ist
    rm -f "$MAINTENANCE_FILE" 2>/dev/null || true
    sleep 1

    local response=$(curl -s -o /dev/null -w "%{http_code}" "$TEST_URL" 2>/dev/null)

    if [ "$response" = "200" ]; then
        pass_test "Startseite antwortet mit HTTP 200"
    else
        fail_test "Startseite antwortet mit HTTP $response (erwartet: 200)"
    fi
}

# Test 8: Health-Check Endpoint
test_health_check() {
    start_test "Health-Check Endpoint testen"

    local response=$(curl -s "$TEST_URL/api/health-check" 2>/dev/null)

    # Prüfen ob JSON zurückkommt
    if echo "$response" | python3 -m json.tool > /dev/null 2>&1; then
        # Status prüfen
        local status=$(echo "$response" | python3 -c "import sys, json; print(json.load(sys.stdin).get('status', 'unknown'))" 2>/dev/null)

        if [ "$status" = "ok" ] || [ "$status" = "warning" ]; then
            pass_test "Health-Check Status: $status"
        else
            fail_test "Health-Check Status: $status (erwartet: ok oder warning)"
        fi
    else
        fail_test "Health-Check gibt kein gültiges JSON zurück"
    fi
}

# Test 9: Wartungsmodus aktivieren
test_maintenance_enable() {
    start_test "Wartungsmodus aktivieren"

    # MAINTENANCE Datei erstellen
    echo "Test-Wartungsmodus" > "$MAINTENANCE_FILE"
    echo "$(date '+%d.%m.%Y um %H:%M Uhr')" >> "$MAINTENANCE_FILE"

    sleep 1

    if [ -f "$MAINTENANCE_FILE" ]; then
        pass_test "MAINTENANCE Datei erstellt"
    else
        fail_test "MAINTENANCE Datei konnte nicht erstellt werden"
    fi
}

# Test 10: Wartungsseite anzeigen
test_maintenance_page() {
    start_test "Wartungsseite wird angezeigt"

    local response=$(curl -s -o /dev/null -w "%{http_code}" "$TEST_URL" 2>/dev/null)

    if [ "$response" = "503" ]; then
        pass_test "Wartungsseite antwortet mit HTTP 503"
    else
        fail_test "Wartungsseite antwortet mit HTTP $response (erwartet: 503)"
    fi
}

# Test 11: Wartungsseite Inhalt
test_maintenance_content() {
    start_test "Wartungsseite Inhalt prüfen"

    local content=$(curl -s "$TEST_URL" 2>/dev/null)

    local errors=0

    # Prüfen ob wichtige Elemente vorhanden sind
    if ! echo "$content" | grep -q "Wartungsarbeiten"; then
        log_error "Text 'Wartungsarbeiten' nicht gefunden"
        errors=$((errors + 1))
    fi

    if ! echo "$content" | grep -q "Test-Wartungsmodus"; then
        log_error "Custom Nachricht nicht gefunden"
        errors=$((errors + 1))
    fi

    if ! echo "$content" | grep -q "info@pc-wittfoot.de"; then
        log_error "Kontakt-Email nicht gefunden"
        errors=$((errors + 1))
    fi

    if [ $errors -eq 0 ]; then
        pass_test "Wartungsseite enthält alle wichtigen Elemente"
    else
        fail_test "Wartungsseite fehlen $errors Element(e)"
    fi
}

# Test 12: Health-Check mit Wartungsmodus
test_health_check_maintenance() {
    start_test "Health-Check erkennt Wartungsmodus"

    local response=$(curl -s "$TEST_URL/api/health-check" 2>/dev/null)

    local maintenance_enabled=$(echo "$response" | python3 -c "import sys, json; print(json.load(sys.stdin)['checks']['maintenance_mode']['enabled'])" 2>/dev/null)

    if [ "$maintenance_enabled" = "True" ]; then
        pass_test "Health-Check erkennt aktiven Wartungsmodus"
    else
        fail_test "Health-Check erkennt Wartungsmodus nicht (enabled: $maintenance_enabled)"
    fi
}

# Test 13: Wartungsmodus deaktivieren
test_maintenance_disable() {
    start_test "Wartungsmodus deaktivieren"

    rm -f "$MAINTENANCE_FILE"
    sleep 1

    if [ ! -f "$MAINTENANCE_FILE" ]; then
        pass_test "MAINTENANCE Datei gelöscht"
    else
        fail_test "MAINTENANCE Datei konnte nicht gelöscht werden"
    fi
}

# Test 14: Seite wieder normal
test_page_restored() {
    start_test "Seite wieder normal erreichbar"

    local response=$(curl -s -o /dev/null -w "%{http_code}" "$TEST_URL" 2>/dev/null)

    if [ "$response" = "200" ]; then
        pass_test "Startseite wieder erreichbar (HTTP 200)"
    else
        fail_test "Startseite antwortet mit HTTP $response (erwartet: 200)"
    fi
}

# Test 15: Admin-UI erreichbar
test_admin_ui() {
    start_test "Admin-UI für Wartungsmodus erreichbar"

    # Admin-UI ist geschützt, sollte zu Login-Seite umleiten
    local response=$(curl -s -o /dev/null -w "%{http_code}" -L "$TEST_URL/admin/maintenance" 2>/dev/null)

    # 200 (wenn bereits eingeloggt) oder 303 (Redirect zu Login) sind OK
    if [ "$response" = "200" ] || [ "$response" = "303" ] || [ "$response" = "302" ]; then
        pass_test "Admin-UI antwortet (HTTP $response)"
    else
        fail_test "Admin-UI antwortet mit HTTP $response (erwartet: 200, 302 oder 303)"
    fi
}

# Test 16: Router-Integration
test_router_integration() {
    start_test "Router-Integration prüfen"

    local errors=0

    # Prüfen ob maintenance.php im Router geladen wird
    if ! grep -q "require_once.*maintenance.php" "$SRC_DIR/router.php"; then
        log_error "Wartungsmodus nicht in router.php integriert"
        errors=$((errors + 1))
    fi

    # Prüfen ob health-check Route existiert
    if ! grep -q "health-check" "$SRC_DIR/router.php"; then
        log_error "Health-Check Route nicht in router.php"
        errors=$((errors + 1))
    fi

    # Prüfen ob Admin-Route existiert
    if ! grep -q "admin/maintenance" "$SRC_DIR/router.php" && ! grep -q "maintenance.*php" "$SRC_DIR/router.php"; then
        log_error "Admin-Wartungsmodus Route nicht in router.php"
        errors=$((errors + 1))
    fi

    if [ $errors -eq 0 ]; then
        pass_test "Alle Routen korrekt integriert"
    else
        fail_test "$errors Router-Integration(en) fehlen"
    fi
}

# Test 17: Header-Warnung
test_header_warning() {
    start_test "Admin-Warnung im Header prüfen"

    # Prüfen ob Header-Template die Warnung enthält
    if grep -q "MAINTENANCE_ADMIN_BYPASS" "$SRC_DIR/templates/header.php"; then
        pass_test "Admin-Warnung in header.php vorhanden"
    else
        fail_test "Admin-Warnung nicht in header.php gefunden"
    fi
}

# ===================================
# HAUPTPROGRAMM
# ===================================

echo ""
echo "================================================"
echo "  Deployment-System Test-Suite"
echo "  PC-Wittfoot UG"
echo "================================================"
echo ""

log_info "Starte Tests..."
echo ""

# Phase 1: Basis-Tests (ohne Server)
echo "=== Phase 1: Basis-Tests ==="
echo ""

test_directory_structure
test_permissions
test_backup_syntax
test_deploy_syntax
test_backup_creation
test_router_integration
test_header_warning

# Phase 2: Server-Tests
echo "=== Phase 2: Server-Tests ==="
echo ""

test_server_start

# Wenn Server nicht startet, weitere Tests überspringen
if [ -z "$SERVER_PID" ] || ! kill -0 $SERVER_PID 2>/dev/null; then
    log_error "Server läuft nicht, überspringe restliche Tests"
    TESTS_SKIPPED=$((TESTS_TOTAL - TESTS_PASSED - TESTS_FAILED))
else
    test_normal_page
    test_health_check

    # Phase 3: Wartungsmodus-Tests
    echo "=== Phase 3: Wartungsmodus-Tests ==="
    echo ""

    test_maintenance_enable
    test_maintenance_page
    test_maintenance_content
    test_health_check_maintenance
    test_maintenance_disable
    test_page_restored
    test_admin_ui
fi

# Server stoppen
stop_server

# ===================================
# ZUSAMMENFASSUNG
# ===================================

echo ""
echo "================================================"
echo "  Test-Zusammenfassung"
echo "================================================"
echo ""

echo -e "${CYAN}Gesamt:${NC}       $TESTS_TOTAL Tests"
echo -e "${GREEN}Erfolgreich:${NC}  $TESTS_PASSED Tests"
echo -e "${RED}Fehlgeschlagen:${NC} $TESTS_FAILED Tests"
echo -e "${YELLOW}Übersprungen:${NC} $TESTS_SKIPPED Tests"
echo ""

# Erfolgsrate berechnen
if [ $TESTS_TOTAL -gt 0 ]; then
    SUCCESS_RATE=$((TESTS_PASSED * 100 / TESTS_TOTAL))
    echo -e "Erfolgsrate: ${SUCCESS_RATE}%"
    echo ""
fi

# Exit-Code
if [ $TESTS_FAILED -eq 0 ]; then
    echo -e "${GREEN}✓ Alle Tests bestanden!${NC}"
    echo ""
    exit 0
else
    echo -e "${RED}✗ $TESTS_FAILED Test(s) fehlgeschlagen!${NC}"
    echo ""
    exit 1
fi
