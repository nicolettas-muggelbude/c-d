# HelloCash Integration

## Inhaltsverzeichnis
- API-Integration
- Kundensuche & Synchronisation
- Produkt-Sync
- Kassenanbindung bei Bestellungen
- Transaktionale Bestellungen
- Steuersätze & HelloCash

Siehe auch: `docs/HELLOCASH_INTEGRATION.md` und `docs/hellocash.apib`

## Session 2026-01-01 (Fortsetzung): HelloCash-Kundensuche & Dark Mode

### Erreichte Ziele ✅

#### 1. HelloCash-Kundensuche in Admin-Kalender
**Problem:** Neue Termine mussten manuell eingegeben werden, obwohl Kundendaten bereits in HelloCash existieren.

**Lösung:**
- **API-Endpoint:** `/api/hellocash-search`
  - Suche nach Name, Email oder Telefonnummer
  - Gibt bis zu 10 Ergebnisse zurück
  - Auto-Complete Dropdown in Modal-Formularen
- **Neue Methode:** `HelloCashClient::getAllUsers($limit = 1000)`
  - Ermöglicht Namenssuche über alle User
- **Integration:**
  - Kalenderansicht (`/admin/booking-calendar`)
  - Wochenansicht (`/admin/booking-week`)
  - Automatisches Ausfüllen beim Klick auf Ergebnis
- **Features:**
  - Live-Suche mit min. 2 Zeichen
  - Enter-Taste unterstützt
  - Dropdown schließt bei Klick außerhalb
  - Zeigt Name, Email und Telefon im Dropdown

**Dateien:**
- `src/api/hellocash-search.php` - Search API
- `src/core/HelloCashClient.php` - getAllUsers() Methode
- `src/admin/booking-calendar-v2.php` - Integration
- `src/admin/booking-week.php` - Integration

#### 2. Multi-Stunden-Zeiträume in Wochenansicht
**Problem:** Termine konnten nur 1 Stunde lang sein.

**Lösung:**
- **Datenbank:** `booking_end_time` Spalte hinzugefügt
- **Backend-Berechnung:**
  - Automatisch +1 Stunde wenn keine Endzeit
  - Speichert Start-/End-Stunde und Dauer
- **Visuelle Darstellung:**
  - Absolute Positionierung über mehrere Stunden
  - Dynamische Höhe: `(Dauer * 60px) - 1px`
  - Zeitanzeige: "11:00 - 14:00"
- **Modal-Formular:**
  - "Von (Uhrzeit)" und "Bis (Uhrzeit)" Felder
  - Optionale Endzeit-Angabe

**Dateien:**
- `database/add-booking-end-time.sql` - Schema-Update
- `src/admin/booking-week.php` - Implementierung

#### 3. Admin-Bereich Erweiterungen
**Neue Features:**
- **Admin-Notizen-Feld** (`admin_notes`)
  - Interne Notizen, nicht für Kunden sichtbar
  - In allen Termin-Formularen verfügbar
- **Verschiedene Terminarten:**
  - `fixed` - Reguläre Termine mit Zeit
  - `walkin` - Walk-in ohne feste Zeit
  - `internal` - Interne Notizen (nur Admin)
  - `blocked` - Gesperrte Zeiträume
- **Modal-basierte Bearbeitung:**
  - Schnelles Bearbeiten ohne Seitenwechsel
  - AJAX-basierte Speicherung
  - Formular passt sich Terminart an
- **Kalenderansicht als Standard:**
  - Dashboard verlinkt auf `/admin/booking-calendar`
  - Übersichtlichere Darstellung

**Dateien:**
- `database/add-admin-notes-and-blocking.sql` - Schema
- `src/admin/booking-calendar-v2.php` - Neue Version
- `src/admin/booking-week.php` - Wochenansicht
- `src/admin/index.php` - Dashboard-Update

#### 4. Globaler Dark Mode
**Problem:** Dark Mode war bisher nur lokal in einzelnen Seiten implementiert.

**Lösung:**
- **Globales System nutzen:**
  - `data-theme="dark"` Attribut am HTML-Element
  - Toggle im Header für gesamte Anwendung
  - localStorage-Speicherung
- **Admin-spezifische Styles:**
  - Kalender-Grid & Zellen
  - Wochen-Grid & Zeitslots
  - Modal-Dialoge & Formulare
  - Dropdown-Suchergebnisse
  - Footer-Styling
- **Konsolidierung:**
  - Alle Dark Mode Styles in `/assets/css/components.css`
  - Lokale Implementierungen entfernt
  - Konsistentes Design über alle Seiten

**Dateien:**
- `src/assets/css/components.css` - Admin Dark Mode Styles
- `src/admin/booking-calendar-v2.php` - Lokale Styles entfernt
- `src/admin/booking-week.php` - Lokale Styles entfernt

#### 5. Bugfixes & Verbesserungen
- ✅ **Admin-Login:** Passwort-Hash korrigiert (admin123)
- ✅ **Database-Methoden:** `execute()` → `update()` korrigiert
- ✅ **PHP 8.1+ Kompatibilität:** `strftime()` → `DateTime` ersetzt
- ✅ **Column-Namen:** `status` → `order_status` korrigiert
- ✅ **Dark Mode Footer:** Footer wird jetzt korrekt dunkel dargestellt

**Dateien:**
- `database/create-admin-user.sql` - Password-Hash
- `src/admin/booking-settings.php` - Method-Namen
- `src/admin/booking-calendar-v2.php` - strftime ersetzt
- `src/admin/index.php` - Column-Namen

### Technische Details

#### HelloCash-Suche API
```php
// Request
POST /api/hellocash-search
{
    "action": "search",
    "query": "mustermann"  // oder Email/Telefon
}

// Response
{
    "success": true,
    "results": [
        {
            "user_id": 123,
            "firstname": "Max",
            "lastname": "Mustermann",
            "company": "Firma GmbH",
            "email": "max@example.com",
            "phone": "+49 170 1234567",
            "display_name": "Max Mustermann (Firma GmbH)"
        }
    ],
    "count": 1
}
```

#### Multi-Stunden-Zeiträume
```php
// Datenbank
booking_time: '11:00'
booking_end_time: '14:00'

// Berechnung
$startHour = 11;
$endHour = 14;
$duration = 3; // Stunden
$heightPixels = (3 * 60) - 1; // = 179px

// CSS
<div style="height: 179px; position: absolute; top: 1px;">
    <strong>Max M.</strong>
    <div>11:00 - 14:00</div>
</div>
```

#### Dark Mode Integration
```css
/* Globale Dark Mode Styles */
[data-theme="dark"] .calendar-grid {
    background-color: #404040;
    border-color: #404040;
}

[data-theme="dark"] .calendar-day {
    background-color: #2d2d2d;
    color: #e0e0e0;
}

[data-theme="dark"] footer {
    background: #1a1a1a;
    color: #b0b0b0;
}
```

### Projektstand nach Session

#### Admin-Features komplett ✅
- ✅ Dashboard mit Statistiken
- ✅ Termineinstellungen konfigurierbar
- ✅ Terminübersicht mit Filter & Suche
- ✅ Termin-Details mit Status-Verwaltung
- ✅ Kalenderansicht (Monat)
- ✅ Wochenansicht mit Stundenraster
- ✅ Modal-basierte Termin-Bearbeitung
- ✅ HelloCash-Kundensuche
- ✅ Multi-Stunden-Zeiträume
- ✅ Admin-Notizen & Terminarten
- ✅ Dark Mode global integriert

#### Nächste Session
- Blog-System vervollständigen
- PayPal-Integration
- Bewertungen einbinden

