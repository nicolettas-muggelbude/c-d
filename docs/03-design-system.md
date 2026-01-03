# Design-System

## Inhaltsverzeichnis
- Design-Entscheidungen
- Farbpalette (Option B - Neutral mit Akzenten)
- Darkmode (Hybrid: automatisch + umschaltbar)
- Barrierefreiheit (WCAG 2.1 Level AA)
- Design-Prinzipien
- Typografie & Spacing

## Design-Entscheidungen (2025-12-31)

### Farbpalette: Option B - Neutral mit Akzenten ✅
- **Hauptfarben:** Grautöne (#2C3E50, #5A5A5A, #E8E8E8)
- **Akzentfarben:** Logo-Grün (#8BC34A), Logo-Orange (#E67E22)
- **Basis:** Weiß (#FFFFFF) / Lightmode-Hintergrund
- **Charakteristik:** Modern, klar, professionell, hohe Kontraste

### Darkmode: Hybrid (automatisch + umschaltbar) ✅
- **Standard:** System-Präferenz (prefers-color-scheme)
- **Optional:** Manueller Toggle-Button
- **Speicherung:** User-Präferenz im localStorage
- **Darkmode-Farben:**
  - Hintergrund: #0F1419, #1A1F26
  - Text: #E8E8E8, #B8B8B8
  - Akzente: Grün/Orange bleiben gleich

### Barrierefreiheit: WCAG 2.1 Level AA (Pflicht!) ✅
- **Kontrast-Verhältnisse:**
  - Normal-Text: min. 4.5:1
  - Großer Text (18pt+): min. 3:1
  - UI-Komponenten: min. 3:1
- **Navigation:**
  - Vollständige Keyboard-Navigation
  - Skip-Links
  - Fokus-Indikatoren (sichtbar!)
- **Semantik:**
  - Korrektes HTML5 (header, nav, main, article, aside, footer)
  - ARIA-Labels wo nötig
  - Landmark-Regions
- **Content:**
  - Alt-Texte für alle Bilder
  - Keine reine Farb-Kodierung
  - Screen-Reader optimiert
  - Lesbare Schriftgrößen (min. 16px)
- **Formulare:**
  - Labels für alle Inputs
  - Error-Messages klar zugeordnet
  - Validierung mit visuellen + Text-Feedback

## Projektfortschritt

### Phase 1: Planung ✅ (Abgeschlossen 2025-12-31)
- ✅ Anforderungen geklärt
- ✅ Server-Spezifikationen dokumentiert
- ✅ Farbpalette gewählt: Option B (Neutral + Akzente)
- ✅ Darkmode: Hybrid (automatisch + umschaltbar)
- ✅ Barrierefreiheit: WCAG 2.1 Level AA
- ✅ Technische Architektur erstellt
- ✅ Sitemap & Wireframes fertig

### Phase 2: Entwicklung 🚧 (Start: 2025-12-31)
- ✅ Entwicklungsumgebung aufgesetzt (PHP 8.2, MySQL)
- ✅ Basis-Struktur erstellt
- ✅ Responsive CSS-Framework (Darkmode, Hamburger-Menu)
- ✅ Datenbank-Schema implementiert (11 Produkte, 8 Kategorien)
- ✅ Core-Funktionen entwickelt (PDO, Helpers, Config)
- ✅ Test-Seite funktioniert mit echten DB-Daten
- ✅ Template-System (Header/Footer Includes)
- ✅ Router-System (.htaccess + router.php + server.php für Dev)
- ✅ Startseite mit echten Produkten aus DB
- ✅ Shop-Seite mit Filtern (Kategorie, Marke, Zustand, Suche, Pagination)
- ✅ Produkt-Detail-Seite (Tabs, Spezifikationen, ähnliche Produkte, AJAX-Warenkorb)
- ✅ 404-Seite
- ✅ Vollständiges CSS (Components, Forms, Shop, Cart, Product-Detail, Checkout)
- ✅ Warenkorb-System komplett (Session-basiert, API, Counter im Header, Brutto/Netto-Toggle)
- ✅ Kontaktformular (Validierung, DB-Speicherung, CSRF-Schutz)
- ✅ Rechtliche Seiten (Impressum, Datenschutz, AGB, Widerruf)
- ✅ Leistungen-Seite
- ✅ Checkout/Kasse (Kundendaten, Lieferart, Zahlungsart, Bestellabwicklung)
- ✅ Bestellbestätigung (Order-Details, Zahlungsinformationen)
- ⏳ Blog-System (Übersicht + Post-Detail)
- ⏳ Termin-Seite (hellocash Integration)
- ⏳ PayPal-Integration (Zahlungsart vorhanden, aber noch nicht verbunden)
- ⏳ Admin-Interface

## Design-Prinzipien
- **Mobile-First:** Entwicklung beginnt mit Mobile-Layout
- **Responsive:** Breakpoints für Mobile, Tablet, Desktop
- **Touch-optimiert:** Min. 44x44px für alle interaktiven Elemente
- **Performance:** Optimierte Assets, lazy loading
- **Barrierefreiheit:** WCAG 2.1 AA von Anfang an

