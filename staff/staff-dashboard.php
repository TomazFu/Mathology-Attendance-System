<!-- tmp -->
<?php
session_start();

//Check if the user is logged in, if not then redirect to login page
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

//Include fetch attendance data
require_once "includes/fetch-attendance-data-process.php";
?>

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
                    <h3>Attendance List</h3>
                    <?php if (!empty($studentsWithAttendance)): ?>
                        <div class="scrollable-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width:60%">Student Name</th>
                                        <th>Present</th>
                                        <th>Absent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($studentsWithAttendance as $student): ?>
                                        <tr>
                                            <td style="width:60%"><?php echo htmlspecialchars($student['student_name']); ?></td>
                                            <td>
                                                <input
                                                    type="checkbox"
                                                    id="present_<?php echo $student['student_id']; ?>"
                                                    <?php echo ($student['attendance_status'] == 'present') ? 'checked' : ''; ?>
                                                    onclick="toggleAttendance(<?php echo $student['student_id']; ?>, 'present')" />
                                            </td>
                                            <td>
                                                <input
                                                    type="checkbox"
                                                    id="absent_<?php echo $student['student_id']; ?>"
                                                    <?php echo ($student['attendance_status'] == 'absent') ? 'checked' : ''; ?>
                                                    onclick="toggleAttendance(<?php echo $student['student_id']; ?>, 'absent')" />
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p>No attendance records available.</p>
                    <?php endif; ?>
                </div>
            </div>                
            <div class="staff-dashboard-sections">
                <!-- View Package -->
                <a href="view-packages.php" class="dashboard-link">
                    <div class="view-package dashboard-staff-box">
                        <h3>View Packages</h3>
                    </div>
                </a>
                <!-- View Registration -->
                <a href="view-packages.php" class="dashboard-link">
                    <div class="parents-registration dashboard-staff-box">
                        <h3>Parents Registration</h3>
                    </div>
                </a>
            </div>
        </div>
    </div>
</body>

<?php
// Include footer
include "../includes/footer.php";
?>
<script src="../assets/javascript/staff.js"></script>