<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireLogin();
$pageTitle = 'Shopping Cart';

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

$cartItems = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    
    $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $items = $stmt->fetchAll();
    
    foreach ($items as $item) {
        $quantity = $_SESSION['cart'][$item['id']];
        $subtotal = $item['price'] * $quantity;
        $total += $subtotal;
        
        $cartItems[] = [
            'id' => $item['id'],
            'name' => $item['name'],
            'price' => $item['price'],
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ];
    }
}

include '../includes/header.php';
?>

<h2>🛒 Shopping Cart</h2>

<?php if (empty($cartItems)): ?>
    <div class="empty-state">
        <div class="empty-icon">🛒</div>
        <p>Your cart is empty.</p>
        <a href="index.php" class="btn btn-primary">Browse Menu</a>
    </div>
<?php else: ?>
    <table class="data-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td><strong><?= escape($item['name']) ?></strong></td>
                    <td><?= formatPrice($item['price']) ?></td>
                    <td>
                        <input type="number" value="<?= $item['quantity'] ?>" min="1" 
                               onchange="updateCart(<?= $item['id'] ?>, this.value)" 
                               style="width:60px;">
                    </td>
                    <td><?= formatPrice($item['subtotal']) ?></td>
                    <td>
                        <button onclick="removeFromCart(<?= $item['id'] ?>)" class="btn btn-danger btn-sm">🗑️</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Total</th>
                <th><?= formatPrice($total) ?></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    
    <div class="btn-group">
        <a href="checkout.php" class="btn btn-success btn-lg">✓ Proceed to Checkout</a>
        <a href="index.php" class="btn btn-secondary">Continue Shopping</a>
    </div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
