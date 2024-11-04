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
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/parent.css">

<div class="dashboard-layout">
    <?php renderSidebar('parent'); ?>
    <div class="main-content">
        <div class="dashboard-container">
            <div class="dashboard-header">
                <img src="../assets/img/book.jpeg" alt="Book Image" class="book-image">
                <h1 class="welcome-title">Welcome to Mathology!</h1>
            </div>

            <div class="dashboard-sections">
                <!-- Enrolled Classes -->
                <div class="dashboard-card enrolled-classes">
                    <h3 class="card-title">
                        <i class="material-icons">school</i>
                        Enrolled Classes
                    </h3>
                    <ul id="enrolled-classes-list">
                        <li class="loading">Loading...</li>
                    </ul>
                </div>
                
                <!-- Enrolled Package -->
                <div class="dashboard-card enrolled-package">
                    <h3 class="card-title">
                        <i class="material-icons">card_membership</i>
                        Enrolled Package
                    </h3>
                    <p id="enrolled-package-text" class="loading">Loading...</p>
                </div>

                <!-- Attendance Chart -->
                <div class="dashboard-card attendance-chart">
                    <h3 class="card-title">
                        <i class="material-icons">insert_chart</i>
                        Attendance Overview
                    </h3>
                    <div class="chart">
                        <span id="attendance-percent">0%</span>
                        <ul class="attendance-stats">
                            <li class="present">
                                <i class="material-icons">check_circle</i>
                                Present
                            </li>
                            <li class="late">
                                <i class="material-icons">access_time</i>
                                Late
                            </li>
                            <li class="leaves">
                                <i class="material-icons">event_busy</i>
                                Leaves
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Latest Leave -->
                <div class="dashboard-card latest-leave">
                    <h3 class="card-title">
                        <i class="material-icons">event_note</i>
                        Latest Leave Request
                    </h3>
                    <div class="leave-details">
                        <p id="leave-id" class="loading">Leave ID: Loading...</p>
                        <p id="student-name" class="loading">Student Name: Loading...</p>
                        <p id="leave-reason" class="loading">Reason: Loading...</p>
                        <p id="leave-dates" class="loading">Date: Loading...</p>
                    </div>
                    <div class="card-actions">
                        <button class="btn btn-primary" id="view-leave-btn">
                            <i class="material-icons">visibility</i>
                            View Details
                        </button>
                        <a href="parent-leave-view.php" class="btn btn-secondary">
                            <i class="material-icons">list</i>
                            View All Leaves
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add JavaScript files -->
<script src="../assets/js/parent.js"></script>
<script src="../assets/js/script.js"></script>

<?php include "../includes/footer.php"; ?>
