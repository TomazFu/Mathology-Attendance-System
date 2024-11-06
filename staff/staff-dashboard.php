<!-- tmp -->
<?php
session_start();

// Check if the user is logged in, if not then redirect to login page
// if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
//     header("location: staff-login.php");
//     exit;
// }

// Include database connection
require_once "../config/connect.php";

// Include header
include "../includes/header.php";

// Include sidebar
require_once "../includes/sidebar.php";
?>

<div class="dashboard-layout">
    <?php renderSidebar('staff'); ?>
    <div class="main-content">
        <img src="../assets/img/book.jpeg" alt="Book Image" class="book-image">
        <h1>Welcome to Mathology!</h1>
        <div class="dashboard-sections">
            
            <!-- Attendance List -->
            <div class="attendance-list dashboard-staff-box">
                <h3>Attendance</h3>
                
            </div>

             <!-- View Package -->
             <div class="view-package dashboard-staff-box">
                <h3>View Packages</h3>
            </div>

            <!-- Latest Leave -->
            <div class="parents-registration dashboard-staff-box">
                <h3>Parents Registration</h3>
            </div>
        </div>
    </div>
    </div>
</div>  

<?php
// Include footer
include "../includes/footer.php";
?>
