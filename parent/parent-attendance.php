<?php
session_start();
error_log("Session data: " . print_r($_SESSION, true));

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: parent-login.php");
    exit;
}

require_once "../config/connect.php";
include "../includes/header.php";
require_once "../includes/sidebar.php";
?>

<link rel="stylesheet" href="../assets/css/parent.css">

<div class="dashboard-layout">
    <?php renderSidebar('parent'); ?>
    <div class="main-content">
        <!-- Hero Section -->
        <div class="attendance-hero">
            <div class="hero-content">
                <div class="overview-container">
                    <div class="overview-content">
                        <h1>Attendance Overview</h1>
                        <p class="subtitle">Track and monitor your child's attendance records</p>
                        <div class="student-selector">
                            <select id="student-select">
                                <!-- Will be populated by JavaScript -->
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="material-icons">calendar_today</i>
                </div>
                <div class="stat-info">
                    <span class="stat-value" id="total-days">180</span>
                    <span class="stat-label">Total School Days</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="material-icons">check_circle</i>
                </div>
                <div class="stat-info">
                    <span class="stat-value" id="present-days"></span>
                    <span class="stat-label">Days Present</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="material-icons">trending_up</i>
                </div>
                <div class="stat-info">
                    <span class="stat-value" id="attendance-rate"></span>
                    <span class="stat-label">Attendance Rate</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon warning">
                    <i class="material-icons">warning</i>
                </div>
                <div class="stat-info">
                    <span class="stat-value" id="absences"></span>
                    <span class="stat-label">Total Absences</span>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="content-grid">
            <!-- Attendance Chart Section -->
            <div class="attendance-chart-section">
                <div class="section-header">
                    <h2>Attendance Trends</h2>
                    <div class="chart-controls">
                        <select id="attendance-period" onchange="updateAttendanceChart(this.value)">
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="semester">This Semester</option>
                        </select>
                        <div class="chart-type-buttons">
                            <button class="chart-type-btn active" data-type="line">
                                <i class="material-icons">show_chart</i>
                            </button>
                            <button class="chart-type-btn" data-type="bar">
                                <i class="material-icons">bar_chart</i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

            <!-- Attendance Log Section -->
            <div class="attendance-log-section">
                <div class="section-header">
                    <h2>Recent Attendance</h2>
                </div>
                <div class="attendance-log">
                    <!-- Logs will be populated dynamically -->
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/attendance.js"></script>
<script src="../assets/js/script.js"></script>

<?php include "../includes/footer.php"; ?>