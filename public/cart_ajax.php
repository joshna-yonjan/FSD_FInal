<?php
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_POST['action'] ?? '';
$itemId = (int)($_POST['item_id'] ?? 0);

switch ($action) {
    case 'add':
        $_SESSION['cart'][$itemId] = ($_SESSION['cart'][$itemId] ?? 0) + 1;
        echo json_encode(['success' => true, 'count' => getCartCount()]);
        break;
        
    case 'update':
        $quantity = (int)($_POST['quantity'] ?? 1);
        if ($quantity > 0) {
            $_SESSION['cart'][$itemId] = $quantity;
        } else {
            unset($_SESSION['cart'][$itemId]);
        }
        echo json_encode(['success' => true, 'count' => getCartCount()]);
        break;
        
    case 'remove':
        unset($_SESSION['cart'][$itemId]);
        echo json_encode(['success' => true, 'count' => getCartCount()]);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
