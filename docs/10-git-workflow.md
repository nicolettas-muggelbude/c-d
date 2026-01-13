# Git-Workflow: master vs. production Branch

> **Dokumentation erstellt:** 2026-01-13
> **Grund:** Nach mehreren Merge-Problemen und fehlenden Commits auf production

---

## 📋 Übersicht

Dieses Projekt verwendet **zwei Branches** für die Entwicklung:

- **`master`**: Entwicklungs-Branch (lokale Arbeit, neue Features)
- **`production`**: Production-Branch (nur getestete, funktionierende Features)

**Wichtig:** Der Produktionsserver pulled **NUR von `production`**, nie von `master`!

---

## 🔄 Standard-Workflow

### 1. Lokale Entwicklung (auf `master`)

```bash
# Auf master Branch wechseln
git checkout master

# Entwicklung durchführen
# ... Code ändern, testen ...

# Änderungen stagen
git add src/pfad/zur/datei.php

# Commit erstellen
git commit -m "Feature: Beschreibung der Änderung

Detaillierte Beschreibung was sich geändert hat.

Co-Authored-By: Claude Sonnet 4.5 <noreply@anthropic.com>"
```

**Wichtig:** Lokal testen BEVOR committen!

```bash
# Lokalen Test-Server starten
php -S localhost:8000 src/server.php

# Im Browser testen: http://localhost:8000
```

---

### 2. Nach `production` übertragen

**Option A: Alle Commits von master übernehmen**

```bash
# Auf production wechseln
git checkout production

# Alle neuen Commits von master holen
git merge master

# Zurück zu master
git checkout master
```

**Option B: Einzelne Commits cherry-picken**

```bash
# Commit-Hash finden
git log --oneline master -5

# Auf production wechseln
git checkout production

# Einzelnen Commit übernehmen
git cherry-pick COMMIT_HASH

# Zurück zu master
git checkout master
```

**Wann welche Option?**
- **Merge:** Wenn alle Commits auf master getestet und bereit sind
- **Cherry-pick:** Wenn nur bestimmte Commits deployed werden sollen

---

### 3. Auf GitHub pushen

```bash
# master pushen
git checkout master
git push origin master

# production pushen (wichtig!)
git checkout production
git push origin production
```

**⚠️ WICHTIG:** Beide Branches pushen, nicht nur einen!

---

### 4. Auf Produktionsserver deployen

```bash
# SSH-Verbindung zum Server
ssh dcp285520007@www116.c.artfiles.de

# Ins Projekt-Verzeichnis
cd /home/www/doc/28552/dcp285520007/pc-wittfoot.de/www

# Production-spezifische Dateien sichern
git stash push -m "Production-Config vor Pull $(date +%Y%m%d-%H%M%S)"

# Neuen Code holen
git pull --no-rebase --no-edit origin production

# Production-Config wiederherstellen
git stash pop

# Testen ob alles läuft
curl -s https://pc-wittfoot.de | head -20
```

---

## ⚠️ Häufige Fehler und Lösungen

### Fehler 1: "Your branch is ahead of 'origin/production' by X commits"

**Ursache:** Commits wurden auf production gemacht, aber nicht gepusht.

**Lösung:**
```bash
# Falls die Commits wichtig sind und nach origin sollen:
git push origin production

# Falls die Commits lokal bleiben sollen (z.B. auf Produktionsserver):
# Nichts tun, das ist normal
```

---

### Fehler 2: Commits fehlen auf production

**Symptom:** Feature ist auf master, aber nicht auf production.

**Ursache:** `git merge master` oder `git cherry-pick` wurde vergessen.

**Lösung:**
```bash
git checkout production
git cherry-pick COMMIT_HASH
git push origin production
```

---

### Fehler 3: "divergent branches" beim Pull

**Symptom:**
```
hint: You have divergent branches and need to specify how to reconcile them.
fatal: Need to specify how to reconcile divergent branches.
```

**Ursache:** Lokale und Remote-Branches haben unterschiedliche History.

