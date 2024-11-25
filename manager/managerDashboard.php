<?php
// Include the process file to fetch data
include('../manager/includes/fetch-managerDashboard.php');

// Include header
include "../includes/header.php";

// Include sidebar functionality
include "../includes/sidebar.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manager Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/managerDashboard.css">
</head>
<body>
    <!-- Sidebar -->
    <?php renderSidebar('manager'); ?>
        <div class="dashboard-container">

            <!-- Main Dashboard Content -->
            <div class="dashboard-content">
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
                    <div>Statistics</div>
                    <!-- TODO student count, staff count, usage percentage -->
                    <!-- <div>Total Students: <?php echo $student_count; ?></div>
                    <div>Total Staff: <?php echo $staff_count; ?></div>
                    <div>Usage: 90%</div> -->
                </div>

                <!-- Attendance -->
                <div class="attendance">
                    <h2>Overall Attendance Today</h2>
                    <!-- TODO attendance percentage -->
                    <!-- <p><?php echo $attendance_percentage; ?>%</p> -->
                </div>

                <!-- Latest Leave Requests -->
                <div class="latest-leave-requests">
                    <h2>Latest Leave Requests</h2>
                    <?php while ($leave = $leave_requests_result->fetch_assoc()): ?>
                        <div class="leave-request">
                            <p>Leave ID: <?php echo htmlspecialchars($leave['leave_id'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p>Student Name: <?php echo htmlspecialchars($leave['student_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p>Reason: <?php echo htmlspecialchars($leave['reason'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p>From: <?php echo htmlspecialchars($leave['start_date'], ENT_QUOTES, 'UTF-8'); ?> to 
                                    <?php echo htmlspecialchars($leave['end_date'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <a href="leave-request-details.php?leave_id=<?php echo urlencode($leave['leave_id']); ?>" 
                            class="view-btn">View</a>
                        </div>
                    <?php endwhile; ?>
                    
                    <!-- View All Link -->
                    <a href="all-leave-requests.php" class="view-all-btn">View All</a>
                </div>

            </div>
        </div>
</body>
</html>

