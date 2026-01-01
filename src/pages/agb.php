<?php
/**
 * AGB - Allgemeine Geschäftsbedingungen
 */

$page_title = 'AGB | PC-Wittfoot UG';
$page_description = 'Allgemeine Geschäftsbedingungen';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container" style="max-width: 800px;">
        <h1>Allgemeine Geschäftsbedingungen</h1>

        <h2>§ 1 Geltungsbereich</h2>
        <p>
            Diese Allgemeinen Geschäftsbedingungen (AGB) gelten für alle Verträge zwischen der PC-Wittfoot UG
            (nachfolgend "Verkäufer") und dem Kunden (nachfolgend "Kunde"), die über den Online-Shop oder im
            Ladengeschäft geschlossen werden.
        </p>

        <h2>§ 2 Vertragsschluss</h2>
        <p>
            (1) Die Darstellung der Produkte im Online-Shop stellt kein rechtlich bindendes Angebot, sondern
            eine Aufforderung zur Bestellung dar.
        </p>
        <p>
            (2) Durch das Absenden der Bestellung gibt der Kunde ein verbindliches Angebot zum Kauf der im
            Warenkorb befindlichen Waren ab.
        </p>
        <p>
            (3) Der Verkäufer bestätigt den Eingang der Bestellung per E-Mail. Diese Bestätigung stellt noch
            keine Annahme des Angebots dar.
        </p>

        <h2>§ 3 Preise und Versandkosten</h2>
        <p>
            (1) Alle Preise sind Bruttopreise und enthalten die gesetzliche Umsatzsteuer.
        </p>
        <p>
            (2) Zusätzlich zu den angegebenen Preisen können Versandkosten anfallen. Die Versandkosten werden
            vor Abschluss der Bestellung angezeigt.
        </p>
        <p>
            (3) Bei Abholung im Ladengeschäft entfallen Versandkosten.
        </p>

        <h2>§ 4 Zahlungsbedingungen</h2>
        <p>
            Folgende Zahlungsarten stehen zur Verfügung:
        </p>
        <ul>
            <li>Vorkasse/Überweisung</li>
            <li>PayPal</li>
            <li>Barzahlung bei Abholung</li>
        </ul>

        <h2>§ 5 Lieferung und Versand</h2>
        <p>
            (1) Die Lieferung erfolgt innerhalb Deutschlands.
        </p>
        <p>
            (2) Die Lieferzeit beträgt in der Regel 3-5 Werktage nach Zahlungseingang.
        </p>
        <p>
            (3) Abholung im Ladengeschäft nach vorheriger Vereinbarung möglich.
        </p>

        <h2>§ 6 Gewährleistung</h2>
        <p>
            (1) Es gelten die gesetzlichen Gewährleistungsrechte.
        </p>
        <p>
            (2) Bei Neugeräten: 24 Monate Gewährleistung
        </p>
        <p>
            (3) Bei Refurbished-Geräten: 12 Monate Gewährleistung
        </p>

        <h2>§ 7 Widerrufsrecht</h2>
        <p>
            Verbrauchern steht ein Widerrufsrecht nach Maßgabe der gesetzlichen Bestimmungen zu.
            Details finden Sie in unserer <a href="<?= BASE_URL ?>/widerruf">Widerrufsbelehrung</a>.
        </p>

        <h2>§ 8 Haftung</h2>
        <p>
            (1) Der Verkäufer haftet unbeschränkt für Vorsatz und grobe Fahrlässigkeit.
        </p>
        <p>
            (2) Bei leichter Fahrlässigkeit haftet der Verkäufer nur bei Verletzung wesentlicher
            Vertragspflichten (Kardinalpflichten).
        </p>

        <h2>§ 9 Datenschutz</h2>
        <p>
            Die Verarbeitung personenbezogener Daten erfolgt gemäß unserer
            <a href="<?= BASE_URL ?>/datenschutz">Datenschutzerklärung</a>.
        </p>

        <h2>§ 10 Schlussbestimmungen</h2>
        <p>
            (1) Es gilt das Recht der Bundesrepublik Deutschland unter Ausschluss des UN-Kaufrechts.
        </p>
        <p>
            (2) Sollten einzelne Bestimmungen dieser AGB unwirksam sein, bleibt die Wirksamkeit der
            übrigen Bestimmungen unberührt.
        </p>

        <p class="text-muted mt-xl">
            <small>Stand: <?= date('d.m.Y') ?></small>
        </p>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
