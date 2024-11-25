<?php
require_once "../../config/connect.php";

// Set header to return JSON
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and validate the student ID and selected package ID
    $student_id = isset($_POST['student_id']) ? intval($_POST['student_id']) : 0;
    $package_id = isset($_POST['package_id']) ? $_POST['package_id'] : '';

    // Handle 'none' package selection
    if ($package_id === 'none') {
        $package_id = null;
    }

    // Prepare the SQL statement to update the student's package ID
    $sql = "UPDATE students SET package_id = ? WHERE student_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameters to the prepared statement
        $stmt->bind_param("ii", $package_id, $student_id);

        // Execute the query
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Package updated successfully.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error updating package: ' . $conn->error
            ]);
        }

        // Close the statement
        $stmt->close();
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error preparing statement: ' . $conn->error
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}

// Close the connection
$conn->close();
?>