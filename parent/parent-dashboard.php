<?php
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: parent-login.php");
    exit;
}

// Include database connection
require_once "../config/connect.php";

// Include header
include "../includes/header.php";

// Include sidebar
require_once "../includes/sidebar.php";
?>

<div class="dashboard-layout">
    <?php renderSidebar('parent'); ?>
    <div class="main-content">
        <!-- dashboard content here -->
        <h2>Welcome to the Parent Dashboard</h2>
        <ul>
            <li><a href="parent-attendance.php">View Attendance</a></li>
            <li><a href="parent-package.php">View Package</a></li>
        </ul>
    </div>
</div>

<?php
// Include footer
include "../includes/footer.php";
?>
