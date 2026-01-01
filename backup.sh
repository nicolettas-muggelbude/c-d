#!/bin/bash
#
# Backup-Script für PC-Wittfoot UG
# Erstellt Backups von Dateien und Datenbank
#
# VERWENDUNG:
#   ./backup.sh                 - Vollständiges Backup
#   ./backup.sh --files-only    - Nur Dateien sichern
#   ./backup.sh --db-only       - Nur Datenbank sichern
#

set -e  # Bei Fehler abbrechen

# ===================================
# KONFIGURATION
# ===================================

# Datenbank-Zugangsdaten (ANPASSEN!)
DB_HOST="localhost"
DB_USER="pc_wittfoot"
DB_PASS="dev123"
DB_NAME="pc_wittfoot"

# Lokale Pfade
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
SRC_DIR="$SCRIPT_DIR/src"
BACKUP_DIR="$SCRIPT_DIR/backups"

# Backup-Aufbewahrung (in Tagen)
BACKUP_RETENTION_DAYS=30

# FTP-Upload für Remote-Backups (optional, leer lassen für nur lokale Backups)
REMOTE_BACKUP_ENABLED=false
REMOTE_FTP_HOST=""
REMOTE_FTP_USER=""
REMOTE_FTP_PASS=""
REMOTE_FTP_DIR="/backups"

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
    echo -e "${BLUE}[INFO]${NC} $1" >&2
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1" >&2
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1" >&2
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1" >&2
}

# Verzeichnisse erstellen
prepare_directories() {
    mkdir -p "$BACKUP_DIR"
    mkdir -p "$BACKUP_DIR/temp"
}

# Dateien sichern
backup_files() {
    log_info "Sichere Dateien..."

    local TEMP_DIR="$BACKUP_DIR/temp/files"
    mkdir -p "$TEMP_DIR"

    # Wichtige Dateien kopieren
    cp -r "$SRC_DIR" "$TEMP_DIR/"

    # .env Datei sichern (falls vorhanden)
    if [ -f "$SCRIPT_DIR/.env" ]; then
        cp "$SCRIPT_DIR/.env" "$TEMP_DIR/"
    fi

    # composer.json und composer.lock sichern
    if [ -f "$SCRIPT_DIR/composer.json" ]; then
        cp "$SCRIPT_DIR/composer.json" "$TEMP_DIR/"
    fi
    if [ -f "$SCRIPT_DIR/composer.lock" ]; then
        cp "$SCRIPT_DIR/composer.lock" "$TEMP_DIR/"
    fi

    # .htaccess sichern (falls vorhanden)
    if [ -f "$SCRIPT_DIR/.htaccess" ]; then
        cp "$SCRIPT_DIR/.htaccess" "$TEMP_DIR/"
    fi

    # Backup-Info erstellen
    cat > "$TEMP_DIR/backup_info.txt" <<EOF
Backup-Informationen
====================

Erstellt am: $(date '+%d.%m.%Y um %H:%M:%S Uhr')
Hostname: $(hostname)
Betriebssystem: $(uname -s)
PHP-Version: $(php -v | head -n 1)

Gesicherte Verzeichnisse:
- src/
$([ -f "$SCRIPT_DIR/.env" ] && echo "- .env")
$([ -f "$SCRIPT_DIR/composer.json" ] && echo "- composer.json")
$([ -f "$SCRIPT_DIR/composer.lock" ] && echo "- composer.lock")
$([ -f "$SCRIPT_DIR/.htaccess" ] && echo "- .htaccess")

Größe: $(du -sh "$TEMP_DIR" | cut -f1)
EOF

    log_success "Dateien gesichert"
}

# Datenbank sichern
backup_database() {
    log_info "Sichere Datenbank..."

    local TEMP_DIR="$BACKUP_DIR/temp/database"
    mkdir -p "$TEMP_DIR"

    # Prüfen ob mysqldump verfügbar ist
    if ! command -v mysqldump &> /dev/null; then
        log_warning "mysqldump nicht verfügbar, überspringe Datenbank-Backup"
        return 1
    fi

    # Datenbank-Dump erstellen
    local DUMP_FILE="$TEMP_DIR/${DB_NAME}_$(date +%Y%m%d_%H%M%S).sql"

    if mysqldump -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$DUMP_FILE" 2>/dev/null; then
        log_success "Datenbank gesichert ($(du -sh "$DUMP_FILE" | cut -f1))"

        # Datenbank-Info erstellen
        cat > "$TEMP_DIR/database_info.txt" <<EOF
Datenbank-Backup
================

Erstellt am: $(date '+%d.%m.%Y um %H:%M:%S Uhr')
Datenbank: $DB_NAME
Host: $DB_HOST
User: $DB_USER

Tabellen:
$(mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "SHOW TABLES;" 2>/dev/null | tail -n +2 | sed 's/^/- /')

Größe: $(du -sh "$DUMP_FILE" | cut -f1)
EOF

        return 0
    else
        log_error "Datenbank-Backup fehlgeschlagen"
        return 1
    fi
}

