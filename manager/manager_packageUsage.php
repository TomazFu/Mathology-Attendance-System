<?php
session_start();
include('../manager/includes/fetch-managerDashboard.php');
include "../includes/header.php";
include "../includes/sidebar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Package Usage Statistics</title>
    <link rel="stylesheet" href="../assets/css/managerDashboard.css">
</head>
<body>
    <?php renderSidebar('manager'); ?>
    <div class="dashboard-container">
        <div class="dashboard-content">
            <h1>Package Usage Statistics</h1>
            
                <div class="stat-cards">
                    <?php foreach ($package_usage_data as $package): ?>
                    <div class="stat-card">
                        <h3><?php echo htmlspecialchars($package['package_name']); ?></h3>
                        <div class="stat-details">
                            <div class="stat-item">
                                <span class="stat-label">Students:</span>
                                <span class="stat-value"><?php echo $package['student_count']; ?></span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Usage:</span>
                                <span class="stat-value"><?php echo $package['usage_percentage']; ?>%</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Price:</span>
                                <span class="stat-value">RM <?php echo $package['package_price']; ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
        </div>
    </div>
</body>
</html> 