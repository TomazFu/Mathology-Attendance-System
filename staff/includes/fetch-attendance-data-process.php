<?php
// Include database connection
require_once "../config/connect.php";

// Get today's date
$dateToday = date("Y-m-d");

// Fetch all students
$sqlStudents = "SELECT * FROM students";
$studentsResult = mysqli_query($conn, $sqlStudents);

// Initialize an array to hold all students and their attendance status for today
$studentsWithAttendance = array();

// Fetch today's attendance records for comparison
$sqlAttendance = "SELECT * FROM attendance WHERE date = '$dateToday'";
$attendanceResult = mysqli_query($conn, $sqlAttendance);
$attendanceRecords = array();

if ($attendanceResult) {
    while ($row = mysqli_fetch_assoc($attendanceResult)) {
        $attendanceRecords[$row['student_id']] = $row['status'];
    }
}

// Process all students and attach their attendance status for today
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

// Return the results as JSON
echo json_encode($studentsWithAttendance);
?>
