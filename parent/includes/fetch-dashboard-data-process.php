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

// Fetch enrolled classes
$sql = "SELECT class_name FROM enrolled_classes WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();

$enrolledClasses = [];
while ($row = $result->fetch_assoc()) {
    $enrolledClasses[] = $row['class_name'];
}

// Fetch enrolled package
$sql = "SELECT p.package_name 
        FROM packages p 
        INNER JOIN students s ON s.package_id = p.id 
        WHERE s.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();
$enrolledPackage = $result->fetch_assoc()['package_name'] ?? 'No package enrolled';

// Fetch latest leave details
$sql = "SELECT * FROM leaves WHERE student_id = ? ORDER BY leave_id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();
$latestLeave = $result->fetch_assoc();

// Fetch attendance percentage (assuming you have an attendance table)
$sql = "SELECT COUNT(*) as total, SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present 
        FROM attendance WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();
$attendance = $result->fetch_assoc();
$attendancePercent = $attendance['total'] > 0 ? round(($attendance['present'] / $attendance['total']) * 100, 2) : 0;

// Build the response array
$response = [
    'enrolledClasses' => $enrolledClasses,
    'enrolledPackage' => $enrolledPackage,
    'attendancePercent' => $attendancePercent,
    'latestLeave' => $latestLeave ? [
        'id' => $latestLeave['leave_id'],
        'reason' => $latestLeave['reason'],
        'fromDate' => $latestLeave['fromDate'],
        'toDate' => $latestLeave['toDate'],
    ] : null
];

// Output JSON response
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
