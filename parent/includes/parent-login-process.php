<?php
session_start();
include '../../config/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare a select statement
    $sql = "SELECT parent_id, email, password, name FROM parent WHERE email = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $param_email);
        
        // Set parameters
        $param_email = $email;
        
        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();
            
            // Check if email exists, if yes then verify password
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($id, $email, $hashed_password, $name);
                if ($stmt->fetch()) {
                    if (password_verify($password, $hashed_password)) {
                        // Password is correct, so start a new session
                        session_start();
                        
                        // Store data in session variables
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["email"] = $email;
                        $_SESSION["name"] = $name;
                        
                        // Redirect user to welcome page
                        header("location: ../parent-dashboard.php");
                        exit();
                    } else {
                        $_SESSION["login_error"] = "Invalid email or password.";
                        header("location: ../parent-login.php");
                        exit();
                    }
                }
            } else {
                $_SESSION["login_error"] = "Invalid email or password.";
                header("location: ../parent-login.php");
                exit();
            }
        } else {
            $_SESSION["login_error"] = "Oops! Something went wrong. Please try again later.";
            header("location: ../parent-login.php");
            exit();
        }

        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $conn->close();
}

// If not a POST request, redirect to login page
if (!isset($_POST["email"])) {
    header("location: ../parent-login.php");
    exit();
}
?>
