<?php
/**
 * Datenbank-Export via reinem PHP (ohne mysqldump)
 */

require __DIR__ . '/src/core/config.php';

$db = Database::getInstance();
$dumpFile = __DIR__ . '/database/production-full-dump.sql';

echo "Starte Datenbank-Export via PHP...\n\n";

$sql = "-- PC-Wittfoot UG - Vollständiger Datenbank-Export\n";
$sql .= "-- Datum: " . date('Y-m-d H:i:s') . "\n";
$sql .= "-- Datenbank: " . DB_NAME . "\n\n";

$sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

// Hole alle Tabellen
$tables = $db->query("SHOW TABLES");
$tableNames = [];

foreach ($tables as $table) {
    $tableName = array_values($table)[0];
    $tableNames[] = $tableName;
}

echo "Gefundene Tabellen: " . count($tableNames) . "\n";

foreach ($tableNames as $tableName) {
    echo "Exportiere: $tableName...";

    // CREATE TABLE Statement
    $createTable = $db->querySingle("SHOW CREATE TABLE `$tableName`");
    $sql .= "-- Tabelle: $tableName\n";
    $sql .= "DROP TABLE IF EXISTS `$tableName`;\n";
    $sql .= $createTable['Create Table'] . ";\n\n";

    // Daten exportieren
    $rows = $db->query("SELECT * FROM `$tableName`");
    $rowCount = 0;

    if (!empty($rows)) {
        foreach ($rows as $row) {
            $values = [];
            foreach ($row as $value) {
                if ($value === null) {
                    $values[] = 'NULL';
                } else {
                    $values[] = "'" . addslashes($value) . "'";
                }
            }

            $columns = array_keys($row);
            $sql .= "INSERT INTO `$tableName` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $values) . ");\n";
            $rowCount++;
        }
        $sql .= "\n";
    }

    echo " $rowCount Zeilen\n";
}

$sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

// Schreibe in Datei
file_put_contents($dumpFile, $sql);

echo "\n✓ Export erfolgreich!\n";
echo "Datei: $dumpFile\n";
echo "Größe: " . number_format(filesize($dumpFile)) . " Bytes\n";
