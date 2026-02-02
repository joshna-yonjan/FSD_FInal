<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

$pageTitle = 'Login';

if (isLoggedIn()) redirect('index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF CHECK
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {

                session_regenerate_id(true); // prevent session fixation

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                unset($_SESSION['csrf_token']); // regenerate token

                setFlash('success', 'Welcome back, ' . $user['username'] . '!');
                redirect('index.php');
            } else {
                $error = 'Invalid username or password.';
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $error = 'Login failed.';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}

include '../includes/header.php';
?>

<div class="auth-container">
    <h2>🔐 Login</h2>

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
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>

    <p class="text-center">Don't have an account? <a href="register.php">Register here</a></p>
</div>

<?php include '../includes/footer.php'; ?>
