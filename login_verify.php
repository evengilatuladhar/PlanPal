<?php
session_start();

// Session timeout duration in seconds (e.g., 30 minutes)
$timeout_duration = 1800;

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // User not logged in, redirect to login page
    header("Location: login.php");
    exit;
}

// Check for session timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    // Last request was more than 30 minutes ago
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit;
}

// Update last activity time stamp
$_SESSION['last_activity'] = time();
