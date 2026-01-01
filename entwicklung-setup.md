# Entwicklungsumgebung Setup - PC-Wittfoot

**Version:** 1.0
**Erstellt:** 2025-12-31

---

## 1. Lokale Entwicklungsumgebung

### Benötigte Software

#### Windows (mit WSL2):
```bash
# Option A: In WSL2 (empfohlen)
sudo apt update
sudo apt install php8.2 php8.2-mysql php8.2-curl php8.2-gd php8.2-mbstring
sudo apt install mysql-server
sudo apt install git

# Apache installieren
sudo apt install apache2
sudo systemctl start apache2
```

#### Oder: XAMPP für Windows
- Download: https://www.apachefriends.org/de/index.html
- Version: Mit PHP 8.2
- Komponenten: Apache, MySQL, PHP

#### Weitere Tools:
- **Code-Editor:** VS Code (empfohlen) oder PhpStorm
- **FTP-Client:** FileZilla oder WinSCP
- **Browser:** Chrome/Firefox (mit DevTools)
- **Git:** Für Versionskontrolle

---

## 2. Verzeichnis-Struktur erstellen

### Lokales Projektverzeichnis:

```bash
# Projekt-Root erstellen
mkdir -p ~/projekte/c-d/src
cd ~/projekte/c-d/src

# Basis-Struktur anlegen
mkdir -p {assets/{css,js,images,fonts},pages,shop,admin,api,core,uploads,cache,content}
mkdir -p shop/classes admin/classes
mkdir -p content/{blog,pages}
mkdir -p uploads/{products,temp}
mkdir -p assets/images/icons
```

**Oder für Windows (CMD):**
```cmd
cd C:\Users\[username]\projekte\c-d
mkdir src
cd src
mkdir assets pages shop admin api core uploads cache content
mkdir assets\css assets\js assets\images assets\fonts
mkdir shop\classes admin\classes
mkdir content\blog content\pages
mkdir uploads\products uploads\temp
```

---

## 3. VS Code Setup (Empfohlen)

### Extensions installieren:
- **PHP Intelephense** - PHP Autocomplete
- **Live Server** - Lokaler Webserver
- **Prettier** - Code-Formatierung
- **GitLens** - Git-Integration
- **EditorConfig** - Konsistente Code-Styles
- **Path Intellisense** - Dateipfad-Autocomplete

### .editorconfig erstellen:
```ini
# .editorconfig
root = true

[*]
charset = utf-8
end_of_line = lf
insert_final_newline = true
indent_style = space
indent_size = 4
trim_trailing_whitespace = true

[*.{html,css,js}]
indent_size = 2

[*.md]
trim_trailing_whitespace = false
```

---

## 4. Git Repository initialisieren

```bash
cd ~/projekte/c-d/src
git init
git config user.name "Dein Name"
git config user.email "deine@email.de"
```

### .gitignore erstellen:
```gitignore
# .gitignore

# Konfiguration (mit sensiblen Daten)
core/config.php

# Uploads
uploads/*
!uploads/.gitkeep

# Cache
cache/*
!cache/.gitkeep

# IDE
.vscode/
.idea/
*.swp
*.swo
*~

# OS
.DS_Store
Thumbs.db

# Logs
*.log
error_log

# Temporäre Dateien
*.tmp
*.temp
```

---

## 5. Responsive Breakpoints (Standard)

```css
/* Mobile-First Ansatz */

/* Extra Small (Smartphones, Portrait) */
/* Default: 0-575px */

/* Small (Smartphones, Landscape) */
@media (min-width: 576px) { ... }

/* Medium (Tablets) */
@media (min-width: 768px) { ... }

/* Large (Desktops) */
@media (min-width: 992px) { ... }

/* Extra Large (Large Desktops) */
@media (min-width: 1200px) { ... }

/* XXL (Ultra-wide) */
@media (min-width: 1400px) { ... }
```

### Unsere Breakpoints:
- **Mobile:** 0-767px (Default)
- **Tablet:** 768px-991px
- **Desktop:** 992px+
- **Wide Desktop:** 1400px+

---

## 6. CSS-Variablen (Design-Tokens)

