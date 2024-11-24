<?php
session_start();
require_once "../config/connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate user is logged in
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: ../parent/parent-login.php");
        exit;
    }

    $parent_id = $_SESSION["id"];
    $leave_type = $_POST["leave_type"];
    $reason = trim($_POST["reason"]);
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];
    
    // Validate dates
    if (strtotime($end_date) < strtotime($start_date)) {
        $_SESSION["error"] = "End date cannot be before start date";
        header("location: ../parent/parent-leave-view.php");
        exit;
    }

    // Get the first student associated with this parent
    $student_sql = "SELECT student_id FROM students WHERE parent_id = ? LIMIT 1";
    $student_stmt = $conn->prepare($student_sql);
    $student_stmt->bind_param("i", $parent_id);
    $student_stmt->execute();
    $result = $student_stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION["error"] = "No student found for this parent";
        header("location: ../parent/parent-leave-view.php");
        exit;
    }

    $student = $result->fetch_assoc();
    $student_id = $student['student_id'];

    // Handle different leave types
    switch($leave_type) {
        case 'medical':
            // Validate medical certificate
            if (!isset($_FILES["medical_certificate"]) || $_FILES["medical_certificate"]["error"] !== 0) {
                $_SESSION["error"] = "Medical certificate is required";
                header("location: ../parent/parent-leave-view.php");
                exit;
            }
            // Handle file upload if present
            $file_path = null;
            if (isset($_FILES["leave_application"]) && $_FILES["leave_application"]["error"] == 0) {
                $allowed = ["pdf" => "application/pdf", "doc" => "application/msword", 
                           "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document", 
                           "jpg" => "image/jpeg", "jpeg" => "image/jpeg", "png" => "image/png"];
                $filename = $_FILES["leave_application"]["name"];
                $filetype = $_FILES["leave_application"]["type"];
                $filesize = $_FILES["leave_application"]["size"];

                // Verify file extension
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if (!array_key_exists($ext, $allowed)) {
                    $_SESSION["error"] = "Error: Please select a valid file format.";
                    header("location: ../parent/parent-leave-view.php");
                    exit;
                }

                // Verify file size - 5MB maximum
                $maxsize = 5 * 1024 * 1024;
                if ($filesize > $maxsize) {
                    $_SESSION["error"] = "Error: File size is larger than the allowed limit.";
                    header("location: ../parent/parent-leave-view.php");
                    exit;
                }

                // Create upload directory if it doesn't exist
                $upload_dir = "../uploads/leave_applications/";
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                // Generate unique filename
                $new_filename = uniqid() . "." . $ext;
                $file_path = $upload_dir . $new_filename;

                if (!move_uploaded_file($_FILES["leave_application"]["tmp_name"], $file_path)) {
                    $_SESSION["error"] = "Error uploading file.";
                    header("location: ../parent/parent-leave-view.php");
                    exit;
                }
            }
            break;
            
        case 'gap':
            $gap_month = $_POST["gap_month"];
            // Validate month selection
            if (!preg_match("/^\d{4}-\d{2}$/", $gap_month)) {
                $_SESSION["error"] = "Invalid month selection";
                header("location: ../parent/parent-leave-view.php");
                exit;
            }
            // Set start and end dates to month boundaries
            $start_date = $gap_month . "-01";
            $end_date = date("Y-m-t", strtotime($start_date));
            break;
            
        case 'normal':
            // Validate 48-hour notice
            $start_timestamp = strtotime($start_date);
            if ($start_timestamp < (time() + (48 * 3600))) {
                $_SESSION["error"] = "Normal leave requires 48 hours advance notice";
                header("location: ../parent/parent-leave-view.php");
                exit;
            }
            break;
    }

    // Insert leave request into database
    $sql = "INSERT INTO leaves (student_id, reason, fromDate, toDate, document_path, status) 
            VALUES (?, ?, ?, ?, ?, 'pending')";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $student_id, $reason, $start_date, $end_date, $file_path);
        
        if ($stmt->execute()) {
            $_SESSION["success"] = "Leave request submitted successfully.";
        } else {
            $_SESSION["error"] = "Error submitting leave request.";
        }
    } catch (Exception $e) {
        $_SESSION["error"] = "Error submitting leave request.";
        error_log("Error submitting leave: " . $e->getMessage());
    }

    header("location: ../parent/parent-leave-view.php");
    exit;
}

// If not POST request, redirect back to leave form
header("location: ../parent/parent-leave-view.php");
exit;
?> 