#!/bin/bash
#
# Server-Management Script
# PC-Wittfoot UG
#
# Vereinfacht das Starten/Stoppen des PHP-Entwicklungsservers
#

# ===================================
# KONFIGURATION
# ===================================

PROJECT_DIR="$(cd "$(dirname "$0")" && pwd)"
SRC_DIR="$PROJECT_DIR/src"
SERVER_HOST="localhost"
SERVER_PORT="8000"
SERVER_URL="http://$SERVER_HOST:$SERVER_PORT"
PID_FILE="$PROJECT_DIR/.server.pid"

# Farben
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m'

# ===================================
# FUNKTIONEN
# ===================================

log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[✓]${NC} $1"
}

log_error() {
    echo -e "${RED}[✗]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[!]${NC} $1"
}

# Server-Status prüfen
get_server_pid() {
    # Suche nach PHP-Server auf dem Port
    lsof -ti:$SERVER_PORT 2>/dev/null | head -1
}

is_server_running() {
    local pid=$(get_server_pid)
    [ -n "$pid" ] && kill -0 "$pid" 2>/dev/null
    return $?
}

# Server-Informationen anzeigen
show_status() {
    echo ""
    echo -e "${CYAN}═══════════════════════════════════════${NC}"
    echo -e "${CYAN}  Server-Status${NC}"
    echo -e "${CYAN}═══════════════════════════════════════${NC}"
    echo ""

    if is_server_running; then
        local pid=$(get_server_pid)
        log_success "Server läuft"
        echo ""
        echo "  PID:  $pid"
        echo "  URL:  $SERVER_URL"
        echo "  Port: $SERVER_PORT"
        echo ""

        # HTTP-Status prüfen
        local http_status=$(curl -s -o /dev/null -w "%{http_code}" "$SERVER_URL" 2>/dev/null)
        if [ "$http_status" = "200" ] || [ "$http_status" = "503" ]; then
            log_success "HTTP erreichbar (Status: $http_status)"
        else
            log_warning "HTTP Status: $http_status"
        fi
    else
        log_error "Server läuft nicht"
        echo ""
        echo "  Starten mit: $0 start"
    fi

    echo ""
}

# Server starten
start_server() {
    echo ""
    echo -e "${CYAN}═══════════════════════════════════════${NC}"
    echo -e "${CYAN}  Server starten${NC}"
    echo -e "${CYAN}═══════════════════════════════════════${NC}"
    echo ""

    # Prüfen ob Server bereits läuft
    if is_server_running; then
        local pid=$(get_server_pid)
        log_warning "Server läuft bereits (PID: $pid)"
        echo ""
        echo "  URL: $SERVER_URL"
        echo ""
        echo "  Stoppen mit: $0 stop"
        echo "  Neu starten mit: $0 restart"
        echo ""
        return 1
    fi

    # Prüfen ob src/ Verzeichnis existiert
    if [ ! -d "$SRC_DIR" ]; then
        log_error "src/ Verzeichnis nicht gefunden: $SRC_DIR"
        echo ""
        return 1
    fi

    # Prüfen ob server.php existiert
    if [ ! -f "$SRC_DIR/server.php" ]; then
        log_error "server.php nicht gefunden: $SRC_DIR/server.php"
        echo ""
        return 1
    fi

    # Server starten
    log_info "Starte PHP-Server auf $SERVER_URL..."

    cd "$SRC_DIR"
    php -S $SERVER_HOST:$SERVER_PORT server.php > /dev/null 2>&1 &
    local pid=$!

    # Warten bis Server bereit
    sleep 2

    # Prüfen ob Server läuft
    if kill -0 $pid 2>/dev/null; then
        # PID speichern
        echo $pid > "$PID_FILE"

        log_success "Server gestartet (PID: $pid)"
        echo ""
        echo "  URL:  $SERVER_URL"
        echo "  Logs: $PROJECT_DIR/logs/"
        echo ""

        # HTTP-Test
        local http_status=$(curl -s -o /dev/null -w "%{http_code}" "$SERVER_URL" 2>/dev/null)
        if [ "$http_status" = "200" ] || [ "$http_status" = "503" ]; then
            log_success "Server antwortet (HTTP $http_status)"
        else
            log_warning "Server antwortet mit HTTP $http_status"
        fi

        echo ""
        log_info "Stoppen mit: $0 stop"
        echo ""

        return 0
    else
        log_error "Server konnte nicht gestartet werden"
        echo ""
        echo "  Prüfe:"
        echo "  - Ist Port $SERVER_PORT bereits belegt? (lsof -i:$SERVER_PORT)"
        echo "  - PHP installiert? (php --version)"
        echo "  - Logs: tail -f logs/error.log"
        echo ""
        return 1
    fi
}

