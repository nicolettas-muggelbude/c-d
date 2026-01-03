# Deployment & Operations

## Inhaltsverzeichnis
- Deployment-System
- Wartungsmodus
- Update-Workflow
- Dateistruktur
- Git-basierte Deployments
- Cronjobs
- Backup-Strategie

## Session 2026-01-01 (Fortsetzung): Deployment-System mit Wartungsmodus

### Erreichte Ziele ✅

#### 1. Wartungsmodus-System
**Problem:** Bei Updates muss die Website offline genommen werden können, ohne dass User Fehler sehen.

**Lösung - Datei-basiertes System:**
- Einfacher File-Check: Wenn `src/MAINTENANCE` existiert → Wartungsseite anzeigen
- Keine Datenbank-Änderung erforderlich
- Schnell aktivierbar (per FTP/SSH oder Admin-UI)

**Features:**
- ✅ **Admin-Bypass:** Eingeloggte Admins können weiter arbeiten
- ✅ **Admin-Warnung:** Orange Sticky-Banner zeigt Wartungsmodus an
- ✅ **Custom Message:** Nachricht aus MAINTENANCE-Datei (erste Zeile)
- ✅ **Geschätzte Endzeit:** Optional in zweiter Zeile
- ✅ **Schöne Wartungsseite:**
  - Gradient-Hintergrund (Lila)
  - Animiertes Werkzeug-Icon (Pulse)
  - Spinner-Animation
  - Kontaktinformationen
  - 503 HTTP Status mit Retry-After Header

**Technische Implementation:**
```php
// src/core/maintenance.php
if (file_exists($maintenanceFile)) {
    // Admin kann trotzdem zugreifen
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
        define('MAINTENANCE_ADMIN_BYPASS', true);
        return;
    }

    // Normale User sehen Wartungsseite
    showMaintenancePage($maintenanceFile);
    exit;
}
```

**MAINTENANCE-Datei Format:**
```
Wir führen gerade ein Update durch.
Heute um 18:00 Uhr
```

**Integration in Router:**
```php
// src/router.php
start_session_safe();

// Wartungsmodus-Check (vor allen Routen!)
require_once __DIR__ . '/core/maintenance.php';

// ... rest of routing
```

**Admin-Warnung im Header:**
```php
// src/templates/header.php
<?php if (defined('MAINTENANCE_ADMIN_BYPASS') && MAINTENANCE_ADMIN_BYPASS): ?>
    <div style="background: #ff9800; color: white; padding: 1rem;
                text-align: center; font-weight: bold; position: sticky;
                top: 0; z-index: 10000;">
        ⚠️ WARTUNGSMODUS AKTIV - Sie sind als Admin eingeloggt...
    </div>
<?php endif; ?>
```

**Datei:** `src/core/maintenance.php`

#### 2. Health-Check Endpoint
**Problem:** Nach Deployment muss Systemstatus überprüfbar sein.

**Lösung - Umfassender Health-Check API:**

**Endpoint:** `GET /api/health-check`

**Prüfungen:**
1. ✅ **Datenbank-Verbindung:**
   - SELECT 1 Query
   - Erfolg/Fehler mit Fehlermeldung

2. ✅ **EmailService:**
   - Klasse ladbar?
   - Instanziierbar?

3. ✅ **Composer Vendor:**
   - autoload.php vorhanden?
   - PHPMailer verfügbar?

4. ✅ **Logs-Verzeichnis:**
   - Existiert?
   - Beschreibbar?

5. ✅ **Uploads-Verzeichnis:**
   - Existiert?
   - Beschreibbar?

6. ✅ **Speicherplatz:**
   - Freier Speicher in GB
   - Prozent verfügbar
   - Warnung bei <10%

7. ✅ **PHP-Version:**
   - Aktuelle Version anzeigen

8. ✅ **Wartungsmodus-Status:**
   - Aktiv/Inaktiv
   - Warnung wenn aktiv

**Response-Format:**
```json
{
    "status": "ok",  // oder "warning", "error"
    "timestamp": "2026-01-01 16:04:10",
    "version": "1.0.0",
    "checks": {
        "database": {
            "status": "ok",
            "message": "Datenbankverbindung erfolgreich"
        },
        "disk_space": {
            "status": "ok",
            "message": "Speicherplatz ausreichend: 93.23%",
            "free_gb": 938.64,
            "total_gb": 1006.85
        },
        "maintenance_mode": {
            "status": "ok",
            "message": "Wartungsmodus ist inaktiv",
            "enabled": false
        }
        // ... weitere Checks
    }
}
```

