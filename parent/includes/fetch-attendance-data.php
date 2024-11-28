<?php
session_start();
require_once "../../config/connect.php";

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

$parent_id = $_SESSION["id"];
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : null;
$period = isset($_GET['period']) ? $_GET['period'] : 'month';
$class_id = isset($_GET['class_id']) ? $_GET['class_id'] : 'all';

if (!$student_id) {
    echo json_encode([
        'success' => false,
        'error' => 'Student ID is required'
    ]);
    exit;
}

try {
    // Verify student belongs to parent
    $verify_sql = "SELECT student_id FROM students WHERE parent_id = ? AND student_id = ?";
    $verify_stmt = $conn->prepare($verify_sql);
    $verify_stmt->bind_param("ii", $parent_id, $student_id);
    $verify_stmt->execute();
    $verify_result = $verify_stmt->get_result();

    if ($verify_result->num_rows === 0) {
        echo json_encode([
            'success' => false,
            'error' => 'Unauthorized access to student data'
        ]);
        exit;
    }

    // Set date condition based on period
    switch ($period) {
        case 'week':
            $date_condition = "AND attendance.date >= DATE_SUB(CURRENT_DATE, INTERVAL 1 WEEK)";
            break;
        case 'month':
            $date_condition = "AND attendance.date >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)";
            break;
        case 'semester':
            $date_condition = "AND attendance.date >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)";
            break;
        default:
            $date_condition = "AND attendance.date >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)";
    }

    // Fetch attendance data
    $sql = "SELECT 
            attendance.date,
            attendance.status,
            subject.title as subject_name,
            subject.day,
            subject.start_time,
            subject.end_time
            FROM attendance
            JOIN subject ON attendance.subject_id = subject.id 
            WHERE attendance.student_id = ? 
            " . ($class_id !== 'all' ? "AND subject.id = ? " : "") . "
            $date_condition
            ORDER BY attendance.date DESC, subject.start_time ASC";

    $stmt = $conn->prepare($sql);
    if ($class_id !== 'all') {
        $stmt->bind_param("ii", $student_id, $class_id);
    } else {
        $stmt->bind_param("i", $student_id);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $attendance_data = [];
    while ($row = $result->fetch_assoc()) {
        $attendance_data[] = [
            'date' => $row['date'],
            'status' => $row['status'],
            'subject_name' => $row['subject_name'],
            'time' => date('h:i A', strtotime($row['start_time'])) . ' - ' . 
                     date('h:i A', strtotime($row['end_time'])),
            'day' => $row['day']
        ];
    }

    // Calculate statistics
    $stats_sql = "SELECT 
            COUNT(*) as total_classes,
            SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as classes_attended,
            SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as classes_missed
            FROM attendance 
            WHERE student_id = ?
            $date_condition";
            
    $stats_stmt = $conn->prepare($stats_sql);
    $stats_stmt->bind_param("i", $student_id);
    $stats_stmt->execute();
    $stats = $stats_stmt->get_result()->fetch_assoc();

    // Get enrolled subjects
    $subjects_sql = "SELECT 
            subject.title,
            subject.day,
            subject.start_time,
            subject.end_time
            FROM enrolled_classes
            JOIN subject ON enrolled_classes.subject_id = subject.id
            WHERE enrolled_classes.student_id = ?";
            
    $subjects_stmt = $conn->prepare($subjects_sql);
    $subjects_stmt->bind_param("i", $student_id);
    $subjects_stmt->execute();
    $subjects_result = $subjects_stmt->get_result();
    
    $enrolled_subjects = [];
    while ($row = $subjects_result->fetch_assoc()) {
        $enrolled_subjects[] = [
            'title' => $row['title'],
            'day' => $row['day'],
            'time' => date('h:i A', strtotime($row['start_time'])) . ' - ' . 
                     date('h:i A', strtotime($row['end_time']))
        ];
    }

    $response = [
        'success' => true,
        'attendance_data' => $attendance_data,
        'overall_stats' => [
            'total_classes' => (int)$stats['total_classes'],
            'classes_attended' => (int)$stats['classes_attended'],
            'classes_missed' => (int)$stats['classes_missed']
        ],
        'enrolled_subjects' => $enrolled_subjects
    ];

    echo json_encode($response);

} catch (Exception $e) {
    error_log("Attendance Data Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Error fetching attendance data: ' . $e->getMessage()
    ]);
}

$conn->close();
?> 