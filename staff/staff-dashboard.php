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
                <!-- Attendance list overview -->
                <div class="attendance-list dashboard-staff-box">
                    <h3>Attendance List for <?php echo date("Y-m-d") ?></h3>
                    <input type="hidden" id="date-select" value="<?php echo date('Y-m-d'); ?>">
                    <?php if (!empty($studentsWithAttendance)): ?>
                        <div class="attendance-container">
                            <?php foreach ($studentsWithAttendance as $student): ?>
                                <div class="attendance-record">
                                    <div class="student-name"><?php echo htmlspecialchars($student['student_name']); ?></div>
                                    <div class="attendance-status">
                                        <label>
                                            Present:
                                            <input
                                                type="checkbox"
                                                id="present_<?php echo $student['student_id']; ?>"
                                                <?php echo ($student['attendance_status'] == 'present') ? 'checked' : ''; ?>
                                                onclick="toggleAttendance(<?php echo $student['student_id']; ?>, 'present')" />
                                        </label>
                                        <label>
                                            Absent:
                                            <input
                                                type="checkbox"
                                                id="absent_<?php echo $student['student_id']; ?>"
                                                <?php echo ($student['attendance_status'] == 'absent') ? 'checked' : ''; ?>
                                                onclick="toggleAttendance(<?php echo $student['student_id']; ?>, 'absent')" />
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>No attendance records available.</p>
                    <?php endif; ?>
                </div>
            </div>                
            <div class="staff-dashboard-sections">
                <!-- View Package -->
                <a href="staff-package.php" class="dashboard-link">
                    <div class="view-package dashboard-staff-box">
                        <h3>View Packages</h3>
                    </div>
                </a>
                <!-- View Registration -->
                <a href="staff-registration.php" class="dashboard-link">
                    <div class="parents-registration dashboard-staff-box">
                        <h3>Parents Registration</h3>
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