**HTTP Status Codes:**
- `200 OK` - Alle Checks erfolgreich oder nur Warnungen
- `503 Service Unavailable` - Kritische Fehler (z.B. DB down)

**Verwendung im Deployment:**
```bash
# Health-Check aufrufen
curl https://pc-wittfoot.de/api/health-check

# Mit jq für bessere Lesbarkeit
curl -s https://pc-wittfoot.de/api/health-check | jq
```

**Datei:** `src/api/health-check.php`

**Router-Integration:**
```php
// src/router.php
case 'api':
    header('Content-Type: application/json; charset=UTF-8');

    switch ($param) {
        // ... existing routes
        case 'health-check':
            require __DIR__ . '/api/health-check.php';
            break;
    }
```

#### 3. Admin-UI für Wartungsmodus
**Problem:** Wartungsmodus sollte nicht per FTP/SSH aktiviert werden müssen.

**Lösung - Vollständige Verwaltungsoberfläche:**

**URL:** `/admin/maintenance`

**Features:**
- ✅ **Status-Übersicht:**
  - Großer Status-Indicator (🔧 AKTIV / ✅ ONLINE)
  - Farbcodiert (Orange/Grün)
  - Zeigt aktuelle Nachricht und Endzeit

- ✅ **Wartungsmodus aktivieren:**
  - Custom Nachricht eingeben (Textarea)
  - Voraussichtliches Ende (optional, Text-Input)
  - Bestätigungs-Dialog
  - Erstellt `src/MAINTENANCE` Datei

- ✅ **Wartungsmodus deaktivieren:**
  - Button mit Bestätigungs-Dialog
  - Löscht `src/MAINTENANCE` Datei

- ✅ **Nachricht bearbeiten:**
  - Während Wartungsmodus aktiv
  - Live-Update ohne Deaktivierung

- ✅ **Info-Bereiche:**
  - Was passiert beim Aktivieren?
  - Health-Check Endpoint Info
  - Empfohlener Deployment-Workflow

**Design:**
- Responsive Cards-Layout
- Status-Badges mit Icons
- Info-Boxen mit Hinweisen
- Preview-Box für aktuelle Nachricht
- Form-Validierung

**Workflow:**
1. Admin öffnet `/admin/maintenance`
2. Klickt "🔧 Wartungsmodus aktivieren"
3. Gibt Nachricht ein (z.B. "Wir führen gerade ein Update durch.")
4. Optional: Gibt geschätzte Endzeit ein (z.B. "Heute um 18:00 Uhr")
5. Bestätigt → Website ist offline für User
6. Admin kann weiter arbeiten (sieht orange Warnung)
7. Nach Deployment: "✅ Wartungsmodus deaktivieren"

**Dateiberechtigungen:**
- `src/` Verzeichnis muss beschreibbar sein
- Fehlermeldung falls MAINTENANCE nicht erstellt/gelöscht werden kann

**Datei:** `src/admin/maintenance.php`

**Router-Integration:**
```php
// src/router.php
case 'admin':
    // ... existing routes
    elseif ($param === 'maintenance') {
        require_admin();
        require __DIR__ . '/admin/maintenance.php';
    }
```

**Dashboard-Link:**
```php
// src/admin/index.php
<a href="<?= BASE_URL ?>/admin/maintenance" class="btn btn-outline btn-block">
    🛠️ Wartungsmodus
</a>
```

#### 4. Deployment-Script (deploy.sh)
**Problem:** Manuelles Deployment via FTP ist fehleranfällig und zeitaufwändig.

**Lösung - Automatisiertes Deployment-Script:**

**Verwendung:**
```bash
./deploy.sh
```

**Features:**
1. ✅ **FTP-Verbindung prüfen**
   - Validiert Zugangsdaten
   - Prüft lftp-Installation

2. ✅ **Backup erstellen**
   - Automatisch vor jedem Deployment
   - Dateien + Datenbank
   - Komprimiert als .tar.gz

3. ✅ **Wartungsmodus aktivieren**
   - Uploaded MAINTENANCE-Datei per FTP
   - Custom Nachricht mit Zeitstempel

4. ✅ **Dateien hochladen**
   - Mirror-Mode (nur geänderte Dateien)
   - Excludes: .git, node_modules, .env, MAINTENANCE
   - Parallele Uploads (5 Connections)

