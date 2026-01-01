#!/bin/bash
#
# Deployment-Script für PC-Wittfoot UG
# Automatisiert den Deployment-Prozess
#
# WICHTIG: Vor dem ersten Einsatz FTP-Zugangsdaten konfigurieren!
#

set -e  # Bei Fehler abbrechen

# ===================================
# KONFIGURATION
# ===================================

# FTP-Zugangsdaten (ANPASSEN!)
FTP_HOST="ftp.example.com"
FTP_USER="username"
FTP_PASS="password"
FTP_REMOTE_DIR="/public_html"

# Lokale Pfade
LOCAL_DIR="$(cd "$(dirname "$0")" && pwd)"
SRC_DIR="$LOCAL_DIR/src"
BACKUP_DIR="$LOCAL_DIR/backups"

# URL zur Website
SITE_URL="https://pc-wittfoot.de"
HEALTH_CHECK_URL="$SITE_URL/api/health-check"

# Farben für Ausgabe
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# ===================================
# FUNKTIONEN
# ===================================

log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Prüfen ob FTP-Verbindung möglich ist
check_ftp_connection() {
    log_info "Prüfe FTP-Verbindung..."

    if ! command -v lftp &> /dev/null; then
        log_error "lftp ist nicht installiert. Bitte installieren: sudo apt-get install lftp"
        exit 1
    fi

    if lftp -u "$FTP_USER","$FTP_PASS" "$FTP_HOST" -e "ls; bye" &> /dev/null; then
        log_success "FTP-Verbindung erfolgreich"
        return 0
    else
        log_error "FTP-Verbindung fehlgeschlagen. Bitte Zugangsdaten prüfen."
        exit 1
    fi
}

# Wartungsmodus aktivieren
enable_maintenance() {
    log_info "Aktiviere Wartungsmodus..."

    # Lokale Wartungsdatei erstellen
    echo "Wir führen gerade ein Update durch." > "$SRC_DIR/MAINTENANCE"
    echo "$(date '+%d.%m.%Y um %H:%M Uhr')" >> "$SRC_DIR/MAINTENANCE"

    # Per FTP hochladen
    lftp -u "$FTP_USER","$FTP_PASS" "$FTP_HOST" <<EOF
cd $FTP_REMOTE_DIR/src
put "$SRC_DIR/MAINTENANCE"
bye
EOF

    log_success "Wartungsmodus aktiviert"
    sleep 2
}

# Wartungsmodus deaktivieren
disable_maintenance() {
    log_info "Deaktiviere Wartungsmodus..."

    # Per FTP löschen
    lftp -u "$FTP_USER","$FTP_PASS" "$FTP_HOST" <<EOF
cd $FTP_REMOTE_DIR/src
rm -f MAINTENANCE
bye
EOF

    # Lokale Datei löschen
    rm -f "$SRC_DIR/MAINTENANCE"

    log_success "Wartungsmodus deaktiviert"
}

# Backup erstellen
create_backup() {
    log_info "Erstelle Backup..."

    # Backup-Verzeichnis erstellen
    mkdir -p "$BACKUP_DIR"

    # Backup-Name mit Zeitstempel
    BACKUP_NAME="backup_$(date +%Y%m%d_%H%M%S)"
    BACKUP_PATH="$BACKUP_DIR/$BACKUP_NAME"

    # Lokale Dateien sichern
    log_info "Sichere lokale Dateien..."
    mkdir -p "$BACKUP_PATH/files"
    cp -r "$SRC_DIR" "$BACKUP_PATH/files/"

    # Datenbank-Backup (falls lokal)
    if command -v mysqldump &> /dev/null; then
        log_info "Erstelle Datenbank-Backup..."
        mkdir -p "$BACKUP_PATH/database"

        # Diese Werte anpassen!
        DB_HOST="localhost"
        DB_USER="pc_wittfoot"
        DB_PASS="dev123"
        DB_NAME="pc_wittfoot"

        mysqldump -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$BACKUP_PATH/database/dump.sql" 2>/dev/null || true

        if [ -f "$BACKUP_PATH/database/dump.sql" ]; then
            log_success "Datenbank-Backup erstellt"
        fi
    fi

    # Backup komprimieren
    log_info "Komprimiere Backup..."
    cd "$BACKUP_DIR"
    tar -czf "$BACKUP_NAME.tar.gz" "$BACKUP_NAME"
    rm -rf "$BACKUP_NAME"

    log_success "Backup erstellt: $BACKUP_DIR/$BACKUP_NAME.tar.gz"

    # Alte Backups löschen (älter als 30 Tage)
    log_info "Lösche alte Backups (>30 Tage)..."
    find "$BACKUP_DIR" -name "backup_*.tar.gz" -mtime +30 -delete
}

