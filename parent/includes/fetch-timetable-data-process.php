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
    // First, let's debug the table structure
    // Uncomment this temporarily to see the actual column names
    /*
    $result = $conn->query("DESCRIBE students");
    $columns = $result->fetch_all(MYSQLI_ASSOC);
    error_log("Students table columns: " . print_r($columns, true));
    */

    // Get all students for this parent
    $stmt = $conn->prepare("SELECT student_id, student_name as name 
                           FROM students 
                           WHERE parent_id = ?");
    $stmt->bind_param("i", $parent_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('No students found for this parent');
    }
    
    // Get selected student_id from request, default to first student if not specified
    $students = $result->fetch_all(MYSQLI_ASSOC);
    $selected_student_id = $_GET['student_id'] ?? $students[0]['student_id'];
    
    // Fetch timetable data including enrolled classes for selected student
    $query = "
        SELECT 
            s.id,
            s.subject_id,
            s.title,
            s.room,
            s.instructor,
            CONCAT(
                LOWER(s.day), ' ',
                TIME_FORMAT(s.start_time, '%H:%i'),
                '-',
                TIME_FORMAT(s.end_time, '%H:%i')
            ) as time
        FROM subject s
        INNER JOIN enrolled_classes ec ON s.id = ec.subject_id
        WHERE ec.student_id = ?
        ORDER BY 
            CASE 
                WHEN LOWER(s.day) = 'monday' THEN 1
                WHEN LOWER(s.day) = 'tuesday' THEN 2
                WHEN LOWER(s.day) = 'wednesday' THEN 3
                WHEN LOWER(s.day) = 'thursday' THEN 4
                WHEN LOWER(s.day) = 'friday' THEN 5
                ELSE 6
            END,
            s.start_time
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $selected_student_id);
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
            'time' => $row['time']
        ];
    }

    // Debug information
    error_log("Fetched timetable data for student ID: " . $selected_student_id);
    error_log("Number of classes found: " . count($timetable));

    echo json_encode([
        'success' => true,
        'timetable' => $timetable,
        'students' => $students,
        'selected_student_id' => $selected_student_id,
        'debug' => [
            'student_id' => $selected_student_id,
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
