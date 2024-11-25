<?php

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
    <link rel="stylesheet" href="../assets/css/manager-leave-details.css">
</head>
<body>
    <div class="leave-request-details">
        <?php renderSidebar('manager'); ?>
        <h2>Leave Request Details</h2>
        <p><strong>Leave ID:</strong> <?php echo $leave_data['leave_id']; ?></p>
        <p><strong>Student Name:</strong> <?php echo $leave_data['student_name']; ?></p>
        <p><strong>Reason:</strong> <?php echo $leave_data['reason']; ?></p>
        <p><strong>From:</strong> <?php echo $leave_data['start_date']; ?></p>
        <p><strong>To:</strong> <?php echo $leave_data['end_date']; ?></p>
        <a href="managerDashboard.php" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>