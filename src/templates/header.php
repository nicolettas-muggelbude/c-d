<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($page_description ?? 'PC-Wittfoot UG - IT-Fachbetrieb mit Herz. Beratung, Verkauf, Reparatur, Softwareentwicklung. Refurbished Hardware & exone Neugeräte.') ?>">
    <title><?= e($page_title ?? 'PC-Wittfoot UG') ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= asset('images/favicon.png') ?>">

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
    <!-- Skip-Link (Barrierefreiheit) -->
    <a href="#main" class="skip-link">Zum Hauptinhalt springen</a>

    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <!-- Logo -->
                <div class="logo">
                    <a href="<?= BASE_URL ?>">
                        <img src="<?= asset('images/logo.png') ?>" alt="PC-Wittfoot Logo" width="150" height="50">
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
                    <a href="<?= BASE_URL ?>" class="<?= ($current_page ?? '') === 'home' ? 'active' : '' ?>">Start</a>
                    <a href="<?= BASE_URL ?>/leistungen" class="<?= ($current_page ?? '') === 'leistungen' ? 'active' : '' ?>">Leistungen</a>
                    <a href="<?= BASE_URL ?>/shop" class="<?= ($current_page ?? '') === 'shop' ? 'active' : '' ?>">Shop</a>
                    <a href="<?= BASE_URL ?>/blog" class="<?= ($current_page ?? '') === 'blog' ? 'active' : '' ?>">Blog</a>
                    <a href="<?= BASE_URL ?>/termin" class="<?= ($current_page ?? '') === 'termin' ? 'active' : '' ?>">Termin buchen</a>
                    <a href="<?= BASE_URL ?>/kontakt" class="<?= ($current_page ?? '') === 'kontakt' ? 'active' : '' ?>">Kontakt</a>
                </nav>

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
