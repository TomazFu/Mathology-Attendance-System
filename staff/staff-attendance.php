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

require_once "includes/fetch-attendance-data-process.php";
?>

<head>
    <link rel="stylesheet" href="../assets/css/staff.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>
    <div class="dashboard-layout">
        <?php renderSidebar('staff'); ?>
        <div class="main-content">
            <h1>Attendance</h1>
            <div class="attendance-page-container">
                <div class="selectors-container">
                    <div class="attendance-date">
                        <label for="date-select">Select Date: </label>
                        <input
                            type="date"
                            id="date-select"
                            value="<?php echo $selectedDate; ?>"
                            max="<?php echo date('Y-m-d'); ?>"
                            onchange="updateAttendanceDate(this.value)">
                    </div>
                    <div class="subject-selector">
                        <label for="subject-select">Select Subject: </label>
                        <select name="subject_select" id="subject-select" required onchange="updateSubject(this.value)">
                            <option value="">Choose a subject</option>
                            <?php
                            $sql = "SELECT id, subject_id, title FROM subject ORDER BY id";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $selected = (isset($_GET['subject']) && $_GET['subject'] == $row['id']) ? 'selected' : '';
                                    echo "<option value='" . $row['id'] . "' " . $selected . ">"
                                        . htmlspecialchars($row['subject_id'] . ' - ' . $row['title'])
                                        . "</option>";
                                }
                            }
                            ?>
                        </select>
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
                                    <label>
                                        Late:
                                        <input
                                            type="checkbox"
                                            id="late_<?php echo $student['student_id']; ?>"
                                            <?php echo ($student['attendance_status'] == 'late') ? 'checked' : ''; ?>
                                            onclick="toggleAttendance(<?php echo $student['student_id']; ?>, 'late')" />
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
    </div>
</body>

<?php
// Include footer
include "../includes/footer.php";
?>

<script src="../assets/js/staff.js"></script>