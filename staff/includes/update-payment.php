<?php
// Include database connection
require_once "../config/connect.php";

// Retrieve data from POST request
$student_id = $_POST['student_id'];
$parent_id = $_POST['parent_id']; // Assuming you pass parent_id from the form
$amount = $_POST['amount'];
$payment_date = $_POST['payment_date'];
$payment_method = $_POST['payment_method'];
$status = $_POST['status'];

// Prepare SQL query to update payment details (excluding fields that don't exist in payments table)
$sql = "INSERT INTO payments (student_id, parent_id, amount, date, payment_method, status) 
        VALUES (?, ?, ?, ?, ?, ?) 
        ON DUPLICATE KEY UPDATE 
        amount = ?, date = ?, payment_method = ?, status = ?";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind parameters to the statement
$stmt->bind_param(
    "iiissssss", 
    $student_id, 
    $parent_id, 
    $amount, 
    $payment_date, 
    $payment_method, 
    $status,
    $amount, 
    $payment_date, 
    $payment_method, 
    $status
);

// Execute the query
if ($stmt->execute()) {
    echo "Payment updated successfully.";
} else {
    echo "Error: " . $stmt->error;
}

// Close the connection
$stmt->close();
$conn->close();
?>
