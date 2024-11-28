<?php
require_once dirname(dirname(__DIR__)) . "/config/connect.php";

// Get selected date
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
$selectedSubject = isset($_GET['subject']) ? $_GET['subject'] : null;

// Initialize array to hold students with attendance
$studentsWithAttendance = array();

// Query to get only enrolled students and their attendance
if ($selectedSubject) {
    $query = "SELECT s.student_id, s.student_name, 
              COALESCE(a.status, '') as attendance_status 
              FROM students s 
              INNER JOIN enrolled_classes ec ON s.student_id = ec.student_id
              AND ec.subject_id = ?
              LEFT JOIN attendance a ON s.student_id = a.student_id 
              AND a.date = ? AND a.subject_id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("isi", $selectedSubject, $selectedDate, $selectedSubject);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $studentsWithAttendance[] = array(
                'student_id' => $row['student_id'],
                'student_name' => $row['student_name'],
                'attendance_status' => $row['attendance_status']
            );
        }
        $stmt->close();
    }
} else {
    // If no subject selected, show message or empty state
    $studentsWithAttendance = array();
}
?>