<?php
// Connect to the MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mathlogydb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//require_once "../config/connect.php";

// Fetch total fees collection and outstanding amount
$fees_result = $conn->query("SELECT SUM(total_fees) AS total_fees, SUM(total_fees - fees_paid) AS outstanding_fees FROM students");
$fees_data = $fees_result->fetch_assoc();
$total_fees = $fees_data['total_fees'];
$outstanding_fees = $fees_data['outstanding_fees'];

// Fetch total students and staff count
$student_count = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()['total'];
$staff_count = $conn->query("SELECT COUNT(*) AS total FROM staff")->fetch_assoc()['total'];

// Fetch today's attendance percentage
$attendance_result = $conn->query("SELECT attendance_percentage FROM attendance WHERE date = CURDATE()");
$attendance_percentage = $attendance_result->num_rows > 0 ? $attendance_result->fetch_assoc()['attendance_percentage'] : 'N/A';

// Fetch latest leave requests
$leave_requests_result = $conn->query("SELECT leave_id, students.name AS student_name, reason, start_date, end_date FROM leave_requests JOIN students ON leave_requests.student_id = students.student_id ORDER BY leave_id DESC LIMIT 3");

// Close the database connection
$conn->close();


