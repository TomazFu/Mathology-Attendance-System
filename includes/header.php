<?php
// Set the base path
$base_path = isset($is_index) && $is_index ? '' : '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mathology</title>
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .profile-icon {
            font-size: 2.5em; /* Increase this value to make the icon larger */
            color: #333; /* Adjust color as needed */
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <i class="fas fa-square-root-alt"></i>
                <span>Mathology</span>
            </div>
            <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
            <div class="profile-dropdown">
                <i class="fas fa-user-circle profile-icon" onclick="toggleDropdown()"></i>
                <div class="dropdown-content" id="profileDropdown">
                    <a href="#">Edit Profile</a>
                    <a href="<?php echo $base_path; ?>includes/logout.php">Log Out</a>
                </div>
            </div>
            <?php endif; ?>
        </nav>
    </header>
    <main>
