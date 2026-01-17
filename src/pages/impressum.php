<?php
/**
 * Impressum
 */

$page_title = 'Impressum | PC-Wittfoot UG';
$page_description = 'Impressum der PC-Wittfoot UG - Anbieterkennzeichnung gemäß § 5 TMG. IT-Fachbetrieb in Oldenburg, Melkbrink 61.';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container" style="max-width: 800px;">
        <div style="text-align: center; margin-bottom: var(--space-xl);">
            <img src="<?= asset('images/logo-square.svg') ?>"
                 alt="PC-Wittfoot UG Logo - IT-Fachbetrieb Oldenburg"
                 style="width: 120px; height: auto;">
        </div>

        <h1>Impressum</h1>

        <h2>Angaben gemäß § 5 TMG</h2>
        <p>
            <strong>PC-Wittfoot UG</strong><br>
            Melkbrink 61<br>
            26121 Oldenburg<br>
            Deutschland
        </p>

        <h2>Vertreten durch</h2>
        <p>
            Nicole Wittfoot (Geschäftsführerin)
        </p>

        <h2>Kontakt</h2>
        <p>
            Telefon: <a href="tel:+4944140576020">+49 441 40576020</a><br>
            E-Mail: <a href="mailto:info@pc-wittfoot.de">info@pc-wittfoot.de</a>
        </p>

        <h2>Registereintrag</h2>
        <p>
            Eingetragen im Handelsregister.<br>
            Registergericht: Oldenburg<br>
            Registernummer: HRB 215517
        </p>

        <h2>Umsatzsteuer-ID</h2>
        <p>
            Umsatzsteuer-Identifikationsnummer gemäß § 27 a Umsatzsteuergesetz:<br>
            DE331470711
        </p>

        <h2>Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV</h2>
        <p>
            Nicole Wittfoot<br>
            Melkbrink 61<br>
            26121 Oldenburg
        </p>

        <h2>EU-Streitschlichtung</h2>
        <p>
            Die Europäische Kommission stellt eine Plattform zur Online-Streitbeilegung (OS) bereit:
            <a href="https://ec.europa.eu/consumers/odr/" target="_blank" rel="noopener">https://ec.europa.eu/consumers/odr/</a><br>
            Unsere E-Mail-Adresse finden Sie oben im Impressum.
        </p>

        <h2>Verbraucherstreitbeilegung / Universalschlichtungsstelle</h2>
        <p>
            Wir sind nicht bereit oder verpflichtet, an Streitbeilegungsverfahren vor einer
            Verbraucherschlichtungsstelle teilzunehmen.
        </p>

        <p class="text-muted mt-xl">
            <small>Quelle: Erstellt mit dem <a href="https://www.e-recht24.de" target="_blank" rel="noopener">Impressum-Generator von eRecht24</a></small>
        </p>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
