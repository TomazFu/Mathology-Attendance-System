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
                    <button class="action-btn" data-action="view-attendance">
                        <i class="material-icons">fact_check</i>
                        View Attendance
                    </button>
                    <button class="action-btn" data-action="view-package">
                        <i class="material-icons">inventory_2</i>
                        View Package
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
                <div class="student-selector">
                    <select id="student-select">
                        <?php
                        // Fetch students for current parent
                        $parent_id = $_SESSION['id'];
                        $student_sql = "SELECT student_id, student_name FROM students WHERE parent_id = ?";
                        $stmt = $conn->prepare($student_sql);
                        $stmt->bind_param("i", $parent_id);
                        $stmt->execute();
                        $students = $stmt->get_result();
                        
                        while ($student = $students->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($student['student_id']) . "'>" 
                                . htmlspecialchars($student['student_name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
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
                        <i class="material-icons">event_busy</i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="leave-status">0</span>
                        <span class="stat-label">Total Leave Requests</span>
                    </div>
                </div>
            </div>

            <div class="dashboard-sections">
                <!-- Class Schedule Card -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="material-icons">schedule</i> Upcoming Classes</h3>
                        <a href="parent-timetable.php" class="view-all-btn">View Schedule</a>
                    </div>
                    <div class="schedule-list" id="upcoming-classes">
                        <!-- Classes will be populated dynamically -->
                        <div class="no-classes-message" style="display: none;">
                            No upcoming classes scheduled
                        </div>
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

<script src="../assets/js/script.js"></script>
<script src="../assets/js/parent.js"></script>

<?php include "../includes/footer.php"; ?>
