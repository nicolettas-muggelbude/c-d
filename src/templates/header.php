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
    <?php
    // Dynamisches OG-Image je Seite
    if (isset($page_og_image_url)) {
        // Direkte URL (z.B. für Hero-Images)
        $og_image_url = $page_og_image_url;
    } else {
        // Relativer Pfad zu assets/images/
        $og_image = $page_og_image ?? 'og-image.png';
        $og_image_url = 'https://pc-wittfoot.de/assets/images/' . $og_image;
    }
    $og_image_alt = $page_og_image_alt ?? 'PC-Wittfoot UG - IT-Fachbetrieb mit Herz';
    $og_type = $page_og_type ?? 'website';
    ?>
    <meta property="og:type" content="<?= $og_type ?>">
    <meta property="og:url" content="<?= e($page_url ?? 'https://pc-wittfoot.de' . $_SERVER['REQUEST_URI']) ?>">
    <meta property="og:title" content="<?= e($page_title ?? 'PC-Wittfoot UG - IT-Fachbetrieb mit Herz') ?>">
    <meta property="og:description" content="<?= e($page_description ?? 'IT-Fachbetrieb in Oldenburg. Beratung, Verkauf, Reparatur & Softwareentwicklung. Refurbished Hardware & exone Neugeräte. Persönlicher Service mit Kaffee!') ?>">
    <meta property="og:image" content="<?= $og_image_url ?>">
    <meta property="og:image:secure_url" content="<?= $og_image_url ?>">
    <meta property="og:image:type" content="<?= strpos($og_image_url, '.jpg') !== false ? 'image/jpeg' : 'image/png' ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="<?= e($og_image_alt) ?>">
    <meta property="og:locale" content="de_DE">
    <meta property="og:site_name" content="PC-Wittfoot UG">

    <?php if ($og_type === 'article'): ?>
    <!-- Article-spezifische Meta-Tags -->
    <meta property="article:published_time" content="<?= e($page_published ?? date('c')) ?>">
    <meta property="article:modified_time" content="<?= e($page_modified ?? date('c')) ?>">
    <meta property="article:author" content="<?= e($page_author ?? 'PC-Wittfoot UG') ?>">
    <meta property="article:section" content="IT-Tipps & News">
    <?php if (!empty($page_keywords)): ?>
    <?php foreach (explode(',', $page_keywords) as $tag): ?>
    <meta property="article:tag" content="<?= e(trim($tag)) ?>">
    <?php endforeach; ?>
    <?php endif; ?>
    <?php endif; ?>

    <!-- Twitter / X -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?= e($page_url ?? 'https://pc-wittfoot.de' . $_SERVER['REQUEST_URI']) ?>">
    <meta name="twitter:title" content="<?= e($page_title ?? 'PC-Wittfoot UG - IT-Fachbetrieb mit Herz') ?>">
    <meta name="twitter:description" content="<?= e($page_description ?? 'IT-Fachbetrieb in Oldenburg. Beratung, Verkauf, Reparatur & Softwareentwicklung. Refurbished Hardware & exone Neugeräte. Persönlicher Service mit Kaffee!') ?>">
    <meta name="twitter:image" content="<?= $og_image_url ?>">
    <meta name="twitter:image:alt" content="<?= e($og_image_alt) ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= e($page_url ?? 'https://pc-wittfoot.de' . $_SERVER['REQUEST_URI']) ?>">

    <!-- RSS Feed Discovery -->
    <link rel="alternate" type="application/rss+xml" title="PC-Wittfoot Blog RSS Feed" href="https://pc-wittfoot.de/blog/feed.xml">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= asset('images/favicon.ico') ?>">
    <link rel="icon" type="image/svg+xml" href="<?= asset('images/logo-square.svg') ?>">
    <link rel="alternate icon" type="image/png" href="<?= asset('images/favicon.png') ?>">

    <!-- Emoji Font (einheitliche Emojis auf allen Geräten) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Color+Emoji&display=swap" rel="stylesheet">

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
                        <img src="<?= asset('images/logo-square.svg') ?>" alt="PC-Wittfoot UG - IT-Fachbetrieb Oldenburg - Reparatur, Beratung, Hardware">
                    </a>
                </div>

                <!-- Hamburger-Menu (Mobile/Tablet) -->
                <button class="hamburger" id="hamburger" aria-label="Menü öffnen" aria-expanded="false">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <!-- Navigation -->
                <nav id="nav" aria-label="Hauptnavigation">
                    <ul>
                        <li><a href="<?= BASE_URL ?>" class="<?= ($current_page ?? '') === 'home' ? 'active' : '' ?>">Start</a></li>
                        <li><a href="<?= BASE_URL ?>/leistungen" class="<?= ($current_page ?? '') === 'leistungen' ? 'active' : '' ?>">Leistungen</a></li>
                        <!-- PRODUCTION: Shop ausgeblendet, wird später aktiviert -->
                        <!-- <li><a href="<?= BASE_URL ?>/shop" class="<?= ($current_page ?? '') === 'shop' ? 'active' : '' ?>">Shop</a></li> -->
                        <li><a href="<?= BASE_URL ?>/blog" class="<?= ($current_page ?? '') === 'blog' ? 'active' : '' ?>">Blog</a></li>
                        <li><a href="<?= BASE_URL ?>/downloads" class="<?= ($current_page ?? '') === 'downloads' ? 'active' : '' ?>">Downloads</a></li>
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
    <main id="main" aria-label="Hauptinhalt der Seite">
