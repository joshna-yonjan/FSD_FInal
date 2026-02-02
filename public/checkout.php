<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireLogin();
$pageTitle = 'Checkout';

if (empty($_SESSION['cart'])) {
    setFlash('error', 'Your cart is empty.');
    redirect('index.php');
}

// Process checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    $name = trim($_POST['customer_name'] ?? '');
    $email = trim($_POST['customer_email'] ?? '');
    $phone = trim($_POST['customer_phone'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    
    if ($name && $email && $phone) {
        try {
            $pdo->beginTransaction();
            
            // Calculate total
            $ids = array_keys($_SESSION['cart']);
            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id IN ($placeholders)");
            $stmt->execute($ids);
            $items = $stmt->fetchAll();
            
            $total = 0;
            foreach ($items as $item) {
                $total += $item['price'] * $_SESSION['cart'][$item['id']];
            }
            
            // Create order
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, total, notes) 
                                   VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([getUserId(), $name, $email, $phone, $total, $notes]);
            $orderId = $pdo->lastInsertId();
            
            // Add order items
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($items as $item) {
                $stmt->execute([$orderId, $item['id'], $_SESSION['cart'][$item['id']], $item['price']]);
            }
            
            $pdo->commit();
            
            // Clear cart
            unset($_SESSION['cart']);
            
            setFlash('success', 'Order placed successfully! Order #' . $orderId);
            redirect('my_orders.php');
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log($e->getMessage());
            $error = 'Failed to place order. Please try again.';
        }
    } else {
        $error = 'Please fill in all required fields.';
    }
}

include '../includes/header.php';
?>

<h2>✓ Checkout</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-error"><?= escape($error) ?></div>
<?php endif; ?>

<form method="POST" class="form-card">
    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
    
    <h3>Customer Information</h3>
    
    <div class="form-group">
        <label>Full Name *</label>
        <input type="text" name="customer_name" required>
    </div>
    
    <div class="form-group">
        <label>Email *</label>
        <input type="email" name="customer_email" required>
    </div>
    
    <div class="form-group">
        <label>Phone *</label>
        <input type="tel" name="customer_phone" required>
    </div>
    
    <div class="form-group">
        <label>Special Instructions</label>
        <textarea name="notes" rows="3" placeholder="Any allergies or special requests?"></textarea>
    </div>
    
    <div class="btn-group">
        <button type="submit" class="btn btn-success btn-lg">✓ Place Order</button>
        <a href="view_cart.php" class="btn btn-secondary">Back to Cart</a>
    </div>
</form>

<?php include '../includes/footer.php'; ?>