# Dateien per FTP hochladen
deploy_files() {
    log_info "Lade Dateien per FTP hoch..."

    lftp -u "$FTP_USER","$FTP_PASS" "$FTP_HOST" <<EOF
set ftp:ssl-allow no
set mirror:use-pget-n 5
cd $FTP_REMOTE_DIR
mirror --reverse --delete --verbose --exclude-glob .git/ --exclude-glob node_modules/ --exclude-glob .env --exclude-glob MAINTENANCE "$SRC_DIR" src
bye
EOF

    log_success "Dateien hochgeladen"
}

# Health-Check durchführen
run_health_check() {
    log_info "Führe Health-Check durch..."

    if ! command -v curl &> /dev/null; then
        log_warning "curl nicht installiert, überspringe Health-Check"
        return 0
    fi

    # 3 Versuche
    for i in {1..3}; do
        RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "$HEALTH_CHECK_URL" || echo "000")

        if [ "$RESPONSE" = "200" ]; then
            log_success "Health-Check erfolgreich (HTTP $RESPONSE)"

            # Status-Details anzeigen
            curl -s "$HEALTH_CHECK_URL" | python3 -m json.tool 2>/dev/null || curl -s "$HEALTH_CHECK_URL"
            return 0
        else
            log_warning "Health-Check fehlgeschlagen (HTTP $RESPONSE), Versuch $i von 3..."
            sleep 2
        fi
    done

    log_error "Health-Check nach 3 Versuchen fehlgeschlagen!"
    return 1
}

# ===================================
# HAUPTPROGRAMM
# ===================================

echo ""
echo "================================================"
echo "  PC-Wittfoot UG - Deployment-Script"
echo "================================================"
echo ""

# Prüfen ob wir im richtigen Verzeichnis sind
if [ ! -d "$SRC_DIR" ]; then
    log_error "src/ Verzeichnis nicht gefunden. Bitte im Projekt-Root ausführen!"
    exit 1
fi

# Bestätigung einholen
echo -e "${YELLOW}ACHTUNG:${NC} Deployment nach $FTP_HOST wird gestartet"
echo ""
read -p "Fortfahren? (j/n): " -n 1 -r
echo ""

if [[ ! $REPLY =~ ^[Jj]$ ]]; then
    log_warning "Deployment abgebrochen"
    exit 0
fi

echo ""
log_info "Starte Deployment..."
echo ""

# Schritt 1: FTP-Verbindung prüfen
check_ftp_connection

# Schritt 2: Backup erstellen
create_backup

# Schritt 3: Wartungsmodus aktivieren
enable_maintenance

# Schritt 4: Dateien hochladen
deploy_files

# Schritt 5: Health-Check durchführen
if run_health_check; then
    log_success "Deployment erfolgreich abgeschlossen!"
else
    log_error "Health-Check fehlgeschlagen. Wartungsmodus bleibt aktiv!"
    echo ""
    echo "Bitte prüfen Sie:"
    echo "  1. Logs auf dem Server überprüfen"
    echo "  2. Datenbank-Status prüfen"
    echo "  3. Manuell Health-Check aufrufen: $HEALTH_CHECK_URL"
    echo ""
    read -p "Wartungsmodus trotzdem deaktivieren? (j/n): " -n 1 -r
    echo ""

    if [[ ! $REPLY =~ ^[Jj]$ ]]; then
        log_warning "Wartungsmodus bleibt aktiv. Manuell deaktivieren wenn bereit."
        exit 1
    fi
fi

# Schritt 6: Wartungsmodus deaktivieren
disable_maintenance

echo ""
log_success "===== Deployment abgeschlossen! ====="
echo ""
echo "Website ist wieder online: $SITE_URL"
echo "Health-Check: $HEALTH_CHECK_URL"
echo "Backup: $BACKUP_DIR/backup_*.tar.gz"
echo ""