```css
/* variables.css - Zentrale Design-Tokens */

:root {
  /* === Farben - Lightmode === */

  /* Grautöne */
  --color-dark: #2C3E50;
  --color-gray: #5A5A5A;
  --color-light-gray: #E8E8E8;
  --color-lighter-gray: #FAFAFA;
  --color-white: #FFFFFF;

  /* Akzentfarben */
  --color-primary: #8BC34A;        /* Logo-Grün */
  --color-primary-hover: #7CB342;
  --color-secondary: #E67E22;      /* Logo-Orange */
  --color-secondary-hover: #D35400;

  /* Funktionale Farben */
  --color-success: #4CAF50;
  --color-warning: #FFC107;
  --color-error: #F44336;
  --color-info: #2196F3;

  /* === Typography === */
  --font-family-base: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  --font-family-heading: var(--font-family-base);
  --font-family-mono: "Courier New", monospace;

  --font-size-base: 16px;
  --font-size-sm: 14px;
  --font-size-lg: 18px;
  --font-size-xl: 20px;

  --font-size-h1: 2.5rem;    /* 40px */
  --font-size-h2: 2rem;      /* 32px */
  --font-size-h3: 1.75rem;   /* 28px */
  --font-size-h4: 1.5rem;    /* 24px */
  --font-size-h5: 1.25rem;   /* 20px */
  --font-size-h6: 1rem;      /* 16px */

  --font-weight-normal: 400;
  --font-weight-medium: 500;
  --font-weight-bold: 700;

  --line-height-base: 1.6;
  --line-height-heading: 1.2;

  /* === Spacing === */
  --space-xs: 0.25rem;   /* 4px */
  --space-sm: 0.5rem;    /* 8px */
  --space-md: 1rem;      /* 16px */
  --space-lg: 1.5rem;    /* 24px */
  --space-xl: 2rem;      /* 32px */
  --space-xxl: 3rem;     /* 48px */
  --space-xxxl: 4rem;    /* 64px */

  /* === Layout === */
  --container-max-width: 1200px;
  --container-padding: var(--space-lg);

  /* === Border === */
  --border-radius-sm: 4px;
  --border-radius-md: 8px;
  --border-radius-lg: 12px;
  --border-width: 2px;

  /* === Shadows === */
  --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
  --shadow-md: 0 4px 8px rgba(0, 0, 0, 0.12);
  --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.15);

  /* === Transitions === */
  --transition-fast: 0.15s ease;
  --transition-base: 0.3s ease;
  --transition-slow: 0.5s ease;

  /* === Z-Index === */
  --z-dropdown: 1000;
  --z-sticky: 1020;
  --z-fixed: 1030;
  --z-modal-backdrop: 1040;
  --z-modal: 1050;
  --z-tooltip: 1060;
}

/* === Darkmode === */
@media (prefers-color-scheme: dark) {
  :root:not([data-theme="light"]) {
    --color-dark: #E8E8E8;
    --color-gray: #B8B8B8;
    --color-light-gray: #2C3E50;
    --color-lighter-gray: #1A1F26;
    --color-white: #0F1419;

    /* Akzentfarben bleiben gleich */
  }
}

/* Manueller Darkmode-Toggle */
[data-theme="dark"] {
  --color-dark: #E8E8E8;
  --color-gray: #B8B8B8;
  --color-light-gray: #2C3E50;
  --color-lighter-gray: #1A1F26;
  --color-white: #0F1419;
}
```

---

## 7. Basis-HTML-Template (Responsive)

```html
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PC-Wittfoot UG - Ihr IT-Partner">
    <title>PC-Wittfoot UG - IT-Service & Reparatur</title>

    <!-- Preload kritische Assets -->
    <link rel="preload" href="/assets/css/main.css" as="style">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/assets/css/variables.css">
    <link rel="stylesheet" href="/assets/css/reset.css">
    <link rel="stylesheet" href="/assets/css/base.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <link rel="stylesheet" href="/assets/css/layout.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/assets/images/favicon.png">

    <!-- Open Graph / Social Media -->
    <meta property="og:title" content="PC-Wittfoot UG">
    <meta property="og:description" content="Ihr IT-Partner in der Region">
    <meta property="og:image" content="/assets/images/og-image.jpg">
    <meta property="og:type" content="website">
</head>
<body>
    <!-- Skip Link (Barrierefreiheit) -->
    <a href="#main" class="skip-link">Zum Hauptinhalt springen</a>

    <!-- Header -->
    <header role="banner">
        <!-- Navigation hier -->
    </header>

    <!-- Main Content -->
    <main id="main" role="main">
        <!-- Seiteninhalt hier -->
    </main>

    <!-- Footer -->
    <footer role="contentinfo">
        <!-- Footer hier -->
    </footer>

    <!-- Scripts (am Ende für Performance) -->
    <script src="/assets/js/darkmode.js"></script>
    <script src="/assets/js/main.js" defer></script>
</body>
</html>
```

---

## 8. Nächste Schritte

1. ✅ Entwicklungsumgebung Setup dokumentiert
2. ⏳ Ordnerstruktur lokal erstellen
3. ⏳ Git initialisieren
4. ⏳ CSS-Reset & Basis-Styles erstellen
5. ⏳ Responsive Grid-System erstellen
6. ⏳ Komponenten-Bibliothek aufbauen
7. ⏳ Startseite entwickeln

---

## 9. Hilfreiche Befehle

### Apache starten (WSL2):
```bash
sudo service apache2 start
```

### MySQL starten (WSL2):
```bash
sudo service mysql start
```

### PHP-Version prüfen:
```bash
php -v
```

### Lokale Website öffnen:
```
http://localhost/c-d/
```

### Git-Workflow:
```bash
git add .
git commit -m "Beschreibung der Änderungen"
```

---

**Bereit zum Coden!** 💻
