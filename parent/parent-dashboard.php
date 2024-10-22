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
        <img src="../assets/img/book.jpeg" alt="Book Image" class="book-image">
        <h1>Welcome to Mathology!</h1>
        <div class="dashboard-sections">
            <!-- Enrolled Classes -->
            <div class="enrolled-classes">
                <h3>Enrolled Classes</h3>
                <ul id="enrolled-classes-list">
                    <!-- To be filled by JS -->
                </ul>
            </div>
            
            <!-- Enrolled Package -->
            <div class="enrolled-package">
                <h3>Enrolled Package</h3>
                <p id="enrolled-package-text">Loading...</p>
            </div>

            <!-- Attendance Chart -->
            <div class="attendance-chart">
                <h3>Attendance</h3>
                <div class="chart">
                    <span id="attendance-percent">0%</span>
                    <ul>
                        <li class="present">Present</li>
                        <li class="late">Late</li>
                        <li class="leaves">Leaves</li>
                    </ul>
                </div>
            </div>

            <!-- Latest Leave -->
            <div class="latest-leave">
                <h3>Latest Leave</h3>
                <p id="leave-id">Leave ID: Loading...</p>
                <p id="student-name">Student Name: Loading...</p>
                <p id="leave-reason">Reason: Loading...</p>
                <p id="leave-dates">Date: Loading...</p>
                <button class="view-btn" id="view-leave-btn">View Details</button>
            </div>
        </div>
    </div>
    </div>
</div>  

<?php
// Include footer
include "../includes/footer.php";
?>
