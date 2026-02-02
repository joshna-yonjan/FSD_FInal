<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

$pageTitle = 'Menu';

// Build query with filters
$sql = "SELECT menu_items.*, categories.name AS category_name 
        FROM menu_items 
        JOIN categories ON menu_items.category_id = categories.id 
        WHERE 1=1";
$params = [];

if (!empty($_GET['keyword'])) {
    $sql .= " AND menu_items.name LIKE ?";
    $params[] = "%" . $_GET['keyword'] . "%";
}

if (!empty($_GET['category_id'])) {
    $sql .= " AND menu_items.category_id = ?";
    $params[] = $_GET['category_id'];
}

if (isset($_GET['availability']) && $_GET['availability'] !== '') {
    $sql .= " AND menu_items.availability = ?";
    $params[] = $_GET['availability'];
}

if (!empty($_GET['min_price'])) {
    $sql .= " AND menu_items.price >= ?";
    $params[] = $_GET['min_price'];
}

if (!empty($_GET['max_price'])) {
    $sql .= " AND menu_items.price <= ?";
    $params[] = $_GET['max_price'];
}

$sql .= " ORDER BY menu_items.id DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $items = $stmt->fetchAll();
    $categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $items = [];
    $categories = [];
}

include '../includes/header.php';
?>

<h2 style="margin-bottom: 2rem;">🍽️ Our Menu</h2>

<!-- Search Form -->
<div class="search-form">
    <form method="GET">
        <div class="form-row">
            <input type="text" name="keyword" placeholder="Search dishes..." value="<?= escape($_GET['keyword'] ?? '') ?>">
            
            <select name="category_id">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= escape($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <input type="number" name="min_price" placeholder="Min $" step="0.01" value="<?= escape($_GET['min_price'] ?? '') ?>">
            <input type="number" name="max_price" placeholder="Max $" step="0.01" value="<?= escape($_GET['max_price'] ?? '') ?>">
            
            <select name="availability">
                <option value="">All Items</option>
                <option value="1" <?= (isset($_GET['availability']) && $_GET['availability'] == '1') ? 'selected' : '' ?>>Available</option>
                <option value="0" <?= (isset($_GET['availability']) && $_GET['availability'] == '0') ? 'selected' : '' ?>>Unavailable</option>
            </select>
        </div>
        
        <div class="btn-group">
            <button type="submit" class="btn btn-primary">🔍 Search</button>
            <a href="index.php" class="btn btn-secondary">🔄 Reset</a>
        </div>
    </form>
</div>

<!-- Menu Items -->
<?php if (empty($items)): ?>
    <div class="empty-state">
        <div class="empty-icon">🍽️</div>
        <p>No menu items found.</p>
        <a href="index.php" class="btn btn-primary">View All</a>
    </div>
<?php else: ?>
    <p class="results-count">Found <?= count($items) ?> item<?= count($items) != 1 ? 's' : '' ?></p>
    
    <div class="menu-grid">
        <?php foreach ($items as $item): ?>
            <div class="menu-card">
                <div class="menu-card-image">
                    🍴
                </div>
                <div class="menu-card-content">
                    <h3><?= escape($item['name']) ?></h3>
                    <div class="category"><?= escape($item['category_name']) ?></div>
                    
                    <?php if ($item['description']): ?>
                        <p><?= escape($item['description']) ?></p>
                    <?php endif; ?>
                    
                    <div class="price-status">
                        <span class="price"><?= formatPrice($item['price']) ?></span>
                        <span class="badge <?= $item['availability'] ? 'badge-success' : 'badge-danger' ?>">
                            <?= $item['availability'] ? '✓ Available' : '✗ Unavailable' ?>
                        </span>
                    </div>
                    
                    <div class="actions">
                        <?php if ($item['availability'] && isLoggedIn()): ?>
                            <button onclick="addToCart(<?= $item['id'] ?>)" class="btn btn-success btn-sm">🛒 Add to Cart</button>
                        <?php endif; ?>
                        
                        <?php if (isAdmin()): ?>
                            <a href="edit_item.php?id=<?= $item['id'] ?>" class="btn btn-warning btn-sm">✏️ Edit</a>
                            <a href="delete_item.php?id=<?= $item['id'] ?>" class="btn btn-danger btn-sm" 
                               onclick="return confirm('Delete this item?')">🗑️</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
