    <?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once('../../config/connect.php');

        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm-password'];
        $name = $_POST['name'];

        if (empty($email) || empty($password) || empty($confirm_password) || empty($name)) {
            $_SESSION['registration_error'] = "All fields are required";
            header("Location: ../staff-registration.php");
            exit();
        }
        // Check if passwords match
        if ($password !== $confirm_password) {
            $_SESSION['registration_error'] = "Passwords do not match";
            header("Location: ../staff-registration.php");
            exit();
        }
        // Validate password strength (optional)
        if (strlen($password) < 8) {
            $_SESSION['registration_error'] = "Password must be at least 8 characters long";
            header("Location: ../staff-registration.php");
            exit();
        }
        //Hash the pasword
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // Generate a unique parent_id (you might want to implement a more robust method)
        $parent_id = time();

        $sql = "INSERT INTO parent (parent_id, email, password, name) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $parent_id, $email, $password, $name);

        if ($stmt->execute()) {
            $_SESSION['registration_success'] = "Parent registration successful!";
            header("Location: ../staff-registration.php");
            exit();
        } else {
            $_SESSION['registration_error'] = "Error: " . $stmt->error;
            header("Location: ../staff-registration.php");
            exit();
        }

        $stmt->close();
        $conn->close();
    }
    ?>
