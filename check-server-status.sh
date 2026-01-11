#!/bin/bash
#
# Server-Status-Prüfung für Production-Server
# Führe dieses Script auf dem Production-Server aus
#

echo "================================================"
echo "  Production Server Status-Check"
echo "  PC-Wittfoot UG"
echo "================================================"
echo ""

# Farben
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Zähler
PASSED=0
FAILED=0

check_item() {
    if [ $1 -eq 0 ]; then
        echo -e "${GREEN}[✓]${NC} $2"
        ((PASSED++))
    else
        echo -e "${RED}[✗]${NC} $2"
        ((FAILED++))
    fi
}

check_warning() {
    echo -e "${YELLOW}[!]${NC} $1"
}

echo "=== 1. Composer Dependencies ==="
echo ""

# Prüfe ob vendor/ Verzeichnis existiert
if [ -d "vendor" ]; then
    check_item 0 "vendor/ Verzeichnis existiert"

    # Prüfe ob autoload.php existiert
    if [ -f "vendor/autoload.php" ]; then
        check_item 0 "vendor/autoload.php vorhanden"
    else
        check_item 1 "vendor/autoload.php fehlt"
    fi

    # Prüfe ob composer.lock existiert
    if [ -f "composer.lock" ]; then
        check_item 0 "composer.lock vorhanden"

        # Zeige Anzahl installierter Packages
        PACKAGE_COUNT=$(cat composer.lock | grep -c '"name":' || echo "0")
        echo "   → $PACKAGE_COUNT Packages installiert"
    else
        check_item 1 "composer.lock fehlt"
    fi
else
    check_item 1 "vendor/ Verzeichnis fehlt - Composer Dependencies NICHT installiert!"
fi

echo ""
echo "=== 2. Berechtigungen ==="
echo ""

# Prüfe Basis-Berechtigungen
CURRENT_DIR_PERMS=$(stat -c "%a" . 2>/dev/null || stat -f "%OLp" . 2>/dev/null)
echo "   Aktuelles Verzeichnis: $CURRENT_DIR_PERMS"

# Prüfe logs/ Verzeichnis
if [ -d "logs" ]; then
    LOGS_PERMS=$(stat -c "%a" logs 2>/dev/null || stat -f "%OLp" logs 2>/dev/null)
    if [ "$LOGS_PERMS" = "777" ] || [ "$LOGS_PERMS" = "775" ]; then
        check_item 0 "logs/ beschreibbar ($LOGS_PERMS)"
    else
        check_warning "logs/ Berechtigung: $LOGS_PERMS (sollte 777 oder 775 sein)"
    fi

    # Test ob logs/ beschreibbar ist
    TEST_FILE="logs/.write-test-$$"
    if touch "$TEST_FILE" 2>/dev/null; then
        rm -f "$TEST_FILE"
        check_item 0 "logs/ Schreibtest erfolgreich"
    else
        check_item 1 "logs/ NICHT beschreibbar!"
    fi
else
    check_item 1 "logs/ Verzeichnis fehlt"
fi

# Prüfe uploads/ Verzeichnis
if [ -d "uploads" ]; then
    UPLOADS_PERMS=$(stat -c "%a" uploads 2>/dev/null || stat -f "%OLp" uploads 2>/dev/null)
    if [ "$UPLOADS_PERMS" = "777" ] || [ "$UPLOADS_PERMS" = "775" ]; then
        check_item 0 "uploads/ beschreibbar ($UPLOADS_PERMS)"
    else
        check_warning "uploads/ Berechtigung: $UPLOADS_PERMS (sollte 777 oder 775 sein)"
    fi

    # Test ob uploads/ beschreibbar ist
    TEST_FILE="uploads/.write-test-$$"
    if touch "$TEST_FILE" 2>/dev/null; then
        rm -f "$TEST_FILE"
        check_item 0 "uploads/ Schreibtest erfolgreich"
    else
        check_item 1 "uploads/ NICHT beschreibbar!"
    fi
else
    check_item 1 "uploads/ Verzeichnis fehlt"
fi

# Prüfe src/ Verzeichnis
if [ -d "src" ]; then
    SRC_PERMS=$(stat -c "%a" src 2>/dev/null || stat -f "%OLp" src 2>/dev/null)
    check_item 0 "src/ Verzeichnis vorhanden ($SRC_PERMS)"
else
    check_item 1 "src/ Verzeichnis fehlt"
fi

