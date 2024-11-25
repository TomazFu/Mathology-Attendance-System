<?php
// Include database connection
require_once "../../config/connect.php";

// Check if POST data exists
if (isset($_POST['student_id']) && isset($_POST['attendance_status']) && isset($_POST['date'])) {
    $studentId = $_POST['student_id'];
    $attendanceStatus = $_POST['attendance_status'];
    $selectedDate = $_POST['date'];  

    // Check if the student has an attendance record for the selected date
    $sql = "SELECT * FROM attendance WHERE student_id = $studentId AND date = '$selectedDate'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Update the existing attendance record for the selected date
        $updateSql = "UPDATE attendance SET status = '$attendanceStatus' WHERE student_id = $studentId AND date = '$selectedDate'";
        if (mysqli_query($conn, $updateSql)) {
            echo "Attendance updated successfully!";
        } else {
            echo "Error updating attendance: " . mysqli_error($conn);
        }
    } else {
        // Create a new attendance record for the selected date
        $insertSql = "INSERT INTO attendance (student_id, date, status) VALUES ($studentId, '$selectedDate', '$attendanceStatus')";
        if (mysqli_query($conn, $insertSql)) {
            echo "New attendance created successfully!";
        } else {
            echo "Error inserting attendance: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);

?>
