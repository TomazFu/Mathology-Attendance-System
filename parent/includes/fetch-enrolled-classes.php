<?php
session_start();
require_once "../../config/connect.php";

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : null;

if (!$student_id) {
    echo json_encode([
        'success' => false,
        'error' => 'Student ID is required'
    ]);
    exit;
}

try {
    $sql = "SELECT s.id, s.title, s.day, s.start_time, s.end_time
            FROM enrolled_classes ec
            JOIN subject s ON ec.subject_id = s.id
            WHERE ec.student_id = ?
            ORDER BY s.day, s.start_time";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $classes = [];
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'classes' => $classes
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Error fetching enrolled classes'
    ]);
}

$conn->close();
?> 