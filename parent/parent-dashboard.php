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
    <div class="dashboard-content">
        <div class="dashboard-container">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>!</h1>
            
            <section class="dashboard-section">
                <h2>Your Children:</h2>
                <div class="student-list">
                    <div class="student-card">
                        <h3>John Doe</h3>
                        <p>Grade: 10</p>
                        <p>Class: 10A</p>
                        <p>Student ID: ST12345</p>
                    </div>
                    <div class="student-card">
                        <h3>Jane Doe</h3>
                        <p>Grade: 8</p>
                        <p>Class: 8B</p>
                        <p>Student ID: ST67890</p>
                    </div>
                </div>
            </section>

            <section class="dashboard-section">
                <h2>Recent Announcements</h2>
                <ul class="announcement-list">
                    <li>
                        <h4>Parent-Teacher Conference</h4>
                        <p>Date: May 15, 2023</p>
                        <p>The annual parent-teacher conference will be held next month. Please mark your calendars.</p>
                    </li>
                    <li>
                        <h4>Summer Break</h4>
                        <p>Date: June 20 - August 31, 2023</p>
                        <p>Summer break begins on June 20. Classes will resume on September 1.</p>
                    </li>
                </ul>
            </section>

            <section class="dashboard-section">
                <h2>Quick Links</h2>
                <ul class="quick-links">
                    <li><a href="#">View Grades</a></li>
                    <li><a href="#">Attendance Records</a></li>
                    <li><a href="#">School Calendar</a></li>
                    <li><a href="#">Contact Teachers</a></li>
                </ul>
            </section>
        </div>
    </div>
</div>

<?php
// Include footer
include "../includes/footer.php";
?>
