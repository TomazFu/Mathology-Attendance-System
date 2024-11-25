<?php
session_start();
require_once "../../config/connect.php";

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

$parent_id = $_SESSION["id"] ?? null;

try {
    // Get student ID from the students table using parent_id
    $stmt = $conn->prepare("SELECT student_id FROM students WHERE parent_id = ?");
    $stmt->bind_param("i", $parent_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('No student found for this parent');
    }
    
    $student = $result->fetch_assoc();
    $student_id = $student['student_id'];

    // Fetch timetable data including enrolled classes
    $query = "
        SELECT 
            t.id,
            t.subject_id,
            t.title,
            t.room,
            t.instructor,
            t.time,
            ec.class_name
        FROM timetable t
        LEFT JOIN enrolled_classes ec ON t.student_id = ec.student_id
        WHERE t.student_id = ?
        ORDER BY 
            CASE 
                WHEN LOWER(SUBSTRING_INDEX(t.time, ' ', 1)) = 'monday' THEN 1
                WHEN LOWER(SUBSTRING_INDEX(t.time, ' ', 1)) = 'tuesday' THEN 2
                WHEN LOWER(SUBSTRING_INDEX(t.time, ' ', 1)) = 'wednesday' THEN 3
                WHEN LOWER(SUBSTRING_INDEX(t.time, ' ', 1)) = 'thursday' THEN 4
                WHEN LOWER(SUBSTRING_INDEX(t.time, ' ', 1)) = 'friday' THEN 5
                ELSE 6
            END,
            SUBSTRING_INDEX(SUBSTRING_INDEX(t.time, ' ', -1), '-', 1)
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $timetable = [];
    while ($row = $result->fetch_assoc()) {
        $timetable[] = [
            'id' => $row['id'],
            'subject_id' => $row['subject_id'],
            'title' => $row['title'],
            'room' => $row['room'],
            'instructor' => $row['instructor'],
            'time' => $row['time'],
            'class_name' => $row['class_name']
        ];
    }

    // Debug information
    error_log("Fetched timetable data for student ID: " . $student_id);
    error_log("Number of classes found: " . count($timetable));

    echo json_encode([
        'success' => true,
        'timetable' => $timetable,
        'debug' => [
            'student_id' => $student_id,
            'parent_id' => $parent_id,
            'class_count' => count($timetable)
        ]
    ]);

} catch (Exception $e) {
    error_log("Timetable Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'debug' => [
            'parent_id' => $parent_id ?? 'not set',
            'message' => $e->getMessage()
        ]
    ]);
}

$conn->close();
?>
