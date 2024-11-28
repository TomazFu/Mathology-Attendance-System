<?php
// Include database connection
require_once "../../config/connect.php";

// Check if all required POST data exists
if (isset($_POST['student_id']) && isset($_POST['attendance_status']) && 
    isset($_POST['date']) && isset($_POST['subject_id'])) {
    
    $studentId = $_POST['student_id'];
    $attendanceStatus = $_POST['attendance_status'];
    $selectedDate = $_POST['date'];
    $subjectId = $_POST['subject_id'];

    // Check if the student has an attendance record for the selected date and subject
    $checkStmt = $conn->prepare("SELECT * FROM attendance 
                                WHERE student_id = ? 
                                AND date = ? 
                                AND subject_id = ?");
    
    $checkStmt->bind_param("isi", $studentId, $selectedDate, $subjectId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // Update existing attendance record
        $updateStmt = $conn->prepare("UPDATE attendance 
                                    SET status = ? 
                                    WHERE student_id = ? 
                                    AND date = ? 
                                    AND subject_id = ?");
        
        $updateStmt->bind_param("sisi", $attendanceStatus, $studentId, $selectedDate, $subjectId);
        
        if ($updateStmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Attendance updated successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating attendance: ' . $conn->error]);
        }
        $updateStmt->close();
    } else {
        // Create new attendance record
        $insertStmt = $conn->prepare("INSERT INTO attendance 
                                    (student_id, date, status, subject_id) 
                                    VALUES (?, ?, ?, ?)");
        
        $insertStmt->bind_param("issi", $studentId, $selectedDate, $attendanceStatus, $subjectId);
        
        if ($insertStmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'New attendance created successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error inserting attendance: ' . $conn->error]);
        }
        $insertStmt->close();
    }
    
    $checkStmt->close();
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Missing required data',
        'received' => $_POST
    ]);
}

$conn->close();
?>