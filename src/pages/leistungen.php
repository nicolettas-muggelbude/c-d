<?php
/**
 * Leistungen-Seite
 */

$page_title = 'Unsere Leistungen | PC-Wittfoot UG';
$page_description = 'Beratung, Verkauf, Reparatur, Softwareentwicklung - Ihr IT-Partner in [Ort]';
$current_page = 'leistungen';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <h1>Unsere Leistungen</h1>
        <p class="lead">
            Als IT-Fachbetrieb bieten wir umfassende Dienstleistungen rund um Computer, Notebooks, Tablets und Smartphones.
            Von der Beratung über den Verkauf bis zur Reparatur - alles aus einer Hand.
        </p>

        <!-- Hauptleistungen -->
        <div class="services-grid">
            <!-- Diagnose & Reparatur -->
            <div class="service-card">
                <div class="service-icon">🔧</div>
                <h2>Diagnose & Reparatur</h2>
                <p>
                    Professionelle Fehlerdiagnose und Reparatur für alle Geräte. Wir analysieren Hardware- und
                    Softwareprobleme, tauschen defekte Komponenten und bringen Ihre Geräte wieder zum Laufen.
                </p>
                <ul class="service-list">
                    <li>Hardware-Reparatur (Display, Akku, Mainboard, etc.)</li>
                    <li>Software-Probleme beheben</li>
                    <li>Virenentfernung</li>
                    <li>Datenrettung</li>
                    <li>Kostenloser Kostenvoranschlag</li>
                </ul>
                <a href="<?= BASE_URL ?>/kontakt" class="btn btn-outline">Reparatur anfragen</a>
            </div>

            <!-- Hardware-Verkauf -->
            <div class="service-card">
                <div class="service-icon">💻</div>
                <h2>Hardware-Verkauf</h2>
                <p>
                    Technik wie Neu! Hochwertige Refurbished Hardware mit 24 Monate Garantie.
                    Neue Business Hardware <strong>exone Business</strong>.
                </p>
                <ul class="service-list">
                    <li>Notebooks & Desktop-PCs</li>
                    <li>Tablets & Smartphones</li>
                    <li>Peripherie & Zubehör</li>
                    <li>Kassensysteme (POS)</li>
                    <li>Custom Gaming PC</li>
                    <li>NAS / (Home)Server</li>
                </ul>
                <a href="<?= BASE_URL ?>/shop" class="btn btn-primary">Zum Shop</a>
            </div>

            <!-- Beratung & Planung -->
            <div class="service-card">
                <div class="service-icon">💡</div>
                <h2>Beratung & Planung</h2>
                <p>
                    Individuelle IT-Beratung für Privatkunden und Gewerbe. Wir finden die passende Lösung für Ihre
                    Anforderungen und Ihr Budget.
                </p>
                <ul class="service-list">
                    <li>Bedarfsanalyse</li>
                    <li>Produktempfehlungen</li>
                    <li>Kosten-Nutzen-Analyse</li>
                    <li>Verständliche Erklärungen</li>
                </ul>
                <a href="<?= BASE_URL ?>/termin" class="btn btn-outline">Termin buchen</a>
            </div>

            <!-- Softwareentwicklung -->
            <div class="service-card">
                <div class="service-icon">⚙️</div>
                <h2>Softwareentwicklung</h2>
                <p>
                    Maßgeschneiderte Software-Lösungen für Ihr Unternehmen. Von der Idee über die Konzeption
                    bis zur fertigen Anwendung.
                </p>
                <ul class="service-list">
                    <li>Individuelle Webanwendungen</li>
                    <li>Automatisierungslösungen</li>
                    <li>Datenbank-Entwicklung</li>
                    <li>API-Integration</li>
                    <li>Wartung & Support</li>
                </ul>
                <a href="<?= BASE_URL ?>/kontakt" class="btn btn-outline">Projekt anfragen</a>
            </div>

            <!-- Wartung & Support -->
            <div class="service-card">
                <div class="service-icon">🛡️</div>
                <h2>Wartung & Support</h2>
                <p>
                    Regelmäßige Wartung und schneller Support für Ihre IT-Infrastruktur. Damit Ihre Systeme
                    zuverlässig laufen.
                </p>
                <ul class="service-list">
                    <li>Regelmäßige Systemwartung</li>
                    <li>Updates & Patches</li>
                    <li>Performance-Optimierung</li>
                    <li>Schneller Support bei Problemen</li>
                    <li>Fernwartung möglich</li>
                </ul>
                <a href="<?= BASE_URL ?>/kontakt" class="btn btn-outline">Support anfragen</a>
            </div>

            <!-- Projektierung -->
            <div class="service-card">
                <div class="service-icon">📦</div>
                <h2>Projektierung</h2>
                <p>
                    Komplette IT-Projekte aus einer Hand. Von der Planung über die Beschaffung bis zur
                    Installation und Schulung.
                </p>
                <ul class="service-list">
                    <li>Büro-Ausstattung komplett</li>
                    <li>Netzwerk-Installation</li>
                    <li>Server-Einrichtung</li>
                    <li>Mitarbeiter-Schulungen</li>
                    <li>Projektmanagement</li>
                </ul>
                <a href="<?= BASE_URL ?>/kontakt" class="btn btn-outline">Projekt besprechen</a>
            </div>
        </div>

        <!-- Besonderheiten -->
        <section class="section bg-primary text-white">
            <div class="container">
                <h2 class="text-center mb-lg">Was uns besonders macht</h2>

                <div class="grid grid-cols-1 grid-cols-md-2 grid-cols-lg-4 gap-lg">
                    <div class="text-center">
                        <div class="icon-large">⭐</div>
                        <h3>5 Sterne</h3>
                        <p>Exzellente Bewertungen auf Google und Kleinanzeigen.de</p>
                    </div>

                    <div class="text-center">
                        <div class="icon-large">☕</div>
                        <h3>Mit Kaffee</h3>
                        <p>Beratung im Sitzen mit einer Tasse Kaffee - entspannt und persönlich</p>
                    </div>

                    <div class="text-center">
                        <div class="icon-large">🗣️</div>
                        <h3>Verständlich</h3>
                        <p>Keine Fachchinesisch - wir erklären IT so, dass jeder es versteht</p>
                    </div>

                    <div class="text-center">
                        <div class="icon-large">🐕</div>
                        <h3>Mit Baileys</h3>
                        <p>Unser Bürohund ist Teil des Teams und sorgt für gute Laune</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <div class="text-center mt-3xl">
            <h2>Haben wir Ihr Interesse geweckt?</h2>
            <p class="lead mb-lg">
                Kontaktieren Sie uns für ein unverbindliches Gespräch oder buchen Sie direkt einen Termin.
            </p>

            <div class="btn-group">
                <a href="<?= BASE_URL ?>/termin" class="btn btn-primary btn-lg">
                    📅 Termin buchen
                </a>
                <a href="<?= BASE_URL ?>/kontakt" class="btn btn-outline btn-lg">
                    ✉️ Kontakt aufnehmen
                </a>
                <a href="tel:+49123456789" class="btn btn-outline btn-lg">
                    📞 Anrufen
                </a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
