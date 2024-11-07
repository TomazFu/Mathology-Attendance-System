<!-- tmp -->
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
        <div class="main-content">
            <div class="timetable-section">
                <h1>Your Timetable</h1>
                <table id="timetable">
                    <thead>
                        <tr>
                            <th>Subject ID</th>
                            <th>Title</th>
                            <th>Room</th>
                            <th>Instructor</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data to be filled by JS -->
                    </tbody>
                </table>
            </div>

            <div class="enrolled-classes-section">
                <h2>Enrolled Classes</h2>
                <ul id="enrolled-classes-list">
                    <!-- Enrolled Classes will be populated here by JS -->
                </ul>
            </div>
        </div>
    </div>

<?php
// Include footer
include "../includes/footer.php";
?>
