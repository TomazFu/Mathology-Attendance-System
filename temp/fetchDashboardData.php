<?php
$host = 'localhost';
$db = 'mathology_db';
$user = 'root';
$pass = '';

// Database connection
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch enrolled classes
$studentId = 1; // Change this based on logged-in user
$sql = "SELECT class_name FROM enrolled_classes WHERE student_id = $studentId";
$result = $conn->query($sql);

$enrolledClasses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $enrolledClasses[] = $row['class_name'];
    }
}

// Fetch enrolled package
$sql = "SELECT package_name FROM packages WHERE student_id = $studentId";
$result = $conn->query($sql);
$enrolledPackage = $result->fetch_assoc()['package_name'];

// Fetch latest leave details
$sql = "SELECT * FROM leaves WHERE student_id = $studentId ORDER BY leave_id DESC LIMIT 1";
$result = $conn->query($sql);
$latestLeave = $result->fetch_assoc();

// Fake attendance percentage for demo purposes
$attendancePercent = 0;

// Build the response array
$response = [
    'enrolledClasses' => $enrolledClasses,
    'enrolledPackage' => $enrolledPackage,
    'attendancePercent' => $attendancePercent,
    'latestLeave' => [
        'id' => $latestLeave['leave_id'],
        'reason' => $latestLeave['reason'],
        'fromDate' => $latestLeave['fromDate'],
        'toDate' => $latestLeave['toDate'],
    ]
];

// Output JSON response
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
