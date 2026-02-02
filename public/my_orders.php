<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireLogin();
$pageTitle = 'My Orders';

try {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([getUserId()]);
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $orders = [];
}

include '../includes/header.php';
?>

<h2>📦 My Orders</h2>

<?php if (empty($orders)): ?>
    <div class="empty-state">
        <div class="empty-icon">📦</div>
        <p>You haven't placed any orders yet.</p>
        <a href="index.php" class="btn btn-primary">Browse Menu</a>
    </div>
<?php else: ?>
    <?php foreach ($orders as $order): 
        // Get order items
        $stmt = $pdo->prepare("SELECT oi.*, mi.name FROM order_items oi 
                               JOIN menu_items mi ON oi.menu_item_id = mi.id 
                               WHERE oi.order_id = ?");
        $stmt->execute([$order['id']]);
        $items = $stmt->fetchAll();
        
        $statusClass = match($order['status']) {
            'Confirmed', 'Preparing' => 'badge-warning',
            'Ready', 'Delivered' => 'badge-success',
            'Cancelled' => 'badge-danger',
            default => 'badge-info'
        };
    ?>
        <div class="order-card">
            <div class="order-header">
                <div>
                    <h3>Order #<?= $order['id'] ?></h3>
                    <p class="order-date"><?= date('M d, Y \a\t h:i A', strtotime($order['created_at'])) ?></p>
                </div>
                <span class="badge <?= $statusClass ?>"><?= escape($order['status']) ?></span>
            </div>
            
            <div class="order-items">
                <strong>Items:</strong>
                <ul>
                    <?php foreach ($items as $item): ?>
                        <li><?= escape($item['name']) ?> × <?= $item['quantity'] ?> @ <?= formatPrice($item['price']) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="order-total">
                <strong>Total:</strong> <?= formatPrice($order['total']) ?>
            </div>
            
            <?php if ($order['notes']): ?>
                <div class="order-notes">
                    <strong>Notes:</strong> <?= escape($order['notes']) ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
