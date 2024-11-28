<?php
// Include database connection
require_once "../config/connect.php";

// Get selected date and subject
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
$selectedSubject = isset($_GET['subject']) ? $_GET['subject'] : null;

// Validate date format
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $selectedDate)) {
    $selectedDate = date('Y-m-d');
}

// Initialize array to hold students with attendance
$studentsWithAttendance = array();

// Only fetch attendance if a subject is selected
if ($selectedSubject) {
    // Use prepared statement to get students and their attendance for the selected subject
    $query = "SELECT s.student_id, s.student_name, 
              COALESCE(a.status, '') as attendance_status 
              FROM students s 
              LEFT JOIN attendance a ON s.student_id = a.student_id 
              AND a.date = ? AND a.subject_id = ?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("si", $selectedDate, $selectedSubject);
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
    // If no subject selected, just get the list of students without attendance
    $query = "SELECT student_id, student_name FROM students";
    $result = $conn->query($query);
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $studentsWithAttendance[] = array(
                'student_id' => $row['student_id'],
                'student_name' => $row['student_name'],
                'attendance_status' => ''
            );
        }
    }
}

// Add debug information
echo "<!-- Selected Date: $selectedDate -->";
echo "<!-- Selected Subject: $selectedSubject -->";
echo "<!-- Number of records: " . count($studentsWithAttendance) . " -->";

// Don't close the connection here as it might be needed for the subject dropdown
?>