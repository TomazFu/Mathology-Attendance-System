<?php
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: parent-login.php");
    exit;
}

// Include database connection
require_once "../config/connect.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h1>
        <h2>Your Children:</h2>
        <?php

        echo "<p>Student information is currently unavailable.</p>";
        ?>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>

<?php
?>
