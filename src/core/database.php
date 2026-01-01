<?php
/**
 * Datenbank-Klasse (PDO-Wrapper)
 * PC-Wittfoot
 *
 * Sichere Datenbank-Verbindung mit Prepared Statements
 */

class Database {
    private static $instance = null;
    private $pdo;

    /**
     * Singleton-Pattern
     */
    private function __construct() {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
            ];

            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                die('Datenbankverbindung fehlgeschlagen: ' . $e->getMessage());
            } else {
                die('Datenbankverbindung fehlgeschlagen. Bitte später versuchen.');
            }
        }
    }

    /**
     * Singleton-Instanz holen
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * PDO-Objekt zurückgeben
     */
    public function getConnection() {
        return $this->pdo;
    }

    /**
     * Query ausführen (SELECT)
     *
     * @param string $sql SQL-Query
     * @param array $params Parameter für Prepared Statement
     * @return array Ergebnisse
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->logError($e, $sql);
            return [];
        }
    }

    /**
     * Einzelne Zeile holen
     */
    public function querySingle($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->logError($e, $sql);
            return null;
        }
    }

    /**
     * INSERT ausführen
     *
     * @return int Last Insert ID
     */
    public function insert($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            $this->logError($e, $sql);
            return false;
        }
    }

    /**
     * UPDATE ausführen
     *
     * @return int Anzahl betroffener Zeilen
     */
    public function update($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->logError($e, $sql);
            return false;
        }
    }

    /**
     * DELETE ausführen
     *
     * @return int Anzahl gelöschter Zeilen
     */
    public function delete($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->logError($e, $sql);
            return false;
        }
    }

    /**
     * Transaktion starten
     */
    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }

    /**
     * Transaktion committen
     */
    public function commit() {
        return $this->pdo->commit();
    }

    /**
     * Transaktion zurückrollen
     */
    public function rollback() {
        return $this->pdo->rollBack();
    }

    /**
     * Fehler loggen
     */
    private function logError($e, $sql) {
        if (DEBUG_MODE) {
            error_log('DB Error: ' . $e->getMessage() . ' | SQL: ' . $sql);
            echo '<div style="background: #ff5722; color: white; padding: 20px; margin: 20px;">';
            echo '<strong>Datenbankfehler:</strong><br>';
            echo htmlspecialchars($e->getMessage()) . '<br>';
            echo '<strong>SQL:</strong> ' . htmlspecialchars($sql);
            echo '</div>';
        } else {
            error_log('DB Error: ' . $e->getMessage());
        }
    }
}
