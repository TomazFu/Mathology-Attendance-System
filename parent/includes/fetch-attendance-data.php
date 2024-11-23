<?php
session_start();
require_once "../../config/connect.php";

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Get the parent_id from session (this should be the numeric ID)
$parent_id = $_SESSION["id"]; // This should be 1729434667 based on your data

try {
    // Debug log
    error_log("Fetching attendance for parent ID: " . $parent_id);
    
    // First, get the student IDs for this parent
    $student_sql = "SELECT student_id FROM students WHERE parent_id = ?";
    $student_stmt = $conn->prepare($student_sql);
    $student_stmt->bind_param("i", $parent_id);
    $student_stmt->execute();
    $result = $student_stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("No students found for parent ID: " . $parent_id);
    }
    
    // Get the first student's ID
    $student = $result->fetch_assoc();
    $student_id = $student['student_id'];
    
    // Debug log
    error_log("Found student ID: " . $student_id);

    // Fetch attendance data for the last 30 days
    $sql = "SELECT 
            date, 
            status,
            TIME_FORMAT(created_at, '%H:%i') as time 
            FROM attendance 
            WHERE student_id = ? 
            AND date >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
            ORDER BY date DESC";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $attendance_data = [];
    while ($row = $result->fetch_assoc()) {
        $attendance_data[] = [
            'date' => $row['date'],
            'status' => $row['status'],
            'time' => $row['time']
        ];
    }
    
    // Debug log
    error_log("Found " . count($attendance_data) . " attendance records");

    // Get attendance statistics
    $stats_sql = "SELECT 
        COUNT(*) as total_days,
        SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_days,
        SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_days
        FROM attendance 
        WHERE student_id = ?
        AND date >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)";
        
    $stats_stmt = $conn->prepare($stats_sql);
    $stats_stmt->bind_param("i", $student_id);
    $stats_stmt->execute();
    $stats = $stats_stmt->get_result()->fetch_assoc();

    $response = [
        'success' => true,
        'attendance_data' => $attendance_data,
        'stats' => $stats,
        'debug' => [
            'parent_id' => $parent_id,
            'student_id' => $student_id,
            'record_count' => count($attendance_data)
        ]
    ];

} catch (Exception $e) {
    error_log("Attendance Data Error: " . $e->getMessage());
    $response = [
        'success' => false,
        'error' => 'Error fetching attendance data',
        'debug' => [
            'message' => $e->getMessage(),
            'parent_id' => $parent_id ?? 'not set'
        ]
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?> 