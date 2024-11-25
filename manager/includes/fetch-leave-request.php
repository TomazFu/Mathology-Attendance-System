<?php
// Check if the leave_id is provided in the URL
if (isset($_GET['leave_id'])) {
    $leave_id = $_GET['leave_id'];

    // Connect to the MySQL database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mathologydb";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch the leave request details - Updated table and column names
    $leave_query = $conn->prepare("SELECT leaves.leave_id, students.name AS student_name, leaves.reason, 
                                   leaves.fromDate as start_date, leaves.toDate as end_date 
                                   FROM leaves 
                                   JOIN students ON leaves.student_id = students.student_id
                                   WHERE leaves.leave_id = ?");
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
