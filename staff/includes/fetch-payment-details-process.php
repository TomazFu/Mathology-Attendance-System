<?php
require_once "../config/connect.php";
function getPaymentDetails($student_id, $conn) {
    $sql = "SELECT p.*, pkg.package_name 
            FROM payments p 
            LEFT JOIN packages pkg ON p.package_id = pkg.id 
            WHERE p.student_id = ? 
            ORDER BY p.date DESC";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $payments = array();
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
    
    return $payments;
}
?>