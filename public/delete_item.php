<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireAdmin();

$id = $_GET['id'] ?? 0;

try {
    $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = ?");
    $stmt->execute([$id]);
    setFlash('success', 'Menu item deleted successfully.');
} catch (PDOException $e) {
    error_log($e->getMessage());
    setFlash('error', 'Failed to delete item.');
}

redirect('index.php');
