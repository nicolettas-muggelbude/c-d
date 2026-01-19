<?php
/**
 * Admin: Terminverwaltung
 * PC-Wittfoot UG
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();

// Admin-Rechte prüfen
require_admin();

$db = Database::getInstance();

// Erfolgsmeldung
$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['success_message']);

// Filter-Parameter
$filterStatus = $_GET['status'] ?? '';
$filterType = $_GET['type'] ?? '';
$filterDate = $_GET['date'] ?? '';
$searchQuery = $_GET['search'] ?? '';

// SQL Query aufbauen
$sql = "SELECT * FROM bookings WHERE 1=1";
$params = [];

if ($filterStatus && in_array($filterStatus, ['pending', 'confirmed', 'completed', 'cancelled'])) {
    $sql .= " AND status = :status";
    $params[':status'] = $filterStatus;
}

if ($filterType && in_array($filterType, ['fixed', 'walkin'])) {
    $sql .= " AND booking_type = :type";
    $params[':type'] = $filterType;
}

if ($filterDate) {
    $sql .= " AND booking_date = :date";
    $params[':date'] = $filterDate;
}

if ($searchQuery) {
    $sql .= " AND (customer_firstname LIKE :search1 OR customer_lastname LIKE :search2 OR customer_email LIKE :search3)";
    $params[':search1'] = '%' . $searchQuery . '%';
    $params[':search2'] = '%' . $searchQuery . '%';
    $params[':search3'] = '%' . $searchQuery . '%';
}

$sql .= " ORDER BY booking_date ASC, booking_time ASC, created_at ASC";

$bookings = $db->query($sql, $params);

// Status-Labels
$statusLabels = [
    'pending' => 'Ausstehend',
    'confirmed' => 'Bestätigt',
    'completed' => 'Abgeschlossen',
    'cancelled' => 'Storniert'
];

$statusColors = [
    'pending' => '#ffc107',
    'confirmed' => '#28a745',
    'completed' => '#6c757d',
    'cancelled' => '#dc3545'
];

// Service-Labels
$serviceLabels = [
    'pc-reparatur' => 'PC-Reparatur',
    'notebook-reparatur' => 'Notebook-Reparatur',
    'beratung' => 'Beratung',
    'software' => 'Software-Installation',
    'datenrettung' => 'Datenrettung',
    'virus-entfernung' => 'Virus-Entfernung',
    'upgrade' => 'Hardware-Upgrade',
    'sonstiges' => 'Sonstiges'
];

$page_title = 'Terminverwaltung | Admin | PC-Wittfoot UG';
$page_description = 'Termine verwalten';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container" style="max-width: 1200px;">
        <div class="mb-lg">
            <a href="<?= BASE_URL ?>/admin" class="btn btn-outline btn-sm">← Zurück zum Dashboard</a>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
            <div>
                <h1 style="margin: 0 0 0.5rem 0;">Terminverwaltung</h1>
                <p class="lead" style="margin: 0;">Alle Buchungen verwalten und bearbeiten</p>
            </div>
            <a href="<?= BASE_URL ?>/admin/booking-calendar" class="btn btn-primary">
                📅 Kalender-Ansicht
            </a>
        </div>

        <?php if ($success_message): ?>
            <div class="alert alert-success mb-lg">
                <?= e($success_message) ?>
            </div>
        <?php endif; ?>

        <!-- Filter -->
        <div class="card mb-lg">
            <form method="GET" action="" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end;">
                <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                    <label for="search">Suche</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        placeholder="Name oder E-Mail"
                        value="<?= e($searchQuery) ?>"
                        class="form-control"
                    >
                </div>

                <div class="form-group" style="flex: 0 0 150px; margin-bottom: 0;">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="">Alle</option>
                        <option value="pending" <?= $filterStatus === 'pending' ? 'selected' : '' ?>>Ausstehend</option>
                        <option value="confirmed" <?= $filterStatus === 'confirmed' ? 'selected' : '' ?>>Bestätigt</option>
                        <option value="completed" <?= $filterStatus === 'completed' ? 'selected' : '' ?>>Abgeschlossen</option>
                        <option value="cancelled" <?= $filterStatus === 'cancelled' ? 'selected' : '' ?>>Storniert</option>
                    </select>
                </div>

                <div class="form-group" style="flex: 0 0 150px; margin-bottom: 0;">
                    <label for="type">Terminart</label>
                    <select id="type" name="type" class="form-control">
                        <option value="">Alle</option>
                        <option value="fixed" <?= $filterType === 'fixed' ? 'selected' : '' ?>>Fester Termin</option>
                        <option value="walkin" <?= $filterType === 'walkin' ? 'selected' : '' ?>>Ich komme vorbei</option>
                    </select>
                </div>

                <div class="form-group" style="flex: 0 0 180px; margin-bottom: 0;">
                    <label for="date">Datum</label>
                    <input
                        type="date"
                        id="date"
                        name="date"
                        value="<?= e($filterDate) ?>"
                        class="form-control"
                    >
                </div>

                <div style="flex: 0 0 auto; display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">Filtern</button>
                    <a href="<?= BASE_URL ?>/admin/bookings" class="btn btn-outline">Zurücksetzen</a>
                </div>
            </form>
        </div>

        <!-- Buchungen-Tabelle -->
        <div class="card">
            <div style="overflow-x: auto;">
                <table class="bookings-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Datum</th>
                            <th>Zeit</th>
                            <th>Kunde</th>
                            <th>Dienstleistung</th>
                            <th>Typ</th>
                            <th>Status</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bookings)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted" style="padding: 2rem;">
                                    Keine Termine gefunden
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($bookings as $booking): ?>
                                <?php
                                $date = DateTime::createFromFormat('Y-m-d', $booking['booking_date']);
                                $dateFormatted = $date ? $date->format('d.m.Y') : $booking['booking_date'];
                                ?>
                                <tr>
                                    <td><strong>#<?= e($booking['id']) ?></strong></td>
                                    <td><?= e($dateFormatted) ?></td>
                                    <td>
                                        <?php if ($booking['booking_type'] === 'fixed'): ?>
                                            <?= e($booking['booking_time']) ?> Uhr
                                        <?php else: ?>
                                            <span class="text-muted">Ich komme vorbei</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?= e($booking['customer_firstname'] . ' ' . $booking['customer_lastname']) ?></strong><br>
                                        <small class="text-muted"><?= e($booking['customer_email']) ?></small>
                                    </td>
                                    <td><?= e($serviceLabels[$booking['service_type']] ?? $booking['service_type']) ?></td>
                                    <td>
                                        <?php if ($booking['booking_type'] === 'fixed'): ?>
                                            <span class="badge badge-info">Fester Termin</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Ich komme vorbei</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: <?= $statusColors[$booking['status']] ?>; color: white;">
                                            <?= e($statusLabels[$booking['status']] ?? $booking['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/admin/booking-detail?id=<?= $booking['id'] ?>" class="btn btn-sm btn-outline">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-lg" style="padding-top: 1rem; border-top: 1px solid #ddd;">
                <p class="text-muted" style="font-size: 0.9rem;">
                    Gesamt: <strong><?= count($bookings) ?></strong> Termine
                </p>
            </div>
        </div>
    </div>
</section>

<style>
.form-control {
    width: 100%;
    padding: 0.5rem 0.75rem;
    font-size: 0.95rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

.form-group label {
    display: block;
    margin-bottom: 0.35rem;
    font-weight: 500;
    font-size: 0.9rem;
}

.alert {
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 1rem;
}

.alert-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.bookings-table {
    width: 100%;
    border-collapse: collapse;
}

.bookings-table th,
.bookings-table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.bookings-table th {
    background-color: #f8f9fa;
    font-weight: 600;
    font-size: 0.9rem;
    color: #495057;
}

.bookings-table tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    display: inline-block;
    padding: 0.35rem 0.65rem;
    font-size: 0.8rem;
    border-radius: 4px;
    font-weight: 500;
}

.badge-info {
    background-color: #17a2b8;
    color: white;
}

.badge-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-sm {
    padding: 0.35rem 0.75rem;
    font-size: 0.875rem;
}
</style>

<?php include __DIR__ . '/../templates/footer.php'; ?>
