<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mathologydb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

try {
    // First, let's verify the tables exist and have data
    $checkTables = "
        SELECT 
            (SELECT COUNT(*) FROM students) as student_count,
            (SELECT COUNT(*) FROM packages) as package_count,
            (SELECT COUNT(*) FROM attendance) as attendance_count
    ";
    
    $tablesResult = $conn->query($checkTables);
    $counts = $tablesResult->fetch_assoc();
    
    // Modified query to include payment status
    $sql = "SELECT 
        s.student_name as name, 
        p.package_name AS programme,
        (
            SELECT status 
            FROM payments 
            WHERE student_id = s.student_id 
            ORDER BY date DESC 
            LIMIT 1
        ) as payment_status,
        (
            SELECT COUNT(*) 
            FROM attendance a 
            WHERE a.student_id = s.student_id 
            AND a.status = 'present'
            AND MONTH(a.date) = MONTH(CURRENT_DATE)
            AND YEAR(a.date) = YEAR(CURRENT_DATE)
        ) as present_days,
        (
            SELECT COUNT(*) 
            FROM attendance a 
            WHERE a.student_id = s.student_id 
            AND MONTH(a.date) = MONTH(CURRENT_DATE)
            AND YEAR(a.date) = YEAR(CURRENT_DATE)
        ) as total_days
    FROM students s
    LEFT JOIN packages p ON s.package_id = p.id";

    // Search functionality
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $conn->real_escape_string($_GET['search']);
        $sql .= " WHERE s.student_name LIKE '%$search%' OR p.package_name LIKE '%$search%'";
    }

    // Sorting functionality
    if (isset($_GET['sort']) && $_GET['sort'] !== 'none') {
        $sql .= " ORDER BY payment_status " . 
                ($_GET['sort'] === 'highest' ? 'DESC' : 'ASC');
    }

    // Execute the query
    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $data = [];

    // Process the results
    while ($row = $result->fetch_assoc()) {
        // Calculate attendance percentage
        $total_days = $row['total_days'];
        $present_days = $row['present_days'];
        
        $attendance = $total_days > 0 ? 
            round(($present_days / $total_days) * 100, 2) : 0;

        $data[] = [
            'name' => $row['name'],
            'programme' => $row['programme'],
            'attendance' => $attendance,
            'payment_status' => $row['payment_status'] ?? 'No Payment'
        ];
    }

    // Add debug information
    $debug = [
        'table_counts' => $counts,
        'query' => $sql,
        'data_count' => count($data)
    ];

    // Return the data as JSON with debug info
    echo json_encode([
        'success' => true,
        'debug' => $debug,
        'data' => $data
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

$conn->close();
?>
