<?php
require_once 'includes/db.php';        // Database connection
require_once 'includes/csrf.php';      // CSRF protection

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        $errors[] = "Invalid form submission.";
    }

    // Retrieve user input
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Basic validation
    if (empty($username) || empty($password)) {
        $errors[] = "Both fields are required.";
    }

    // Check credentials against database
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT uid, username, password FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password using hashed value
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true); // Prevent session fixation

            $_SESSION['uid'] = $user['uid'];             // Store user ID in session
            $_SESSION['username'] = $user['username'];   // Store username

            header("Location: dashboard.php");
            exit;
        } else {
            $errors[] = "Invalid username/email or password.";
        }
    }
}

require_once 'includes/header.php';
?>

<h2 class="page-title">Login</h2>

<?php if (isset($_GET['success']) && $_GET['success'] === 'registered'): ?>
    <div class="success-box">
        <p>Registration successful. You can now log in.</p>
    </div>
<?php endif; ?>

<?php if (isset($_GET['success']) && $_GET['success'] === 'logged_out'): ?>
    <div class="info-box">
        <p>You have logged out successfully.</p>
    </div>
<?php endif; ?>

<?php if ($errors): ?>
    <div class="error-box">
        <?php foreach ($errors as $error): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" class="form-card" novalidate>
    <label>Username or Email</label>
    <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">

    <button type="submit">Login</button>
</form>

<?php require_once 'includes/footer.php'; ?>