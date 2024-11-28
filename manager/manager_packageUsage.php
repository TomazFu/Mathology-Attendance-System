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
    <link rel="stylesheet" href="../assets/css/manager-package.css">
</head>
<body>
    <?php renderSidebar('manager'); ?>
    <div class="dashboard-container">
        <div class="dashboard-content">
            <h1>Package Usage Statistics</h1>
            
            <div class="search-sort-container">
                <div class="search-form">
                    <input type="text" id="searchInput" placeholder="Search by package name">
                </div>

                <div class="sort-form">
                    <select id="sortSelect">
                        <option value="">Default Sort</option>
                        <option value="usage_high">Usage (High to Low)</option>
                        <option value="usage_low">Usage (Low to High)</option>
                        <option value="price_high">Price (High to Low)</option>
                        <option value="price_low">Price (Low to High)</option>
                    </select>
                </div>
            </div>

            <div class="stat-cards" id="packageCards">
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
    <script src="../assets/js/manager-package.js"></script>
</body>
</html> 