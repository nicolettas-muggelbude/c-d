<?php
/**
 * Admin: Termin-Kalender
 * PC-Wittfoot UG
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();

// Admin-Rechte prüfen
require_admin();

$db = Database::getInstance();

// Monat und Jahr aus Query-String (oder aktueller Monat)
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('n');
$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

// Validierung
if ($month < 1 || $month > 12) $month = (int)date('n');
if ($year < 2020 || $year > 2030) $year = (int)date('Y');

// Datum-Objekte
$firstDay = new DateTime("$year-$month-01");
$lastDay = (clone $firstDay)->modify('last day of this month');

// Vorige/Nächste Monat
$prevMonth = (clone $firstDay)->modify('-1 month');
$nextMonth = (clone $firstDay)->modify('+1 month');

// Alle Buchungen für diesen Monat laden
$sql = "SELECT * FROM bookings
        WHERE booking_date >= :start_date
        AND booking_date <= :end_date
        AND status != 'cancelled'
        ORDER BY booking_date, booking_time";

$bookings = $db->query($sql, [
    ':start_date' => $firstDay->format('Y-m-d'),
    ':end_date' => $lastDay->format('Y-m-d')
]);

// Buchungen nach Datum gruppieren
$bookingsByDate = [];
foreach ($bookings as $booking) {
    $date = $booking['booking_date'];
    if (!isset($bookingsByDate[$date])) {
        $bookingsByDate[$date] = [];
    }
    $bookingsByDate[$date][] = $booking;
}

// Service-Labels
$serviceLabels = [
    'pc-reparatur' => 'PC-Reparatur',
    'notebook-reparatur' => 'Notebook-Reparatur',
    'beratung' => 'Beratung',
    'software' => 'Software',
    'datenrettung' => 'Datenrettung',
    'virus-entfernung' => 'Virus',
    'upgrade' => 'Upgrade',
    'sonstiges' => 'Sonstiges'
];

$statusColors = [
    'pending' => '#ffc107',
    'confirmed' => '#28a745',
    'completed' => '#6c757d'
];

$page_title = 'Termin-Kalender | Admin | PC-Wittfoot UG';
$page_description = 'Kalender-Übersicht';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container" style="max-width: 1400px;">
        <div class="mb-lg">
            <a href="<?= BASE_URL ?>/admin" class="btn btn-outline btn-sm">← Zurück zum Dashboard</a>
        </div>

        <!-- Kalender-Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1 style="margin: 0;">
                <?= strftime('%B %Y', $firstDay->getTimestamp()) ?>
            </h1>

            <div style="display: flex; gap: 1rem;">
                <a href="?month=<?= $prevMonth->format('n') ?>&year=<?= $prevMonth->format('Y') ?>"
                   class="btn btn-outline btn-sm">
                    ← Vorheriger Monat
                </a>
                <a href="?month=<?= date('n') ?>&year=<?= date('Y') ?>"
                   class="btn btn-outline btn-sm">
                    Heute
                </a>
                <a href="?month=<?= $nextMonth->format('n') ?>&year=<?= $nextMonth->format('Y') ?>"
                   class="btn btn-outline btn-sm">
                    Nächster Monat →
                </a>
            </div>
        </div>

        <!-- Legende -->
        <div class="card mb-lg" style="padding: 1rem;">
            <div style="display: flex; gap: 2rem; flex-wrap: wrap; align-items: center;">
                <strong>Legende:</strong>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span class="badge" style="background-color: #28a745; color: white;">Bestätigt</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span class="badge" style="background-color: #ffc107; color: #000;">Ausstehend</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span class="badge" style="background-color: #6c757d; color: white;">Abgeschlossen</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span class="badge" style="background-color: #17a2b8; color: white;">F</span> = Fester Termin
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span class="badge" style="background-color: #6c757d; color: white;">W</span> = Ich komme vorbei
                </div>
            </div>
        </div>

        <!-- Kalender -->
        <div class="calendar-grid">
            <!-- Wochentage-Header -->
            <div class="calendar-header">Montag</div>
            <div class="calendar-header">Dienstag</div>
            <div class="calendar-header">Mittwoch</div>
            <div class="calendar-header">Donnerstag</div>
            <div class="calendar-header">Freitag</div>
            <div class="calendar-header">Samstag</div>
            <div class="calendar-header">Sonntag</div>

            <?php
            // Kalender beginnt am Montag der Woche
            $current = clone $firstDay;
            $dayOfWeek = (int)$current->format('N'); // 1 = Montag

            // Zurück zum Montag
            if ($dayOfWeek > 1) {
                $current->modify('-' . ($dayOfWeek - 1) . ' days');
            }

            // 6 Wochen anzeigen
            for ($week = 0; $week < 6; $week++) {
                for ($day = 1; $day <= 7; $day++) {
                    $dateStr = $current->format('Y-m-d');
                    $dayNum = $current->format('j');
                    $isCurrentMonth = $current->format('n') == $month;
                    $isToday = $dateStr === date('Y-m-d');
                    $dayBookings = $bookingsByDate[$dateStr] ?? [];

                    $cellClass = 'calendar-day';
                    if (!$isCurrentMonth) $cellClass .= ' other-month';
                    if ($isToday) $cellClass .= ' today';
                    ?>

                    <div class="<?= $cellClass ?>">
                        <div class="day-number"><?= $dayNum ?></div>

                        <?php if (!empty($dayBookings)): ?>
                            <div class="bookings-list">
                                <?php foreach ($dayBookings as $booking): ?>
                                    <a href="<?= BASE_URL ?>/admin/booking-detail?id=<?= $booking['id'] ?>"
                                       class="booking-item"
                                       style="background-color: <?= $statusColors[$booking['status']] ?? '#ccc' ?>;">
                                        <span class="booking-type-badge">
                                            <?= $booking['booking_type'] === 'fixed' ? 'F' : 'W' ?>
                                        </span>
                                        <?php if ($booking['booking_type'] === 'fixed'): ?>
                                            <strong><?= substr($booking['booking_time'], 0, 5) ?></strong>
                                        <?php endif; ?>
                                        <span class="booking-service">
                                            <?= e($serviceLabels[$booking['service_type']] ?? $booking['service_type']) ?>
                                        </span>
                                        <span class="booking-customer">
                                            <?= e($booking['customer_firstname'] . ' ' . substr($booking['customer_lastname'], 0, 1)) ?>.
                                        </span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php
                    $current->modify('+1 day');
                }
            }
            ?>
        </div>

        <!-- Statistik -->
        <div class="card mt-lg">
            <h3 class="mb-md">Statistik für <?= strftime('%B %Y', $firstDay->getTimestamp()) ?></h3>
            <div class="grid grid-cols-1 grid-cols-md-4 gap-md">
                <div class="text-center">
                    <div style="font-size: 2rem; font-weight: bold; color: var(--color-primary);">
                        <?= count($bookings) ?>
                    </div>
                    <div class="text-muted">Termine gesamt</div>
                </div>
                <div class="text-center">
                    <div style="font-size: 2rem; font-weight: bold; color: #28a745;">
                        <?= count(array_filter($bookings, fn($b) => $b['status'] === 'confirmed')) ?>
                    </div>
                    <div class="text-muted">Bestätigt</div>
                </div>
                <div class="text-center">
                    <div style="font-size: 2rem; font-weight: bold; color: #ffc107;">
                        <?= count(array_filter($bookings, fn($b) => $b['status'] === 'pending')) ?>
                    </div>
                    <div class="text-muted">Ausstehend</div>
                </div>
                <div class="text-center">
                    <div style="font-size: 2rem; font-weight: bold; color: #6c757d;">
                        <?= count(array_filter($bookings, fn($b) => $b['status'] === 'completed')) ?>
                    </div>
                    <div class="text-muted">Abgeschlossen</div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    background-color: #ddd;
    border: 1px solid #ddd;
}

.calendar-header {
    background-color: var(--color-primary);
    color: white;
    padding: 1rem;
    text-align: center;
    font-weight: 600;
    font-size: 0.9rem;
}

.calendar-day {
    background-color: white;
    min-height: 120px;
    padding: 0.5rem;
    position: relative;
}

.calendar-day.other-month {
    background-color: #f8f9fa;
    opacity: 0.6;
}

.calendar-day.today {
    background-color: #fff3cd;
    border: 2px solid var(--color-primary);
}

.day-number {
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    color: #495057;
}

.bookings-list {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.booking-item {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    text-decoration: none;
    color: #000;
    transition: opacity 0.2s;
}

.booking-item:hover {
    opacity: 0.8;
    text-decoration: none;
}

.booking-type-badge {
    display: inline-block;
    width: 18px;
    height: 18px;
    line-height: 18px;
    text-align: center;
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 50%;
    font-weight: bold;
    font-size: 0.7rem;
    flex-shrink: 0;
}

.booking-service {
    font-weight: 500;
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.booking-customer {
    font-size: 0.7rem;
    opacity: 0.8;
}

.badge {
    display: inline-block;
    padding: 0.35rem 0.65rem;
    font-size: 0.8rem;
    border-radius: 4px;
    font-weight: 500;
}

@media (max-width: 768px) {
    .calendar-grid {
        font-size: 0.85rem;
    }

    .calendar-day {
        min-height: 80px;
        padding: 0.25rem;
    }

    .booking-item {
        font-size: 0.7rem;
        padding: 0.25rem;
    }

    .booking-customer {
        display: none;
    }
}
</style>

<?php include __DIR__ . '/../templates/footer.php'; ?>
