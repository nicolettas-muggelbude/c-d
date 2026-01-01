# Sitemap - PC-Wittfoot Webseite

**Version:** 1.0
**Erstellt:** 2025-12-31

---

## 1. Hauptnavigation (Header)

```
┌─────────────────────────────────────────────────────────┐
│  [LOGO]  Home  |  Leistungen  |  Shop  |  News  |  Über uns  |  Kontakt  │
│                                      [Termin buchen] [🛒]    │
└─────────────────────────────────────────────────────────┘
```

### Navigation-Items:

1. **Home** → `/`
2. **Leistungen** → `/leistungen/` (mit Dropdown)
3. **Shop** → `/shop/`
4. **News/Blog** → `/news/`
5. **Über uns** → `/ueber-uns/`
6. **Kontakt** → `/kontakt/`
7. **[CTA] Termin buchen** → `/termin/` (hellocash Integration)
8. **[Icon] Warenkorb** → `/warenkorb/`

---

## 2. Vollständige Seitenstruktur

```
🏠 PC-Wittfoot Website
│
├── 📄 Home (/)
│   ├── Hero-Bereich (IT-Partner mit Herz)
│   ├── Service-Übersicht (6 Kacheln)
│   ├── Warum PC-Wittfoot? (USPs)
│   ├── Aktuell im Shop (4 Produkte)
│   ├── Google-Bewertungen
│   └── CTA: Termin buchen / Kontakt
│
├── 🛠️ Leistungen (/leistungen/)
│   ├── Übersicht (alle 6 Services)
│   ├── → Beratung (/leistungen/beratung/)
│   ├── → Projektierung (/leistungen/projektierung/)
│   ├── → Verkauf (/leistungen/verkauf/)
│   ├── → Diagnose (/leistungen/diagnose/)
│   ├── → Reparatur (/leistungen/reparatur/)
│   └── → Softwareentwicklung (/leistungen/softwareentwicklung/)
│
├── 🛒 Shop (/shop/)
│   ├── Übersicht (Produktliste mit Filter)
│   ├── → Kategorien
│   │   ├── Laptops & Notebooks
│   │   ├── Desktop-PCs
│   │   ├── Monitore
│   │   ├── Tablets & Handys
│   │   ├── Peripherie (Maus, Tastatur, etc.)
│   │   ├── Drucker
│   │   ├── Netzwerk
│   │   └── Zubehör (Tinte, Toner, Kabel)
│   ├── → Produkt-Detail (/shop/produkt/{slug}/)
│   ├── → Warenkorb (/warenkorb/)
│   ├── → Kasse (/kasse/)
│   └── → Bestellbestätigung (/bestellung/{id}/)
│
├── 📰 News/Blog (/news/)
│   ├── Blog-Übersicht
│   ├── → Einzelner Beitrag (/news/{slug}/)
│   └── → Archiv (nach Monat/Jahr)
│
├── ℹ️ Über uns (/ueber-uns/)
│   ├── Unsere Geschichte
│   ├── Das Team
│   ├── Baileys (Firmenhund) 🐕
│   ├── Unsere Philosophie
│   ├── Bewertungen & Auszeichnungen
│   └── Standort (Karte)
│
├── 📞 Kontakt (/kontakt/)
│   ├── Kontaktformular
│   ├── Alle Kontaktmöglichkeiten
│   │   ├── Telefon/Anrufbeantworter
│   │   ├── E-Mail
│   │   ├── Social Media (Facebook, Instagram)
│   │   ├── Messenger (WhatsApp Business, Telegram, Signal)
│   │   └── Online-Terminbuchung
│   ├── Öffnungszeiten
│   ├── Anfahrt (Google Maps)
│   └── FAQ
│
├── 📅 Termin buchen (/termin/)
│   ├── hellocash Kalender-Integration
│   ├── Terminauswahl
│   └── Bestätigung
│
├── 👤 Mein Konto (/konto/) [Optional, Phase 2]
│   ├── Login
│   ├── Registrierung
│   ├── Bestellhistorie
│   └── Profil
│
└── 📋 Rechtliches
    ├── Impressum (/impressum/)
    ├── Datenschutz (/datenschutz/)
    ├── AGB (/agb/)
    └── Widerrufsrecht (/widerrufsrecht/)
```

---

## 3. Service-Seiten im Detail

### 3.1 Beratung (/leistungen/beratung/)
- Private Kunden: Hardware & Software
- OpenSource-Schwerpunkt
- Verständliche Erklärungen
- **CTA:** Beratungstermin buchen

### 3.2 Projektierung (/leistungen/projektierung/)
- Zielgruppe: Gewerbe, Praxen, Kanzleien, Vereine
- Individuelle IT-Lösungen
- Bedarfsanalyse
- **CTA:** Projekt anfragen

### 3.3 Verkauf (/leistungen/verkauf/)
- Refurbished Hardware (Schwerpunkt)
- exone Neugeräte (Extracomputer)
- Alle Marken verfügbar
- **CTA:** Zum Shop / Anfrage stellen

### 3.4 Diagnose (/leistungen/diagnose/)
- Hardware, Windows, Linux, MacOS
- Drucker & Netzwerk
- Fehleranalyse
- **CTA:** Termin zur Diagnose

### 3.5 Reparatur (/leistungen/reparatur/)
- Fachwerkstatt vor Ort
- Partner für Tablets/Handys
- Fachgerechte Reparaturen
- **CTA:** Reparaturtermin buchen

### 3.6 Softwareentwicklung (/leistungen/softwareentwicklung/)
- Kleine Tools & Scripte
- Windows & Linux
- Nach Kundenbedarf
- **CTA:** Entwicklung anfragen

