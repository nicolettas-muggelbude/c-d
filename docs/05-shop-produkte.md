# Shop-System & Produktverwaltung

## Inhaltsverzeichnis
- Shop-System Implementierung
- Produktverwaltung
- CSV-Import System
- Kategorienverwaltung mit Löschschutz
- Steuersatz-Verwaltung (19%, 7%, 0%)
- Detaillierte Produktinformationen (Zustand, Garantie, Bilder)
- Produktdetailseite mit Galerie
- Warenkorb & Bestellabwicklung

## Session 2026-01-01: Shop-System, Produktverwaltung & CSV-Import

### Implementierte Features

#### 1. Shop-System: HelloCash Integration für Bestellungen
**Status:** ✅ Abgeschlossen

**Funktionsweise:**
- Kundendaten werden automatisch in HelloCash angelegt (findOrCreateUser)
- Bei Bestellung wird HelloCash-Rechnung erstellt (digital, mit Link)
- Rechnung wird per E-Mail an Kunde versendet
- Admin erhält Bestellbenachrichtigung

**Dateien:**
- `src/pages/kasse.php` - HelloCash-Integration beim Checkout
- `src/core/HelloCashClient.php` - createInvoice() Methode
- `src/core/EmailService.php` - Template-System für Shop-E-Mails

**Database:**
- Migration 006: `hellocash_invoice_id`, `hellocash_invoice_number`
- Migration 007: `hellocash_invoice_link`
- Migration 008: E-Mail-Templates (order_confirmation, order_notification)
- Migration 009: `delivery_method` ENUM ('billing', 'pickup', 'shipping')
- Migration 010: Entfernung 'cash' Zahlungsart

**E-Mail-Templates:**
- Bestellbestätigung (Kunde) mit Rechnung-Link
- Bestellbenachrichtigung (Admin)
- Konfigurierbar im Admin-Bereich
- Platzhalter: {customer_firstname}, {order_number}, {order_items}, {invoice_link_section}, etc.

#### 2. Bestellungen-Verwaltung
**Status:** ✅ Abgeschlossen

**Admin-Seiten:**
- `/admin/orders` - Übersicht mit Grid-Layout, Filtern (Status, Suche)
- `/admin/order/{id}` - Detailansicht mit Status-Änderung, Kundendaten, Positionen

**Features:**
- Responsive Grid-Layout (1/2/3 Spalten)
- Filter: Status (Neu, In Bearbeitung, Versandt, etc.)
- Suchfunktion (Bestellnummer, Name, E-Mail)
- Status-Badges mit Emojis
- Kompakte Sidebar-Cards mit `height: fit-content`
- Grid mit `align-items: start` für korrekte Footer-Position
- HelloCash-Rechnung-Link Integration

**Layout-Optimierungen:**
- Sidebar-Cards kompakt gestaltet
- Bestellinformationen von Tabelle zu div-Layout
- Status-Card minimal mit direktem Dropdown

#### 3. Produktverwaltung - Phase 1
**Status:** ✅ Abgeschlossen

**Database:**
- Migration 011: Erweiterte Felder für Produktverwaltung
  - `source` ENUM('csv_import', 'hellocash', 'manual')
  - `supplier_id`, `supplier_name`, `supplier_stock`
  - `in_showroom` (Verfügbar in Oldenburg)
  - `sync_with_hellocash`, `last_csv_sync`

**Admin-Seiten:**
- `/admin/products` - Übersicht mit Grid-Layout
- `/admin/product-edit` - Erstellen/Bearbeiten

**Features:**
- Filter: Quelle (Manuell/CSV/HelloCash), Status, Lagerbestand
- Bild-Upload mit Validierung (JPG, PNG, WEBP)
- Checkbox: "Verfügbar in Oldenburg" (statt "Ausstellung")
- Checkbox: "Mit HelloCash synchronisieren"
- Status-Badges: Aktiv, Oldenburg, Ausverkauft, Niedriger Bestand
- **Schutz:** Produkte mit Bestellungen können nicht gelöscht werden
- **Bulk-Delete:** Alle inaktiven/ausverkauften Produkte ohne Bestellungen löschen

