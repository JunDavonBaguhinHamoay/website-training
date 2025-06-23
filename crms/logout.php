<?php
// Start the session
session_start();

// Destroy all session data
session_unset();

// Destroy the session itself
session_destroy();

// Redirect to the login page or home page
header("Location: index.html");  // Adjust the URL as needed
exit();
?>