---

## 4. Footer-Struktur

```
┌─────────────────────────────────────────────────────────────────┐
│  FOOTER                                                          │
│  ┌──────────────┬──────────────┬──────────────┬──────────────┐  │
│  │ PC-Wittfoot  │  Leistungen  │   Shop       │   Kontakt    │  │
│  │              │              │              │              │  │
│  │ Über uns     │ Beratung     │ Kategorien   │ Telefon      │  │
│  │ Team         │ Reparatur    │ Neuheiten    │ E-Mail       │  │
│  │ Bewertungen  │ Diagnose     │ Angebote     │ WhatsApp     │  │
│  │ News         │ Verkauf      │              │ Social Media │  │
│  │              │ etc.         │              │ Termin       │  │
│  └──────────────┴──────────────┴──────────────┴──────────────┘  │
│                                                                  │
│  Social Media: [Facebook] [Instagram]                           │
│  Messenger: [WhatsApp] [Telegram] [Signal]                      │
│                                                                  │
│  © 2025 PC-Wittfoot UG  |  Impressum  |  Datenschutz  |  AGB   │
│  [Darkmode Toggle 🌙]                                           │
└─────────────────────────────────────────────────────────────────┘
```

---

## 5. Mobile Navigation

```
┌────────────────────────┐
│  [☰]  LOGO      [🛒]  │  ← Header (sticky)
└────────────────────────┘
        ↓ (bei Klick auf ☰)
┌────────────────────────┐
│  📱 Mobile Menu        │
│  ────────────────────  │
│  Home                  │
│  Leistungen       [▼]  │
│    → Beratung          │
│    → Reparatur         │
│    → ...               │
│  Shop                  │
│  News                  │
│  Über uns              │
│  Kontakt               │
│  ────────────────────  │
│  [Termin buchen]       │
│  🌙 Darkmode           │
└────────────────────────┘
```

---

## 6. Shop-Filter & Kategorien

### Filter-Optionen:
- **Kategorie** (siehe oben)
- **Zustand:** Neu / Refurbished / Gebraucht
- **Marke:** Alle Marken (Dell, HP, Lenovo, Apple, etc.)
- **Preis:** Slider (min-max)
- **Verfügbarkeit:** Auf Lager / Bestellbar
- **Sortierung:** Preis, Neuheit, Beliebtheit

---

## 7. Besondere Seiten

### 7.1 Baileys-Seite (/baileys/) [Optional, aber charmant!]
- Foto-Galerie von Baileys
- "Unsere Kunden lieben sie"
- Kundenmeinungen zu Baileys
- Vielleicht als Easter-Egg oder Unterseite von "Über uns"

### 7.2 Bewertungen (/bewertungen/)
- Alle Google-Bewertungen
- Kleinanzeigen.de Status
- Kundenstimmen

---

## 8. Admin-Bereich (nicht öffentlich)

```
/admin/
├── Login
├── Dashboard
├── Produkte verwalten
├── Bestellungen
├── Blog/News
├── Kontaktanfragen
├── Kalender (hellocash)
└── Einstellungen
```

---

## 9. URL-Struktur (SEO-optimiert)

**Beispiele:**
- `/` → Startseite
- `/leistungen/reparatur/` → Service-Seite
- `/shop/laptops/` → Shop-Kategorie
- `/shop/produkt/dell-latitude-e7470-refurbished/` → Produkt
- `/news/neue-exone-pcs-eingetroffen/` → Blog-Artikel
- `/termin/` → Terminbuchung

**Eigenschaften:**
- ✅ Sprechende URLs (kein `?id=123`)
- ✅ Kleinbuchstaben
- ✅ Bindestriche statt Unterstriche
- ✅ Keine Datei-Endungen (.html, .php)
- ✅ Trailing Slash (/) für Konsistenz

---

## 10. Breadcrumbs (Navigation-Hilfe)

**Beispiel Shop:**
```
Home > Shop > Laptops & Notebooks > Dell Latitude E7470 Refurbished
```

**Beispiel Leistung:**
```
Home > Leistungen > Reparatur
```

---

## 11. Call-to-Actions (CTAs)

### Primäre CTAs (grüner Button):
- "Termin buchen"
- "Jetzt anfragen"
- "Zum Shop"
- "In den Warenkorb"

### Sekundäre CTAs (orange Button):
- "Anrufen"
- "WhatsApp"
- "Mehr erfahren"

---

## 12. Anzahl Seiten (Gesamt)

**Statische Hauptseiten:** ~15-20
**Service-Seiten:** 6
**Shop-Seiten:** Dynamisch (Kategorien + Produkte)
**Blog:** Dynamisch
**Rechtliches:** 4

**Geschätzt für Phase 1:** ~25-30 eindeutige Templates/Layouts

---

## 13. Priorisierung (MVP = Minimum Viable Product)

### Phase 1 (MVP) - Must-Have:
✅ Home
✅ Leistungen (Übersicht + 6 Unterseiten)
✅ Shop (Basic mit 3-4 Kategorien)
✅ Kontakt (mit Formular)
✅ Über uns
✅ Termin buchen
✅ Impressum, Datenschutz, AGB

### Phase 2 - Nice-to-Have:
⏳ Blog/News-System
⏳ Erweiterte Shop-Filter
⏳ Kunden-Accounts
⏳ Baileys-Seite
⏳ Newsletter

---

**Feedback erwünscht:**
- Fehlt eine wichtige Seite?
- Gibt es Seiten, die nicht nötig sind?
- Soll die Struktur angepasst werden?