**Reorganisierungs-Funktion:**
- Warnung-Box zeigt Anzahl löschbarer Produkte
- Bestätigungs-Dialog
- Löscht automatisch Produktbilder

#### 4. CSV-Import-System - Phase 2
**Status:** ✅ Kern-Funktionalität abgeschlossen, Cronjob ausstehend

**Database:**
- Migration 012: `suppliers` und `product_import_logs` Tabellen
- Foreign Key: `products.supplier_id` → `suppliers.id`

**Core-Komponenten:**
- `src/core/CSVImporter.php` - Flexibler Parser mit Spalten-Mapping
  - Unterstützt verschiedene Delimiter (Komma, Semikolon, Tab)
  - Encoding-Konvertierung (UTF-8, ISO-8859-1, Windows-1252)
  - Automatische Preis-Kalkulation mit Aufschlag
  - Import-Statistiken (neu/aktualisiert/übersprungen/Fehler)

**Admin-Seiten:**
- `/admin/suppliers` - Lieferanten-Übersicht mit Statistiken
- `/admin/supplier-edit` - Lieferant erstellen/bearbeiten
- `/admin/csv-import` - Import durchführen mit Historie

**Lieferanten-Konfiguration:**
- Name, Beschreibung
- CSV-URL oder lokaler Pfad
- CSV-Delimiter und Encoding
- Spalten-Mapping (Name, SKU, Preis, Lagerbestand, Beschreibung)
- Preis-Aufschlag in %
- Aktiv/Inaktiv Status

**Import-Workflow:**
1. CSV-Datei herunterladen (falls URL) oder lokal laden
2. Zeilen parsen mit konfiguriertem Mapping
3. Neue Produkte: Anlegen mit `source='csv_import'`, inaktiv
4. Bestehende Produkte: Aktualisieren (gleiche SKU + supplier_id)
5. Verkaufspreis = Lieferanten-Preis × (1 + Aufschlag/100)
6. Statistiken und Fehler-Log erstellen

**Import-Logs:**
- Status: running, completed, failed
- Statistiken: imported_count, updated_count, skipped_count, error_count
- Details: JSON mit Fehlermeldungen
- Dauer in Sekunden

**Dashboard-Integration:**
- Link "📦 Lieferanten & CSV-Import"

#### 5. Produkttypen-Konzept
**Hybrid-Ansatz für verschiedene Produktquellen:**

**1. CSV-Import (Dropshipping):**
- Stündlicher Import aus Lieferanten-CSV
- Bei Verkauf: Dynamisch zu HelloCash (Kategorie "Online-Shop")
- Kein Lagerbestand in HelloCash

**2. Ausstellungs-Artikel (Lieferanten vor Ort):**
- In HelloCash (Kategorie "Showroom")
- `in_showroom = 1`
- Mit Lagerbestand

**3. HelloCash-Artikel (eigene Ware):**
- Manuell ausgewählte HelloCash-Artikel für Shop
- Im Shop anzeigbar

### Offene Punkte

#### Cronjob-Script für CSV-Import
**Status:** ⏳ Ausstehend

**Anforderung:**
- Stündlicher automatischer Import
- Script: `/scripts/cron-csv-import.php`
- Durchläuft alle aktiven Lieferanten
- Ruft CSVImporter auf

**Alternativen ohne Cronjob:**
- Webhook-basierter Trigger
- Manueller Import über Admin-Interface

### Nächste Session

#### Priorität Hoch
- Cronjob-Script für CSV-Import erstellen
- PayPal-Integration (Zahlung abwickeln)
- CSV-Import testen mit echten Lieferanten-Daten

