<?php
require_once "../config/connect.php";
function getPaymentDetails($student_id, $conn)
{
    $sql = "SELECT p.*, pkg.package_name, pkg.price as package_price, s.student_name, pr.name as parent_name
        FROM payments p 
        LEFT JOIN packages pkg ON p.package_id = pkg.id 
        LEFT JOIN students s ON p.student_id = s.student_id
        LEFT JOIN parent pr ON s.parent_id = pr.parent_id
        WHERE p.student_id = ? 
        ORDER BY p.date DESC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $payments = array();
    while ($row = $result->fetch_assoc()) {
        $payments[] = [
            'id' => $row['id'],
            'student_id' => $row['student_id'],
            'student_name' => $row['student_name'],
            'parent_name' => $row['parent_name'],
            'package_id' => $row['package_id'],
            'package_price' => $row['package_price'],
            'package_name' => $row['package_name'],
            'amount' => $row['amount'],
            'date' => $row['date'],
            'payment_method' => $row['payment_method'],
            'registration' => $row['registration'],
            'deposit_fee' => $row['deposit_fee'],
            'diagnostic_test' => $row['diagnostic_test'],
            'status' => $row['status']
        ];
    }

    return $payments;
}
