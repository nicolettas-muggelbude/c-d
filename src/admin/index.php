<?php
/**
 * Admin-Dashboard
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();

// Admin-Rechte prüfen
require_admin();

$db = Database::getInstance();

// Statistiken holen
$stats = [
    'orders_total' => $db->querySingle("SELECT COUNT(*) as count FROM orders")['count'] ?? 0,
    'orders_pending' => $db->querySingle("SELECT COUNT(*) as count FROM orders WHERE order_status IN ('new', 'processing')")['count'] ?? 0,
    'products' => $db->querySingle("SELECT COUNT(*) as count FROM products WHERE is_active = 1")['count'] ?? 0,
    'blog_posts' => $db->querySingle("SELECT COUNT(*) as count FROM blog_posts WHERE published = 1")['count'] ?? 0,
    'bookings_total' => $db->querySingle("SELECT COUNT(*) as count FROM bookings")['count'] ?? 0,
    'bookings_pending' => $db->querySingle("SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'")['count'] ?? 0,
];

$page_title = 'Admin-Dashboard | PC-Wittfoot UG';
$page_description = 'Admin-Bereich';
$current_page = '';

include __DIR__ . '/../templates/header.php';
?>

<section class="section">
    <div class="container">
        <h1>Admin-Dashboard</h1>
        <p class="lead mb-xl">Willkommen, <?= e($_SESSION['user_name'] ?? 'Admin') ?>!</p>

        <!-- Statistiken -->
        <div class="grid grid-cols-1 grid-cols-md-2 grid-cols-lg-3 gap-lg mb-xl">
            <div class="card text-center">
                <div class="card-icon">📦</div>
                <h3><?= $stats['orders_total'] ?></h3>
                <p class="text-muted">Bestellungen gesamt</p>
            </div>

            <div class="card text-center">
                <div class="card-icon">⏳</div>
                <h3><?= $stats['orders_pending'] ?></h3>
                <p class="text-muted">Offene Bestellungen</p>
            </div>

            <div class="card text-center">
                <div class="card-icon">💻</div>
                <h3><?= $stats['products'] ?></h3>
                <p class="text-muted">Aktive Produkte</p>
            </div>

            <div class="card text-center">
                <div class="card-icon">📝</div>
                <h3><?= $stats['blog_posts'] ?></h3>
                <p class="text-muted">Blog-Beiträge</p>
            </div>

            <div class="card text-center">
                <div class="card-icon">📅</div>
                <h3><?= $stats['bookings_total'] ?></h3>
                <p class="text-muted">Termine gesamt</p>
            </div>

            <div class="card text-center">
                <div class="card-icon">🔔</div>
                <h3><?= $stats['bookings_pending'] ?></h3>
                <p class="text-muted">Offene Termine</p>
            </div>
        </div>

        <!-- Schnellzugriff -->
        <div class="card">
            <h2 class="mb-lg">Verwaltung</h2>

            <div class="grid grid-cols-1 grid-cols-md-2 gap-md">
                <a href="<?= BASE_URL ?>/admin/blog-posts" class="btn btn-outline btn-block">
                    📝 Blog-Posts verwalten
                </a>

                <a href="<?= BASE_URL ?>/admin/products" class="btn btn-outline btn-block">
                    💻 Produkte verwalten
                </a>

                <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-outline btn-block">
                    📦 Bestellungen ansehen
                </a>

                <a href="<?= BASE_URL ?>/admin/booking-calendar" class="btn btn-outline btn-block">
                    📅 Termine verwalten
                </a>

                <a href="<?= BASE_URL ?>/admin/booking-settings" class="btn btn-outline btn-block">
                    ⚙️ Termineinstellungen
                </a>

                <a href="<?= BASE_URL ?>/admin/logout" class="btn btn-outline btn-block" style="color: var(--color-error);">
                    🚪 Abmelden
                </a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
