<?php
$host = 'localhost';
$db = 'mathology_db';
$user = 'root';
$pass = '';

// Database connection
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed.']);
    exit();
}

// Assuming student_id is stored in session
session_start();
$studentId = $_SESSION['student_id'] ?? 1; // Default for testing

// Fetch timetable data for a specific student
$stmt = $conn->prepare("SELECT subject_id, title, room, instructor, time FROM timetable WHERE student_id = ?");
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();

$timetable = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $timetable[] = $row;
    }
}

// Fetch enrolled classes
$stmt = $conn->prepare("SELECT class_name FROM enrolled_classes WHERE student_id = ?");
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();

$enrolledClasses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $enrolledClasses[] = $row['class_name'];
    }
}

// Build the response array
$response = [
    'timetable' => $timetable ?: [],
    'enrolledClasses' => $enrolledClasses ?: []
];

// Output JSON response
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
