<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireAdmin();
$pageTitle = 'Manage Orders';

// Update order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    $validStatuses = ['Pending', 'Confirmed', 'Preparing', 'Ready', 'Delivered', 'Cancelled'];
    
    if (in_array($_POST['status'], $validStatuses)) {
        try {
            $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->execute([$_POST['status'], $_POST['order_id']]);
            setFlash('success', 'Order status updated.');
        } catch (PDOException $e) {
            error_log($e->getMessage());
            setFlash('error', 'Failed to update status.');
        }
    }
    redirect('admin_orders.php');
}

// Get orders with filter
$filter = $_GET['filter'] ?? 'all';
$sql = "SELECT orders.*, users.username FROM orders LEFT JOIN users ON orders.user_id = users.id";

if ($filter !== 'all') {
    $sql .= " WHERE orders.status = :status";
}

$sql .= " ORDER BY orders.created_at DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($filter !== 'all' ? ['status' => ucfirst($filter)] : []);
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $orders = [];
}

include '../includes/header.php';
?>

<h2>📊 Order Management</h2>

<!-- Filter Buttons -->
<div class="btn-group">
    <?php 
    $filters = ['all' => 'All', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 
                'preparing' => 'Preparing', 'ready' => 'Ready', 'delivered' => 'Delivered'];
    foreach ($filters as $key => $label): 
    ?>
        <a href="?filter=<?= $key ?>" class="btn btn-sm <?= $filter === $key ? 'btn-primary' : 'btn-secondary' ?>">
            <?= $label ?>
        </a>
    <?php endforeach; ?>
</div>

<?php if (empty($orders)): ?>
    <div class="empty-state">
        <div class="empty-icon">📦</div>
        <p>No orders found.</p>
    </div>
<?php else: ?>
    <p class="results-count">Showing <?= count($orders) ?> order<?= count($orders) != 1 ? 's' : '' ?></p>
    
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
                    <p class="order-date">
                        <?= date('M d, Y \a\t h:i A', strtotime($order['created_at'])) ?>
                        <?php if ($order['username']): ?>by <strong><?= escape($order['username']) ?></strong><?php endif; ?>
                    </p>
                </div>
                <div>
                    <span class="badge <?= $statusClass ?>"><?= escape($order['status']) ?></span>
                    
                    <form method="POST" style="display:inline-block;margin-left:1rem;">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <select name="status" onchange="this.form.submit()" class="btn btn-sm">
                            <option value="">Change Status...</option>
                            <?php foreach (['Pending', 'Confirmed', 'Preparing', 'Ready', 'Delivered', 'Cancelled'] as $s): ?>
                                <option value="<?= $s ?>"><?= $s ?></option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>
            
            <div class="order-details">
                <div>
                    <strong>Items:</strong>
                    <ul>
                        <?php foreach ($items as $item): ?>
                            <li><?= escape($item['name']) ?> × <?= $item['quantity'] ?> @ <?= formatPrice($item['price']) ?> 
                                = <strong><?= formatPrice($item['price'] * $item['quantity']) ?></strong>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div>
                    <strong>Customer:</strong>
                    <p><?= escape($order['customer_name']) ?><br>
                    <?= escape($order['customer_email']) ?><br>
                    <?= escape($order['customer_phone']) ?></p>
                </div>
                
                <div>
                    <strong>Total:</strong>
                    <p class="order-total"><?= formatPrice($order['total']) ?></p>
                </div>
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
