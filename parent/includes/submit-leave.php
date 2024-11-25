<?php
// Prevent any output before headers
ob_start();

// Enable error reporting but log to file instead of output
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../logs/php-errors.log');

// Ensure proper content type
header('Content-Type: application/json');

// Initialize response
$response = ['success' => false, 'message' => '', 'leave' => null];

try {
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Include database connection
    require_once "../../config/connect.php";

    // Debug logging
    error_log("POST Data: " . print_r($_POST, true));
    error_log("FILES Data: " . print_r($_FILES, true));

    // Validate session
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        throw new Exception("User not logged in");
    }

    // Get and validate form data
    $parent_id = $_SESSION["id"] ?? null;
    $student_id = $_POST["student_id"] ?? null;
    $leave_type = $_POST["leave_type"] ?? '';
    $reason = trim($_POST["reason"] ?? '');

    if (!$parent_id || !$student_id) {
        throw new Exception("Invalid session or student data");
    }

    // Verify student belongs to parent
    $student_sql = "SELECT student_name FROM students WHERE student_id = ? AND parent_id = ? LIMIT 1";
    $student_stmt = $conn->prepare($student_sql);
    if (!$student_stmt) {
        throw new Exception("Database prepare error: " . $conn->error);
    }

    $student_stmt->bind_param("ii", $student_id, $parent_id);
    if (!$student_stmt->execute()) {
        throw new Exception("Database execution error: " . $student_stmt->error);
    }

    $result = $student_stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception("Invalid student selection");
    }

    $student = $result->fetch_assoc();
    $student_name = $student['student_name'];

    // Handle dates based on leave type
    switch ($leave_type) {
        case 'gap':
            $gap_month = $_POST["gap_month"] ?? '';
            if (!preg_match("/^\d{4}-\d{2}$/", $gap_month)) {
                throw new Exception("Invalid month selection");
            }
            $start_date = $gap_month . "-01";
            $end_date = date("Y-m-t", strtotime($start_date));
            break;
            
        case 'normal':
        case 'medical':
            $start_date = $_POST["start_date"] ?? '';
            $end_date = $_POST["end_date"] ?? '';
            
            if (empty($start_date) || empty($end_date)) {
                throw new Exception("Start and end dates are required");
            }

            // Validate dates
            $start_timestamp = strtotime($start_date);
            $end_timestamp = strtotime($end_date);
            
            if (!$start_timestamp || !$end_timestamp) {
                throw new Exception("Invalid date format");
            }
            
            if ($start_timestamp > $end_timestamp) {
                throw new Exception("End date cannot be before start date");
            }

            // Additional validation for medical leave
            if ($leave_type === 'medical' && 
                (!isset($_FILES["medical_certificate"]) || $_FILES["medical_certificate"]["size"] === 0)) {
                throw new Exception("Medical certificate is required for medical leave");
            }
            break;
            
        default:
            throw new Exception("Invalid leave type");
    }

    // Process supporting document if provided
    $document_path = null;
    if (isset($_FILES["supporting_document"]) && $_FILES["supporting_document"]["size"] > 0) {
        $file = $_FILES["supporting_document"];
        $allowed = ["pdf", "jpg", "jpeg", "png"];
        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        
        if (!in_array($ext, $allowed)) {
            throw new Exception("Invalid file type for supporting document. Allowed types: PDF, JPG, PNG");
        }
        
        if ($file["size"] > 5 * 1024 * 1024) {
            throw new Exception("Supporting document exceeds 5MB limit");
        }

        $upload_dir = "../../uploads/";
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                throw new Exception("Failed to create upload directory");
            }
        }

        // Sanitize original filename
        $original_name = preg_replace("/[^a-zA-Z0-9.-]/", "_", $file["name"]);
        
        // Create unique filename while keeping original name
        $filename = pathinfo($original_name, PATHINFO_FILENAME);
        $new_filename = $filename . '_' . uniqid() . '.' . $ext;
        $document_path = $upload_dir . $new_filename;
        
        if (!move_uploaded_file($file["tmp_name"], $document_path)) {
            throw new Exception("Failed to upload supporting document");
        }
    }

    // Insert leave request
    $sql = "INSERT INTO leaves (student_id, reason, fromDate, toDate, document_path, status, leave_type) 
            VALUES (?, ?, ?, ?, ?, 'pending', ?)";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Database prepare error: " . $conn->error);
    }

    if (!$stmt->bind_param("isssss", $student_id, $reason, $start_date, $end_date, $document_path, $leave_type)) {
        throw new Exception("Database binding error: " . $stmt->error);
    }

    if (!$stmt->execute()) {
        throw new Exception("Database execution error: " . $stmt->error);
    }

    $leave_id = $conn->insert_id;
    $response['success'] = true;
    $response['message'] = "Leave request submitted successfully";
    $response['leave'] = [
        'leave_id' => $leave_id,
        'student_name' => $student_name,
        'reason' => $reason,
        'fromDate' => $start_date,
        'toDate' => $end_date,
        'status' => 'pending',
        'leave_type' => $leave_type
    ];

} catch (Exception $e) {
    error_log("Leave submission error: " . $e->getMessage());
    $response['message'] = $e->getMessage();
} finally {
    // Clean any output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }

    // Ensure proper JSON response
    echo json_encode($response);
    exit;
}
?> 