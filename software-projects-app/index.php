<?php
require_once 'includes/db.php'; // Database connection

$search = '';

// Get search term from URL if submitted
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

// If searching, filter by title or exact start date
if ($search !== '') {
    $sql = "SELECT pid, title, start_date, short_description
            FROM projects
            WHERE title LIKE ? OR start_date = ?
            ORDER BY start_date DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$search%", $search]);
} else {
    // Otherwise load all projects
    $sql = "SELECT pid, title, start_date, short_description
            FROM projects
            ORDER BY start_date DESC";

    $stmt = $pdo->query($sql);
}

// Fetch all matching project records
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once 'includes/header.php';
?>

<h2 class="page-title">Browse Software Projects</h2>

<!-- Search form for public users -->
<form method="GET" action="index.php" class="search-form">
    <input
        type="text"
        name="search"
        placeholder="Search by title or start date (YYYY-MM-DD)"
        value="<?php echo htmlspecialchars($search); ?>"
    >
    <button type="submit">Search</button>

    <?php if ($search !== ''): ?>
        <a href="index.php" class="clear-link">Clear</a>
    <?php endif; ?>
</form>

<?php if ($projects): ?>
    <?php foreach ($projects as $project): ?>
        <div class="project-card">
            <h2>
                <a href="project.php?pid=<?php echo $project['pid']; ?>">
                    <?php echo htmlspecialchars($project['title']); ?>
                </a>
            </h2>
            <p><strong>Start Date:</strong> <?php echo htmlspecialchars($project['start_date']); ?></p>
            <p><?php echo htmlspecialchars($project['short_description']); ?></p>
        </div>
    <?php endforeach; ?>
<?php else: ?>
     <!-- Show message if no projects found -->
    <div class="empty-message">
        <p>No projects found.</p>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