5. ✅ **Health-Check durchführen**
   - 3 Versuche bei Fehler
   - Zeigt Status-Details
   - Bei Fehler: Frage ob trotzdem online gehen

6. ✅ **Wartungsmodus deaktivieren**
   - Nur wenn Health-Check erfolgreich
   - Optional: Manuelles Override bei Fehler

**Workflow:**
```
Bestätigung → FTP-Check → Backup → Wartung AN
  → Upload → Health-Check → Wartung AUS → Fertig
```

**Konfiguration (anpassen!):**
```bash
# FTP-Zugangsdaten
FTP_HOST="ftp.example.com"
FTP_USER="username"
FTP_PASS="password"
FTP_REMOTE_DIR="/public_html"

# Website-URL
SITE_URL="https://pc-wittfoot.de"
HEALTH_CHECK_URL="$SITE_URL/api/health-check"

# Backup-Aufbewahrung
BACKUP_RETENTION_DAYS=30
```

**Logging:**
- Farbcodierte Ausgabe (INFO/SUCCESS/WARNING/ERROR)
- Alle Schritte werden geloggt
- Backup-Pfad wird angezeigt

**Error-Handling:**
- Bei FTP-Fehler → Abbruch vor Wartungsmodus
- Bei Upload-Fehler → Wartungsmodus bleibt aktiv
- Bei Health-Check-Fehler → Nachfrage ob trotzdem online
- Backup-Fehler stoppt Deployment nicht (Warnung)

**Datei:** `deploy.sh`

**Ausführbar machen:**
```bash
chmod +x deploy.sh
```

**Abhängigkeiten:**
```bash
# lftp für FTP-Upload
sudo apt-get install lftp

# curl für Health-Check
sudo apt-get install curl

# python3 für JSON-Formatierung (optional)
sudo apt-get install python3
```

#### 5. Backup-Script (backup.sh)
**Problem:** Regelmäßige Backups sind essentiell, sollten aber automatisiert sein.

**Lösung - Flexibles Backup-Script:**

**Verwendung:**
```bash
./backup.sh                 # Vollständiges Backup
./backup.sh --files-only    # Nur Dateien
./backup.sh --db-only       # Nur Datenbank
./backup.sh --list          # Backups auflisten
```

**Features:**
1. ✅ **Dateien sichern:**
   - Kompletter `src/` Ordner
   - .env, composer.json, composer.lock
   - .htaccess
   - Erstellt backup_info.txt mit Metadaten

2. ✅ **Datenbank sichern:**
   - mysqldump aller Tabellen
   - Komplett mit Struktur und Daten
   - Erstellt database_info.txt

3. ✅ **Backup komprimieren:**
   - tar.gz Format
   - Zeitstempel im Dateinamen
   - Temp-Verzeichnis wird aufgeräumt

4. ✅ **Alte Backups löschen:**
   - Automatisch Backups >30 Tage
   - Konfigurierbar

5. ✅ **Remote-Upload (optional):**
   - FTP-Upload auf Remote-Server
   - Konfigurierbar ein/ausschalten

6. ✅ **Backup-Übersicht:**
   - Liste aller Backups
   - Datum, Zeit, Größe
   - Sortiert nach Datum

**Backup-Format:**
```
backups/
├── backup_20260101_160808.tar.gz  (112K)
├── backup_20260101_160746.tar.gz  (112K)
└── backup_20250101_143022.tar.gz  (108K)
```

**Backup-Inhalt:**
```
backup_20260101_160808.tar.gz
├── files/
│   ├── src/
│   ├── composer.json
│   ├── composer.lock
│   ├── .htaccess
│   └── backup_info.txt
└── database/
    ├── pc_wittfoot_20260101_160808.sql
    └── database_info.txt
```

**Konfiguration:**
```bash
# Datenbank-Zugangsdaten
DB_HOST="localhost"
DB_USER="pc_wittfoot"
DB_PASS="dev123"
DB_NAME="pc_wittfoot"

# Backup-Aufbewahrung
BACKUP_RETENTION_DAYS=30

# Remote-Upload (optional)
REMOTE_BACKUP_ENABLED=false
REMOTE_FTP_HOST=""
REMOTE_FTP_USER=""
REMOTE_FTP_PASS=""
```

**Logging:**
- Farbcodierte Ausgabe
- Zeigt Backup-Größe
- Listet gelöschte alte Backups
- Zusammenfassung am Ende

