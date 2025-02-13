<?php
session_start();

// Store the current page (before logout) in the session if not already set
if (!isset($_SESSION['redirect_url'])) {
  $_SESSION['redirect_url'] = $_SERVER['HTTP_REFERER'] ?? 'landingpg.php';  // Default to 'landingpg.php' if no referrer
}

// Destroy session and log out user
session_destroy();

// Redirect to the page user came from or to the landing page
header("Location: " . $_SESSION['redirect_url']);
exit();
?>
