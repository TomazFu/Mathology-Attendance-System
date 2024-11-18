<?php
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: staff-login.php");
    exit;
}

// Include database connection
require_once "../config/connect.php";

// Include header
include "../includes/header.php";

// Include sidebar
require_once "../includes/sidebar.php";

// Include fetch attendance data
require_once "includes/fetch-attendance-data-process.php";
?>
<!DOCTYPE html>
<html>

<head> 
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/staff.css">
</head>

<body>
    <div class="dashboard-layout">
        <?php renderSidebar('staff'); ?>

        <div class="main-content">
            <h1> Parent Registration </h1>
            <div class="form-container">
                <form id="parent-account-registration-form" action="../includes/register-process.php" method="POST">
                    <div class="form-row">
                        <input type="text" name="username" placeholder="Username *" class="half-width" required>
                    </div>
                    <div class="form-row">
                        <input type="password" name="password" placeholder="Password *" class="half-width" required>
                    </div>
                    <div class="form-row">
                        <input type="text" name="name" placeholder="Full name *" class="half-width" required>
                    </div>
                    <!-- <div class="form-row">
                        <div class="gender-container">
                            <label class="radio-label">
                                <input type="radio" name="gender" value="male">
                                Male
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="gender" value="female">
                                Female
                            </label>
                        </div>
                        <select class="half-width">
                            <option value="" selected>Department</option>
                            <option value="hr">HR</option>
                            <option value="it">IT</option>
                            <option value="finance">Finance</option>
                        </select>
                    </div> -->

                    <button type="submit" type="register-button">REGISTER</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>