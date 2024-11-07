<?php
// Include the process file to fetch data
include('../manager/includes/fetch-managerDashboard.php');

// Include header
include "../includes/header.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/managerDashboard.css">
</head>
<body>
    <div class="dashboard">
        <!-- Export Button -->
        <div class="export-options">
            <h2>Export Report</h2>
            <a href="../manager/includes/downloadReport.php?type=pdf" class="export-btn">Download PDF</a>
            <a href="../manager/includes/downloadReport.php?type=csv" class="export-btn">Download CSV</a>
        </div>
        
        <!-- Monthly Summary -->
        <div class="monthly-summary">
            <h2>This Month</h2>
            <p>Total Fees Collection: RM <?php echo number_format($total_fees, 2); ?></p>
            <p>Total Fees Outstanding: RM <?php echo number_format($outstanding_fees, 2); ?></p>
        </div>

        <!-- Stats -->
        <div class="stats">
            <div>Total Students: <?php echo $student_count; ?></div>
            <div>Total Staff: <?php echo $staff_count; ?></div>
            <div>Usage: 90%</div>
        </div>

        <!-- Attendance -->
        <div class="attendance">
            <h2>Overall Attendance Today</h2>
            <p><?php echo $attendance_percentage; ?>%</p>
        </div>

        <!-- Latest Leave Requests -->
        <div class="latest-leave-requests">
            <h2>Latest Leave Requests</h2>
            <?php while ($leave = $leave_requests_result->fetch_assoc()): ?>
                <div class="leave-request">
                    <p>Leave ID: <?php echo $leave['leave_id']; ?></p>
                    <p>Student Name: <?php echo $leave['student_name']; ?></p>
                    <p>Reason: <?php echo $leave['reason']; ?></p>
                    <p>From: <?php echo $leave['start_date']; ?> to <?php echo $leave['end_date']; ?></p>
                    <a href="leave-request-details.php?leave_id=<?php echo $leave['leave_id']; ?>" class="view-btn">View</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>

<?php
// Include footer
include "../includes/footer.php";
?>