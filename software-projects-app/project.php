<?php
require_once 'includes/db.php';

if (!isset($_GET['pid']) || !is_numeric($_GET['pid'])) {
    require_once 'includes/header.php';
    echo '<div class="message-box">';
    echo '<h3>Invalid Project</h3>';
    echo '<p>The project ID provided is not valid.</p>';
    echo '<a href="index.php" class="back-link">Return to Home</a>';
    echo '</div>';
    require_once 'includes/footer.php';
    exit;
}

$pid = (int) $_GET['pid'];

$stmt = $pdo->prepare("
    SELECT projects.title, projects.start_date, projects.end_date,
           projects.short_description, projects.phase, users.email
    FROM projects
    JOIN users ON projects.uid = users.uid
    WHERE projects.pid = ?
");
$stmt->execute([$pid]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

require_once 'includes/header.php';
?>

<?php if ($project): ?>
    <h2 class="page-title"><?php echo htmlspecialchars($project['title']); ?></h2>

    <div class="project-card">
        <p><strong>Start Date:</strong> <?php echo htmlspecialchars($project['start_date']); ?></p>
        <p><strong>End Date:</strong>
            <?php echo $project['end_date'] ? htmlspecialchars($project['end_date']) : 'Ongoing'; ?>
        </p>
        <p><strong>Phase:</strong> <?php echo htmlspecialchars($project['phase']); ?></p>
        <p><strong>Owner Email:</strong> <?php echo htmlspecialchars($project['email']); ?></p>
        <p><strong>Description:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($project['short_description'])); ?></p>
    </div>
<?php else: ?>
    <div class="message-box">
        <h3>Project Not Found</h3>
        <p>The requested project does not exist.</p>
        <a href="index.php" class="back-link">Return to Home</a>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>