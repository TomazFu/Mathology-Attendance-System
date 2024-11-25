<?php
require_once "../config/connect.php";
function getPaymentDetails($student_id, $conn)
{
    $sql = "SELECT 
    s.studentid,
   s.student_name,
   COALESCE(a.status, '') as attendance_status
FROM 
   students s
LEFT JOIN 
   attendance a ON s.student_id = a.student_id 
   AND a.date = ?
ORDER BY 
   s.student_name";

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