**Lösung:**
```bash
# Lokale Änderungen sichern
git stash push -m "Vor Pull $(date)"

# Mit Merge pullen
git pull --no-rebase --no-edit origin production

# Lokale Änderungen wiederherstellen
git stash pop
```

---

### Fehler 4: Doppelte Domain in URLs (og:image)

**Symptom:** URLs wie `https://pc-wittfoot.dehttps://pc-wittfoot.de/...`

**Ursache:** `asset()` Funktion gibt bereits vollständige URL zurück.

**Falsch:**
```php
'https://pc-wittfoot.de' . asset('images/og-image.png')
```

**Richtig:**
```php
asset('images/og-image.png')
```

---

### Fehler 5: Binärdateien fehlen nach Git-Pull

**Symptom:** `og-image.png` ist im Commit, aber nicht auf dem Server.

**Ursache:** Git LFS nicht konfiguriert oder Datei nicht committed.

**Lösung:**
```bash
# Prüfen ob Datei im Commit ist
git show COMMIT_HASH:src/assets/images/og-image.png > /dev/null && echo "Datei ist im Commit" || echo "Datei fehlt"

# Falls Datei fehlt: Nochmal committen
git add src/assets/images/og-image.png
git commit -m "Add: og-image.png"
```

---

## 🎯 Best Practices

### ✅ DO's

1. **Immer auf master entwickeln**
   ```bash
   git checkout master
   # ... Entwicklung ...
   git commit -m "..."
   ```

2. **Lokal testen vor Commit**
   ```bash
   php -S localhost:8000 src/server.php
   ```

3. **Beide Branches pushen**
   ```bash
   git push origin master
   git push origin production
   ```

4. **Production-Config auf Server sichern**
   ```bash
   git stash push -m "Vor Pull"
   git pull origin production
   git stash pop
   ```

5. **Commits beschreiben**
   ```bash
   git commit -m "Fix: Kurze Beschreibung

   Detaillierte Erklärung was, warum, wie.

   Co-Authored-By: Claude Sonnet 4.5 <noreply@anthropic.com>"
   ```

---

### ❌ DON'Ts

1. **Nicht direkt auf production entwickeln**
   ```bash
   # FALSCH:
   git checkout production
   # ... Änderungen ...
   git commit
   ```

2. **Nicht ohne Merge/Cherry-pick pushen**
   ```bash
   # FALSCH:
   git checkout master
   git commit -m "Feature"
   git push origin production  # ❌ production hat den Commit nicht!
   ```

3. **Nicht Production-Config committen**
   ```bash
   # FALSCH:
   git add src/core/config.php
   git commit -m "Update config"  # ❌ Enthält DB-Credentials!
   ```

4. **Nicht force-pushen**
   ```bash
   git push --force origin production  # ❌ Kann History zerstören!
   ```

---

## 📊 Branch-Vergleich

| Aspekt | master | production |
|--------|--------|------------|
| **Zweck** | Entwicklung | Live-System |
| **Commits** | Alle neuen Features | Nur getestete Features |
| **Push** | Nach jedem Commit | Nach Merge/Cherry-pick |
| **Server** | - | Produktionsserver pulled hier |
| **Testing** | Lokal (localhost:8000) | Live-Site |

---

## 🔧 Troubleshooting

### Problem: Ich bin auf dem falschen Branch

```bash
# Aktuellen Branch anzeigen
git branch --show-current

# Auf richtigen Branch wechseln
git checkout master   # oder production
```

---

### Problem: Ungespeicherte Änderungen beim Branch-Wechsel

```bash
# Änderungen temporär sichern
git stash push -m "WIP: Beschreibung"

# Branch wechseln
git checkout production

# Zurück zum alten Branch
git checkout master

# Änderungen wiederherstellen
git stash pop
```

---

### Problem: Merge-Konflikt

