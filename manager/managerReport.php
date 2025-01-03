<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: manager-login.php");
    exit;
}

// Include header and sidebar
include "../includes/header.php";
include "../includes/sidebar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/manager-report.css">
</head>
<body>
    <?php renderSidebar('manager'); ?>
    <div class="main-content">
        <div class="container">
            <h1>Student's Report</h1>
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search by name or programme">
                <select id="sortSelect">
                    <option value="none" selected>Default Sort</option>
                    <option value="highest">Payment Status (Paid First)</option>
                    <option value="lowest">Payment Status (Unpaid First)</option>
                </select>
            </div>
            <table id="studentTable">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Programme</th>
                        <th>Attendance</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be dynamically populated here -->
                </tbody>
            </table>
            <div class="pagination">
                <!-- Pagination buttons will go here (if implemented) -->
            </div>
        </div>
    </div>
    <script src="../assets/js/manager-report.js"></script>
</body>
</html>
