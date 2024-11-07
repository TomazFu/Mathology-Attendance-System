<?php
// Include database connection
require_once "../../config/connect.php";

// Check if POST data exists
if (isset($_POST['student_id']) && isset($_POST['attendance_status'])) {
    $studentId = $_POST['student_id'];
    $attendanceStatus = $_POST['attendance_status'];

    // Get today's date
    $todayDate = date('Y-m-d');

    // Check if the student has an attendance record for today
    $sql = "SELECT * FROM attendance WHERE student_id = $studentId AND date = '$todayDate'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Update the existing attendance record
        $updateSql = "UPDATE attendance SET status = '$attendanceStatus' WHERE student_id = $studentId AND date = '$todayDate'";
        if (mysqli_query($conn, $updateSql)) {
            echo "Attendance updated successfully!";
        } else {
            echo "Error updating attendance: " . mysqli_error($conn);
        }
    } else {
        // Create a new attendance record
        $insertSql = "INSERT INTO attendance (student_id, date, status) VALUES ($studentId, '$todayDate', '$attendanceStatus')";
        if (mysqli_query($conn, $insertSql)) {
            echo "New attendance created successfully!";
        } else {
            echo "Error inserting attendance: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>
