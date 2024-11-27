<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Login - Mathology</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <h1>Manager Portal</h1>
        <p class="login-subtitle">Enter your credentials to access the manager dashboard</p>
        
        <?php 
        if(isset($_SESSION["login_error"])) {
            echo '<div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    ' . htmlspecialchars($_SESSION["login_error"]) . '
                  </div>';
            unset($_SESSION["login_error"]);
        }
        ?>
        
        <form action="includes/manager-login-process.php" method="POST" class="login-form">
            <div class="input-group">
                <div class="input-wrapper">
                    <input type="text" name="username" id="username" placeholder="Username" required>
                    <i class="fas fa-user"></i>
                </div>
            </div>
            
            <div class="input-group">
                <div class="input-wrapper">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <i class="fas fa-lock"></i>
                </div>
            </div>
            
            <div class="login-options">
                <label class="remember-me" for="remember">
                    <input type="checkbox" name="remember" id="remember">
                    <span>Remember me</span>
                </label>
                <a href="#" class="forgot-password" title="Reset your password">
                    <i class="fas fa-key"></i>
                    Forgot Password?
                </a>
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
