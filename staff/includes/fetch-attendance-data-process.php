<?php
// Include database connection
require_once "../config/connect.php";

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
