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

                <!-- Package Details Card -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="material-icons">inventory_2</i> Current Package Details</h3>
                    </div>
                    <div class="package-details">
                        <?php
                        // Get the first student's ID as default
                        $default_student_sql = "SELECT student_id FROM students WHERE parent_id = ? LIMIT 1";
                        $stmt = $conn->prepare($default_student_sql);
                        $stmt->bind_param("i", $parent_id);
                        $stmt->execute();
                        $default_result = $stmt->get_result();
                        $default_student = $default_result->fetch_assoc();
                        $student_id = $default_student['student_id'];

                        // Fetch package details for the selected student
                        $package_sql = "SELECT p.package_name, p.price, p.details 
                                       FROM students s 
                                       LEFT JOIN packages p ON s.package_id = p.id 
                                       WHERE s.student_id = ?";
                        $stmt = $conn->prepare($package_sql);
                        $stmt->bind_param("i", $student_id);
                        $stmt->execute();
                        $package_result = $stmt->get_result();
                        $package = $package_result->fetch_assoc();
                        
                        if ($package && $package['package_name']): ?>
                            <div class="package-info">
                                <div class="info-row">
                                    <span class="label">Package Name:</span>
                                    <span class="value"><?php echo htmlspecialchars($package['package_name']); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="label">Monthly Fee:</span>
                                    <span class="value">RM <?php echo number_format($package['price'], 2); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="label">Package Details:</span>
                                    <span class="value"><?php echo htmlspecialchars($package['details']); ?></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="no-package-message">
                                No package currently assigned
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/script.js"></script>
<script src="../assets/js/parent.js"></script>

<?php include "../includes/footer.php"; ?>
