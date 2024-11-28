<?php
session_start();
// Include header and sidebar
include "../includes/header.php";
include "../includes/sidebar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/manager-staff.css">
</head>
<body>
    <?php renderSidebar('manager'); ?>
    <div class="main-content">
        <div class="container">
            <h1>Current Staff</h1>
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search by name">
                <select id="sortSelect">
                    <option value="">Default Sort</option>
                    <option value="leave_desc">Leave (Highest to Lowest)</option>
                    <option value="leave_asc">Leave (Lowest to Highest)</option>
                </select>
            </div>
            <table id="staffTable">
                <thead>
                    <tr>
                        <th>Staff ID</th>
                        <th>Email</th>
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
                    <div class="form-group">
                        <label for="staffEmail">Email</label>
                        <input type="email" name="email" id="staffEmail" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <label for="staffPassword">Password</label>
                        <input type="password" name="password" id="staffPassword" placeholder="Password">
                        <small class="password-hint">Leave blank to keep existing password when editing</small>
                    </div>
                    <div class="form-group">
                        <label for="staffName">Name</label>
                        <input type="text" name="name" id="staffName" placeholder="Name" required>
                    </div>
                    <div class="form-group">
                        <label for="staffQualification">Qualification</label>
                        <input type="text" name="qualification" id="staffQualification" placeholder="Qualification" required>
                    </div>
                    <div class="form-group">
                        <label for="staffContact">Contact Number</label>
                        <input type="text" name="contact" id="staffContact" placeholder="Contact Number" required>
                    </div>
                    <div class="form-group">
                        <label for="staffLeave">Leave Left</label>
                        <input type="number" name="leave" id="staffLeave" placeholder="Leave Left" required>
                    </div>
                    <div class="form-group">
                        <label for="staffStatus">Status</label>
                        <select name="status" id="staffStatus" required>
                            <option value="Active">Active</option>
                            <option value="On Leave">On Leave</option>
                        </select>
                    </div>
                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <script src="../assets/js/manager-staff.js"></script>
</body>
</html>
