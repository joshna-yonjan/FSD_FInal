<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireAdmin();
$pageTitle = 'Add Menu Item';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    $name = trim($_POST['name'] ?? '');
    $category_id = $_POST['category_id'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $cuisine = trim($_POST['cuisine'] ?? '');
    $availability = isset($_POST['availability']) ? 1 : 0;
    
    if ($name && $category_id && $price) {
        try {
            $stmt = $pdo->prepare("INSERT INTO menu_items (name, category_id, price, description, cuisine, availability) 
                                   VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $category_id, $price, $description, $cuisine, $availability]);
            setFlash('success', 'Menu item added successfully!');
            redirect('index.php');
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $error = 'Failed to add item.';
        }
    } else {
        $error = 'Please fill in required fields.';
    }
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

include '../includes/header.php';
?>

<h2>➕ Add Menu Item</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-error"><?= escape($error) ?></div>
<?php endif; ?>

<form method="POST" class="form-card">
    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
    
    <div class="form-group">
        <label>Item Name *</label>
        <input type="text" name="name" required>
    </div>
    
    <div class="form-group">
        <label>Category *</label>
        <select name="category_id" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= escape($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label>Price ($) *</label>
        <input type="number" name="price" step="0.01" min="0" required>
    </div>
    
    <div class="form-group">
        <label>Cuisine Type</label>
        <input type="text" name="cuisine" placeholder="e.g., Italian, Asian">
    </div>
    
    <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="3"></textarea>
    </div>
    
    <div class="form-group">
        <label>
            <input type="checkbox" name="availability" checked> Available
        </label>
    </div>
    
    <div class="btn-group">
        <button type="submit" class="btn btn-success">✓ Add Item</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<?php include '../includes/footer.php'; ?>
