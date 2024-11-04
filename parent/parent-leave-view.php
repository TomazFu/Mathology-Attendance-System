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
?>

<!-- Update CSS references -->
<link rel="stylesheet" href="../assets/css/style.css">  <!-- Global styles -->
<link rel="stylesheet" href="../assets/css/parent.css"> <!-- Parent-specific styles -->

<div class="dashboard-layout">
    <?php renderSidebar('parent'); ?>
    <div class="main-content">
        <div class="card" id="leaveForm">
            <h2 class="form-title">Submit Leave Request</h2>
            
            <div class="success-message" id="successMessage">
                Leave request submitted successfully!
            </div>

            <form id="leaveApplicationForm" method="POST" action="../includes/submit-leave.php" enctype="multipart/form-data">
                <div class="form-group">
                    <textarea 
                        name="reason" 
                        placeholder="Please refer to the email sent for leave reason" 
                        rows="4" 
                        class="form-control"
                        required
                    ></textarea>
                </div>

                <div class="form-group">
                    <label for="file-upload" class="custom-file-upload">
                        <i class="material-icons">cloud_upload</i>
                        <span>Upload Supporting Document</span>
                    </label>
                    <input 
                        type="file" 
                        id="file-upload" 
                        name="leave_application" 
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                        onchange="updateFileName(this)"
                    >
                    <div class="selected-file" id="selectedFile"></div>
                    <small class="file-help-text">Supported formats: PDF, DOC, DOCX, JPG, PNG (Max 5MB)</small>
                </div>

                <div class="date-range">
                    <div class="date-input">
                        <i class="material-icons">calendar_today</i>
                        <input 
                            type="date" 
                            name="start_date" 
                            id="start_date"
                            required
                            min="<?php echo date('Y-m-d'); ?>"
                        >
                    </div>
                    
                    <span class="date-range-separator">to</span>
                    
                    <div class="date-input">
                        <i class="material-icons">calendar_today</i>
                        <input 
                            type="date" 
                            name="end_date" 
                            id="end_date"
                            required
                            min="<?php echo date('Y-m-d'); ?>"
                        >
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Request Leave</button>
                </div>
            </form>
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
