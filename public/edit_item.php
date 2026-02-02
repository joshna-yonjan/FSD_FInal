<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireAdmin();
$pageTitle = 'Edit Menu Item';

$id = $_GET['id'] ?? 0;

// Get current item
try {
    $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    
    if (!$item) {
        setFlash('error', 'Item not found.');
        redirect('index.php');
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    redirect('index.php');
}

// Update item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    $name = trim($_POST['name'] ?? '');
    $category_id = $_POST['category_id'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $cuisine = trim($_POST['cuisine'] ?? '');
    $availability = isset($_POST['availability']) ? 1 : 0;
    
    if ($name && $category_id && $price) {
        try {
            $stmt = $pdo->prepare("UPDATE menu_items SET name=?, category_id=?, price=?, description=?, cuisine=?, availability=? WHERE id=?");
            $stmt->execute([$name, $category_id, $price, $description, $cuisine, $availability, $id]);
            setFlash('success', 'Item updated successfully!');
            redirect('index.php');
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $error = 'Failed to update item.';
        }
    } else {
        $error = 'Please fill in required fields.';
    }
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

include '../includes/header.php';
?>

<h2>✏️ Edit Menu Item</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-error"><?= escape($error) ?></div>
<?php endif; ?>

<form method="POST" class="form-card">
    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
    
    <div class="form-group">
        <label>Item Name *</label>
        <input type="text" name="name" value="<?= escape($item['name']) ?>" required>
    </div>
    
    <div class="form-group">
        <label>Category *</label>
        <select name="category_id" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $item['category_id'] ? 'selected' : '' ?>>
                    <?= escape($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label>Price ($) *</label>
        <input type="number" name="price" step="0.01" value="<?= $item['price'] ?>" required>
    </div>
    
    <div class="form-group">
        <label>Cuisine Type</label>
        <input type="text" name="cuisine" value="<?= escape($item['cuisine']) ?>">
    </div>
    
    <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="3"><?= escape($item['description']) ?></textarea>
    </div>
    
    <div class="form-group">
        <label>
            <input type="checkbox" name="availability" <?= $item['availability'] ? 'checked' : '' ?>> Available
        </label>
    </div>
    
    <div class="btn-group">
        <button type="submit" class="btn btn-success">✓ Update Item</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<?php include '../includes/footer.php'; ?>
