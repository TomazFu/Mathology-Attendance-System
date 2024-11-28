<?php
session_start();

// Check if the user is logged in
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
        <div class="package-container">
            <div class="package-header">
                <h1>Choose Your Learning Journey</h1>
                <p>Select the perfect package that suits your child's educational needs</p>
                
                <!-- Add Student Selector -->
                <div class="student-selector">
                    <select id="student-select">
                        <?php
                        // Fetch students for current parent
                        $parent_id = $_SESSION['id'];
                        $student_sql = "SELECT s.student_id, s.student_name, p.package_name as current_package 
                                      FROM students s 
                                      LEFT JOIN packages p ON s.package_id = p.id 
                                      WHERE s.parent_id = ?";
                        $stmt = $conn->prepare($student_sql);
                        $stmt->bind_param("i", $parent_id);
                        $stmt->execute();
                        $students = $stmt->get_result();
                        
                        while ($student = $students->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($student['student_id']) . "' 
                                  data-package='" . htmlspecialchars($student['current_package']) . "'>" 
                                . htmlspecialchars($student['student_name']) 
                                . "</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <!-- Current Package Indicator -->
                <div class="current-package-indicator">
                    <span class="label">Current Package:</span>
                    <span id="current-package-name" class="value">-</span>
                </div>
            </div>

            <!-- Level Selection -->
            <div class="level-selector">
                <button class="level-btn active" data-level="pre-primary">Pre-Primary & Primary</button>
                <button class="level-btn" data-level="secondary">Secondary</button>
                <button class="level-btn" data-level="upper-secondary">Upper Secondary</button>
                <button class="level-btn" data-level="post-secondary">Post Secondary</button>
            </div>

            <!-- Package Grid -->
            <div class="packages-grid">
                <!-- Regular Program -->
                <div class="package-card" id="regular-program-card">
                    <div class="package-badge">Regular Program</div>
                    <div class="current-package-tag" style="display: none;">Current Package</div>
                    <div class="package-price">
                        <span class="currency">RM</span>
                        <span class="amount">280</span>
                        <span class="period">/month</span>
                    </div>
                    <div class="package-subtitle">8 hours/month</div>
                    <ul class="package-features">
                        <li><i class="material-icons">schedule</i> 2 visits per week (1 hour each)</li>
                        <li><i class="material-icons">schedule</i> OR 1 visit per week (2 hours each)</li>
                        <li><i class="material-icons">check_circle</i> Quarterly Package (24 hours): RM 800</li>
                        <li><i class="material-icons">check_circle</i> Half-Yearly Package (48 hours): RM 1560</li>
                    </ul>
                    <button class="package-btn">Get Started</button>
                </div>

                <!-- Maintenance Program -->
                <div class="package-card" id="maintenance-program-card">
                    <div class="package-badge">Maintenance Program</div>
                    <div class="current-package-tag" style="display: none;">Current Package</div>
                    <div class="package-price">
                        <span class="currency">RM</span>
                        <span class="amount">690</span>
                        <span class="period">/quarter</span>
                    </div>
                    <div class="package-subtitle">6 hours/month</div>
                    <ul class="package-features">
                        <li><i class="material-icons">schedule</i> 1 visit per week (1.5 hours each)</li>
                        <li><i class="material-icons">check_circle</i> Quarterly Payment Only</li>
                        <li><i class="material-icons">check_circle</i> Flexible Schedule</li>
                        <li><i class="material-icons">check_circle</i> Personalized Learning Path</li>
                        <li><i class="material-icons">check_circle</i> Progress Monitoring</li>
                    </ul>
                    <button class="package-btn">Get Started</button>
                </div>

                <!-- Intensive Program -->
                <div class="package-card" id="intensive-program-card">
                    <div class="package-badge">Intensive Program</div>
                    <div class="current-package-tag" style="display: none;">Current Package</div>
                    <div class="package-price">
                        <span class="currency">RM</span>
                        <span class="amount">420</span>
                        <span class="period">/month</span>
                    </div>
                    <div class="package-subtitle">12 hours/month</div>
                    <ul class="package-features">
                        <li><i class="material-icons">schedule</i> 2 visits per week (1.5 hours each)</li>
                        <li><i class="material-icons">check_circle</i> Quarterly Package (36 hours): RM 1200</li>
                        <li><i class="material-icons">check_circle</i> Enhanced Learning Support</li>
                        <li><i class="material-icons">check_circle</i> Detailed Progress Tracking</li>
                        <li><i class="material-icons">check_circle</i> Priority Scheduling</li>
                    </ul>
                    <button class="package-btn">Get Started</button>
                </div>

                <!-- Super Intensive Program -->
                <div class="package-card" id="super-intensive-program-card">
                    <div class="package-badge">Super Intensive Program</div>
                    <div class="current-package-tag" style="display: none;">Current Package</div>
                    <div class="package-price">
                        <span class="currency">RM</span>
                        <span class="amount">560</span>
                        <span class="period">/month</span>
                    </div>
                    <div class="package-subtitle">16 hours/month</div>
                    <ul class="package-features">
                        <li><i class="material-icons">schedule</i> 2 visits per week (2 hours each)</li>
                        <li><i class="material-icons">check_circle</i> Quarterly Package (48 hours): RM 1600</li>
                        <li><i class="material-icons">check_circle</i> Program suitability depends on diagnostic assessment</li>
                        <li><i class="material-icons">check_circle</i> Premium Learning Support</li>
                        <li><i class="material-icons">check_circle</i> Advanced Progress Analytics</li>                        
                    </ul>
                    <button class="package-btn">Get Started</button>
                </div>
            </div>

            <!-- Additional Fees Section -->
            <div class="additional-fees">
                <h3>Additional Fees</h3>
                <div class="fees-grid">
                    <div class="fee-item">
                        <span class="fee-label">Registration</span>
                        <span class="fee-amount">RM 50</span>
                    </div>
                    <div class="fee-item">
                        <span class="fee-label">Diagnostic Assessment (Lifetime)</span>
                        <span class="fee-amount">RM 100</span>
                    </div>
                    <div class="fee-item">
                        <span class="fee-label">Deposit (1 month)</span>
                        <span class="fee-amount deposit-amount">RM 280</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/package.js"></script>
<script src="../assets/js/script.js"></script>

<?php include "../includes/footer.php"; ?>