# Server stoppen
stop_server() {
    echo ""
    echo -e "${CYAN}═══════════════════════════════════════${NC}"
    echo -e "${CYAN}  Server stoppen${NC}"
    echo -e "${CYAN}═══════════════════════════════════════${NC}"
    echo ""

    if ! is_server_running; then
        log_warning "Server läuft nicht"
        echo ""
        return 1
    fi

    local pid=$(get_server_pid)
    log_info "Stoppe Server (PID: $pid)..."

    # Server stoppen
    kill $pid 2>/dev/null

    # Warten auf Beendigung
    local count=0
    while kill -0 $pid 2>/dev/null && [ $count -lt 10 ]; do
        sleep 0.5
        count=$((count + 1))
    done

    # Force kill falls nötig
    if kill -0 $pid 2>/dev/null; then
        log_warning "Server reagiert nicht, erzwinge Beendigung..."
        kill -9 $pid 2>/dev/null
    fi

    # PID-Datei löschen
    rm -f "$PID_FILE"

    log_success "Server gestoppt"
    echo ""
}

# Server neu starten
restart_server() {
    echo ""
    echo -e "${CYAN}═══════════════════════════════════════${NC}"
    echo -e "${CYAN}  Server neu starten${NC}"
    echo -e "${CYAN}═══════════════════════════════════════${NC}"
    echo ""

    if is_server_running; then
        stop_server
        sleep 1
    fi

    start_server
}

# Server-Logs anzeigen
show_logs() {
    echo ""
    echo -e "${CYAN}═══════════════════════════════════════${NC}"
    echo -e "${CYAN}  Server-Logs (Letzte 20 Zeilen)${NC}"
    echo -e "${CYAN}═══════════════════════════════════════${NC}"
    echo ""

    if [ -f "$PROJECT_DIR/logs/error.log" ]; then
        echo -e "${BLUE}Error-Log:${NC}"
        tail -20 "$PROJECT_DIR/logs/error.log"
        echo ""
    else
        log_warning "Keine Error-Logs gefunden"
        echo ""
    fi

    if [ -f "$PROJECT_DIR/logs/server.log" ]; then
        echo -e "${BLUE}Server-Log:${NC}"
        tail -20 "$PROJECT_DIR/logs/server.log"
        echo ""
    fi
}

# Hilfe anzeigen
show_help() {
    echo ""
    echo -e "${CYAN}═══════════════════════════════════════${NC}"
    echo -e "${CYAN}  Server-Management Script${NC}"
    echo -e "${CYAN}  PC-Wittfoot UG${NC}"
    echo -e "${CYAN}═══════════════════════════════════════${NC}"
    echo ""
    echo "Verwendung: $0 [BEFEHL]"
    echo ""
    echo "Befehle:"
    echo "  start      Server starten"
    echo "  stop       Server stoppen"
    echo "  restart    Server neu starten"
    echo "  status     Server-Status anzeigen"
    echo "  logs       Server-Logs anzeigen"
    echo "  help       Diese Hilfe anzeigen"
    echo ""
    echo "Beispiele:"
    echo "  $0 start           # Server starten"
    echo "  $0 stop            # Server stoppen"
    echo "  $0 restart         # Server neu starten"
    echo "  $0 status          # Status prüfen"
    echo ""
    echo "Konfiguration:"
    echo "  URL:  $SERVER_URL"
    echo "  Port: $SERVER_PORT"
    echo "  Dir:  $SRC_DIR"
    echo ""
}

# ===================================
# HAUPTPROGRAMM
# ===================================

# Befehl aus Argument
COMMAND="${1:-help}"

case "$COMMAND" in
    start)
        start_server
        exit $?
        ;;
    stop)
        stop_server
        exit $?
        ;;
    restart)
        restart_server
        exit $?
        ;;
    status)
        show_status
        exit 0
        ;;
    logs)
        show_logs
        exit 0
        ;;
    help|--help|-h)
        show_help
        exit 0
        ;;
    *)
        echo ""
        log_error "Unbekannter Befehl: $COMMAND"
        show_help
        exit 1
        ;;
esac
