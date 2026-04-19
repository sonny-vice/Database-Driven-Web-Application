<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/csrf.php';

requireLogin();

$stmt = $pdo->prepare("SELECT pid, title, start_date, phase FROM projects WHERE uid = ? ORDER BY start_date DESC");
$stmt->execute([$_SESSION['uid']]);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once 'includes/header.php';
?>

<h2 class="page-title">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>

<?php if (isset($_GET['success'])): ?>
    <?php if ($_GET['success'] === 'added'): ?>
        <div class="success-box">
            <p>Project added successfully.</p>
        </div>
    <?php elseif ($_GET['success'] === 'updated'): ?>
        <div class="success-box">
            <p>Project updated successfully.</p>
        </div>
    <?php elseif ($_GET['success'] === 'deleted'): ?>
        <div class="success-box">
            <p>Project deleted successfully.</p>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="dashboard-actions">
    <a href="add_project.php" class="button-link">Add New Project</a>
</div>

<h3 class="section-title">My Projects</h3>

<?php if ($projects): ?>
    <?php foreach ($projects as $project): ?>
        <div class="project-card">
            <h2><?php echo htmlspecialchars($project['title']); ?></h2>
            <p><strong>Start Date:</strong> <?php echo htmlspecialchars($project['start_date']); ?></p>
            <p><strong>Phase:</strong> <?php echo htmlspecialchars($project['phase']); ?></p>

            <div class="project-actions">
                <a href="edit_project.php?pid=<?php echo $project['pid']; ?>" class="button-link">Edit Project</a>

                <form method="POST" action="delete_project.php" onsubmit="return confirm('Are you sure you want to delete this project?');">
                    <input type="hidden" name="pid" value="<?php echo $project['pid']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">
                    <button type="submit" class="delete-button">Delete Project</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="empty-message">
        <p>You have not added any projects yet. Use the “Add New Project” button to create your first project.</p>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