**Automatisierung via Cron:**
```bash
# Täglich um 3:00 Uhr
0 3 * * * /pfad/zu/backup.sh

# Wöchentlich Sonntags um 4:00 Uhr
0 4 * * 0 /pfad/zu/backup.sh
```

**Datei:** `backup.sh`

**Ausführbar machen:**
```bash
chmod +x backup.sh
```

**Abhängigkeiten:**
```bash
# mysqldump für Datenbank-Backup
sudo apt-get install mysql-client

# lftp für Remote-Upload (optional)
sudo apt-get install lftp
```

**Backup wiederherstellen:**
```bash
# Backup entpacken
tar -xzf backups/backup_20260101_160808.tar.gz

# Dateien zurückspielen
cp -r files/src/* /pfad/zu/src/

# Datenbank importieren
mysql -u pc_wittfoot -p pc_wittfoot < database/pc_wittfoot_*.sql
```

### Deployment-Workflow (Empfohlen)

#### Manuelles Deployment
```bash
# 1. Änderungen testen lokal
php -S localhost:8000 server.php

# 2. Backup erstellen
./backup.sh

# 3. Wartungsmodus aktivieren (via Admin-UI oder Script)
touch src/MAINTENANCE

# 4. Dateien per FTP hochladen
# ... manuell oder via FileZilla

# 5. Health-Check prüfen
curl https://pc-wittfoot.de/api/health-check

# 6. Wartungsmodus deaktivieren
rm src/MAINTENANCE
```

#### Automatisches Deployment
```bash
# Alles in einem Schritt
./deploy.sh

# Das Script führt alle Schritte automatisch aus:
# ✅ Backup
# ✅ Wartungsmodus AN
# ✅ Upload
# ✅ Health-Check
# ✅ Wartungsmodus AUS
```

#### Deployment mit Datenbank-Migration
```bash
# 1. Deploy wie gewohnt
./deploy.sh

# 2. Via FTP: SQL-Datei hochladen nach /tmp

# 3. Via phpMyAdmin oder SSH:
mysql -u pc_wittfoot -p pc_wittfoot < /tmp/migration.sql

# 4. Health-Check prüfen
curl https://pc-wittfoot.de/api/health-check

# 5. Falls Fehler: Wartungsmodus manuell deaktivieren
# Via Admin-UI: /admin/maintenance
```

### Technische Details

#### Wartungsmodus-Check (Performance)
```php
// Sehr schnell - nur File-Check
if (file_exists($maintenanceFile)) {
    // Kein DB-Query nötig!
}

// Pro Request: ~0.001s Overhead
```

#### Health-Check Performance
- Führt ~8 Checks durch
- Response-Time: ~50-200ms
- Cached: Nein (immer aktuell)
- Geeignet für Monitoring-Tools

#### Backup-Größen (Beispiel)
```
Dateien (src/):              ~2 MB
Datenbank (SQL-Dump):       ~100 KB
Komprimiert (tar.gz):       ~500 KB
```

**Mit Bildern/Uploads:**
```
Dateien + Uploads:           ~50 MB
Komprimiert:                 ~20 MB
```

#### FTP-Upload via lftp
```bash
# Vorteile gegenüber Standard-FTP:
- Mirror-Mode (nur geänderte Dateien)
- Parallele Verbindungen (schneller)
- Resume bei Abbruch
- SSL/TLS Support
- Scripting-fähig
```

### Dateistruktur (Deployment-System)

```
/
├── deploy.sh                      # Deployment-Script (NEU)
├── backup.sh                      # Backup-Script (NEU)
├── backups/                       # Backup-Verzeichnis (NEU)
│   └── backup_*.tar.gz
├── src/
│   ├── MAINTENANCE                # Wartungsmodus-Trigger
│   ├── core/
│   │   └── maintenance.php        # Wartungsmodus-Handler (NEU)
│   ├── templates/
│   │   └── header.php             # Admin-Warnung (AKTUALISIERT)
│   ├── admin/
│   │   └── maintenance.php        # Admin-UI (NEU)
│   ├── api/
│   │   └── health-check.php       # Health-Check (NEU)
│   └── router.php                 # Maintenance-Check (AKTUALISIERT)
```

### .gitignore Anpassungen

```bash
# Deployment-System
/backups/                  # Backups nicht committen
/src/MAINTENANCE           # Wartungsmodus-Datei nicht committen

# Bereits vorhanden
/vendor/
composer.phar
/logs/*.log
.vscode/
.idea/
```

