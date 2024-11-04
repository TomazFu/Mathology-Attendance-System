<!-- tmp -->
<?php
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: parent-login.php");
    exit;
}

// Include database connection
require_once "../config/connect.php";

// Include header
include "../includes/header.php";

// Include sidebar
require_once "../includes/sidebar.php";
?>

    <div class="dashboard-layout">
        <?php renderSidebar('parent'); ?>
        <div class="main-content">
            <div class="card" id="leaveForm">
                <h2>Leave Form</h2>
                <form>
                    <div class="form-group">
                        <select name="student" required>
                            <option value="">Select student</option>
                            <option value="1">Student 1</option>
                            <option value="2">Student 2</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea name="reason" placeholder="Reason for Leave" rows="4" required></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="file-upload" class="custom-file-upload">
                                <i class="material-icons">cloud_upload</i> Upload Leave Application
                            </label>
                            <input type="file" id="file-upload" name="leave_application">
                        </div>
                        <div class="form-group date-range">
                            <div class="date-input">
                                <i class="material-icons">calendar_today</i>
                                <input type="date" name="start_date" required>
                            </div>
                            <span class="date-range-separator">to</span>
                            <div class="date-input">
                                <i class="material-icons">calendar_today</i>
                                <input type="date" name="end_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary">Grant Leave</button>
                    </div>
                </form>
            </div>
            <div class="card" id="leaveHistory" style="display: none;">
                <h2>Leave History</h2>
                <div class="leave-history-item">
                    <div class="leave-details">
                        <p><strong>Leave ID:</strong> P120</p>
                        <p><strong>Student Name:</strong> John Doe</p>
                        <p><strong>Reason:</strong> Sick</p>
                    </div>
                    <div class="leave-dates">
                        <p>12-01-2023 to 12-01-2023</p>
                        <button class="btn-icon"><i class="material-icons">visibility</i></button>
                    </div>
                </div>
                <!-- Add more leave history items here -->
            </div>
            <button class="track-leave-btn">Track Leave</button>
        </div>
    </div>

<?php
// Include footer
    include "../includes/footer.php";
?>