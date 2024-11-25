<?php
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
    <link rel="stylesheet" href="../assets/css/manager-report.css">
</head>
<body>
    <div class="main-content">
        <?php renderSidebar('manager'); ?>
        <div class="container">
            <h1>Student's Report</h1>
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search by name or programme">
                <select id="sortSelect">
                    <option value="none" selected>No Sorting</option>
                    <option value="highest">Amount Payable: Highest</option>
                    <option value="lowest">Amount Payable: Lowest</option>
                </select>
            </div>
            <table id="studentTable">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Programme</th>
                        <th>Attendance</th>
                        <th>Remaining Payment</th>
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
