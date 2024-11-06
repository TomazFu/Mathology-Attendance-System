<!-- tmp -->
<?php
session_start();
include '../config/connect.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

$studentId = $_SESSION["id"] ?? null;

if (!$studentId) {
    http_response_code(400);
    echo json_encode(['error' => 'Student ID not found']);
    exit();
}

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
