<?php
/**
 * Health-Check Endpoint
 * PC-Wittfoot UG
 *
 * Überprüft Systemstatus für Deployment-Monitoring
 * GET /api/health-check
 */

require_once __DIR__ . '/../core/config.php';

header('Content-Type: application/json; charset=UTF-8');

// Antwort-Array
$response = [
    'status' => 'ok',
    'timestamp' => date('Y-m-d H:i:s'),
    'version' => '1.0.0',
    'checks' => []
];

// 1. Datenbank-Check
try {
    $db = Database::getInstance();
    $result = $db->querySingle("SELECT 1 as test");

    if ($result && isset($result['test']) && $result['test'] == 1) {
        $response['checks']['database'] = [
            'status' => 'ok',
            'message' => 'Datenbankverbindung erfolgreich'
        ];
    } else {
        throw new Exception('Ungültiges Ergebnis');
    }
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['checks']['database'] = [
        'status' => 'error',
        'message' => 'Datenbankverbindung fehlgeschlagen: ' . $e->getMessage()
    ];
}

// 2. Email-Service Check
try {
    if (class_exists('EmailService')) {
        $emailService = new EmailService();
        $response['checks']['email_service'] = [
            'status' => 'ok',
            'message' => 'EmailService geladen'
        ];
    } else {
        throw new Exception('EmailService Klasse nicht gefunden');
    }
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['checks']['email_service'] = [
        'status' => 'error',
        'message' => 'EmailService fehlgeschlagen: ' . $e->getMessage()
    ];
}

// 3. Composer Vendor Check
if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    $response['checks']['composer'] = [
        'status' => 'ok',
        'message' => 'Composer Autoloader vorhanden'
    ];
} else {
    $response['status'] = 'warning';
    $response['checks']['composer'] = [
        'status' => 'warning',
        'message' => 'Composer vendor/ Verzeichnis nicht gefunden'
    ];
}

// 4. Logs-Verzeichnis Check
$logsDir = __DIR__ . '/../../logs';
if (is_dir($logsDir) && is_writable($logsDir)) {
    $response['checks']['logs'] = [
        'status' => 'ok',
        'message' => 'Logs-Verzeichnis beschreibbar'
    ];
} else {
    $response['status'] = 'warning';
    $response['checks']['logs'] = [
        'status' => 'warning',
        'message' => 'Logs-Verzeichnis nicht beschreibbar'
    ];
}

// 5. Uploads-Verzeichnis Check
$uploadsDir = __DIR__ . '/../uploads';
if (is_dir($uploadsDir) && is_writable($uploadsDir)) {
    $response['checks']['uploads'] = [
        'status' => 'ok',
        'message' => 'Uploads-Verzeichnis beschreibbar'
    ];
} else {
    $response['status'] = 'warning';
    $response['checks']['uploads'] = [
        'status' => 'warning',
        'message' => 'Uploads-Verzeichnis nicht beschreibbar'
    ];
}

// 6. Speicherplatz Check (wenn verfügbar)
$diskFree = @disk_free_space(__DIR__);
$diskTotal = @disk_total_space(__DIR__);

if ($diskFree !== false && $diskTotal !== false) {
    $percentFree = ($diskFree / $diskTotal) * 100;

    if ($percentFree < 10) {
        $response['status'] = 'warning';
        $response['checks']['disk_space'] = [
            'status' => 'warning',
            'message' => 'Wenig Speicherplatz verfügbar: ' . round($percentFree, 2) . '%',
            'free_gb' => round($diskFree / 1024 / 1024 / 1024, 2),
            'total_gb' => round($diskTotal / 1024 / 1024 / 1024, 2)
        ];
    } else {
        $response['checks']['disk_space'] = [
            'status' => 'ok',
            'message' => 'Speicherplatz ausreichend: ' . round($percentFree, 2) . '%',
            'free_gb' => round($diskFree / 1024 / 1024 / 1024, 2),
            'total_gb' => round($diskTotal / 1024 / 1024 / 1024, 2)
        ];
    }
}

// 7. PHP Version Check
$phpVersion = phpversion();
$response['checks']['php_version'] = [
    'status' => 'ok',
    'message' => 'PHP Version: ' . $phpVersion,
    'version' => $phpVersion
];

// 8. Wartungsmodus Status
$maintenanceFile = __DIR__ . '/../MAINTENANCE';
if (file_exists($maintenanceFile)) {
    $response['checks']['maintenance_mode'] = [
        'status' => 'warning',
        'message' => 'Wartungsmodus ist AKTIV',
        'enabled' => true
    ];
} else {
    $response['checks']['maintenance_mode'] = [
        'status' => 'ok',
        'message' => 'Wartungsmodus ist inaktiv',
        'enabled' => false
    ];
}

// HTTP Status Code basierend auf Gesamtstatus
if ($response['status'] === 'error') {
    http_response_code(503); // Service Unavailable
} elseif ($response['status'] === 'warning') {
    http_response_code(200); // OK, aber mit Warnungen
} else {
    http_response_code(200); // OK
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
