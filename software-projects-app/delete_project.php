<?php
require_once 'includes/db.php';        // Database connection
require_once 'includes/auth.php';      // Authentication check
require_once 'includes/csrf.php';      // CSRF protection

requireLogin();                        // Restrict access to logged-in users

// Only allow POST requests for deletion
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    require_once 'includes/header.php';
    echo '<div class="message-box">';
    echo '<h3>Invalid Request</h3>';
    echo '<p>Project deletion must be submitted using the correct form.</p>';
    echo '<a href="dashboard.php" class="back-link">Return to Dashboard</a>';
    echo '</div>';
    require_once 'includes/footer.php';
    exit;
}

$errors = [];

// Validate CSRF token
if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
    $errors[] = "Invalid form submission.";
}

// Validate project ID
if (!isset($_POST['pid']) || !is_numeric($_POST['pid'])) {
    $errors[] = "Invalid project ID.";
}

if (empty($errors)) {
    $pid = (int) $_POST['pid'];

    // Ensure the project belongs to the logged-in user
    $stmt = $pdo->prepare("SELECT pid FROM projects WHERE pid = ? AND uid = ?");
    $stmt->execute([$pid, $_SESSION['uid']]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$project) {
        require_once 'includes/header.php';
        echo '<div class="message-box">';
        echo '<h3>Access Denied</h3>';
        echo '<p>You do not have permission to delete this project, or it does not exist.</p>';
        echo '<a href="dashboard.php" class="back-link">Return to Dashboard</a>';
        echo '</div>';
        require_once 'includes/footer.php';
        exit;
    }

    // Delete the project
    $stmt = $pdo->prepare("DELETE FROM projects WHERE pid = ? AND uid = ?");
    $stmt->execute([$pid, $_SESSION['uid']]);

    header("Location: dashboard.php?success=deleted");
    exit;
}

require_once 'includes/header.php';
?>

<div class="message-box">
    <h3>Unable to Delete Project</h3>
    <?php foreach ($errors as $error): ?>
        <p><?php echo htmlspecialchars($error); ?></p>
    <?php endforeach; ?>
    <a href="dashboard.php" class="back-link">Return to Dashboard</a>
</div>

<?php require_once 'includes/footer.php'; ?>