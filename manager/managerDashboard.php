<?php
session_start();
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Sidebar -->
    <?php renderSidebar('manager'); ?>
        <div class="dashboard-container">

            <!-- Main Dashboard Content -->
            <div class="dashboard-content">
                <!-- Charts Container -->
                <div class="charts-container">
                    <!-- Sales Chart -->
                    <div class="sales-chart">
                        <h2>Monthly Sales</h2>
                        <canvas id="salesChart"></canvas>
                    </div>
                    <!-- Population Chart -->
                    <div class="population-chart">
                        <h2>Current Population</h2>
                        <canvas id="populationChart"></canvas>
                    </div>
                </div>

                <!-- Export Button -->
                <div class="export-options">
                    <h2>Export Report</h2>
                    <form method="GET" action="../manager/includes/downloadReport.php">
                        <select name="year" required>
                            <option value="" disabled selected>Select Year</option>
                            <?php
                            $currentYear = date('Y');
                            for ($y = $currentYear; $y >= $currentYear - 2; $y--) {
                                echo "<option value=\"$y\">$y</option>";
                            }
                            ?>
                        </select>
                        <select name="month" required>
                            <option value="" disabled selected>Select Month</option>
                            <?php
                            for ($m = 1; $m <= 12; $m++) {
                                $monthName = date('F', mktime(0, 0, 0, $m, 1));
                                echo "<option value=\"$m\">$monthName</option>";
                            }
                            ?>
                        </select>
                        <button type="submit" name="type" value="pdf" class="export-btn">Download PDF</button>
                        <button type="submit" name="type" value="csv" class="export-btn">Download CSV</button>
                    </form>
                </div>


                <!-- Stats -->
                <div class="package-stats">
                    <div class="package-stat-cards">
                        <h2>Package Usage Statistics</h2>
                        <a href="manager_packageUsage.php" class="package-usage-link">
                            <p>Click to view detailed package usage information</p>
                        </a>
                    </div>
                </div>

                <!-- Attendance -->
                <div class="attendance">
                    <h2>Overall Attendance This Month</h2>
                    <!-- TODO attendance percentage -->
                    <p><?php echo $attendance_percentage; ?>%</p>
                </div>

                <!-- Latest Leave Requests -->
                <div class="latest-leave-requests">
                    <h2>Latest Leave Requests</h2>
                    <?php while ($leave = $leave_requests_result->fetch_assoc()): ?>
                        <a href="leave-request-details.php?leave_id=<?php echo urlencode($leave['leave_id']); ?>" class="leave-request">
                            <div class="leave-content">
                                <p>Leave ID: <?php echo htmlspecialchars($leave['leave_id'], ENT_QUOTES, 'UTF-8'); ?></p>
                                <p>Student Name: <?php echo htmlspecialchars($leave['student_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                                <p>Reason: <?php echo htmlspecialchars($leave['reason'], ENT_QUOTES, 'UTF-8'); ?></p>
                                <p>From: <?php echo htmlspecialchars($leave['start_date'], ENT_QUOTES, 'UTF-8'); ?> to 
                                        <?php echo htmlspecialchars($leave['end_date'], ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>
                        </a>
                    <?php endwhile; ?>
                    
                    <!-- View All Link -->
                    <a href="all-leave-requests.php" class="view-all-btn">View All</a>
                </div>

            </div>
        </div>

    <script>
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: <?php echo $sales_dates_json; ?>,
                datasets: [{
                    label: 'Monthly Sales (RM)',
                    data: <?php echo $sales_amounts_json; ?>,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'RM ' + value;
                            },
                            font: {
                                size: 12  // Fixed font size for y-axis
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12  // Fixed font size for x-axis
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 14  // Fixed font size for legend
                            }
                        }
                    }
                }
            }
        });

        const populationCtx = document.getElementById('populationChart').getContext('2d');
        new Chart(populationCtx, {
            type: 'bar',
            data: {
                labels: ['Students', 'Staff'],
                datasets: [{
                    label: 'Total Count',
                    data: [<?php echo $student_count; ?>, <?php echo $staff_count; ?>],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)'
                    ],
                    borderColor: [
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>
</html>

