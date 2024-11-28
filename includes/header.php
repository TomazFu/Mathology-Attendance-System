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
    <link rel="icon" type="image/x-icon" href="<?php echo $base_path; ?>assets/img/mathology.ico">
    <script src="../assets/js/script.js"></script>
</head>
<body>
    <header>
        <div class="header-container">
            <a href="<?php echo $base_path; ?>index.php" class="logo">
                <img src="<?php echo $base_path; ?>assets/img/mathology.png" alt="Mathology" class="logo-img">
            </a>
            <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
            <div class="header-right">
                <div class="profile-dropdown">
                    <div class="profile-icon" onclick="toggleDropdown()">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="dropdown-content" id="profileDropdown">
                        <a href="<?php echo $base_path; ?>includes/logout.php">
                            <i class="fas fa-sign-out-alt"></i>Log Out
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </header>
    <main>
