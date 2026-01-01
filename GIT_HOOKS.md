# Git Hooks - PC-Wittfoot UG

Dieses Projekt verwendet Git Hooks für automatische Qualitätssicherung.

## Installierte Hooks

### 1. Pre-Commit Hook
**Wann:** Vor jedem `git commit`
**Was:** Prüft PHP-Syntax aller geänderten `.php` Dateien

**Beispiel:**
```bash
git commit -m "Neue Funktion"

# Hook läuft automatisch:
🔍 Prüfe PHP-Syntax...
✓ PHP-Syntax OK (3 Datei(en) geprüft)
```

**Bei Fehler:**
```bash
✗ Syntax-Fehler in: src/api/booking.php
→ Commit wurde abgebrochen
→ Bitte Fehler beheben und erneut committen
```

### 2. Pre-Push Hook
**Wann:** Vor jedem `git push`
**Was:** Führt komplette Test-Suite aus (`test-deployment.sh`)

**Beispiel:**
```bash
git push origin main

# Hook läuft automatisch:
═══════════════════════════════════════
  Pre-Push Hook: Tests werden ausgeführt
═══════════════════════════════════════

[TEST] Verzeichnisstruktur prüfen
[✓] Alle erforderlichen Dateien vorhanden
...
✓ Alle Tests bestanden! Push wird durchgeführt...
```

**Bei Fehler:**
```bash
✗ Tests fehlgeschlagen!
→ Push wurde abgebrochen
→ Bitte Fehler beheben und erneut pushen
```

### 3. Post-Merge Hook
**Wann:** Nach jedem `git pull` oder `git merge`
**Was:** Aktualisiert Composer-Dependencies automatisch

**Beispiel:**
```bash
git pull origin main

# Wenn composer.lock geändert wurde:
📦 Prüfe Composer-Dependencies...
⚠ composer.lock wurde geändert!
→ Führe 'composer install' aus...
✓ Composer-Dependencies erfolgreich aktualisiert
```

## Hook temporär umgehen

### Einzelner Commit/Push ohne Hook
```bash
# Pre-Commit umgehen
git commit --no-verify -m "Quick fix"

# Pre-Push umgehen
git push --no-verify
```

### Hook dauerhaft deaktivieren
```bash
# Einzelnen Hook entfernen
rm .git/hooks/pre-commit

# Alle Hooks entfernen
rm .git/hooks/pre-*
rm .git/hooks/post-*

# Hook umbenennen (deaktivieren ohne löschen)
mv .git/hooks/pre-push .git/hooks/pre-push.disabled
```

### Hook wieder aktivieren
```bash
# Umbenannten Hook wieder aktivieren
mv .git/hooks/pre-push.disabled .git/hooks/pre-push
```

## Hooks anpassen

Alle Hooks liegen in `.git/hooks/` und können bearbeitet werden:

```bash
# Pre-Commit Hook bearbeiten
nano .git/hooks/pre-commit

# Nach Änderungen: Ausführbar machen
chmod +x .git/hooks/pre-commit
```

## Troubleshooting

### Hook läuft nicht
```bash
# Prüfen ob ausführbar
ls -la .git/hooks/pre-push

# Sollte sein: -rwxr-xr-x (x = executable)
# Falls nicht:
chmod +x .git/hooks/pre-push
```

### Hook schlägt fehl, obwohl alles OK
```bash
# Mit --no-verify umgehen
git push --no-verify

# Dann Hook manuell testen:
./test-deployment.sh
```

### Composer nicht gefunden (Post-Merge)
```bash
# composer.phar sollte im Projekt-Root liegen
ls composer.phar

# Falls nicht vorhanden:
curl -sS https://getcomposer.org/installer | php
```

## Best Practices

### ✅ Wann Hook umgehen ist OK:
- Experimenteller Branch (kein main/master)
- Work-in-Progress Commits
- Revert von kaputtem Code
- Dringende Hotfixes (nachher testen!)

### ❌ Wann Hook NICHT umgehen:
- Push zu main/master Branch
- Merge zu Production Branch
- Release-Tags
- Code der reviewed werden soll

## Hook-Status prüfen

```bash
# Alle installierten Hooks anzeigen
ls -la .git/hooks/

# Sollte enthalten:
# -rwxr-xr-x  pre-commit
# -rwxr-xr-x  pre-push
# -rwxr-xr-x  post-merge
```

## Für Team-Setup

Wenn weitere Entwickler dem Projekt beitreten:

```bash
# Nach git clone:
cd pc-wittfoot-projekt

# Hooks sind bereits da und aktiv!
# (weil in .git/hooks/ enthalten)

# Beim ersten Commit/Push werden sie automatisch ausgeführt
```

**Hinweis:** Git Hooks werden NICHT automatisch geclont!
Für Team-Projekte besser: Hooks ins Repo packen und Setup-Script erstellen.

## Weitere Informationen

- Test-Suite: `./test-deployment.sh`
- Deployment: `./deploy.sh`
- Backup: `./backup.sh`
- Dokumentation: `claude.md`
