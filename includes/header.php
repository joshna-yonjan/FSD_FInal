<?php 
require_once __DIR__ . '/../includes/functions.php';
$cartCount = getCartCount();
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? escape($pageTitle) . ' - ' : '' ?>Restaurant Deluxe</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <!-- Sidebar Navigation -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <span class="logo-icon">🍽️</span>
                <h1>Restaurant Deluxe</h1>
            </div>
        </div>

        <?php if (isLoggedIn()): ?>
            <div class="user-profile">
                <div class="avatar">
                    <?= strtoupper(substr(getUsername(), 0, 1)) ?>
                </div>
                <div class="user-details">
                    <span class="username"><?= escape(getUsername()) ?></span>
                    <?php if (isAdmin()): ?>
                        <span class="role-badge admin">Administrator</span>
                    <?php else: ?>
                        <span class="role-badge customer">Customer</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <span class="nav-section-title">Main</span>
                <a href="index.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                    <span class="nav-icon">🏠</span>
                    <span class="nav-text">Browse Menu</span>
                </a>
                
                <?php if (isLoggedIn()): ?>
                    <a href="view_cart.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'view_cart.php' ? 'active' : '' ?>">
                        <span class="nav-icon">🛒</span>
                        <span class="nav-text">My Cart</span>
                        <?php if ($cartCount > 0): ?>
                            <span class="nav-badge"><?= $cartCount ?></span>
                        <?php endif; ?>
                    </a>
                    
                    <a href="my_orders.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'my_orders.php' ? 'active' : '' ?>">
                        <span class="nav-icon">📦</span>
                        <span class="nav-text">My Orders</span>
                    </a>
                <?php endif; ?>
            </div>

            <?php if (isAdmin()): ?>
                <div class="nav-section">
                    <span class="nav-section-title">Management</span>
                    <a href="admin_orders.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'admin_orders.php' ? 'active' : '' ?>">
                        <span class="nav-icon">📊</span>
                        <span class="nav-text">All Orders</span>
                    </a>
                    <a href="add_item.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'add_item.php' ? 'active' : '' ?>">
                        <span class="nav-icon">➕</span>
                        <span class="nav-text">Add Menu Item</span>
                    </a>
                    <a href="manage_categories.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'manage_categories.php' ? 'active' : '' ?>">
                        <span class="nav-icon">📂</span>
                        <span class="nav-text">Categories</span>
                    </a>
                </div>
            <?php endif; ?>

            <div class="nav-section">
                <span class="nav-section-title">Account</span>
                <?php if (isLoggedIn()): ?>
                    <a href="logout.php" class="nav-item">
                        <span class="nav-icon">🚪</span>
                        <span class="nav-text">Logout</span>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : '' ?>">
                        <span class="nav-icon">🔐</span>
                        <span class="nav-text">Login</span>
                    </a>
                    <a href="register.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'register.php' ? 'active' : '' ?>">
                        <span class="nav-icon">📝</span>
                        <span class="nav-text">Register</span>
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <div class="main-wrapper">
        <!-- Top Bar -->
        <header class="topbar">
            <div class="topbar-left">
                <h2 class="page-title"><?= isset($pageTitle) ? escape($pageTitle) : 'Dashboard' ?></h2>
            </div>
            <div class="topbar-right">
                <div class="quick-actions">
                    <?php if (isLoggedIn()): ?>
                        <button class="quick-action-btn" onclick="window.location.href='view_cart.php'">
                            <span class="icon">🛒</span>
                            <?php if ($cartCount > 0): ?>
                                <span class="badge"><?= $cartCount ?></span>
                            <?php endif; ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <?php if ($flash): ?>
                <div class="notification notification-<?= $flash['type'] ?>">
                    <button class="notification-close" onclick="this.parentElement.remove()">×</button>
                    <div class="notification-content">
                        <?= escape($flash['message']) ?>
                    </div>
                </div>
            <?php endif; ?>