```bash
# Konflikt tritt auf bei:
git merge master

# Lösung:
# 1. Konflikt-Dateien bearbeiten (zwischen <<<< und >>>> Markierungen)
# 2. Resolved markieren:
git add konflikt-datei.php

# 3. Merge abschließen:
git commit -m "Merge master into production"
```

---

## 📝 Checkliste für Deployment

### Vor dem Deployment:

- [ ] Alle Änderungen auf `master` committed
- [ ] Lokal getestet (http://localhost:8000)
- [ ] Auf `production` gemerged/cherry-picked
- [ ] `master` gepusht
- [ ] `production` gepusht
- [ ] Pre-Push-Hook Tests bestanden (17/17)

### Auf dem Produktionsserver:

- [ ] SSH-Verbindung hergestellt
- [ ] Im richtigen Verzeichnis (`/home/www/doc/28552/dcp285520007/pc-wittfoot.de/www`)
- [ ] `git stash` ausgeführt (Production-Config sichern)
- [ ] `git pull origin production` ausgeführt
- [ ] `git stash pop` ausgeführt (Production-Config wiederherstellen)
- [ ] Website getestet (curl oder Browser)
- [ ] Logs geprüft (`tail -20 logs/error.log`)

### Nach dem Deployment:

- [ ] https://pc-wittfoot.de lädt
- [ ] Neue Features funktionieren
- [ ] Keine Fehler in `logs/error.log`
- [ ] Admin-Login funktioniert (falls relevant)

---

## 🚨 Notfall: Rollback

Falls nach dem Deployment etwas nicht funktioniert:

```bash
# Auf dem Produktionsserver:

# 1. Letzten funktionierenden Commit finden
git log --oneline -10

# 2. Zu diesem Commit zurückgehen
git reset --hard COMMIT_HASH

# 3. Production-Config aus Backup wiederherstellen (falls nötig)
git stash apply stash@{0}

# 4. Testen
curl -s https://pc-wittfoot.de | head -20
```

---

## 📚 Weiterführende Dokumentation

- **[Deployment & Operations](08-deployment-ops.md)** - Wartungsmodus, Backup, Server-Details
- **[Session-Log](09-session-log.md)** - Chronologische Entwicklungshistorie
- **[claude.md](../claude.md)** - Projekt-Übersicht

---

## ✅ Erfolgreiche Deployments (Historie)

### 2026-01-13: OG-Image und LinkedIn Meta-Tags

**Commits:**
- `b4ca840` - Add: Open Graph Image für Social Media Previews
- `ea9a72b` - Add: LinkedIn Open Graph Meta-Tags
- `c73fb6a` - Fix: Doppelte Domain in OG-Image URL entfernt

**Workflow:**
1. Commits waren auf `master`, fehlten auf `production`
2. `git cherry-pick b4ca840 ea9a72b` auf production
3. URL-Bug gefixed
4. Gepusht nach `origin/production`
5. Produktionsserver: `git pull origin production`
6. Tests: ✅ Alle Meta-Tags korrekt, og-image.png vorhanden

**Lessons Learned:**
- ⚠️ Immer beide Branches pushen (master UND production)
- ⚠️ `asset()` Funktion gibt bereits vollständige URL zurück

---

### 2026-01-13: Health-Check während Wartungsmodus

**Commit:** `b8d7eda` - Fix: Health-Check Endpoint während Wartungsmodus ermöglichen

**Workflow:**
1. Bug gefunden: Tests schlugen fehl (16/17)
2. Fix auf `master` committed
3. `git cherry-pick` auf `production`
4. Gepusht nach `origin/production`
5. Tests: ✅ 17/17 bestanden

---

## 🎓 Fazit

**Der korrekte Workflow in Kürze:**

1. Auf `master` entwickeln
2. Lokal testen
3. Committen auf `master`
4. Auf `production` wechseln und mergen/cherry-picken
5. Beide Branches pushen
6. Auf Produktionsserver: stash → pull → stash pop
7. Testen

**Bei Problemen:** Diese Dokumentation lesen oder im [Session-Log](09-session-log.md) nachschlagen!
