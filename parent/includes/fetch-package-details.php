<?php
session_start();
require_once "../../config/connect.php";

header('Content-Type: application/json');

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;

if (!$student_id) {
    echo json_encode(['error' => 'Invalid student ID']);
    exit;
}

$sql = "SELECT p.package_name, p.price, p.details 
        FROM students s 
        LEFT JOIN packages p ON s.package_id = p.id 
        WHERE s.student_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$package = $result->fetch_assoc();

echo json_encode($package ?: ['error' => 'No package found']);

$conn->close();
?>