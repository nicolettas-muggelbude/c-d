<?php
/**
 * Widerrufsbelehrung
 */

$page_title = 'Widerrufsrecht | PC-Wittfoot UG';
$page_description = 'Widerrufsbelehrung für Verbraucher';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container" style="max-width: 800px;">
        <h1>Widerrufsbelehrung</h1>

        <h2>Widerrufsrecht</h2>
        <p>
            Sie haben das Recht, binnen vierzehn Tagen ohne Angabe von Gründen diesen Vertrag zu widerrufen.
        </p>
        <p>
            Die Widerrufsfrist beträgt vierzehn Tage ab dem Tag, an dem Sie oder ein von Ihnen benannter Dritter,
            der nicht der Beförderer ist, die Waren in Besitz genommen haben bzw. hat.
        </p>
        <p>
            Um Ihr Widerrufsrecht auszuüben, müssen Sie uns
        </p>
        <p>
            <strong>PC-Wittfoot UG</strong><br>
            Musterstraße 123<br>
            12345 Musterstadt<br>
            E-Mail: <a href="mailto:info@pc-wittfoot.de">info@pc-wittfoot.de</a><br>
            Telefon: +49 (0) 123 456789
        </p>
        <p>
            mittels einer eindeutigen Erklärung (z.B. ein mit der Post versandter Brief oder E-Mail) über Ihren
            Entschluss, diesen Vertrag zu widerrufen, informieren. Sie können dafür das beigefügte Muster-Widerrufsformular
            verwenden, das jedoch nicht vorgeschrieben ist.
        </p>

        <h2>Widerrufsfolgen</h2>
        <p>
            Wenn Sie diesen Vertrag widerrufen, haben wir Ihnen alle Zahlungen, die wir von Ihnen erhalten haben,
            einschließlich der Lieferkosten (mit Ausnahme der zusätzlichen Kosten, die sich daraus ergeben, dass
            Sie eine andere Art der Lieferung als die von uns angebotene, günstigste Standardlieferung gewählt haben),
            unverzüglich und spätestens binnen vierzehn Tagen ab dem Tag zurückzuzahlen, an dem die Mitteilung über
            Ihren Widerruf dieses Vertrags bei uns eingegangen ist.
        </p>
        <p>
            Für diese Rückzahlung verwenden wir dasselbe Zahlungsmittel, das Sie bei der ursprünglichen Transaktion
            eingesetzt haben, es sei denn, mit Ihnen wurde ausdrücklich etwas anderes vereinbart; in keinem Fall
            werden Ihnen wegen dieser Rückzahlung Entgelte berechnet.
        </p>
        <p>
            Wir können die Rückzahlung verweigern, bis wir die Waren wieder zurückerhalten haben oder bis Sie den
            Nachweis erbracht haben, dass Sie die Waren zurückgesandt haben, je nachdem, welches der frühere Zeitpunkt ist.
        </p>
        <p>
            Sie haben die Waren unverzüglich und in jedem Fall spätestens binnen vierzehn Tagen ab dem Tag, an dem
            Sie uns über den Widerruf dieses Vertrags unterrichten, an uns zurückzusenden oder zu übergeben. Die
            Frist ist gewahrt, wenn Sie die Waren vor Ablauf der Frist von vierzehn Tagen absenden.
        </p>
        <p>
            Sie tragen die unmittelbaren Kosten der Rücksendung der Waren.
        </p>
        <p>
            Sie müssen für einen etwaigen Wertverlust der Waren nur aufkommen, wenn dieser Wertverlust auf einen
            zur Prüfung der Beschaffenheit, Eigenschaften und Funktionsweise der Waren nicht notwendigen Umgang
            mit ihnen zurückzuführen ist.
        </p>

        <h2>Muster-Widerrufsformular</h2>
        <div class="card mt-lg" style="background: var(--bg-secondary); padding: var(--space-xl);">
            <p>
                (Wenn Sie den Vertrag widerrufen wollen, dann füllen Sie bitte dieses Formular aus und senden Sie es zurück.)
            </p>
            <p>
                An<br>
                <strong>PC-Wittfoot UG</strong><br>
                Musterstraße 123<br>
                12345 Musterstadt<br>
                E-Mail: info@pc-wittfoot.de
            </p>
            <p>
                Hiermit widerrufe(n) ich/wir (*) den von mir/uns (*) abgeschlossenen Vertrag über den Kauf der
                folgenden Waren (*)/die Erbringung der folgenden Dienstleistung (*)
            </p>
            <p>
                Bestellt am (*)/erhalten am (*):<br>
                Name des/der Verbraucher(s):<br>
                Anschrift des/der Verbraucher(s):<br>
                Unterschrift des/der Verbraucher(s) (nur bei Mitteilung auf Papier):<br>
                Datum:
            </p>
            <p class="text-muted">
                <small>(*) Unzutreffendes streichen</small>
            </p>
        </div>

        <p class="text-muted mt-xl">
            <small>Stand: <?= date('d.m.Y') ?></small>
        </p>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
