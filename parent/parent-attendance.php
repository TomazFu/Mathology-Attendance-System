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
                    <span class="stat-value" id="total-classes">0</span>
                    <span class="stat-label">Total Classes</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="material-icons">check_circle</i>
                </div>
                <div class="stat-info">
                    <span class="stat-value" id="classes-attended">0</span>
                    <span class="stat-label">Classes Attended</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon warning">
                    <i class="material-icons">warning</i>
                </div>
                <div class="stat-info">
                    <span class="stat-value" id="classes-missed">0</span>
                    <span class="stat-label">Classes Missed</span>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="content-grid">
            <!-- Attendance Records Section -->
            <div class="attendance-records-section">
                <div class="section-header">
                    <h2>Attendance Records</h2>
                    <div class="filters">
                        <div class="class-selector">
                            <select id="class-select">
                                <option value="all">All Classes</option>
                                <!-- Will be populated by JavaScript -->
                            </select>
                        </div>
                        <div class="period-selector">
                            <select id="attendance-period">
                                <option value="week">This Week</option>
                                <option value="month" selected>This Month</option>
                                <option value="semester">This Semester</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="attendance-list">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/attendance.js"></script>
<script src="../assets/js/script.js"></script>

<?php include "../includes/footer.php"; ?>