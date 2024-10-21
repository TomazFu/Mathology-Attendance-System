<?php
// Initialize the session
session_start();

// Perform role-specific logout actions if needed
if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'parent':
            // Perform parent-specific logout actions
            break;
        case 'teacher':
            // Perform teacher-specific logout actions
            break;
        // Add more cases as needed
    }
}

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

// Redirect to login page
header("location: ../index.php");
exit;
?>
