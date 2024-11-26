<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: parent-login.php");
    exit;
}

require_once "../config/connect.php";
include "../includes/header.php";
require_once "../includes/sidebar.php";

$parent_id = $_SESSION["id"];
?>

<!-- Update CSS references -->
<link rel="stylesheet" href="../assets/css/parent.css">

<div class="dashboard-layout">
    <?php renderSidebar('parent'); ?>
    <div class="main-content">
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="hero-content">
                <div class="welcome-text">
                    <h1>Welcome back, <?php echo htmlspecialchars($_SESSION["name"]); ?>!</h1>
                    <p class="hero-subtitle">Monitor your child's progress, track attendance, and stay connected with their learning journey at Mathology Math Centre.</p>
                </div>
                <div class="quick-actions">
                    <button class="action-btn primary" data-action="apply-leave">
                        <i class="material-icons">add_circle</i>
                        Apply Leave
                    </button>
                    <button class="action-btn" data-action="view-schedule">
                        <i class="material-icons">calendar_today</i>
                        View Schedule
                    </button>
                    <button class="action-btn" data-action="contact-teacher">
                        <i class="material-icons">message</i>
                        Contact Teacher
                    </button>
                </div>
            </div>
            <div class="hero-illustration">
                <div class="hero-image-wrapper">
                    <img src="../assets/img/math-hero.png" alt="Mathematics Education" class="hero-image">
                </div>
            </div>
        </div>

        <div class="dashboard-container">
            <!-- Progress Overview -->
            <div class="section-header">
                <h2>Progress Overview</h2>
                <p class="section-subtitle">Track your child's academic performance</p>
            </div>

            <div class="dashboard-stats">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="material-icons">school</i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="total-classes">0</span>
                        <span class="stat-label">Enrolled Classes</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="material-icons">event_available</i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="attendance-rate">0%</span>
                        <span class="stat-label">Attendance Rate</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="material-icons">trending_up</i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value">85%</span>
                        <span class="stat-label">Average Performance</span>
                    </div>
                </div>
            </div>

            <div class="dashboard-sections">
                <!-- Class Schedule Card -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="material-icons">schedule</i> Upcoming Classes</h3>
                        <button class="view-all-btn">View Schedule</button>
                    </div>
                    <div class="schedule-list">
                        <!-- Add schedule items here -->
                        <div class="schedule-item" data-class-id="123">
                            <div class="schedule-time">
                                <i class="material-icons">access_time</i>
                                <span>09:00 AM</span>
                            </div>
                            <div class="schedule-info">
                                <h4>Advanced Mathematics</h4>
                                <p>with Mr. John Smith</p>
                            </div>
                            <div class="schedule-status">
                                <span class="status-badge upcoming">Upcoming</span>
                            </div>
                        </div>
                        <!-- Add more schedule items -->
                    </div>
                </div>

                <!-- Performance Analytics Card -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="material-icons">analytics</i> Performance Analytics</h3>
                        <select id="performance-period">
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="quarter">This Quarter</option>
                        </select>
                    </div>
                    <div class="performance-chart">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add required scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/parent.js"></script>

<?php include "../includes/footer.php"; ?>