**Datei:** `.gitignore`

### Projektstand nach Session

#### Komplett implementiert ✅
- ✅ Wartungsmodus-System (datei-basiert)
- ✅ Health-Check Endpoint (8 Prüfungen)
- ✅ Admin-UI für Wartungsmodus
- ✅ Deployment-Script (deploy.sh)
- ✅ Backup-Script (backup.sh)
- ✅ Admin-Warnung im Header
- ✅ Router-Integration
- ✅ Dashboard-Integration
- ✅ .gitignore aktualisiert

#### Bereit für Produktion
- **Wartungsmodus:** Jederzeit aktivierbar
- **Deployment:** Voll automatisiert
- **Backups:** Automatisch vor jedem Deployment
- **Monitoring:** Health-Check für Systemstatus
- **Admin-Bypass:** Admins können während Wartung arbeiten

#### Deployment-Komplexität nach Änderungsart

| Änderungsart | Komplexität | Zeit | Vorgehen |
|-------------|-------------|------|----------|
| Content (Text, Bilder) | Einfach | 5-15 Min | Direkt per FTP, kein Backup nötig |
| CSS/JS | Einfach | 5-15 Min | FTP-Upload, Browser-Cache leeren |
| PHP-Code | Mittel | 30-60 Min | `./deploy.sh` verwenden |
| Datenbank-Schema | Komplex | 1-2 Std | Deploy + manuelle SQL-Migration |
| Neue Features | Komplex | Variabel | Staging → Test → Deploy |

#### Best Practices

**Vor Deployment:**
- ✅ Lokale Tests durchführen
- ✅ Git commit & push
- ✅ Backup-Strategie prüfen

**Während Deployment:**
- ✅ Wartungsmodus aktivieren
- ✅ Automatisches Backup läuft
- ✅ Health-Check nach Upload

**Nach Deployment:**
- ✅ Website testen (alle Hauptfunktionen)
- ✅ Health-Check prüfen
- ✅ Error-Logs checken
- ✅ Backup verifizieren

**Bei Problemen:**
- ✅ Wartungsmodus bleibt aktiv
- ✅ Fehler beheben
- ✅ Erneut deployen
- ✅ Oder: Backup zurückspielen

#### Monitoring & Wartung

**Health-Check URL:**
```
https://pc-wittfoot.de/api/health-check
```

**Monitoring-Integration:**
- UptimeRobot: HTTP-Monitor auf Health-Check
- Statuscake: JSON-Response parsen
- Cronjob: Täglicher Check + Email bei Fehler

**Backup-Strategie:**
```bash
# Täglich automatisches Backup
0 3 * * * /pfad/zu/backup.sh

# Vor jedem Deployment (automatisch in deploy.sh)
./deploy.sh  # erstellt automatisch Backup

# Manuelle Backups bei großen Änderungen
./backup.sh
```

**Backup-Aufbewahrung:**
- Täglich: 30 Tage
- Vor Deployments: Unbegrenzt (manuell löschen)
- Kritische Versionen: Separat archivieren

#### Troubleshooting

**Problem: Wartungsmodus aktiviert sich nicht**
```bash
# Prüfen ob Datei erstellt wurde
ls -la src/MAINTENANCE

# Prüfen ob Router maintenance.php lädt
grep "maintenance.php" src/router.php

# Manuell aktivieren
echo "Wartungsarbeiten" > src/MAINTENANCE
```

**Problem: Health-Check schlägt fehl**
```bash
# Direkt im Browser öffnen
https://pc-wittfoot.de/api/health-check

# Welcher Check failed?
curl -s https://pc-wittfoot.de/api/health-check | jq '.checks'

# Logs prüfen
tail -f logs/error.log
```

**Problem: Deployment-Script kann nicht hochladen**
```bash
# FTP-Zugangsdaten testen
lftp -u username,password ftp.example.com -e "ls; bye"

# Rechte prüfen
lftp -u username,password ftp.example.com
cd /public_html
mkdir test
# Falls Fehler → Keine Schreibrechte
```

**Problem: Backup schlägt fehl**
```bash
# Datenbank-Zugangsdaten testen
mysql -u pc_wittfoot -pdev123 -e "SELECT 1"

# Backup-Verzeichnis beschreibbar?
ls -la backups/

# Manuell ausführen mit Debug
bash -x backup.sh
```

