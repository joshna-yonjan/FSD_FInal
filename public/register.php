<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

$pageTitle = 'Register';

if (isLoggedIn()) redirect('index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF CHECK
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($username && $email && $password && $confirm) {
        if ($password !== $confirm) {
            $error = 'Passwords do not match.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email address.';
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);

                unset($_SESSION['csrf_token']); // regenerate token
                setFlash('success', 'Registration successful! Please login.');
                redirect('login.php');
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $error = 'Username already exists.';
                } else {
                    error_log($e->getMessage());
                    $error = 'Registration failed.';
                }
            }
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}

include '../includes/header.php';
?>

<div class="auth-container">
    <h2>📝 Register</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= escape($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form>

    <p class="text-center">Already have an account? <a href="login.php">Login here</a></p>
</div>

<?php include '../includes/footer.php'; ?>