#### Priorität Mittel
- HelloCash-Sync für eigene Artikel (Phase 3)
- Dropshipping-API-Integration (falls Lieferant API bietet)
- Bewertungen einbinden (Google Reviews API)

#### Priorität Niedrig
- Newsletter-System
- Statistiken im Dashboard
- CSV-Export für Bestellungen


---

## Session 2026-01-02: Detaillierte Produktinformationen & Steuersätze

### Implementierte Features

#### 1. Kategorien-Löschschutz (Gefahrenzone)
**Status:** ✅ Abgeschlossen

**Implementierung:**
- Datei: `src/admin/category-edit.php`
- Löschung nur möglich, wenn:
  - Keine Produkte in der Kategorie vorhanden
  - Keine Unterkategorien existieren
- Visuelle Warnung mit Anzahl der Blocker
- Delete-Button wird nur bei Erfüllung aller Bedingungen angezeigt

**Code:**
```php
// Prüfen ob Kategorie Produkte hat
$product_count = $db->querySingle(
    "SELECT COUNT(*) as count FROM products WHERE category_id = :id",
    [':id' => $category_id]
);
// Prüfen ob Unterkategorien existieren
$sub_count = $db->querySingle(
    "SELECT COUNT(*) as count FROM categories WHERE parent_id = :id",
    [':id' => $category_id]
);
$can_delete = empty($delete_blockers);
```

#### 2. Steuersatz-Verwaltung
**Status:** ✅ Abgeschlossen

**Migration:** `database/migrations/017_product_tax_rate.sql`
```sql
ALTER TABLE products
ADD COLUMN tax_rate DECIMAL(5,2) DEFAULT 19.00 COMMENT 'Steuersatz in Prozent' AFTER price,
ADD INDEX idx_tax_rate (tax_rate);
```

**Features:**
- Standard: 19% (Regelsteuersatz)
- Optional: 7% (ermäßigt), 0% (steuerfrei)
- Dropdown in Produktverwaltung (`src/admin/product-edit.php`)
- CSV-Import-Support mit Validierung (`src/core/CSVImporter.php`)
- HelloCash-Export mit dynamischen Steuersätzen (`src/pages/kasse.php`)

**CSV-Validierung:**
```php
$tax_rate = 19.00;
if (!empty($data['tax_rate'])) {
    $csv_tax = (float)$data['tax_rate'];
    if (in_array($csv_tax, [0, 7, 19])) {
        $tax_rate = $csv_tax;
    }
}
```

#### 3. Phase 1 - Erweiterte Produktfelder
**Status:** ✅ Abgeschlossen

**Migration:** `database/migrations/018_product_details.sql`
```sql
ALTER TABLE products
ADD COLUMN warranty_months INT DEFAULT 24 COMMENT 'Garantie in Monaten' AFTER condition_type,
ADD COLUMN images JSON DEFAULT NULL COMMENT 'Zusätzliche Produktbilder (bis zu 5 URLs)' AFTER image_url;
```

**Neue Felder:**

1. **Artikelzustand** (`condition_type` - bereits vorhanden):
   - ✨ Neu
   - 🔧 Refurbished
   - 📦 Gebraucht
   - ENUM-Validierung im CSV-Import

2. **Garantie** (`warranty_months`):
   - Standard: 24 Monate
   - Range: 0-60 Monate
   - Input in `product-edit.php`
   - CSV-Import mit Validierung

3. **Zusätzliche Bilder** (`images`):
   - JSON-Array mit bis zu 5 URLs
   - Input-Felder in `product-edit.php`
   - CSV-Mapping: `image1` bis `image5`
   - Anzeige in Produktgalerie

**CSV-Import Erweiterungen:**
- `src/admin/supplier-edit.php`: Mapping-Felder hinzugefügt
- `src/core/CSVImporter.php`: Verarbeitung mit Validierung
- JSON-Encoding für Bilder-Array

#### 4. Phase 2 - Detaillierte Produktansicht
**Status:** ✅ Abgeschlossen

