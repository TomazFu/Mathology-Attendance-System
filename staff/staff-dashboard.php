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

    // Include fetch attendance data
    require_once "includes/fetch-attendance-data-process.php";
    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <link rel="stylesheet" href="../assets/css/staff.css">
    </head>

    <body>
        <div class="dashboard-layout">
            <?php renderSidebar('staff'); ?>
            <div class="main-content">
                <h1>Dashboard</h1>
                <div class="staff-dashboard-sections">
                    <!-- Attendance Card -->
                    <a href="staff-attendance.php" class="dashboard-link">
                        <div class="attendance-management dashboard-staff-box">
                            <h3 style="text-align: center;">Attendance Management</h3>
                            <i style="font-size: 30px;" class="fa fa-clipboard-check"></i>
                        </div>
                    </a>
                    <!-- View Package -->
                    <a href="staff-package.php" class="dashboard-link">
                        <div class="view-package dashboard-staff-box">
                            <h3>View Packages</h3>
                            <i style="font-size: 30px;" class="fa fa-eye"></i>
                        </div>
                    </a>
                    <!-- View Registration -->
                    <a href="staff-registration.php" class="dashboard-link">
                        <div class="parents-registration dashboard-staff-box">
                            <h3>Parents Registration</h3>
                            <i style="font-size: 30px;" class="fa fa-user-plus"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </body>

    </html>
    <?php
    // Include footer
    include "../includes/footer.php";
    ?>
    <script src="../assets/js/staff.js"></script>