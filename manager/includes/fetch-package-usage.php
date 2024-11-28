<?php
session_start();
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mathologydb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Connection failed: ' . $conn->connect_error]));
}

try {
    $sql = "SELECT 
        p.package_name,
        p.price as package_price,
        COUNT(s.student_id) as student_count,
        ROUND((COUNT(s.student_id) * 100.0) / (SELECT COUNT(*) FROM students), 1) as usage_percentage
    FROM packages p
    LEFT JOIN students s ON p.id = s.package_id
    WHERE 1=1";

    // Add search condition
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $conn->real_escape_string($_GET['search']);
        $sql .= " AND p.package_name LIKE '%$search%'";
    }

    $sql .= " GROUP BY p.id";

    // Add sorting
    if (isset($_GET['sort'])) {
        switch ($_GET['sort']) {
            case 'usage_high':
                $sql .= " ORDER BY usage_percentage DESC";
                break;
            case 'usage_low':
                $sql .= " ORDER BY usage_percentage ASC";
                break;
            case 'price_high':
                $sql .= " ORDER BY package_price DESC";
                break;
            case 'price_low':
                $sql .= " ORDER BY package_price ASC";
                break;
        }
    }

    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception($conn->error);
    }

    $packages = [];
    while ($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }

    echo json_encode(['success' => true, 'packages' => $packages]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();