**Datei:** `src/pages/produkt-detail.php`

**Features:**

1. **Bildergalerie:**
   - Hauptbild-Anzeige
   - Thumbnail-Navigation (Hauptbild + bis zu 5 zusätzliche)
   - JavaScript Click-Handler für Bildwechsel
   - Responsive Design mit CSS Grid

2. **Trust-Badges:**
   ```php
   <?php if ($product['free_shipping']): ?>
       <div class="trust-item">
           <span class="trust-icon">📦</span>
           <span>Versandkostenfrei</span>
       </div>
   <?php endif; ?>
   ```
   - Versandkostenfrei-Badge
   - Garantie-Anzeige (dynamisch)
   - Oldenburg-Verfügbarkeit

3. **Erweiterte Produktinformationen:**
   - Tab-System für übersichtliche Darstellung
   - "Garantie & Lieferung" Tab mit Details
   - Zustandsbeschreibung mit farbigen Badges
   - Dynamische Steuersatz-Anzeige

4. **Thumbnail-Navigation:**
   ```javascript
   document.querySelectorAll('.thumbnail').forEach(thumb => {
       thumb.addEventListener('click', function() {
           const imageUrl = this.dataset.image;
           document.getElementById('main-product-image').src = imageUrl;
           document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
           this.classList.add('active');
       });
   });
   ```

#### 5. Darkmode-Support für URL-Inputs
**Status:** ✅ Abgeschlossen

**Problem:**
- URL-Eingabefelder wurden nicht im CSS gestylt
- Fehlende Theme-Unterstützung → weiße Schrift auf weißem Grund
- Zu große Abstände zwischen Feldern

**Lösung:**

1. **CSS erweitert** (`src/assets/css/components.css`):
   ```css
   .form-group input[type="text"],
   .form-group input[type="email"],
   .form-group input[type="tel"],
   .form-group input[type="url"],  /* ← NEU */
   .form-group input[type="password"],
   .form-group input[type="number"],
   .form-group input[type="date"],
   .form-group input[type="time"],
   .form-group select,
   .form-group textarea {
       background: var(--bg-primary);
       color: var(--text-primary);
       /* ... */
   }
   ```

2. **Abstände optimiert** (`src/admin/product-edit.php`):
   ```php
   <div class="form-group" style="margin-bottom: 0.75rem;">
       <label for="image_url_<?= $i ?>">Bild <?= $i ?></label>
       <input type="url" id="image_url_<?= $i ?>" name="image_url_<?= $i ?>" />
   </div>
   ```

**Resultat:**
- Korrekte Theme-Farben in Light- und Darkmode
- Kompaktere, übersichtlichere Darstellung
- Konsistentes Styling mit anderen Form-Elementen

### Geänderte Dateien

#### Backend
- `database/migrations/017_product_tax_rate.sql` - Steuersatz-Feld
- `database/migrations/018_product_details.sql` - Garantie & Bilder
- `src/core/CSVImporter.php` - Verarbeitung neuer Felder mit Validierung
- `src/core/Cart.php` - tax_rate zu Produktabfrage hinzugefügt

#### Admin-Interface
- `src/admin/category-edit.php` - Löschschutz mit Bedingungsprüfung
- `src/admin/product-edit.php` - Neue Felder (Steuersatz, Zustand, Garantie, 5 Bilder)
- `src/admin/products.php` - Anzeige neuer Felder in Übersicht
- `src/admin/supplier-edit.php` - CSV-Mappings erweitert

#### Frontend
- `src/pages/produkt-detail.php` - Komplett überarbeitet mit Galerie
- `src/pages/kasse.php` - Dynamische Steuersätze für HelloCash
- `src/assets/css/components.css` - URL-Input-Support für Darkmode

### Git-Commits

