<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../config/connect.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get and validate input
$student_id = isset($_POST['student_id']) ? intval($_POST['student_id']) : 0;
$parent_id = isset($_POST['parent_id']) ? intval($_POST['parent_id']) : 0;
$package_id = isset($_POST['package_id']) ? intval($_POST['package_id']) : 0;
$amount = isset($_POST['amount']) ? intval($_POST['amount']) : 0;
$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';
$registration = isset($_POST['registration']) ? (bool)$_POST['registration'] : false;
$deposit_fee = isset($_POST['deposit_fee']) ? intval($_POST['deposit_fee']) : null;
$diagnostic_test = isset($_POST['diagnostic_test']) ? (bool)$_POST['diagnostic_test'] : false;
$status = isset($_POST['status']) ? $_POST['status'] : '';
$date = isset($_POST['date']) ? $_POST['date'] : null;

// Validate required fields

try {
    $conn->begin_transaction();

    $sql = "INSERT INTO payments (parent_id, student_id, package_id, amount, date, payment_method, 
            registration, deposit_fee, diagnostic_test, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("iiiissiiss", 
        $parent_id,
        $student_id,
        $package_id,
        $amount,
        $date,
        $payment_method,
        $registration,
        $deposit_fee,
        $diagnostic_test,
        $status
    );

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Payment recorded successfully',
        'amount' => $amount
    ]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}

$conn->close();
?>