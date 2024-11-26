<?php
// Connect to the MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mathologydb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//require_once "../config/connect.php";




// Fetch total students and staff count
$student_count = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()['total'];
$staff_count = $conn->query("SELECT COUNT(*) AS total FROM staff")->fetch_assoc()['total'];

// Fetch today's attendance percentage
$attendance_query = "SELECT 
    COUNT(CASE WHEN status = 'present' THEN 1 END) * 100.0 / COUNT(*) as attendance_percentage
    FROM attendance 
    WHERE DATE(date) = CURDATE()";
$attendance_result = $conn->query($attendance_query);
$attendance_percentage = $attendance_result->num_rows > 0 ? 
    number_format($attendance_result->fetch_assoc()['attendance_percentage'], 1) : 'N/A';

// Fetch latest leave requests - Updated table and column names
$leave_requests_result = $conn->query("SELECT leave_id, students.student_name AS student_name, reason, fromDate as start_date, toDate as end_date 
                                     FROM leaves 
                                     JOIN students ON leaves.student_id = students.student_id 
                                     ORDER BY leave_id DESC LIMIT 3");

// Fetch monthly sales data for the current year
$sales_query = "SELECT 
    DATE_FORMAT(date, '%Y-%m') as month,
    SUM(amount) as monthly_total 
    FROM payments 
    WHERE YEAR(date) = YEAR(CURRENT_DATE())
    GROUP BY DATE_FORMAT(date, '%Y-%m')
    ORDER BY month";
$sales_result = $conn->query($sales_query);

$sales_dates = [];
$sales_amounts = [];
while ($row = $sales_result->fetch_assoc()) {
    $sales_dates[] = date('M Y', strtotime($row['month'])); // Format as 'Jan 2024', 'Feb 2024', etc.
    $sales_amounts[] = $row['monthly_total'];
}

// Fill in missing months with zero values
$current_year = date('Y');
$all_months = [];
$all_amounts = [];

for ($month = 1; $month <= 12; $month++) {
    $month_key = date('M Y', strtotime("$current_year-$month-01"));
    $month_index = array_search($month_key, $sales_dates);
    
    $all_months[] = $month_key;
    $all_amounts[] = ($month_index !== false) ? $sales_amounts[$month_index] : 0;
}

// Update the JSON variables to use the complete dataset
$sales_dates_json = json_encode($all_months);
$sales_amounts_json = json_encode($all_amounts);

// Fetch package usage statistics with additional details
$package_usage_query = "SELECT 
    p.package_name,
    COUNT(DISTINCT s.student_id) as student_count,
    COUNT(DISTINCT s.student_id) * 100.0 / (SELECT COUNT(*) FROM students) as usage_percentage,
    p.price as package_price
    FROM packages p
    LEFT JOIN students s ON p.id = s.package_id
    GROUP BY p.id, p.package_name, p.price
    ORDER BY student_count DESC";
$package_usage_result = $conn->query($package_usage_query);

// Store the results in an array
$package_usage_data = [];
while ($row = $package_usage_result->fetch_assoc()) {
    $package_usage_data[] = [
        'package_name' => $row['package_name'],
        'student_count' => $row['student_count'],
        'usage_percentage' => number_format($row['usage_percentage'], 1),
        'package_price' => number_format($row['package_price'], 2)
    ];
}

// Close the database connection
$conn->close();


