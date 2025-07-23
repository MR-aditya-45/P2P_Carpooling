<?php
// Start session only if it's not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is a driver
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'driver') {
    header("Location: ../login_driver.php"); // adjust path if needed
    exit();
}
?>
