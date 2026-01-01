<?php
/**
 * CSV-Importer für Lieferanten-Produkte
 * PC-Wittfoot UG
 */

class CSVImporter {
    private $db;
    private $supplier;
    private $log_id;
    private $stats = [
        'imported' => 0,
        'updated' => 0,
        'skipped' => 0,
        'errors' => 0
    ];
    private $errors = [];
    private $start_time;

    public function __construct($supplier_id) {
        $this->db = Database::getInstance();
        $this->start_time = microtime(true);

        // Lieferant laden
        $this->supplier = $this->db->querySingle(
            "SELECT * FROM suppliers WHERE id = :id AND is_active = 1",
            [':id' => $supplier_id]
        );

        if (!$this->supplier) {
            throw new Exception("Lieferant nicht gefunden oder inaktiv");
        }

        // Import-Log erstellen
        $this->log_id = $this->db->insert(
            "INSERT INTO product_import_logs (supplier_id, status, created_at) VALUES (:supplier_id, 'running', NOW())",
            [':supplier_id' => $supplier_id]
        );
    }

    /**
     * CSV-Import durchführen
     */
    public function import($csv_path) {
        try {
            // CSV-Datei öffnen
            if (!file_exists($csv_path)) {
                throw new Exception("CSV-Datei nicht gefunden: $csv_path");
            }

            $handle = fopen($csv_path, 'r');
            if (!$handle) {
                throw new Exception("CSV-Datei konnte nicht geöffnet werden");
            }

            // Encoding konvertieren falls nötig
            $encoding = $this->supplier['csv_encoding'] ?? 'UTF-8';
            $delimiter = $this->supplier['csv_delimiter'] ?? ',';

            // Spalten-Mapping laden
            $column_mapping = json_decode($this->supplier['column_mapping'], true);
            if (!$column_mapping) {
                throw new Exception("Spalten-Mapping nicht konfiguriert");
            }

            // Header-Zeile lesen
            $header = fgetcsv($handle, 0, $delimiter);
            if (!$header) {
                throw new Exception("CSV-Header konnte nicht gelesen werden");
            }

            // Zeilen verarbeiten
            $row_number = 1;
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                $row_number++;

                // Encoding konvertieren
                if ($encoding !== 'UTF-8') {
                    $row = array_map(function($val) use ($encoding) {
                        return mb_convert_encoding($val, 'UTF-8', $encoding);
                    }, $row);
                }

                try {
                    $this->processRow($row, $header, $column_mapping);
                } catch (Exception $e) {
                    $this->stats['errors']++;
                    $this->errors[] = "Zeile $row_number: " . $e->getMessage();
                }
            }

            fclose($handle);

            // Log aktualisieren
            $duration = round(microtime(true) - $this->start_time);
            $this->db->update("
                UPDATE product_import_logs SET
                    status = 'completed',
                    imported_count = :imported,
                    updated_count = :updated,
                    skipped_count = :skipped,
                    error_count = :errors,
                    log_details = :details,
                    duration_seconds = :duration,
                    completed_at = NOW()
                WHERE id = :id
            ", [
                ':imported' => $this->stats['imported'],
                ':updated' => $this->stats['updated'],
                ':skipped' => $this->stats['skipped'],
                ':errors' => $this->stats['errors'],
                ':details' => json_encode(['errors' => $this->errors]),
                ':duration' => $duration,
                ':id' => $this->log_id
            ]);

            // Lieferant: last_import_at aktualisieren
            $this->db->update(
                "UPDATE suppliers SET last_import_at = NOW() WHERE id = :id",
                [':id' => $this->supplier['id']]
            );

