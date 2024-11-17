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

// Get the selected date from the URL parameter (fallback to today if not set)
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");

// Fetch all students
$sqlStudents = "SELECT * FROM students";
$studentsResult = mysqli_query($conn, $sqlStudents);

// Initialize an array to hold all students and their attendance status for the selected date
$studentsWithAttendance = array();

// Fetch attendance records for the selected date
$sqlAttendance = "SELECT * FROM attendance WHERE date = '$selectedDate'";
$attendanceResult = mysqli_query($conn, $sqlAttendance);
$attendanceRecords = array();

if ($attendanceResult) {
    while ($row = mysqli_fetch_assoc($attendanceResult)) {
        $attendanceRecords[$row['student_id']] = $row['status'];
    }
}

// Process all students and attach their attendance status for the selected date
while ($student = mysqli_fetch_assoc($studentsResult)) {
    $studentsWithAttendance[] = array(
        'student_id' => $student['student_id'],
        'student_name' => $student['student_name'],
        'attendance_status' => isset($attendanceRecords[$student['student_id']]) ? $attendanceRecords[$student['student_id']] : null
    );
}

// Free result sets
mysqli_free_result($studentsResult);
mysqli_free_result($attendanceResult);

// Close connection
mysqli_close($conn);
?>

<head>
    <link rel="stylesheet" href="../assets/css/staff.css">
</head>

<body>
    <div class="dashboard-layout">
        <?php renderSidebar('staff'); ?>
        <div class="main-content">
            <h1>Attendance</h1>
            
            <!-- Date Selector Dropdown -->
            <div class="attendance-date">
                <label for="date-select">Select Date: </label>
                <select id="date-select" onchange="window.location.href='staff-attendance.php?date=' + this.value">
                    <?php 
                    // Generate the past 10 days options for the date dropdown
                    for ($i = 0; $i < 10; $i++) {
                        $date = date("Y-m-d", strtotime("-$i days"));
                        echo "<option value='$date' " . ($selectedDate == $date ? 'selected' : '') . ">" . date("d/m/y", strtotime($date)) . "</option>";
                    }
                    ?>
                </select>
                <div id="selected-date">
                    Date: <?php echo date("d/m/y", strtotime($selectedDate)); ?>
                </div>
            </div>

            <!-- Display Attendance Records -->
            <?php if (!empty($studentsWithAttendance)): ?>
                <div class="attendance-container-list">
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
                <p>No attendance records available for this date.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

<?php
// Include footer
include "../includes/footer.php";
?>

<script src="../assets/javascript/staff.js"></script>
