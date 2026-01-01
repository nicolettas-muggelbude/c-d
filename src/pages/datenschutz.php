<?php
/**
 * Datenschutzerklärung
 */

$page_title = 'Datenschutzerklärung | PC-Wittfoot UG';
$page_description = 'Datenschutzerklärung gemäß DSGVO';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container" style="max-width: 800px;">
        <h1>Datenschutzerklärung</h1>

        <h2>1. Datenschutz auf einen Blick</h2>

        <h3>Allgemeine Hinweise</h3>
        <p>
            Die folgenden Hinweise geben einen einfachen Überblick darüber, was mit Ihren personenbezogenen Daten
            passiert, wenn Sie diese Website besuchen. Personenbezogene Daten sind alle Daten, mit denen Sie
            persönlich identifiziert werden können.
        </p>

        <h3>Datenerfassung auf dieser Website</h3>
        <h4>Wer ist verantwortlich für die Datenerfassung auf dieser Website?</h4>
        <p>
            Die Datenverarbeitung auf dieser Website erfolgt durch den Websitebetreiber. Dessen Kontaktdaten
            können Sie dem Abschnitt „Hinweis zur verantwortlichen Stelle" in dieser Datenschutzerklärung entnehmen.
        </p>

        <h4>Wie erfassen wir Ihre Daten?</h4>
        <p>
            Ihre Daten werden zum einen dadurch erhoben, dass Sie uns diese mitteilen. Hierbei kann es sich z.B. um
            Daten handeln, die Sie in ein Kontaktformular eingeben.
        </p>
        <p>
            Andere Daten werden automatisch oder nach Ihrer Einwilligung beim Besuch der Website durch unsere
            IT-Systeme erfasst. Das sind vor allem technische Daten (z.B. Internetbrowser, Betriebssystem oder
            Uhrzeit des Seitenaufrufs).
        </p>

        <h4>Wofür nutzen wir Ihre Daten?</h4>
        <p>
            Ein Teil der Daten wird erhoben, um eine fehlerfreie Bereitstellung der Website zu gewährleisten.
            Andere Daten können zur Analyse Ihres Nutzerverhaltens verwendet werden.
        </p>

        <h2>2. Hosting</h2>
        <p>
            Wir hosten die Inhalte unserer Website bei folgendem Anbieter:
        </p>
        <p>
            <strong>[Hosting-Provider einfügen]</strong><br>
            [Adresse einfügen]
        </p>

        <h2>3. Allgemeine Hinweise und Pflichtinformationen</h2>

        <h3>Datenschutz</h3>
        <p>
            Die Betreiber dieser Seiten nehmen den Schutz Ihrer persönlichen Daten sehr ernst. Wir behandeln Ihre
            personenbezogenen Daten vertraulich und entsprechend den gesetzlichen Datenschutzvorschriften sowie
            dieser Datenschutzerklärung.
        </p>

        <h3>Hinweis zur verantwortlichen Stelle</h3>
        <p>
            Die verantwortliche Stelle für die Datenverarbeitung auf dieser Website ist:
        </p>
        <p>
            <strong>PC-Wittfoot UG (haftungsbeschränkt)</strong><br>
            Musterstraße 123<br>
            12345 Musterstadt<br>
            <br>
            Telefon: +49 (0) 123 456789<br>
            E-Mail: <a href="mailto:info@pc-wittfoot.de">info@pc-wittfoot.de</a>
        </p>

        <h2>4. Datenerfassung auf dieser Website</h2>

        <h3>Kontaktformular</h3>
        <p>
            Wenn Sie uns per Kontaktformular Anfragen zukommen lassen, werden Ihre Angaben aus dem Anfrageformular
            inklusive der von Ihnen dort angegebenen Kontaktdaten zwecks Bearbeitung der Anfrage und für den Fall
            von Anschlussfragen bei uns gespeichert.
        </p>

        <h3>Anfrage per E-Mail, Telefon oder Telefax</h3>
        <p>
            Wenn Sie uns per E-Mail, Telefon oder Telefax kontaktieren, wird Ihre Anfrage inklusive aller daraus
            hervorgehenden personenbezogenen Daten (Name, Anfrage) zum Zwecke der Bearbeitung Ihres Anliegens bei
            uns gespeichert und verarbeitet.
        </p>

        <h2>5. Ihre Rechte</h2>
        <p>
            Sie haben jederzeit das Recht:
        </p>
        <ul>
            <li>Auskunft über Ihre bei uns gespeicherten personenbezogenen Daten zu erhalten</li>
            <li>Berichtigung unrichtiger Daten zu verlangen</li>
            <li>Löschung Ihrer Daten zu verlangen</li>
            <li>Einschränkung der Datenverarbeitung zu verlangen</li>
            <li>Der Datenverarbeitung zu widersprechen</li>
            <li>Datenübertragbarkeit zu verlangen</li>
        </ul>

        <p class="text-muted mt-xl">
            <small>Stand: <?= date('d.m.Y') ?></small>
        </p>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
