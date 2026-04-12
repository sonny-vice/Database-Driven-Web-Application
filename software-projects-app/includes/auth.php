<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect users to login page if they are not authenticated
function requireLogin()
{
    if (!isset($_SESSION['uid'])) {
        header("Location: login.php");
        exit;
    }
}
?>