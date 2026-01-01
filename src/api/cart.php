<?php
/**
 * Warenkorb API
 * Aktionen: add, update, remove, clear, get
 */

require_once __DIR__ . '/../core/config.php';

start_session_safe();

header('Content-Type: application/json; charset=UTF-8');

// Cart-Instanz erstellen
$cart = new Cart();

// Aktion aus POST oder GET
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// CSRF-Schutz für modifizierende Aktionen
if (in_array($action, ['add', 'update', 'remove', 'clear'])) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_response(['success' => false, 'message' => 'Nur POST erlaubt.'], 405);
    }

    $token = $_POST['csrf_token'] ?? '';
    if (!csrf_verify($token)) {
        json_response(['success' => false, 'message' => 'CSRF-Token ungültig.'], 403);
    }
}

switch ($action) {

    // Produkt hinzufügen
    case 'add':
        $product_id = intval($_POST['product_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 1);

        $result = $cart->addItem($product_id, $quantity);
        $result['cart_count'] = $cart->getItemCount();
        json_response($result);
        break;

    // Menge aktualisieren
    case 'update':
        $product_id = intval($_POST['product_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 0);

        $result = $cart->updateQuantity($product_id, $quantity);
        $result['cart_count'] = $cart->getItemCount();
        $result['cart_totals'] = [
            'net' => $cart->getNet(),
            'tax' => $cart->getTax(),
            'total' => $cart->getTotal()
        ];
        json_response($result);
        break;

    // Produkt entfernen
    case 'remove':
        $product_id = intval($_POST['product_id'] ?? 0);

        $result = $cart->removeItem($product_id);
        $result['cart_count'] = $cart->getItemCount();
        $result['cart_totals'] = [
            'net' => $cart->getNet(),
            'tax' => $cart->getTax(),
            'total' => $cart->getTotal()
        ];
        json_response($result);
        break;

    // Warenkorb leeren
    case 'clear':
        $result = $cart->clear();
        json_response($result);
        break;

    // Warenkorb-Daten holen (GET)
    case 'get':
        json_response([
            'success' => true,
            'items' => $cart->getItems(),
            'count' => $cart->getItemCount(),
            'net' => $cart->getNet(),
            'tax' => $cart->getTax(),
            'total' => $cart->getTotal()
        ]);
        break;

    default:
        json_response(['success' => false, 'message' => 'Ungültige Aktion.'], 400);
}
