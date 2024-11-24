<?php
require_once "../config/connect.php";

// Query to get student records along with package details (if available)
$sql = "SELECT s.student_id, s.student_name, s.class, p.package_name, p.price, p.details 
        FROM students s
        LEFT JOIN packages p ON s.package_id = p.id";

// Execute query
$result = $conn->query($sql);

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
                "package_name" => $row["package_name"],
                "price" => $row["price"],
                "details" => $row["details"]
            )
        );

        // If there's no package, set the package details to null
        if ($row["package_name"] == null) {
            $student["package"] = null;
        }

        // Add the student details to the students array
        $students[] = $student;
    }
} else {
    // If no records found, return an empty array
    $students = [];
}

// Close connection
$conn->close();

?>
