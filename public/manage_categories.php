<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireAdmin();
$pageTitle = 'Manage Categories';

// Add category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add']) && verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    if ($name) {
        try {
            $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
            $stmt->execute([$name, $description]);
            setFlash('success', 'Category added.');
        } catch (PDOException $e) {
            error_log($e->getMessage());
            setFlash('error', 'Failed to add category.');
        }
    }
    redirect('manage_categories.php');
}

// Delete category
if (isset($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        setFlash('success', 'Category deleted.');
    } catch (PDOException $e) {
        setFlash('error', 'Cannot delete category with items.');
    }
    redirect('manage_categories.php');
}

$categories = $pdo->query("SELECT c.*, COUNT(mi.id) as item_count 
                           FROM categories c 
                           LEFT JOIN menu_items mi ON c.id = mi.category_id 
                           GROUP BY c.id 
                           ORDER BY c.name")->fetchAll();

include '../includes/header.php';
?>

<h2>📂 Manage Categories</h2>

<div class="form-card">
    <h3>Add New Category</h3>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
        <input type="hidden" name="add" value="1">
        
        <div class="form-group">
            <label>Category Name *</label>
            <input type="text" name="name" required>
        </div>
        
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="2"></textarea>
        </div>
        
        <button type="submit" class="btn btn-success">✓ Add Category</button>
    </form>
</div>

<h3>Existing Categories</h3>
<?php if (empty($categories)): ?>
    <p>No categories found.</p>
<?php else: ?>
    <table class="data-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Items</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $cat): ?>
                <tr>
                    <td><strong><?= escape($cat['name']) ?></strong></td>
                    <td><?= escape($cat['description']) ?></td>
                    <td><?= $cat['item_count'] ?> items</td>
                    <td>
                        <a href="?delete=<?= $cat['id'] ?>" class="btn btn-danger btn-sm" 
                           onclick="return confirm('Delete this category?')">🗑️ Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
