<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('../config/connect.php');

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];

    // Generate a unique parent_id (you might want to implement a more robust method)
    $parent_id = time();

    $sql = "INSERT INTO parent (parent_id, username, password, name) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $parent_id, $username, $password, $name);

    if ($stmt->execute()) {
        echo "Parent registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
