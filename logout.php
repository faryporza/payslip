<?php
session_start();

// Destroy the session and unset all session variables
session_destroy();
session_unset();

// Redirect to the login page
header('location: index.php');
exit;
?>
