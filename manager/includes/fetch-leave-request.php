<?php
// Check if the leave_id is provided in the URL
if (isset($_GET['leave_id'])) {
    $leave_id = $_GET['leave_id'];

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

    // Fetch the leave request details
    $leave_query = $conn->prepare("SELECT leave_requests.leave_id, students.name AS student_name, leave_requests.reason, leave_requests.start_date, leave_requests.end_date 
                                   FROM leave_requests 
                                   JOIN students ON leave_requests.student_id = students.student_id
                                   WHERE leave_requests.leave_id = ?");
    $leave_query->bind_param("i", $leave_id);
    $leave_query->execute();
    $leave_result = $leave_query->get_result();
    $leave_data = $leave_result->fetch_assoc();

    // Close the database connection
    $conn->close();

    if (!$leave_data) {
        die("Leave request not found.");
    }
} else {
    die("No leave ID provided.");
}
?>
