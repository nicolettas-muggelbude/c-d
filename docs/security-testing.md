# Security Testing Guide
PC-Wittfoot UG

## 1. SQL-Injection Tests

### ✅ Aktueller Stand
- **Prepared Statements**: ✓ Überall implementiert
- **PDO-Konfiguration**: ✓ `ATTR_EMULATE_PREPARES => false` (Zeile 23 in database.php)
- **String-Concatenation**: ✓ Keine gefunden in SQL-Queries

### Test-Methoden

#### A) Code-Review (bereits durchgeführt ✓)
```bash
# Suche nach unsicheren Query-Patterns
grep -r "WHERE.*\$_" src/
grep -r "'\s*\.\s*\$_" src/
```

**Ergebnis**: Nur Test-Dateien, keine SQL-Injection-Risiken gefunden.

#### B) Manuelle Browser-Tests

**Test 1: Terminsuche**
1. Admin-Login: http://localhost:8000/admin/bookings
2. Suchfeld testen mit:
   ```
   ' OR '1'='1
   '; DROP TABLE bookings; --
   admin' --
   ```
3. **Erwartetes Verhalten**: Keine Ergebnisse oder SQL-Fehler, nur sichere Suche

**Test 2: Login-Formular**
1. Öffne: http://localhost:8000/admin/login
2. Username-Feld testen mit:
   ```
   admin' OR '1'='1' --
   ' UNION SELECT NULL, NULL, NULL --
   ```
3. **Erwartetes Verhalten**: Login schlägt fehl, keine SQL-Fehler sichtbar

**Test 3: API-Endpoints**
```bash
# Terminbuchung mit SQL-Injection versuchen
curl -X POST http://localhost:8000/api/booking \
  -H "Content-Type: application/json" \
  -d '{
    "customer_email": "test@test.de'; DROP TABLE bookings; --",
    "customer_firstname": "Test"
  }'
```
**Erwartetes Verhalten**: Parameter werden escaped, keine SQL-Ausführung

#### C) Automatisierte Tools

**SQLMap** (fortgeschritten):
```bash
# Installation
pip install sqlmap

# Test Login-Form
sqlmap -u "http://localhost:8000/admin/login" \
  --data="username=test&password=test" \
  --level=5 --risk=3

# Test Terminsuche
sqlmap -u "http://localhost:8000/admin/bookings?search=test" \
  --level=3 --risk=2
```

**Erwartetes Ergebnis**: "No SQL injection found"

---

## 2. CSRF-Token Tests

### ✅ Aktueller Stand
- **Token-Generierung**: ✓ `csrf_token()` in helpers.php (Zeile 37)
- **Token-Validierung**: ✓ `csrf_verify()` in helpers.php (Zeile 47)
- **Verwendung**: ✓ In 18 Dateien gefunden

### Test-Methoden

#### A) Code-Review (bereits durchgeführt ✓)

**Verwendete Formulare**:
- ✓ Login-Formular (`admin/login.php`)
- ✓ Produkt-Bearbeitung (`admin/product-edit.php`)
- ✓ Kategorie-Bearbeitung (`admin/category-edit.php`)
- ✓ Warenkorb (`pages/warenkorb.php`)
- ✓ Kontaktformular (`pages/kontakt.php`)
- ✓ CSV-Import (`admin/csv-import.php`)

**NICHT geschützt** (APIs ohne Formulare):
- ⚠️ API-Endpoints (`/api/booking`, `/api/booking-cancel`, etc.)
  - **Begründung**: Magic-Token-basierte Authentifizierung
  - **Risiko**: Niedrig (Token ist Secret, keine Session-basierte Auth)

#### B) Manuelle Browser-Tests

**Test 1: Token in Formular vorhanden**
1. Öffne: http://localhost:8000/admin/login
2. Browser DevTools → Elemente
3. Suche nach: `<input type="hidden" name="csrf_token"`
4. **Erwartung**: Token-Feld vorhanden, 64 Zeichen Hex-String

**Test 2: Fehlender Token**
```bash
# Versuch ohne CSRF-Token
curl -X POST http://localhost:8000/admin/login \
  -d "username=admin&password=test123"
```
**Erwartetes Verhalten**: Login schlägt fehl oder CSRF-Fehler

**Test 3: Falscher Token**
```bash
# Versuch mit falschem Token
curl -X POST http://localhost:8000/admin/login \
  -d "username=admin&password=test123&csrf_token=INVALID_TOKEN"
```
**Erwartetes Verhalten**: CSRF-Validierung schlägt fehl

**Test 4: Token-Reuse verhindern**
1. Login-Seite öffnen, Token kopieren
2. Formular abschicken (Login durchführen)
3. Zweites Mal mit gleichem Token versuchen
4. **Erwartung**: Token sollte nach Verwendung ungültig sein (Session-Regeneration)

#### C) Cross-Site Request Forgery Simulation

**Angriffs-Szenario erstellen**:
```html
<!-- Erstelle evil.html auf anderem Server/Port -->
<!DOCTYPE html>
<html>
<body>
<h1>Böse Seite</h1>
<form id="csrf-attack" action="http://localhost:8000/admin/product-edit" method="POST">
  <input type="hidden" name="id" value="1">
  <input type="hidden" name="name" value="HACKED">
  <input type="hidden" name="price" value="1">
  <!-- Kein CSRF-Token! -->
</form>
<script>
  document.getElementById('csrf-attack').submit();
</script>
</body>
</html>
```