# Backup komprimieren
compress_backup() {
    log_info "Komprimiere Backup..."

    local BACKUP_NAME="backup_$(date +%Y%m%d_%H%M%S).tar.gz"
    local BACKUP_FILE="$BACKUP_DIR/$BACKUP_NAME"

    # Ins temp-Verzeichnis wechseln und komprimieren
    cd "$BACKUP_DIR/temp"
    tar -czf "$BACKUP_FILE" . 2>/dev/null

    # Temp-Verzeichnis aufräumen
    rm -rf "$BACKUP_DIR/temp"

    # Größe und Pfad ausgeben
    local SIZE=$(du -sh "$BACKUP_FILE" | cut -f1)
    log_success "Backup erstellt: $BACKUP_NAME ($SIZE)"

    echo "$BACKUP_FILE"
}

# Remote-Backup hochladen (optional)
upload_remote_backup() {
    local BACKUP_FILE="$1"

    if [ "$REMOTE_BACKUP_ENABLED" != "true" ]; then
        return 0
    fi

    log_info "Lade Backup auf Remote-Server hoch..."

    if ! command -v lftp &> /dev/null; then
        log_warning "lftp nicht installiert, überspringe Remote-Upload"
        return 1
    fi

    if [ -z "$REMOTE_FTP_HOST" ] || [ -z "$REMOTE_FTP_USER" ]; then
        log_warning "Remote-FTP nicht konfiguriert, überspringe Upload"
        return 1
    fi

    # Per FTP hochladen
    if lftp -u "$REMOTE_FTP_USER","$REMOTE_FTP_PASS" "$REMOTE_FTP_HOST" <<EOF
set ftp:ssl-allow no
cd $REMOTE_FTP_DIR
put "$BACKUP_FILE"
bye
EOF
    then
        log_success "Backup auf Remote-Server hochgeladen"
        return 0
    else
        log_error "Remote-Upload fehlgeschlagen"
        return 1
    fi
}

# Alte Backups löschen
cleanup_old_backups() {
    log_info "Lösche alte Backups (älter als $BACKUP_RETENTION_DAYS Tage)..."

    local DELETED_COUNT=0

    # Lokale Backups löschen
    while IFS= read -r file; do
        if [ -f "$file" ]; then
            rm -f "$file"
            DELETED_COUNT=$((DELETED_COUNT + 1))
            log_info "Gelöscht: $(basename "$file")"
        fi
    done < <(find "$BACKUP_DIR" -name "backup_*.tar.gz" -mtime +$BACKUP_RETENTION_DAYS)

    if [ $DELETED_COUNT -eq 0 ]; then
        log_info "Keine alten Backups gefunden"
    else
        log_success "$DELETED_COUNT alte Backup(s) gelöscht"
    fi
}

# Backup-Übersicht anzeigen
show_backup_list() {
    log_info "Verfügbare Backups:"
    echo ""

    if [ ! -d "$BACKUP_DIR" ] || [ -z "$(ls -A "$BACKUP_DIR"/*.tar.gz 2>/dev/null)" ]; then
        echo "  Keine Backups vorhanden"
        echo ""
        return
    fi

    # Liste aller Backups mit Größe und Datum
    find "$BACKUP_DIR" -name "backup_*.tar.gz" -printf "%T@ %p\n" | sort -rn | while read -r timestamp file; do
        local DATE=$(date -d "@${timestamp}" "+%d.%m.%Y %H:%M:%S")
        local SIZE=$(du -sh "$file" | cut -f1)
        local NAME=$(basename "$file")
        echo "  $NAME"
        echo "    Datum: $DATE"
        echo "    Größe: $SIZE"
        echo ""
    done
}

# ===================================
# HAUPTPROGRAMM
# ===================================

echo ""
echo "================================================"
echo "  PC-Wittfoot UG - Backup-Script"
echo "================================================"
echo ""

# Parameter verarbeiten
BACKUP_FILES=true
BACKUP_DB=true

while [[ $# -gt 0 ]]; do
    case $1 in
        --files-only)
            BACKUP_DB=false
            shift
            ;;
        --db-only)
            BACKUP_FILES=false
            shift
            ;;
        --list)
            show_backup_list
            exit 0
            ;;
        --help)
            echo "Verwendung:"
            echo "  $0                 - Vollständiges Backup"
            echo "  $0 --files-only    - Nur Dateien sichern"
            echo "  $0 --db-only       - Nur Datenbank sichern"
            echo "  $0 --list          - Verfügbare Backups anzeigen"
            echo "  $0 --help          - Diese Hilfe anzeigen"
            echo ""
            exit 0
            ;;
        *)
            log_error "Unbekannte Option: $1"
            echo "Verwenden Sie --help für Hilfe"
            exit 1
            ;;
    esac
done

# Verzeichnisse vorbereiten
prepare_directories

# Dateien sichern
if [ "$BACKUP_FILES" = true ]; then
    backup_files
fi

# Datenbank sichern
if [ "$BACKUP_DB" = true ]; then
    backup_database || log_warning "Datenbank-Backup übersprungen"
fi

# Backup komprimieren
BACKUP_FILE=$(compress_backup)

# Remote-Upload (optional)
if [ "$REMOTE_BACKUP_ENABLED" = true ]; then
    upload_remote_backup "$BACKUP_FILE" || log_warning "Remote-Upload fehlgeschlagen"
fi

# Alte Backups aufräumen
cleanup_old_backups

# Zusammenfassung
echo ""
log_success "===== Backup abgeschlossen! ====="
echo ""
echo "Backup-Datei: $BACKUP_FILE"
echo "Größe: $(du -sh "$BACKUP_FILE" | cut -f1)"
echo "Backup-Verzeichnis: $BACKUP_DIR"
echo ""

# Backup-Liste anzeigen
show_backup_list

exit 0
