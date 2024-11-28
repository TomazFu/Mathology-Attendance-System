<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

require_once "../../config/connect.php";
require_once "send-leave-status-email.php";

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['leave_id']) || !isset($data['action']) || !isset($data['reason'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit;
}

$leave_id = $data['leave_id'];
$action = $data['action'];
$reason = $data['reason'];

try {
    // Start transaction
    $conn->begin_transaction();

    // Get leave request and parent email
    $query = "SELECT l.*, s.student_name, p.email as parent_email 
              FROM leaves l
              JOIN students s ON l.student_id = s.student_id
              JOIN parent p ON s.parent_id = p.parent_id
              WHERE l.leave_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $leave_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $leaveData = $result->fetch_assoc();

    if (!$leaveData) {
        throw new Exception("Leave request not found");
    }

    // Update leave status
    $updateStmt = $conn->prepare("UPDATE leaves SET status = ?, response_reason = ? WHERE leave_id = ?");
    $updateStmt->bind_param("ssi", $action, $reason, $leave_id);
    
    if (!$updateStmt->execute()) {
        throw new Exception("Failed to update leave status");
    }

    // Send email notification
    $emailSent = sendLeaveStatusEmail($leaveData, $leaveData['parent_email'], $action, $reason);
    
    if (!$emailSent) {
        // Log the error but don't stop the process
        error_log("Failed to send email notification for leave ID: " . $leave_id);
    }

    // Commit transaction
    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Leave request has been ' . $action . ' successfully'
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    $conn->close();
}
?> 