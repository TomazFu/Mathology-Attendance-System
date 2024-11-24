<?php
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: staff-login.php");
    exit;
}

// Include database connection
require_once "../config/connect.php";

// Include header
include "../includes/header.php";

// Include sidebar
require_once "../includes/sidebar.php";
?>

<head>
    <link rel="stylesheet" href="../assets/css/staff.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="dashboard-layout">
        <?php renderSidebar('staff'); ?>
        <div class="main-content">
            <h1>Manage Packages</h1>
            <div class="student-package-record">
                <div class="student-name"><?php echo htmlspecialchars($student['student_name']); ?></div>
            </div>
        </div>
    </div>
</body>


<?php
// Include footer
include "../includes/footer.php";
?>

<script src="../assets/javascript/staff.js"></script>