            return [
                'success' => true,
                'stats' => $this->stats,
                'errors' => $this->errors,
                'duration' => $duration
            ];

        } catch (Exception $e) {
            // Log als fehlgeschlagen markieren
            $this->db->update("
                UPDATE product_import_logs SET
                    status = 'failed',
                    log_details = :details,
                    completed_at = NOW()
                WHERE id = :id
            ", [
                ':details' => json_encode(['error' => $e->getMessage()]),
                ':id' => $this->log_id
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'stats' => $this->stats
            ];
        }
    }

    /**
     * Einzelne CSV-Zeile verarbeiten
     */
    private function processRow($row, $header, $column_mapping) {
        // Daten aus CSV extrahieren nach Mapping
        $data = [];
        foreach ($column_mapping as $field => $csv_column) {
            $column_index = array_search($csv_column, $header);
            if ($column_index !== false && isset($row[$column_index])) {
                $data[$field] = trim($row[$column_index]);
            }
        }

        // Pflichtfelder prüfen
        if (empty($data['name']) || empty($data['sku'])) {
            $this->stats['skipped']++;
            throw new Exception("Name oder SKU fehlt");
        }

        // Preis mit Aufschlag berechnen
        $supplier_price = (float)($data['price'] ?? 0);
        if ($supplier_price <= 0) {
            $this->stats['skipped']++;
            throw new Exception("Ungültiger Preis");
        }

        $markup = (float)($this->supplier['price_markup'] ?? 0);
        $calculated_price = $supplier_price * (1 + ($markup / 100));

        // Auf nächsten 10er minus 1 runden (40,20 → 49)
        $selling_price = ceil($calculated_price / 10) * 10 - 1;

        // Lagerbestand
        $supplier_stock = (int)($data['stock'] ?? 0);

        // Versandkosten vom Lieferanten übernehmen
        $free_shipping = (int)($this->supplier['free_shipping'] ?? 0);

        // Prüfen ob Produkt bereits existiert
        $existing = $this->db->querySingle(
            "SELECT id FROM products WHERE sku = :sku AND supplier_id = :supplier_id",
            [
                ':sku' => $data['sku'],
                ':supplier_id' => $this->supplier['id']
            ]
        );

        if ($existing) {
            // Produkt aktualisieren
            $this->db->update("
                UPDATE products SET
                    name = :name,
                    ean = :ean,
                    description = :description,
                    price = :price,
                    supplier_stock = :supplier_stock,
                    supplier_name = :supplier_name,
                    free_shipping = :free_shipping,
                    last_csv_sync = NOW(),
                    updated_at = NOW()
                WHERE id = :id
            ", [
                ':name' => $data['name'],
                ':ean' => $data['ean'] ?? null,
                ':description' => $data['description'] ?? '',
                ':price' => $selling_price,
                ':supplier_stock' => $supplier_stock,
                ':supplier_name' => $this->supplier['name'],
                ':free_shipping' => $free_shipping,
                ':id' => $existing['id']
            ]);

            $this->stats['updated']++;
        } else {
            // Neues Produkt erstellen
            $this->db->insert("
                INSERT INTO products (
                    name, sku, ean, slug, description, price, stock, supplier_id, supplier_name,
                    supplier_stock, free_shipping, source, is_active, last_csv_sync, created_at
                ) VALUES (
                    :name, :sku, :ean, :slug, :description, :price, 0, :supplier_id, :supplier_name,
                    :supplier_stock, :free_shipping, 'csv_import', 0, NOW(), NOW()
                )
            ", [
                ':name' => $data['name'],
                ':sku' => $data['sku'],
                ':ean' => $data['ean'] ?? null,
                ':slug' => create_slug($data['name']),
                ':description' => $data['description'] ?? '',
                ':price' => $selling_price,
                ':supplier_id' => $this->supplier['id'],
                ':supplier_name' => $this->supplier['name'],
                ':supplier_stock' => $supplier_stock,
                ':free_shipping' => $free_shipping
            ]);

            $this->stats['imported']++;
        }
    }

    /**
     * Statistiken abrufen
     */
    public function getStats() {
        return $this->stats;
    }
}
