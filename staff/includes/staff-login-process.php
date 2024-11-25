<?php
session_start();
include '../../config/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare a select statement
    $sql = "SELECT staff_id, username, password, name FROM staff WHERE username = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $param_username);
        
        // Set parameters
        $param_username = $username;
        
        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();
            
            // Check if username exists, if yes then verify password
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($id, $username, $hashed_password, $name);
                if ($stmt->fetch()) {
                    if (password_verify($password, $hashed_password)) {
                        // Password is correct, so start a new session
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username;
                        $_SESSION["name"] = $name;
                        
                        // Redirect user to welcome page
                        header("location: ../staff-dashboard.php");
                        exit();
                    } else {
                        $_SESSION['login_error'] = "Invalid username or password.";
                        header("location: ../staff-login.php");
                        exit();
                    }
                }
            } else {
                $_SESSION['login_error'] = "Invalid username or password.";
                header("location: ../staff-login.php");
                exit();
            }
        } else {
            $_SESSION['login_error'] = "Oops! Something went wrong. Please try again later.";
            header("location: ../staff-login.php");
            exit();
        }

        // Close statement
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>
