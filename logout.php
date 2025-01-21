<?php
session_start();
session_destroy();
header("Location: landingpg.php"); // Redirect to home after logout
exit();
?>
