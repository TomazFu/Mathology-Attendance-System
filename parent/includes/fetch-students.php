<?php
session_start();
require_once "../../config/connect.php";

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

$parent_id = $_SESSION["id"];

try {
    $sql = "SELECT student_id, student_name FROM students WHERE parent_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $parent_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    
    $response = [
        'success' => true,
        'students' => $students
    ];
    
} catch (Exception $e) {
    $response = [
        'success' => false,
        'error' => 'Error fetching students data'
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?> 