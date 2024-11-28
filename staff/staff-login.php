<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login - Mathology</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <h1>Staff Portal</h1>
        <p class="login-subtitle">Enter your credentials to access the staff dashboard</p>
        
        <?php 
        if(isset($_SESSION["login_error"])) {
            echo '<div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    ' . htmlspecialchars($_SESSION["login_error"]) . '
                  </div>';
            unset($_SESSION["login_error"]);
        }
        ?>
        
        <form action="includes/staff-login-process.php" method="POST" class="login-form">
            <div class="input-group">
                <div class="input-wrapper">
                    <input type="text" name="email" id="email" placeholder="Email" required>
                    <i class="fas fa-user"></i>
                </div>
            </div>
            
            <div class="input-group">
                <div class="input-wrapper">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <i class="fas fa-lock"></i>
                </div>
            </div>
            <button type="submit">
                <i class="fas fa-sign-in-alt"></i>
                Sign In
            </button>
        </form>
        
        <div class="login-footer">
            Need help? <a href="#">Contact Support</a>
        </div>
    </div>
</body>
</html>
