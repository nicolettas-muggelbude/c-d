#!/bin/bash
# Zeige die letzten PHP Error-Logs

echo "=== PHP Error Logs ==="
echo ""

# Verschiedene mögliche Log-Dateien prüfen
LOG_FILES=(
    "/var/log/php_errors.log"
    "/var/log/php/error.log"
    "/var/log/apache2/error.log"
    "/var/log/nginx/error.log"
    "$HOME/logs/error.log"
    "$HOME/public_html/error.log"
)

FOUND=0

for LOG in "${LOG_FILES[@]}"; do
    if [ -f "$LOG" ] && [ -r "$LOG" ]; then
        echo ">>> Log-Datei: $LOG"
        echo ">>> Letzte 30 Zeilen:"
        tail -30 "$LOG" | grep -A 5 -B 5 "Booking" || tail -30 "$LOG"
        echo ""
        FOUND=1
    fi
done

if [ $FOUND -eq 0 ]; then
    echo "Keine Log-Dateien gefunden oder lesbar."
    echo ""
    echo "PHP-Error-Log-Pfad prüfen:"
    php -i | grep error_log
fi

echo ""
echo "=== Ende ==="
