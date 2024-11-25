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
$sql = "SELECT l.*, s.student_name 
        FROM leaves l 
        INNER JOIN students s ON l.student_id = s.student_id 
        WHERE s.parent_id = ? 
        ORDER BY l.created_at DESC";

try {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $parent_id);
    $stmt->execute();
    $leave_history = $stmt->get_result();
} catch (Exception $e) {
    $leave_history = null;
    error_log("Error fetching leave history: " . $e->getMessage());
}

$base_url = rtrim(dirname($_SERVER['PHP_SELF']), '/parent');
?>

<!-- Update CSS references -->
<link rel="stylesheet" href="../assets/css/parent.css"> <!-- Parent-specific styles -->

<head>
    <meta name="base-url" content="<?php echo $base_url; ?>">
    <script>
        const BASE_URL = '<?php echo rtrim(dirname($_SERVER['PHP_SELF']), '/parent'); ?>';
    </script>
</head>

<div class="dashboard-layout">
    <?php renderSidebar('parent'); ?>
    <div class="main-content">
        <div class="card" id="leaveForm">
            <h2 class="form-title">Submit Leave Request</h2>
            
            <form id="leaveApplicationForm" enctype="multipart/form-data" method="post">
                <div class="student-selector">
                    <select name="student_id" id="student_id" required>
                        <?php
                        // Fetch students for current parent
                        $student_sql = "SELECT student_id, student_name FROM students WHERE parent_id = ?";
                        $stmt = $conn->prepare($student_sql);
                        $stmt->bind_param("i", $_SESSION['id']);
                        $stmt->execute();
                        $students = $stmt->get_result();
                        
                        echo "<option value=''>Select Student</option>"; // Add a default option
                        while ($student = $students->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($student['student_id']) . "'>" 
                                . htmlspecialchars($student['student_name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Leave Type Selection -->
                <div class="leave-type-selector">
                    <h3>Select Leave Type</h3>
                    <div class="leave-types">
                        <div class="leave-type-card" data-type="medical">
                            <div class="leave-type-header">
                                <i class="material-icons">local_hospital</i>
                                <h4>Medical Leave</h4>
                            </div>
                            <ul class="leave-requirements">
                                <li>Medical Certificate required</li>
                                <li>Can submit same day</li>
                                <li>Up to 6 days per year</li>
                            </ul>
                        </div>

                        <div class="leave-type-card" data-type="normal">
                            <div class="leave-type-header">
                                <i class="material-icons">event_available</i>
                                <h4>Normal Leave</h4>
                            </div>
                            <ul class="leave-requirements">
                                <li>No documentation needed</li>
                                <li>48 hours advance notice</li>
                                <li>Auto-approved with replacement</li>
                            </ul>
                        </div>

                        <div class="leave-type-card" data-type="gap">
                            <div class="leave-type-header">
                                <i class="material-icons">date_range</i>
                                <h4>Gap Month</h4>
                            </div>
                            <ul class="leave-requirements">
                                <li>Monthly leave option</li>
                                <li>Up to 2 months per year</li>
                                <li>Auto stop charging</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Dynamic form fields based on leave type -->
                <div class="form-sections">
                    <!-- Medical Leave Fields -->
                    <div class="leave-section medical-leave" style="display: none;">
                        <div class="form-group required-doc">
                            <label>Medical Certificate (Required)</label>
                            <div class="file-upload-container">
                                <label class="custom-file-upload">
                                    <input type="file" id="medical_certificate" name="medical_certificate" accept=".pdf,.jpg,.jpeg,.png" style="display: none;" onchange="updateFileName(this, 'medical-file-name')">
                                    <i class="material-icons">upload_file</i>
                                    <span class="file-upload-text">Click to upload Medical Certificate</span>
                                </label>
                                <div id="medical-file-name" class="selected-file"></div>
                            </div>
                            <small>Accepted formats: PDF, JPG, PNG (Max 5MB)</small>
                        </div>
                    </div>

                    <!-- Gap Month Fields -->
                    <div class="leave-section gap-month" style="display: none;">
                        <div class="form-group">
                            <label>Select Month</label>
                            <select name="gap_month" class="form-control">
                                <?php
                                for ($i = 0; $i < 3; $i++) {
                                    $month = date('F Y', strtotime("+$i month"));
                                    echo "<option value='".date('Y-m', strtotime("+$i month"))."'>$month</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Document Upload Section -->
                    <div class="leave-section document-section" style="display: none;">
                        <div class="form-group">
                            <label>Supporting Documents (Optional)</label>
                            <div class="file-upload-container">
                                <label class="custom-file-upload">
                                    <input type="file" id="supporting_document" name="supporting_document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="display: none;" onchange="updateFileName(this, 'support-file-name')">
                                    <i class="material-icons">upload_file</i>
                                    <span class="file-upload-text">Click to upload supporting documents</span>
                                </label>
                                <div id="support-file-name" class="selected-file"></div>
                            </div>
                            <small>Accepted formats: PDF, DOC, DOCX, JPG, PNG (Max 5MB)</small>
                        </div>
                    </div>

                    <!-- Common Fields -->
                    <div class="form-group">
                        <label>Reason for Leave</label>
                        <textarea name="reason" placeholder="Please provide detailed reason for leave" rows="4" class="form-control" required></textarea>
                    </div>

                    <!-- Date Selection (Hidden for Gap Month) -->
                    <div class="date-selection" style="display: none;">
                        <div class="date-range">
                            <div class="date-input">
                                <i class="material-icons">calendar_today</i>
                                <input type="date" name="start_date" id="start_date">
                            </div>
                            <span class="date-range-separator">to</span>
                            <div class="date-input">
                                <i class="material-icons">calendar_today</i>
                                <input type="date" name="end_date" id="end_date">
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="leave_type" id="leave_type">

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Submit Leave Request</button>
                </div>
            </form>

            <!-- Add this alert div after the form -->
            <div id="leaveAlert" class="alert" style="display: none;">
                <span class="alert-message"></span>
                <button type="button" class="close-alert">&times;</button>
            </div>
        </div>

        <div class="card" id="leaveHistory">
            <h2 class="form-title">Leave History</h2>
            <?php if ($leave_history && $leave_history->num_rows > 0): ?>
                <?php while($leave = $leave_history->fetch_assoc()): ?>
                    <div class="leave-history-item">
                        <div class="leave-details">
                            <p><strong>Leave ID:</strong> <?php echo htmlspecialchars($leave['leave_id']); ?></p>
                            <p><strong>Student Name:</strong> <?php echo htmlspecialchars($leave['student_name']); ?></p>
                            <p><strong>Reason:</strong> <?php echo htmlspecialchars($leave['reason']); ?></p>
                            <p>
                                <strong>Status:</strong> 
                                <span class="status-badge <?php echo $leave['status'] ?? 'pending'; ?>">
                                    <?php echo ucfirst($leave['status'] ?? 'pending'); ?>
                                </span>
                            </p>
                        </div>
                        <div class="leave-dates">
                            <p><?php echo date('d-m-Y', strtotime($leave['fromDate'])); ?> to <?php echo date('d-m-Y', strtotime($leave['toDate'])); ?></p>
                            <button class="btn-icon" onclick="viewLeaveDetails(<?php echo $leave['leave_id']; ?>)">
                                <i class="material-icons">visibility</i>
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-records">
                    <i class="material-icons">info</i>
                    <p>No leave history found</p>
                </div>
            <?php endif; ?>
        </div>

        <button class="track-leave-btn" onclick="toggleView()">Track Leave</button>
    </div>
</div>

<!-- Add JavaScript file -->
<script src="../assets/js/script.js"></script>
<script src="../assets/js/parent.js"></script>

<?php include "../includes/footer.php"; ?>
