<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mathologydb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the response type to JSON
header('Content-Type: application/json');

// Simpler and more efficient query using student table fields
$sql = "SELECT s.student_name as name, 
               p.package_name AS programme,
               (s.total_fees - s.fees_paid) AS remaining_payment
        FROM students s
        LEFT JOIN packages p ON s.package_id = p.id";

// Search functionality
$conditions = []; 

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $conditions[] = "(s.name LIKE '%$search%' OR p.package_name LIKE '%$search%')";
}

// Append search conditions to SQL
if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Sorting functionality
if (isset($_GET['sort']) && $_GET['sort'] !== 'none') {
    if ($_GET['sort'] == 'highest') {
        $sql .= " ORDER BY remaining_payment DESC";
    } elseif ($_GET['sort'] == 'lowest') {
        $sql .= " ORDER BY remaining_payment ASC";
    }
}

// Execute the query
$result = $conn->query($sql);
$data = [];

// Fetch all rows
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    $data = [];
}

// Return the data as JSON
echo json_encode($data);

// Close connection
$conn->close();
?>
