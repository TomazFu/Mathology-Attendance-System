<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start logging
error_log("Starting dashboard data fetch");

try {
    session_start();
    require_once '../../config/connect.php';
    
    error_log("Database connection established");

    if (!isset($_GET['student_id'])) {
        throw new Exception('Student ID not provided');
    }

    $selected_student_id = $_GET['student_id'];
    error_log("Selected student ID: " . $selected_student_id);

    // Get total enrolled classes
    $sql = "SELECT COUNT(*) as total_classes FROM enrolled_classes WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $selected_student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $totalClasses = $row['total_classes'] ?? 0;

    error_log("Total Classes: " . $totalClasses);

    // Calculate attendance rate
    $sql = "SELECT 
                COUNT(*) as total_days,
                SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_days
            FROM attendance 
            WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $selected_student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $attendance = $result->fetch_assoc();

    $attendanceRate = 0;
    if ($attendance['total_days'] > 0) {
        $attendanceRate = round(($attendance['present_days'] / $attendance['total_days']) * 100, 2);
    }

    error_log("Attendance Rate: " . $attendanceRate);

    // Get total leave requests
    $sql = "SELECT COUNT(*) as total_leaves FROM leaves WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $selected_student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $leaves = $result->fetch_assoc();
    $totalLeaves = $leaves['total_leaves'] ?? 0;

    error_log("Total Leaves: " . $totalLeaves);

    // Get upcoming classes
    $sql = "SELECT s.title as class_name, s.day, s.start_time as time, s.room
            FROM enrolled_classes ec
            JOIN subject s ON ec.subject_id = s.id
            WHERE ec.student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $selected_student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $upcomingClasses = [];
    while ($class = $result->fetch_assoc()) {
        error_log("Processing class: " . print_r($class, true));
        
        // Get the next occurrence of the class day
        $today = new DateTime();
        $nextClass = clone $today;
        $daysUntilClass = 0;
        
        // Calculate days until next class
        $currentDay = strtolower($today->format('l'));
        $classDay = strtolower($class['day']);
        
        if ($currentDay == $classDay) {
            // If it's the same day, check if the class time has passed
            $currentTime = $today->format('H:i:s');
            if ($currentTime > $class['time']) {
                $daysUntilClass = 7; // Next week
            }
        } else {
            $daysUntilClass = (strtotime("next " . $class['day']) - strtotime('today')) / (60 * 60 * 24);
        }
        
        $nextClass->modify("+{$daysUntilClass} days");
        
        $upcomingClasses[] = [
            'name' => $class['class_name'],
            'time' => $class['time'],
            'date' => $nextClass->format('Y-m-d'),
            'room' => $class['room']
        ];
    }

    // Sort upcoming classes by date and time
    usort($upcomingClasses, function($a, $b) {
        $dateA = strtotime($a['date'] . ' ' . $a['time']);
        $dateB = strtotime($b['date'] . ' ' . $b['time']);
        return $dateA - $dateB;
    });

    // Limit to next 5 classes
    $upcomingClasses = array_slice($upcomingClasses, 0, 5);

    error_log("Upcoming Classes: " . print_r($upcomingClasses, true));

    $response = [
        'totalClasses' => (int)$totalClasses,
        'attendanceRate' => $attendanceRate,
        'totalLeaves' => (int)$totalLeaves,
        'upcomingClasses' => $upcomingClasses
    ];

    header('Content-Type: application/json');
    echo json_encode($response);

} catch (Exception $e) {
    error_log("Error in dashboard data fetch: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'totalClasses' => 0,
        'attendanceRate' => 0,
        'totalLeaves' => 0,
        'upcomingClasses' => []
    ]);
}

if ($conn) {
    $conn->close();
}
?>