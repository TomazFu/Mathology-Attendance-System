<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: manager-login.php");
    exit;
}

include '../manager/includes/fetch-leave-request.php';

// Include header
include "../includes/header.php";

// Include sidebar functionality
include "../includes/sidebar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leave Request Details</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/manager-leave-details.css">
</head>
<body>
    <?php renderSidebar('manager'); ?>
    <div class="main-content">
        <div class="leave-request-details">
            <h2>Leave Request Details</h2>
            <p><strong>Leave ID:</strong> <?php echo htmlspecialchars($leave_data['leave_id'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Student Name:</strong> <?php echo htmlspecialchars($leave_data['student_name'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Reason:</strong> <?php echo htmlspecialchars($leave_data['reason'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>From:</strong> <?php echo htmlspecialchars($leave_data['start_date'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>To:</strong> <?php echo htmlspecialchars($leave_data['end_date'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($leave_data['status']), ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Proof:</strong> 
                <?php 
                if (!empty($leave_data['document_path'])) {
                    $proofPath = htmlspecialchars($leave_data['document_path'], ENT_QUOTES, 'UTF-8');
                    echo '<a href="' . $proofPath . '" target="_blank">View Proof</a>';
                } else {
                    echo 'No proof provided.';
                }
                ?>
            </p>

            <a href="managerDashboard.php" class="back-button">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>