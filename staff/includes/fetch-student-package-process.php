<?php
require_once "../config/connect.php";

// Modified query to include package_id
$sql = "SELECT s.student_id, s.student_name, s.class, s.package_id, 
        p.package_name, p.price, p.details 
        FROM students s
        LEFT JOIN packages p ON s.package_id = p.id";

// Execute query
$result = $conn->query($sql);

// Add error checking
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Initialize an array to store results
$students = array();

// Check if we have results
if ($result->num_rows > 0) {
    // Loop through the results and store them in the array
    while($row = $result->fetch_assoc()) {
        $student = array(
            "student_id" => $row["student_id"],
            "student_name" => $row["student_name"],
            "class" => $row["class"],
            "package" => array(
                "package_id" => $row["package_id"],  // Added this line
                "package_name" => $row["package_name"],
                "price" => $row["price"],
                "details" => $row["details"]
            )
        );

        // Add the student details to the students array
        $students[] = $student;
    }
}

// Debug output
echo "<pre style='display:none'>Debug students array: ";
var_dump($students);
echo "</pre>";

// Don't close the connection here as it might be needed later
// $conn->close();
?>