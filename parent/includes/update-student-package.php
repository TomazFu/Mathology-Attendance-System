<?php
session_start();
require_once "../../config/connect.php";

header('Content-Type: application/json');

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$student_id = isset($_POST['student_id']) ? intval($_POST['student_id']) : 0;
$package_name = isset($_POST['package_name']) ? $_POST['package_name'] : '';

if (!$student_id || !$package_name) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

try {
    // Get package ID from package name
    $stmt = $conn->prepare("SELECT id FROM packages WHERE package_name = ?");
    $stmt->bind_param("s", $package_name);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('Package not found');
    }
    
    $package = $result->fetch_assoc();
    $package_id = $package['id'];
    
    // Update student's package
    $stmt = $conn->prepare("UPDATE students SET package_id = ? WHERE student_id = ?");
    $stmt->bind_param("ii", $package_id, $student_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Package updated successfully']);
    } else {
        throw new Exception('Failed to update package');
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?> 