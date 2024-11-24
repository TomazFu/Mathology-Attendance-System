<?php
// Include header and sidebar
include "../includes/header.php";
include "../manager/includes/sidebar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management</title>
    <link rel="stylesheet" href="../assets/css/manager-staff.css">
</head>
<body>
    <div class="main-content">
        <?php renderSidebar('manager'); ?>
        <div class="container">
            <h1>Current Staff</h1>
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search">
                <select id="sortSelect">
                    <option value="newest">Newest</option>
                    <option value="oldest">Oldest</option>
                </select>
            </div>
            <table id="staffTable">
                <thead>
                    <tr>
                        <th>Staff ID</th>
                        <th>Staff Name</th>
                        <th>Highest Qualification</th>
                        <th>Contact Number</th>
                        <th>Leave Left</th>
                        <th>Current Status</th>
                        <th>Action</th> 
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be dynamically populated here -->
                </tbody>
            </table>
            <div class="pagination">
                <!-- Pagination buttons will go here -->
            </div>
            <button id="addStaffButton">Add Staff</button>
        </div>

        <!-- Add/Edit Staff Modal -->
        <div id="addStaffModal" class="modal" style="display: none;" aria-hidden="true">
            <div class="modal-content">
                <button id="closeModalButton" aria-label="Close Modal">&times;</button>
                <h2 id="modalTitle">Add Staff</h2>
                <form id="addStaffForm">
                    <input type="hidden" name="id" id="staffId"> 
                    <input type="text" name="name" id="staffName" placeholder="Name" required>
                    <input type="text" name="qualification" id="staffQualification" placeholder="Qualification" required>
                    <input type="text" name="contact" id="staffContact" placeholder="Contact Number" required>
                    <input type="number" name="leave" id="staffLeave" placeholder="Leave Left" required>
                    <select name="status" id="staffStatus" required>
                        <option value="Active">Active</option>
                        <option value="On Leave">On Leave</option>
                    </select>
                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <script src="../assets/javascript/manager-staff.js"></script>
</body>
</html>