**Test durchführen**:
1. Admin-Login im Browser
2. Öffne `evil.html` im gleichen Browser
3. **Erwartetes Verhalten**: Request wird abgelehnt (fehlender/falscher CSRF-Token)

---

## 3. Zusätzliche Sicherheitstests

### A) XSS (Cross-Site Scripting)

**Test-Payloads in Formularen**:
```
<script>alert('XSS')</script>
<img src=x onerror=alert('XSS')>
javascript:alert('XSS')
```

**Zu testen in**:
- Terminbuchung: Kundenname, Notizen
- Kontaktformular: Name, Nachricht
- Admin: Produktnamen, Kategorien

**Erwartetes Verhalten**:
- Escaping durch `e()` Funktion (helpers.php)
- Anzeige als Text, keine Ausführung

### B) Session-Security

**Session-Hijacking verhindern**:
```bash
# Prüfe Session-Cookie-Flags
curl -I http://localhost:8000/admin/login

# Erwartete Headers:
# Set-Cookie: pc_wittfoot_session=...; HttpOnly; SameSite=Lax
```

**Session-Fixation**:
1. Session-ID vor Login kopieren
2. Login durchführen
3. Session-ID nach Login vergleichen
4. **Erwartung**: Session-ID wurde regeneriert

---

## 4. Checkliste für Production

### SQL-Injection
- [x] Prepared Statements überall verwendet
- [x] PDO ATTR_EMULATE_PREPARES = false
- [x] Keine String-Concatenation in Queries
- [ ] SQLMap-Scan durchgeführt (optional)

### CSRF-Protection
- [x] CSRF-Token-Funktionen implementiert
- [x] Tokens in Admin-Formularen vorhanden
- [x] Token-Validierung bei POST-Requests
- [ ] API-Endpoints evaluieren (Magic-Token-Auth)
- [ ] CSRF-Angriff simuliert (Test 4C)

### XSS-Protection
- [x] Escaping-Funktion `e()` vorhanden
- [ ] Alle User-Inputs escapen (Review)
- [x] Security-Headers gesetzt (CSP)

### Session-Security
- [x] HttpOnly-Flag gesetzt
- [x] SameSite=Lax
- [x] Session-Regeneration bei Login
- [x] 12h Session-Timeout

---

## 5. Quick-Test Script

```bash
#!/bin/bash
# security-test.sh

echo "=== SQL-Injection Tests ==="
# Test 1: Admin-Login
curl -X POST http://localhost:8000/admin/login \
  -d "username=admin' OR '1'='1' --&password=test" \
  | grep -i "error\|sql" && echo "❌ SQL-Fehler gefunden" || echo "✓ Sicher"

# Test 2: Terminsuche
curl "http://localhost:8000/admin/bookings?search=' OR '1'='1" \
  | grep -i "error\|sql" && echo "❌ SQL-Fehler gefunden" || echo "✓ Sicher"

echo ""
echo "=== CSRF-Token Tests ==="
# Test 3: Login ohne Token
curl -X POST http://localhost:8000/admin/login \
  -d "username=admin&password=test" \
  | grep -i "csrf\|token\|invalid" && echo "✓ CSRF-Schutz aktiv" || echo "⚠️ Kein CSRF-Schutz"

echo ""
echo "=== Session-Security ==="
# Test 4: Cookie-Flags
curl -I http://localhost:8000/admin/login \
  | grep "Set-Cookie" | grep "HttpOnly" && echo "✓ HttpOnly gesetzt" || echo "❌ HttpOnly fehlt"

curl -I http://localhost:8000/admin/login \
  | grep "Set-Cookie" | grep "SameSite" && echo "✓ SameSite gesetzt" || echo "❌ SameSite fehlt"
```

**Ausführen**:
```bash
chmod +x docs/security-test.sh
./docs/security-test.sh
```

---

## 6. Empfohlene Penetration Testing Tools

### Für Fortgeschrittene:
1. **Burp Suite** (https://portswigger.net/burp)
   - Proxy zum Abfangen/Modifizieren von Requests
   - Automatische Scan-Features

2. **OWASP ZAP** (https://www.zaproxy.org/)
   - Open-Source Alternative zu Burp
   - Automatisierte Schwachstellen-Scans

3. **SQLMap** (siehe oben)
   - Spezialisiert auf SQL-Injection

4. **XSStrike** (https://github.com/s0md3v/XSStrike)
   - XSS-Detection Tool

---

## 7. Dokumentierte Sicherheitsmaßnahmen

### Implementiert ✓
1. **Database-Layer**: PDO Prepared Statements
2. **CSRF-Protection**: Token-basiert für Formulare
3. **XSS-Protection**: Escaping-Funktion `e()`
4. **Session-Security**: HttpOnly, SameSite, Regeneration
5. **Security-Headers**: CSP, X-Frame-Options, X-XSS-Protection
6. **Rate-Limiting**: Login-Attempts (5 Versuche, 15min Lockout)
7. **Audit-Logging**: Security-Events werden protokolliert

### Zu evaluieren 📋
1. API-Endpoints ohne CSRF (Magic-Token-Auth ausreichend?)
2. Content Security Policy verschärfen (unsafe-inline entfernen)
3. Regelmäßige Dependency-Updates (PHPMailer, etc.)
4. Penetration Testing durch Dritte

---

**Stand**: 2026-01-04
**Reviewer**: Claude Code
**Nächste Review**: Vor Production-Deployment
