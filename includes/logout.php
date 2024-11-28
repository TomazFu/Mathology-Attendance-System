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
        case 'manager':
            // Perform manager-specific logout actions
            $redirect_path = "../manager/manager-login.php";
            break;
        // Add more cases as needed
    }
}

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

// Redirect to login page based on role, or default to index
if (isset($redirect_path)) {
    header("location: " . $redirect_path);
} else {
    header("location: ../index.php");
}
exit;
?>