# Prüfe config.php
if [ -f "src/core/config.php" ]; then
    CONFIG_PERMS=$(stat -c "%a" src/core/config.php 2>/dev/null || stat -f "%OLp" src/core/config.php 2>/dev/null)
    if [ "$CONFIG_PERMS" = "644" ] || [ "$CONFIG_PERMS" = "640" ]; then
        check_item 0 "config.php Berechtigung: $CONFIG_PERMS (sicher)"
    else
        check_warning "config.php Berechtigung: $CONFIG_PERMS (sollte 644 oder 640 sein)"
    fi
else
    check_item 1 "src/core/config.php fehlt!"
fi

echo ""
echo "=== 3. Git Status ==="
echo ""

# Prüfe ob Git-Repository
if [ -d ".git" ]; then
    check_item 0 "Git-Repository initialisiert"

    # Aktueller Branch
    CURRENT_BRANCH=$(git branch --show-current 2>/dev/null)
    if [ "$CURRENT_BRANCH" = "production" ]; then
        check_item 0 "Branch: production (korrekt)"
    else
        check_warning "Branch: $CURRENT_BRANCH (sollte 'production' sein)"
    fi

    # Git Status
    GIT_STATUS=$(git status --porcelain 2>/dev/null | wc -l)
    if [ "$GIT_STATUS" -eq 0 ]; then
        check_item 0 "Git Working Tree clean"
    else
        check_warning "$GIT_STATUS Dateien geändert (uncommitted)"
    fi

    # Letzte Commits
    echo ""
    echo "   Letzte 3 Commits:"
    git log --oneline -3 | sed 's/^/   → /'
else
    check_item 1 "Kein Git-Repository"
fi

echo ""
echo "=== 4. PHP & Software ==="
echo ""

# PHP Version
PHP_VERSION=$(php -v 2>/dev/null | head -1 | cut -d' ' -f2)
if [ ! -z "$PHP_VERSION" ]; then
    check_item 0 "PHP verfügbar: $PHP_VERSION"
else
    check_item 1 "PHP nicht verfügbar"
fi

# Composer
COMPOSER_VERSION=$(composer --version 2>/dev/null | cut -d' ' -f3)
if [ ! -z "$COMPOSER_VERSION" ]; then
    check_item 0 "Composer verfügbar: $COMPOSER_VERSION"
else
    check_item 1 "Composer nicht verfügbar"
fi

# Git
GIT_VERSION=$(git --version 2>/dev/null | cut -d' ' -f3)
if [ ! -z "$GIT_VERSION" ]; then
    check_item 0 "Git verfügbar: $GIT_VERSION"
else
    check_item 1 "Git nicht verfügbar"
fi

echo ""
echo "=== 5. Wichtige Dateien ==="
echo ""

# Prüfe wichtige Dateien
[ -f "composer.json" ] && check_item 0 "composer.json vorhanden" || check_item 1 "composer.json fehlt"
[ -f "src/router.php" ] && check_item 0 "router.php vorhanden" || check_item 1 "router.php fehlt"
[ -f "src/core/config.php" ] && check_item 0 "config.php vorhanden" || check_item 1 "config.php fehlt"
[ -f "src/core/Database.php" ] && check_item 0 "Database.php vorhanden" || check_item 1 "Database.php fehlt"
[ -f ".htaccess" ] && check_item 0 ".htaccess vorhanden" || check_item 1 ".htaccess fehlt"

echo ""
echo "=== 6. Verzeichnisstruktur ==="
echo ""

# Wichtige Verzeichnisse
[ -d "src" ] && check_item 0 "src/ vorhanden" || check_item 1 "src/ fehlt"
[ -d "src/pages" ] && check_item 0 "src/pages/ vorhanden" || check_item 1 "src/pages/ fehlt"
[ -d "src/api" ] && check_item 0 "src/api/ vorhanden" || check_item 1 "src/api/ fehlt"
[ -d "src/admin" ] && check_item 0 "src/admin/ vorhanden" || check_item 1 "src/admin/ fehlt"
[ -d "src/core" ] && check_item 0 "src/core/ vorhanden" || check_item 1 "src/core/ fehlt"
[ -d "src/templates" ] && check_item 0 "src/templates/ vorhanden" || check_item 1 "src/templates/ fehlt"
[ -d "src/assets" ] && check_item 0 "src/assets/ vorhanden" || check_item 1 "src/assets/ fehlt"
[ -d "database" ] && check_item 0 "database/ vorhanden" || check_item 1 "database/ fehlt"

echo ""
echo "================================================"
echo "  Zusammenfassung"
echo "================================================"
echo ""
echo -e "${GREEN}Erfolgreich: $PASSED${NC}"
echo -e "${RED}Fehlgeschlagen: $FAILED${NC}"
echo ""

if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}✓ Server-Setup sieht gut aus!${NC}"
    exit 0
else
    echo -e "${YELLOW}! Es gibt noch offene Punkte${NC}"
    exit 1
fi
