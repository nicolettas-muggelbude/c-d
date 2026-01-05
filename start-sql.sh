#!/bin/bash

# MySQL-Verwaltungsskript für WSL (Debian/Ubuntu-basiert)
# Autor: Nicoletta Schnute
# Datum: 02.01.2026

# Farben für die Ausgabe
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Prüfen, ob das Skript als root ausgeführt wird
if [ "$(id -u)" -ne 0 ]; then
    echo -e "${RED}Fehler: Dieses Skript muss als root ausgeführt werden. Bitte verwende 'sudo'.${NC}"
    exit 1
fi

# MySQL-Dienstname (kann je nach Distribution variieren)
SERVICE_NAME="mysql"

# Funktion zum Starten von MySQL
start_mysql() {
    echo -e "${YELLOW}Starte MySQL-Dienst...${NC}"
    if systemctl start $SERVICE_NAME 2>/dev/null; then
        echo -e "${GREEN}MySQL wurde erfolgreich gestartet.${NC}"
    else
        echo -e "${RED}Fehler: MySQL konnte nicht gestartet werden.${NC}"
        echo -e "${YELLOW}Hinweis: Stelle sicher, dass MySQL installiert ist (z.B. mit 'sudo apt install mysql-server').${NC}"
    fi
}

# Funktion zum Stoppen von MySQL
stop_mysql() {
    echo -e "${YELLOW}Stoppe MySQL-Dienst...${NC}"
    if systemctl stop $SERVICE_NAME 2>/dev/null; then
        echo -e "${GREEN}MySQL wurde erfolgreich gestoppt.${NC}"
    else
        echo -e "${RED}Fehler: MySQL konnte nicht gestoppt werden.${NC}"
    fi
}

# Funktion zum Neustarten von MySQL
restart_mysql() {
    echo -e "${YELLOW}Starte MySQL-Dienst neu...${NC}"
    if systemctl restart $SERVICE_NAME 2>/dev/null; then
        echo -e "${GREEN}MySQL wurde erfolgreich neugestartet.${NC}"
    else
        echo -e "${RED}Fehler: MySQL konnte nicht neugestartet werden.${NC}"
    fi
}

# Funktion zum Anzeigen des MySQL-Status
status_mysql() {
    echo -e "${YELLOW}Aktueller Status von MySQL:${NC}"
    systemctl status $SERVICE_NAME --no-pager
}

# Hauptmenü
echo -e "${GREEN}MySQL-Verwaltung für WSL${NC}"
echo "----------------------------------------"
echo "1. MySQL starten"
echo "2. MySQL stoppen"
echo "3. MySQL neustarten"
echo "4. MySQL-Status anzeigen"
echo "5. Beenden"
echo "----------------------------------------"
echo -n "Wähle eine Option (1-5): "
read option

case $option in
    1) start_mysql ;;
    2) stop_mysql ;;
    3) restart_mysql ;;
    4) status_mysql ;;
    5) echo -e "${GREEN}Skript beendet.${NC}"; exit 0 ;;
    *) echo -e "${RED}Ungültige Option.${NC}"; exit 1 ;;
esac
