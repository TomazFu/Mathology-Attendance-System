<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mathlogydb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the request method
$requestMethod = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json'); // Set response type to JSON

if ($requestMethod === 'GET') {
    if (isset($_GET['staff_id'])) {
        $staff_id = intval($_GET['staff_id']); // Ensure staff_id is an integer

        $stmt = $conn->prepare("SELECT * FROM staff WHERE staff_id = ?");
        $stmt->bind_param("i", $staff_id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $staff = $result->fetch_assoc();
                echo json_encode($staff); // Return the staff data as JSON
            } else {
                error_log("No staff found for staff_id: " . $staff_id);
                echo json_encode(["success" => false, "message" => "Staff not found"]);
            }
        } else {
            error_log("SQL query failed: " . $stmt->error);
            echo json_encode(["success" => false, "message" => "SQL query failed"]);
        }

        $stmt->close();
    } else {
        // Fetch all staff data if no staff_id is provided
        $result = $conn->query("SELECT * FROM staff");
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data); // Return all staff data as JSON
    }
} elseif ($requestMethod === 'POST') {
    // Add new staff member
    $name = $_POST['name'] ?? '';
    $qualification = $_POST['qualification'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $leave = $_POST['leave'] ?? '';
    $status = $_POST['status'] ?? '';

    // Validate required fields
    if (empty($name) || empty($qualification) || empty($contact) || empty($leave) || empty($status)) {
        echo json_encode(["success" => false, "message" => "All fields are required"]);
        exit;
    }

    // Prepare the SQL query to insert new staff
    $stmt = $conn->prepare("INSERT INTO staff (name, qualification, contact_number, leave_left, current_status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $name, $qualification, $contact, $leave, $status);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Staff added successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Database insertion failed"]);
    }

    $stmt->close();
} elseif ($requestMethod === 'PUT') {
    // Parse JSON input
    $_PUT = json_decode(file_get_contents("php://input"), true);

    // Extract variables
    $staff_id = $_PUT['staff_id'] ?? '';
    $name = $_PUT['name'] ?? '';
    $qualification = $_PUT['qualification'] ?? '';
    $contact = $_PUT['contact'] ?? '';
    $leave = $_PUT['leave'] ?? '';
    $status = $_PUT['status'] ?? '';

    // Validate required fields
    if (empty($staff_id) || empty($name) || empty($qualification) || empty($contact) || empty($leave) || empty($status)) {
        echo json_encode(["success" => false, "message" => "All fields are required"]);
        exit;
    }

    // Validate numeric fields
    if (!is_numeric($staff_id) || intval($staff_id) <= 0) {
        echo json_encode(["success" => false, "message" => "Invalid staff ID"]);
        exit;
    }
    $leave = intval($leave); // Convert leave to integer

    // Prepare the SQL query to update staff
    $stmt = $conn->prepare("UPDATE staff SET name = ?, qualification = ?, contact_number = ?, leave_left = ?, current_status = ? WHERE staff_id = ?");
    $stmt->bind_param("sssis", $name, $qualification, $contact, $leave, $status, $staff_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Staff updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Update failed: " . $stmt->error]);
    }

    $stmt->close();
} elseif ($requestMethod === 'DELETE') {
    // Delete staff member
    parse_str(file_get_contents("php://input"), $_DELETE);

    $staff_id = $_DELETE['staff_id'] ?? '';

    if (empty($staff_id)) {
        echo json_encode(["success" => false, "message" => "Staff ID is required"]);
        exit;
    }

    // Prepare the SQL query to delete staff
    $stmt = $conn->prepare("DELETE FROM staff WHERE staff_id = ?");
    $stmt->bind_param("i", $staff_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Staff deleted successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Deletion failed"]);
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>
