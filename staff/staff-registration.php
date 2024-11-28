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

?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/staff.css">
</head>

<body>
    <div class="dashboard-layout">
        <?php renderSidebar('staff'); ?>

        <div class="main-content">
            <h1> Parent Registration </h1>
            <?php
            if (isset($_SESSION['registration_success'])) {
                echo '<div class="warning-box">' . $_SESSION['registration_success'] . '</div>';
                unset($_SESSION['registration_success']);
            }
            if (isset($_SESSION['registration_error'])) {
                echo '<div class="warning-box">' . $_SESSION['registration_error'] . '</div>';
                unset($_SESSION['registration_error']);
            }
            ?>
            <div class="form-container">
                <form id="parent-account-registration-form" action="includes/register-process.php" method="POST">
                    <div class="form-row">
                        <input type="text" name="email" placeholder="Email *" class="full-width" required>
                    </div>
                    <div class="form-row">
                        <input type="text" name="name" placeholder="Full name *" class="full-width" required>
                    </div>
                    <div class="form-row">
                        <input type="password" name="password" placeholder="Password *" class="full-width" required>
                    </div>
                    <div class="form-row">
                        <input type="password" name="confirm-password" placeholder="Confirm Password *" class="full-width" required>
                    </div>
                    <button type="submit" class="register-button"><i class="fa fa-user-plus"></i>&nbsp;REGISTER</button>
                </form>
                <?php
                if (isset($_SESSION['registration_error'])) {
                    echo '<div class="error">' . $_SESSION['registration_error'] . '</div>';
                    unset($_SESSION['registration_error']);
                }
                if (isset($_SESSION['registration_success'])) {
                    echo '<div class="success">' . $_SESSION['registration_success'] . '</div>';
                    unset($_SESSION['registration_success']);
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>