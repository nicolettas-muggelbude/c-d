<?php
/**
 * Wartungsmodus-Check
 * PC-Wittfoot UG
 *
 * Prüft ob Wartungsmodus aktiv ist und zeigt Wartungsseite an.
 * Admin-Benutzer können trotz Wartungsmodus auf die Seite zugreifen.
 */

// Prüfen ob Wartungsmodus aktiv ist
$maintenanceFile = dirname(__DIR__) . '/MAINTENANCE';

if (file_exists($maintenanceFile)) {
    // Admin-Login-Seite immer erlauben (sonst kann sich niemand einloggen!)
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    if (strpos($requestUri, '/admin/login') !== false) {
        define('MAINTENANCE_MODE', true);
        return; // Login-Seite nicht blockieren
    }

    // Admin-Bypass: Eingeloggte Admins können trotzdem zugreifen
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
        // Admin ist eingeloggt - Warnung anzeigen aber Zugriff erlauben
        define('MAINTENANCE_MODE', true);
        define('MAINTENANCE_ADMIN_BYPASS', true);
        return; // Nicht blockieren
    }

    // Wartungsseite anzeigen
    define('MAINTENANCE_MODE', true);
    showMaintenancePage($maintenanceFile);
    exit;
}

/**
 * Wartungsseite anzeigen
 */
function showMaintenancePage($maintenanceFile) {
    // Wartungsmeldung aus Datei lesen (falls vorhanden)
    $message = file_get_contents($maintenanceFile);
    if (empty(trim($message))) {
        $message = "Wir führen gerade Wartungsarbeiten durch.\nBitte versuchen Sie es in wenigen Minuten erneut.";
    }

    // Geschätzte Endzeit (falls in zweiter Zeile angegeben)
    $lines = explode("\n", $message, 2);
    $customMessage = $lines[0];
    $estimatedEnd = isset($lines[1]) ? trim($lines[1]) : null;

    http_response_code(503); // Service Unavailable
    header('Retry-After: 600'); // Retry nach 10 Minuten
    ?>
    <!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Wartungsarbeiten - PC-Wittfoot UG</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: #333;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                padding: 2rem;
            }
            .maintenance-container {
                background: white;
                border-radius: 20px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                padding: 3rem;
                max-width: 600px;
                text-align: center;
                animation: fadeIn 0.5s ease-in;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .icon {
                font-size: 4rem;
                margin-bottom: 1rem;
                animation: pulse 2s infinite;
            }
            @keyframes pulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.1); }
            }
            h1 {
                color: #667eea;
                font-size: 2rem;
                margin-bottom: 1rem;
            }
            p {
                color: #666;
                font-size: 1.1rem;
                line-height: 1.6;
                margin-bottom: 1rem;
                white-space: pre-line;
            }
            .estimated-time {
                background: #f0f0f0;
                border-radius: 10px;
                padding: 1rem;
                margin-top: 1.5rem;
                font-size: 0.9rem;
                color: #555;
            }
            .spinner {
                margin: 2rem auto;
                width: 50px;
                height: 50px;
                border: 5px solid #f3f3f3;
                border-top: 5px solid #667eea;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            .contact {
                margin-top: 2rem;
                padding-top: 2rem;
                border-top: 1px solid #eee;
                font-size: 0.9rem;
                color: #999;
            }
            .contact a {
                color: #667eea;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div class="maintenance-container">
            <div class="icon">🔧</div>
            <h1>Wartungsarbeiten</h1>
            <p><?= htmlspecialchars($customMessage) ?></p>
            <div class="spinner"></div>
            <?php if ($estimatedEnd): ?>
                <div class="estimated-time">
                    <strong>Voraussichtlich fertig:</strong><br>
                    <?= htmlspecialchars($estimatedEnd) ?>
                </div>
            <?php endif; ?>
            <div class="contact">
                Bei dringenden Anfragen:<br>
                <a href="mailto:info@pc-wittfoot.de">info@pc-wittfoot.de</a><br>
                Tel: +49 (0) 123 456789
            </div>
        </div>
    </body>
    </html>
    <?php
}
