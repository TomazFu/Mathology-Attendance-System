<?php
session_start();

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
        <div class="timetable-container">
            <div class="timetable-header">
                <h1><i class="fas fa-calendar-alt"></i> Class Schedule</h1>
                <div class="student-selector">
                    <select id="student-select" class="form-control">
                        <!-- Will be populated by JavaScript -->
                    </select>
                </div>
            </div>

            <!-- Weekly View -->
            <div id="weekly-view" class="timetable-view">
                <div class="weekly-timetable">
                    <div class="weekly-grid">
                    </div>
                </div>
            </div>

            <!-- List view will be dynamically added by JavaScript -->
        </div>
    </div>
</div>

<script src="../assets/js/timetable.js"></script>
<script src="../assets/js/script.js"></script>

<?php include "../includes/footer.php"; ?>
