<?php
/**
 * Warenkorb-Klasse (Session-basiert)
 * PC-Wittfoot
 */

class Cart {
    private $db;
    private $items = [];

    public function __construct() {
        $this->db = Database::getInstance();

        // Warenkorb aus Session laden
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            $this->items = $_SESSION['cart'];
        }
    }

    /**
     * Produkt zum Warenkorb hinzufügen
     */
    public function addItem($product_id, $quantity = 1) {
        $product_id = intval($product_id);
        $quantity = max(1, intval($quantity));

        // Produkt aus DB laden
        $product = $this->db->querySingle("
            SELECT id, name, price, stock, slug, ean
            FROM products
            WHERE id = :id AND is_active = 1
        ", [':id' => $product_id]);

        if (!$product) {
            return ['success' => false, 'message' => 'Produkt nicht gefunden.'];
        }

        if ($product['stock'] < 1) {
            return ['success' => false, 'message' => 'Produkt nicht auf Lager.'];
        }

        // Wenn Produkt bereits im Warenkorb, Menge erhöhen
        if (isset($this->items[$product_id])) {
            $new_quantity = $this->items[$product_id]['quantity'] + $quantity;

            // Lagerbestand prüfen
            if ($new_quantity > $product['stock']) {
                return ['success' => false, 'message' => 'Nicht genügend auf Lager. Verfügbar: ' . $product['stock']];
            }

            $this->items[$product_id]['quantity'] = $new_quantity;
        } else {
            // Neues Produkt hinzufügen
            if ($quantity > $product['stock']) {
                return ['success' => false, 'message' => 'Nicht genügend auf Lager. Verfügbar: ' . $product['stock']];
            }

            $this->items[$product_id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => floatval($product['price']),
                'quantity' => $quantity,
                'slug' => $product['slug'],
                'ean' => $product['ean'] ?? null
            ];
        }

        $this->save();
        return ['success' => true, 'message' => 'Produkt zum Warenkorb hinzugefügt.'];
    }

    /**
     * Produktmenge aktualisieren
     */
    public function updateQuantity($product_id, $quantity) {
        $product_id = intval($product_id);
        $quantity = max(0, intval($quantity));

        if (!isset($this->items[$product_id])) {
            return ['success' => false, 'message' => 'Produkt nicht im Warenkorb.'];
        }

        // Wenn Menge 0, Produkt entfernen
        if ($quantity === 0) {
            return $this->removeItem($product_id);
        }

        // Lagerbestand prüfen
        $product = $this->db->querySingle("
            SELECT stock FROM products WHERE id = :id
        ", [':id' => $product_id]);

        if ($quantity > $product['stock']) {
            return ['success' => false, 'message' => 'Nicht genügend auf Lager. Verfügbar: ' . $product['stock']];
        }

        $this->items[$product_id]['quantity'] = $quantity;
        $this->save();

        return ['success' => true, 'message' => 'Menge aktualisiert.'];
    }

    /**
     * Produkt aus Warenkorb entfernen
     */
    public function removeItem($product_id) {
        $product_id = intval($product_id);

        if (isset($this->items[$product_id])) {
            unset($this->items[$product_id]);
            $this->save();
            return ['success' => true, 'message' => 'Produkt entfernt.'];
        }

        return ['success' => false, 'message' => 'Produkt nicht im Warenkorb.'];
    }

    /**
     * Warenkorb leeren
     */
    public function clear() {
        $this->items = [];
        $this->save();
        return ['success' => true, 'message' => 'Warenkorb geleert.'];
    }

    /**
     * Alle Artikel holen
     */
    public function getItems() {
        return $this->items;
    }

    /**
     * Anzahl Artikel
     */
    public function getItemCount() {
        $count = 0;
        foreach ($this->items as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    /**
     * Gesamtsumme berechnen (brutto)
     * Preise in DB sind bereits Bruttopreise
     */
    public function getTotal() {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    /**
     * Nettosumme berechnen
     * Für Gewerbekunden
     */
    public function getNet() {
        return $this->getTotal() / (1 + TAX_RATE);
    }

    /**
     * MwSt berechnen
     */
    public function getTax() {
        return $this->getTotal() - $this->getNet();
    }

    /**
     * Alias für Kompatibilität
     * @deprecated Verwende getTotal() für Brutto oder getNet() für Netto
     */
    public function getSubtotal() {
        return $this->getNet();
    }

    /**
     * Warenkorb in Session speichern
     */
    private function save() {
        $_SESSION['cart'] = $this->items;
    }

    /**
     * Prüfen ob Warenkorb leer ist
     */
    public function isEmpty() {
        return empty($this->items);
    }

    /**
     * Lagerbestände validieren (vor Bestellung)
     */
    public function validateStock() {
        $errors = [];

        foreach ($this->items as $product_id => $item) {
            $product = $this->db->querySingle("
                SELECT stock, name FROM products WHERE id = :id
            ", [':id' => $product_id]);

            if (!$product) {
                $errors[] = "Produkt '{$item['name']}' nicht mehr verfügbar.";
                continue;
            }

            if ($item['quantity'] > $product['stock']) {
                $errors[] = "'{$product['name']}' nur noch {$product['stock']}x verfügbar (im Warenkorb: {$item['quantity']}x).";
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}
