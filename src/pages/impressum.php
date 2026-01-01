<?php
/**
 * Impressum
 */

$page_title = 'Impressum | PC-Wittfoot UG';
$page_description = 'Impressum und Anbieterkennzeichnung';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container" style="max-width: 800px;">
        <h1>Impressum</h1>

        <h2>Angaben gemäß § 5 TMG</h2>
        <p>
            <strong>PC-Wittfoot UG (haftungsbeschränkt)</strong><br>
            Musterstraße 123<br>
            12345 Musterstadt
        </p>

        <h2>Vertreten durch</h2>
        <p>
            Geschäftsführer: [Name einfügen]
        </p>

        <h2>Kontakt</h2>
        <p>
            Telefon: +49 (0) 123 456789<br>
            E-Mail: <a href="mailto:info@pc-wittfoot.de">info@pc-wittfoot.de</a>
        </p>

        <h2>Registereintrag</h2>
        <p>
            Eintragung im Handelsregister<br>
            Registergericht: [Ort einfügen]<br>
            Registernummer: [HRB-Nummer einfügen]
        </p>

        <h2>Umsatzsteuer-ID</h2>
        <p>
            Umsatzsteuer-Identifikationsnummer gemäß § 27a UStG:<br>
            [USt-IdNr. einfügen]
        </p>

        <h2>Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV</h2>
        <p>
            [Name und Anschrift einfügen]
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
