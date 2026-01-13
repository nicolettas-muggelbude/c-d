<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($page_description ?? 'PC-Wittfoot UG - IT-Fachbetrieb mit Herz. Beratung, Verkauf, Reparatur, Softwareentwicklung. Refurbished Hardware & exone Neugeräte.') ?>">
    <meta name="author" content="PC-Wittfoot UG">
    <meta name="keywords" content="<?= e($page_keywords ?? 'PC Reparatur Oldenburg, IT Service, Refurbished Hardware, Computerreparatur, Softwareentwicklung') ?>">
    <title><?= e($page_title ?? 'PC-Wittfoot UG') ?></title>

    <!-- Open Graph / Facebook / LinkedIn -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e($page_url ?? 'https://pc-wittfoot.de' . $_SERVER['REQUEST_URI']) ?>">
    <meta property="og:title" content="<?= e($page_title ?? 'PC-Wittfoot UG') ?>">
    <meta property="og:description" content="<?= e($page_description ?? 'PC-Wittfoot UG - IT-Fachbetrieb mit Herz. Beratung, Verkauf, Reparatur, Softwareentwicklung. Refurbished Hardware & exone Neugeräte.') ?>">
    <meta property="og:image" content="<?= e($page_image ?? asset('images/og-image.png')) ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="de_DE">
    <meta property="og:site_name" content="PC-Wittfoot UG">
    <meta property="article:published_time" content="<?= e($page_published ?? '2024-01-01T00:00:00+01:00') ?>">
    <meta property="article:modified_time" content="<?= e($page_modified ?? date('c')) ?>">
    <meta property="article:author" content="PC-Wittfoot UG">

    <!-- Twitter / X -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?= e($page_url ?? 'https://pc-wittfoot.de' . $_SERVER['REQUEST_URI']) ?>">
    <meta name="twitter:title" content="<?= e($page_title ?? 'PC-Wittfoot UG') ?>">
    <meta name="twitter:description" content="<?= e($page_description ?? 'PC-Wittfoot UG - IT-Fachbetrieb mit Herz. Beratung, Verkauf, Reparatur, Softwareentwicklung. Refurbished Hardware & exone Neugeräte.') ?>">
    <meta name="twitter:image" content="<?= e($page_image ?? asset('images/og-image.png')) ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= e($page_url ?? 'https://pc-wittfoot.de' . $_SERVER['REQUEST_URI']) ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= asset('images/logo-square.svg') ?>">
    <link rel="alternate icon" type="image/png" href="<?= asset('images/favicon.png') ?>")

    <!-- CSS -->
    <link rel="stylesheet" href="<?= asset('css/variables.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/reset.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/base.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/buttons.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/components.css') ?>">
    <?php if (isset($extra_css)): ?>
        <?php foreach ($extra_css as $css): ?>
            <link rel="stylesheet" href="<?= asset($css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Wartungsmodus-Warnung (nur für Admins sichtbar) -->
    <?php if (defined('MAINTENANCE_ADMIN_BYPASS') && MAINTENANCE_ADMIN_BYPASS): ?>
        <div style="background: #ff9800; color: white; padding: 1rem; text-align: center; font-weight: bold; position: sticky; top: 0; z-index: 10000;">
            ⚠️ WARTUNGSMODUS AKTIV - Sie sind als Admin eingeloggt und können trotzdem zugreifen. Normale Besucher sehen die Wartungsseite.
        </div>
    <?php endif; ?>

    <!-- Skip-Link (Barrierefreiheit) -->
    <a href="#main" class="skip-link">Zum Hauptinhalt springen</a>

    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <!-- Logo -->
                <div class="logo">
                    <a href="<?= BASE_URL ?>">
                        <img src="<?= asset('images/logo-square.svg') ?>" alt="PC-Wittfoot - Zur Startseite">
                    </a>
                </div>

                <!-- Hamburger-Menu (Mobile/Tablet) -->
                <button class="hamburger" id="hamburger" aria-label="Menü öffnen" aria-expanded="false">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <!-- Navigation -->
                <nav id="nav" role="navigation" aria-label="Hauptnavigation">
                    <ul>
                        <li><a href="<?= BASE_URL ?>" class="<?= ($current_page ?? '') === 'home' ? 'active' : '' ?>">Start</a></li>
                        <li><a href="<?= BASE_URL ?>/leistungen" class="<?= ($current_page ?? '') === 'leistungen' ? 'active' : '' ?>">Leistungen</a></li>
                        <!-- PRODUCTION: Shop ausgeblendet, wird später aktiviert -->
                        <!-- <li><a href="<?= BASE_URL ?>/shop" class="<?= ($current_page ?? '') === 'shop' ? 'active' : '' ?>">Shop</a></li> -->
                        <li><a href="<?= BASE_URL ?>/blog" class="<?= ($current_page ?? '') === 'blog' ? 'active' : '' ?>">Blog</a></li>
                        <li><a href="<?= BASE_URL ?>/termin" class="<?= ($current_page ?? '') === 'termin' ? 'active' : '' ?>">Termin buchen</a></li>
                        <li><a href="<?= BASE_URL ?>/kontakt" class="<?= ($current_page ?? '') === 'kontakt' ? 'active' : '' ?>">Kontakt</a></li>
                    </ul>
                </nav>

                <!-- PRODUCTION: Warenkorb ausgeblendet, wird später aktiviert -->
                <?php /*
                <!-- Warenkorb -->
                <?php
                $cart_instance = new Cart();
                $cart_count = $cart_instance->getItemCount();
                ?>
                <a href="<?= BASE_URL ?>/warenkorb" class="cart-button" aria-label="Warenkorb (<?= $cart_count ?> Artikel)">
                    🛒
                    <?php if ($cart_count > 0): ?>
                        <span class="cart-badge"><?= $cart_count ?></span>
                    <?php endif; ?>
                </a>
                */ ?>

                <!-- Darkmode-Toggle -->
                <button class="darkmode-toggle" id="darkmode-toggle" aria-label="Farbmodus umschalten">
                    <span class="icon-sun">☀️</span>
                    <span class="icon-moon">🌙</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main id="main" role="main">
