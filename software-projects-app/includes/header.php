<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Software Projects App</title>

    <!-- Main stylesheet -->
    <link rel="stylesheet" href="/software-projects-app/assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container nav-container">
            <h1 class="logo">
                <a href="/software-projects-app/index.php">Software Projects</a>
            </h1>

            <nav>
                <ul class="nav-links">
                    <li><a href="/software-projects-app/index.php">Home</a></li>

                    <?php if (isset($_SESSION['uid'])): ?>
                        <!-- Links for logged-in users -->
                        <li><a href="/software-projects-app/dashboard.php">Dashboard</a></li>
                        <li><a href="/software-projects-app/add_project.php">Add Project</a></li>
                        <li><a href="/software-projects-app/logout.php">Logout</a></li>
                    <?php else: ?>
                        <!-- Links for guests -->
                        <li><a href="/software-projects-app/register.php">Register</a></li>
                        <li><a href="/software-projects-app/login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">