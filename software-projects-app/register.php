<?php
require_once 'includes/db.php';        // Database connection (PDO)
require_once 'includes/csrf.php';      // CSRF protection functions

$errors = [];                          // Store validation errors
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate CSRF token to prevent forged requests
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        $errors[] = "Invalid form submission.";
    }

    // Retrieve and sanitise user input
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate form fields
    if (empty($errors)) {
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            $errors[] = "All fields are required.";
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        // Enforce minimum password length
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        }

        // Ensure passwords match
        if ($password !== $confirm_password) {
            $errors[] = "Passwords do not match.";
        }
    }

    // Check if username or email already exists in database
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT uid FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);

        if ($stmt->fetch()) {
            $errors[] = "Username or email already exists.";
        }
    }

    // Hash password and insert new user into database
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->execute([$username, $hashed_password, $email]);

        // Redirect with success message
        header("Location: login.php?success=registered");
        exit;
    }
}

require_once 'includes/header.php';
?>

<h2 class="page-title">Register</h2>

<?php if ($errors): ?>
    <div class="error-box">
        <?php foreach ($errors as $error): ?>
            <p><?php echo htmlspecialchars($error); ?></p> <!-- Prevent XSS -->
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" class="form-card" novalidate>
    <label>Username</label>
    <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <label>Confirm Password</label>
    <input type="password" name="confirm_password" required>

    <!-- CSRF token for secure form submission -->
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">

    <button type="submit">Register</button>
</form>

<?php require_once 'includes/footer.php'; ?>