```bash
54e3842 - Kategorie-Löschschutz implementiert
2ce0f25 - Steuersatz-Feld hinzugefügt
a88af57 - Steuersatz in Admin-Interface integriert
9bc8795 - Steuersatz Migration + HelloCash Export
b95fb76 - Migration 018: Garantie & Bilder
ecd5716 - Phase 1: Admin-Interface für neue Felder
6607a70 - Phase 1: CSV-Import Erweiterung
a428bc1 - Phase 1: Validierung in CSVImporter
fd4cf02 - Phase 2: Detaillierte Produktansicht mit Galerie
df27e35 - Darkmode Fix Versuch 1 (nicht erfolgreich)
062f559 - Darkmode Fix Versuch 2 (teilweise)
501f590 - Darkmode Fix: form-group Struktur
c4e3356 - Fix: URL-Input Darkmode-Support + kompaktere Abstände
```

### Erkenntnisse & Best Practices

#### CSS-Typing für Inputs
**Problem:** Vergessen `input[type="url"]` in CSS-Selektoren einzubeziehen
**Lösung:** Alle Input-Typen explizit auflisten oder `input[type]` verwenden
**Lesson:** Bei Theme-Support alle verwendeten Input-Typen prüfen

#### Darkmode-Support
**Strategie:**
1. CSS-Variablen verwenden: `var(--bg-primary)`, `var(--text-primary)`
2. Standard-Klassen bevorzugen (z.B. `form-group`)
3. Inline-Styles nur für strukturelles Layout
4. Nie Farben inline überschreiben

#### Form-Abstände
**Kompakte Darstellung:**
- Standard `form-group` margin-bottom: ~1.5rem
- Für kompakte Listen: `margin-bottom: 0.75rem` inline überschreiben
- Alternative: Eigene CSS-Klasse `.form-group-compact`

#### JSON-Datenfelder
**Images-Array:**
```php
// Speichern
$images_json = !empty($images) ? json_encode($images) : null;

// Laden
$existing_images = [];
if (!empty($product['images'])) {
    $existing_images = json_decode($product['images'], true) ?: [];
}
```

**Vorteile:**
- Flexibel erweiterbar
- Keine Schema-Änderungen nötig
- Einfache Validierung

#### CSV-Import Validierung
**Pattern:**
```php
// Standard-Wert definieren
$field = $default_value;

// CSV-Wert prüfen und nur bei Validität überschreiben
if (!empty($data['field'])) {
    $csv_value = process($data['field']);
    if (is_valid($csv_value)) {
        $field = $csv_value;
    }
}
```

**Angewendet auf:**
- Steuersätze: nur 0, 7, 19 erlaubt
- Zustand: nur neu, refurbished, gebraucht
- Garantie: nur 0-60 Monate

### Nächste Session

#### Aktualisierte Prioritäten

**Priorität Hoch:**
- ✅ Detaillierte Produktinformationen (abgeschlossen)
- Cronjob-Script für CSV-Import erstellen
- PayPal-Integration (Zahlung abwickeln)
- CSV-Import testen mit echten Lieferanten-Daten

**Priorität Mittel:**
- HelloCash-Sync für eigene Artikel (Phase 3)
- Dropshipping-API-Integration (falls Lieferant API bietet)
- Bewertungen einbinden (Google Reviews API)

**Priorität Niedrig:**
- Newsletter-System
- Statistiken im Dashboard
- CSV-Export für Bestellungen

#### Optionale Erweiterungen

**Produktdetails:**
- Video-URLs für Produktvideos
- Technische Spezifikationen als JSON-Feld
- 360°-Ansichten für Produkte
- PDF-Downloads (Datenblätter, Handbücher)

**CSV-Import:**
- Import-Preview vor Ausführung
- Mapping-Vorlagen für häufige Formate
- Fehler-Export als CSV
- Automatische Kategorie-Erstellung

**Galerie:**
- Image-Lazy-Loading
- Lightbox für Vollbildansicht
- Zoom-Funktion
- Touch-Swipe für mobile Geräte

