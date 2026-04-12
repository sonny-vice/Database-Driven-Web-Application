<?php
require_once 'includes/db.php';        // Database connection
require_once 'includes/auth.php';      // Authentication check
require_once 'includes/csrf.php';      // CSRF protection

requireLogin();                        // Restrict access to logged-in users

$errors = [];
$title = '';
$start_date = '';
$end_date = '';
$short_description = '';
$phase = '';

$allowed_phases = ['design', 'development', 'testing', 'deployment', 'complete'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        $errors[] = "Invalid form submission.";
    }

    // Collect form data
    $title = trim($_POST['title']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $short_description = trim($_POST['short_description']);
    $phase = $_POST['phase'];

    // Validate input data
    if (empty($errors)) {
        if (empty($title) || empty($start_date) || empty($short_description) || empty($phase)) {
            $errors[] = "Please fill in all required fields.";
        }

        // Ensure phase is valid (whitelist)
        if (!in_array($phase, $allowed_phases, true)) {
            $errors[] = "Invalid project phase selected.";
        }

        // Validate date logic
        if (!empty($end_date) && $end_date < $start_date) {
            $errors[] = "End date cannot be earlier than start date.";
        }
    }

    // Insert project into database
    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO projects (title, start_date, end_date, short_description, phase, uid)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $title,
            $start_date,
            $end_date ?: null,
            $short_description,
            $phase,
            $_SESSION['uid']   // Associate project with logged-in user
        ]);

        header("Location: dashboard.php?success=added");
        exit;
    }
}

require_once 'includes/header.php';
?>

<h2 class="page-title">Add New Project</h2>

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
        <option value="">Select phase</option>
        <?php foreach ($allowed_phases as $allowed_phase): ?>
            <option value="<?php echo $allowed_phase; ?>" <?php echo $phase === $allowed_phase ? 'selected' : ''; ?>>
                <?php echo ucfirst($allowed_phase); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">

    <button type="submit">Add Project</button>
</form>

<?php require_once 'includes/footer.php'; ?>