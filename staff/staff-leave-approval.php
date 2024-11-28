<?php
session_start();

// Check if the user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: staff-login.php");
    exit;
}

// Include necessary files
require_once "../config/connect.php";
include "../includes/header.php";
require_once "../includes/sidebar.php";

// Fetch pending leave requests
$sql = "SELECT l.*, s.student_name 
        FROM leaves l 
        JOIN students s ON l.student_id = s.student_id 
        WHERE l.status = 'pending' 
        ORDER BY l.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Approval</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/staff-leave-approval.css">
</head>
<body>
    <?php renderSidebar('staff'); ?>
    
    <div class="main-content">
        <h2>Pending Leave Requests</h2>
        
        <div class="leave-requests-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while($leave = $result->fetch_assoc()): ?>
                    <div class="leave-request-card">
                        <div class="leave-details">
                            <h3>Student: <?php echo htmlspecialchars($leave['student_name']); ?></h3>
                            <p><strong>Leave Type:</strong> <?php echo ucfirst(htmlspecialchars($leave['leave_type'])); ?></p>
                            <p><strong>Reason:</strong> <?php echo htmlspecialchars($leave['reason']); ?></p>
                            <p><strong>Duration:</strong> 
                                <?php echo date('d/m/Y', strtotime($leave['fromDate'])); ?> - 
                                <?php echo date('d/m/Y', strtotime($leave['toDate'])); ?>
                            </p>
                            <?php if ($leave['document_path']): ?>
                                <p><strong>Medical Certificate:</strong> 
                                    <a href="<?php echo htmlspecialchars($leave['document_path']); ?>" target="_blank">View Document</a>
                                </p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="approval-section">
                            <textarea class="response-reason" 
                                    id="reason_<?php echo $leave['leave_id']; ?>" 
                                    placeholder="Enter reason for approval/rejection"></textarea>
                            <div class="action-buttons">
                                <button class="approve-btn" 
                                        onclick="handleLeaveAction(<?php echo $leave['leave_id']; ?>, 'approved')">
                                    Approve
                                </button>
                                <button class="reject-btn" 
                                        onclick="handleLeaveAction(<?php echo $leave['leave_id']; ?>, 'rejected')">
                                    Reject
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
    <script src="../assets/js/staff-leave-approval.js"></script>
</body>
</html>
