<?php
require_once 'includes/db.php';        // Database connection
require_once 'includes/auth.php';      // Authentication check
require_once 'includes/csrf.php';      // CSRF protection

requireLogin();                        // Restrict access to logged-in users

$errors = [];

// Validate project ID from URL
if (!isset($_GET['pid']) || !is_numeric($_GET['pid'])) {
    require_once 'includes/header.php';
    echo '<div class="message-box">';
    echo '<h3>Invalid Project</h3>';
    echo '<p>The project ID provided is not valid.</p>';
    echo '<a href="dashboard.php" class="back-link">Return to Dashboard</a>';
    echo '</div>';
    require_once 'includes/footer.php';
    exit;
}

$pid = (int) $_GET['pid'];

// Load project ONLY if it belongs to logged-in user (authorisation)
$stmt = $pdo->prepare("SELECT * FROM projects WHERE pid = ? AND uid = ?");
$stmt->execute([$pid, $_SESSION['uid']]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

// Block access if project does not belong to user
if (!$project) {
    require_once 'includes/header.php';
    echo '<div class="message-box">';
    echo '<h3>Access Denied</h3>';
    echo '<p>You do not have permission to edit this project, or it does not exist.</p>';
    echo '<a href="dashboard.php" class="back-link">Return to Dashboard</a>';
    echo '</div>';
    require_once 'includes/footer.php';
    exit;
}

// Populate form with existing project data
$title = $project['title'];
$start_date = $project['start_date'];
$end_date = $project['end_date'];
$short_description = $project['short_description'];
$phase = $project['phase'];

$allowed_phases = ['design', 'development', 'testing', 'deployment', 'complete'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        $errors[] = "Invalid form submission.";
    }

    // Collect updated data
    $title = trim($_POST['title']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $short_description = trim($_POST['short_description']);
    $phase = $_POST['phase'];

    // Validate updated data
    if (empty($title) || empty($start_date) || empty($short_description) || empty($phase)) {
        $errors[] = "Please fill in all required fields.";
    }

    if (!in_array($phase, $allowed_phases, true)) {
        $errors[] = "Invalid project phase.";
    }

    if (!empty($end_date) && $end_date < $start_date) {
        $errors[] = "End date cannot be earlier than start date.";
    }

    // Update project if valid
    if (empty($errors)) {
        $stmt = $pdo->prepare("
            UPDATE projects
            SET title = ?, start_date = ?, end_date = ?, short_description = ?, phase = ?
            WHERE pid = ? AND uid = ?
        ");

        $stmt->execute([
            $title,
            $start_date,
            $end_date ?: null,
            $short_description,
            $phase,
            $pid,
            $_SESSION['uid']
        ]);

        header("Location: dashboard.php?success=updated");
        exit;
    }
}

require_once 'includes/header.php';
?>

<h2 class="page-title">Edit Project</h2>

<?php if ($errors): ?>
    <div class="error-box">
        <?php foreach ($errors as $error): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" class="form-card" novalidate>
    <label>Project Title</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required>

    <label>Start Date</label>
    <input type="date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>" required>

    <label>End Date</label>
    <input type="date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">

    <label>Short Description</label>
    <textarea name="short_description" rows="5" required><?php echo htmlspecialchars($short_description); ?></textarea>

    <label>Phase</label>
    <select name="phase" required>
        <?php foreach ($allowed_phases as $allowed_phase): ?>
            <option value="<?php echo $allowed_phase; ?>" <?php echo $phase === $allowed_phase ? 'selected' : ''; ?>>
                <?php echo ucfirst($allowed_phase); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">

    <button type="submit">Update Project</button>
</form>

<?php require_once 'includes/footer.php'; ?>