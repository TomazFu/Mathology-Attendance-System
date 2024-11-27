<?php
error_reporting(0); // Disable error reporting for production
require_once "../../config/connect.php";
require_once "send-receipt-email.php";

// Set timezone to Malaysia
date_default_timezone_set('Asia/Kuala_Lumpur');

// Ensure we only output JSON
header('Content-Type: application/json');
ob_clean(); // Clear any previous output

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_id'])) {
    try {
        $payment_id = intval($_POST['payment_id']);
        
        // Start transaction
        $conn->begin_transaction();
        
        // Get payment details
        $sql = "SELECT p.*, s.student_name, pr.name as parent_name, pr.email as parent_email, 
                pkg.package_name, pkg.price as package_price
                FROM payments p
                JOIN students s ON p.student_id = s.student_id
                JOIN parent pr ON s.parent_id = pr.parent_id
                LEFT JOIN packages pkg ON p.package_id = pkg.id
                WHERE p.id = ?";
                
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $payment_id);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $paymentData = $result->fetch_assoc();
        
        if (!$paymentData) {
            throw new Exception("Payment not found");
        }
        
        // Update payment status
        $updateSql = "UPDATE payments SET status = 'paid' WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        if (!$updateStmt) {
            throw new Exception("Prepare update failed: " . $conn->error);
        }

        $updateStmt->bind_param("i", $payment_id);
        if (!$updateStmt->execute()) {
            throw new Exception("Failed to update payment status: " . $updateStmt->error);
        }
        
        // Send email only if parent email exists
        $emailSent = false;
        if (!empty($paymentData['parent_email'])) {
            $emailSent = sendReceiptEmail($paymentData, $paymentData['parent_email']);
        }
        
        $conn->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Payment status updated successfully' . 
                        ($emailSent ? ' and receipt sent to email' : '')
        ]);
        
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    } finally {
        $stmt->close();
        $updateStmt->close();
        $conn->close();
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
}
exit;
?>