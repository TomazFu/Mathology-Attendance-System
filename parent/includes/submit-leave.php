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
    // Debug logging
    error_log("Submit leave request started");
    error_log("Session Data: " . print_r($_SESSION, true));
    error_log("POST Data: " . print_r($_POST, true));
    error_log("FILES Data: " . print_r($_FILES, true));

    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Include database connection
    require_once "../../config/connect.php";

    // Validate session
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        error_log("Session validation failed - loggedin status: " . (isset($_SESSION["loggedin"]) ? $_SESSION["loggedin"] : "not set"));
        throw new Exception("User not logged in");
    }

    // Get and validate form data
    $parent_id = $_SESSION["id"] ?? null;
    $student_id = $_POST["student_id"] ?? null;
    $leave_type = $_POST["leave_type"] ?? '';
    $reason = trim($_POST["reason"] ?? '');

    // Separate validation for better error messages
    if (!$parent_id) {
        error_log("Missing parent_id in session: " . print_r($_SESSION, true));
        throw new Exception("Parent session not found. Please try logging in again.");
    }

    if (!$student_id) {
        error_log("Missing student_id in POST data: " . print_r($_POST, true));
        throw new Exception("Please select a student.");
    }

    error_log("Parsed data - Parent ID: $parent_id, Student ID: $student_id, Leave Type: $leave_type");

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
            
            // Check gap month limit
            $current_year = date('Y', strtotime($gap_month));
            $check_gap_sql = "SELECT COUNT(*) as gap_count 
                             FROM leaves 
                             WHERE student_id = ? 
                             AND leave_type = 'gap' 
                             AND YEAR(fromDate) = ?";
            
            $gap_stmt = $conn->prepare($check_gap_sql);
            if (!$gap_stmt) {
                throw new Exception("Database prepare error: " . $conn->error);
            }
            
            $gap_stmt->bind_param("ii", $student_id, $current_year);
            if (!$gap_stmt->execute()) {
                throw new Exception("Database execution error: " . $gap_stmt->error);
            }
            
            $gap_result = $gap_stmt->get_result();
            $gap_count = $gap_result->fetch_assoc()['gap_count'];
            
            if ($gap_count >= 2) {
                throw new Exception("Gap month limit exceeded. Please contact staff.");
            }
            
            $start_date = $gap_month . "-01";
            $end_date = date("Y-m-t", strtotime($start_date));
            break;
            
        case 'normal':
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

            // Check if the start date is at least 48 hours in advance
            $current_timestamp = time();
            $hours_difference = ($start_timestamp - $current_timestamp) / 3600;
            
            if ($hours_difference < 48) {
                throw new Exception("Normal leave must be submitted at least 48 hours in advance. Please contact staff.");
            }

            // Automatically approve the leave
            $status = 'approved';
            break;
            
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

            // Calculate days for current request
            $days_requested = (($end_timestamp - $start_timestamp) / (60 * 60 * 24)) + 1;

            // Check medical leave limit for the year
            $current_year = date('Y', $start_timestamp);
            $check_medical_sql = "SELECT 
                                    SUM(DATEDIFF(toDate, fromDate) + 1) as total_days
                                FROM leaves 
                                WHERE student_id = ? 
                                AND leave_type = 'medical' 
                                AND YEAR(fromDate) = ?
                                AND status != 'rejected'";
            
            $medical_stmt = $conn->prepare($check_medical_sql);
            if (!$medical_stmt) {
                throw new Exception("Database prepare error: " . $conn->error);
            }
            
            $medical_stmt->bind_param("ii", $student_id, $current_year);
            if (!$medical_stmt->execute()) {
                throw new Exception("Database execution error: " . $medical_stmt->error);
            }
            
            $medical_result = $medical_stmt->get_result();
            $total_days = $medical_result->fetch_assoc()['total_days'] ?? 0;
            
            if (($total_days + $days_requested) > 6) {
                throw new Exception("Medical leave limit of 6 days per year exceeded. Please contact staff.");
            }

            // Additional validation for medical leave
            if (!isset($_FILES["medical_certificate"]) || $_FILES["medical_certificate"]["size"] === 0) {
                throw new Exception("Medical certificate is required for medical leave");
            }
            break;
            
        default:
            throw new Exception("Invalid leave type");
    }

    // Process medical certificate for medical leave
    $document_path = null;
    if ($leave_type === 'medical') {
        if (!isset($_FILES["medical_certificate"]) || $_FILES["medical_certificate"]["size"] === 0) {
            throw new Exception("Medical certificate is required for medical leave");
        }

        $file = $_FILES["medical_certificate"];
        $allowed = ["pdf", "jpg", "jpeg", "png"];
        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        
        if (!in_array($ext, $allowed)) {
            throw new Exception("Invalid file type for medical certificate. Allowed types: PDF, JPG, PNG");
        }
        
        if ($file["size"] > 5 * 1024 * 1024) {
            throw new Exception("Medical certificate exceeds 5MB limit");
        }

        $upload_dir = "../../uploads/medical/";
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                throw new Exception("Failed to create medical certificate upload directory");
            }
        }

        $original_name = preg_replace("/[^a-zA-Z0-9.-]/", "_", $file["name"]);
        $filename = pathinfo($original_name, PATHINFO_FILENAME);
        $new_filename = 'mc_' . $filename . '_' . uniqid() . '.' . $ext;
        $document_path = $upload_dir . $new_filename;
        
        if (!move_uploaded_file($file["tmp_name"], $document_path)) {
            throw new Exception("Failed to upload medical certificate");
        }
    }

    // Process supporting document if provided
    $supportive_document_path = null;
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

        $upload_dir = "../../uploads/supporting/";
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
        $supportive_document_path = $upload_dir . $new_filename;
        
        if (!move_uploaded_file($file["tmp_name"], $supportive_document_path)) {
            throw new Exception("Failed to upload supporting document");
        }
    }

    // Insert leave request
    $sql = "INSERT INTO leaves (student_id, reason, fromDate, toDate, document_path, supportive_document_path, status, leave_type) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Database prepare error: " . $conn->error);
    }

    if (!$stmt->bind_param("isssssss", $student_id, $reason, $start_date, $end_date, $document_path, $supportive_document_path, $status, $leave_type)) {
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
        'status' => $status,
        'leave_type' => $leave_type
    ];

    error_log("Leave request submitted successfully. Leave ID: " . $conn->insert_id);

} catch (Exception $e) {
    error_log("Leave submission error: " . $e->getMessage());
    $response['message'] = $e->getMessage();
} finally {
    // Clean any output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    error_log("Final response: " . print_r($response, true));
    // Ensure proper JSON response
    echo json_encode($response);
    exit;
}
?> 