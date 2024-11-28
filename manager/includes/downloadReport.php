<?php
// Include the process file to fetch data
include('fetch-managerDashboard.php');
require_once('../TCPDF-main/TCPDF-main/tcpdf.php'); 

// Create a new database connection for this script
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Calculate monthly statistics first
// Calculate monthly student count
$monthly_student_count = "SELECT COUNT(*) as count 
    FROM students 
    WHERE MONTH(created_at) = MONTH(CURRENT_DATE) 
    AND YEAR(created_at) = YEAR(CURRENT_DATE)";
$result = $conn->query($monthly_student_count);
$monthly_student_count = $result->fetch_assoc()['count'];

// Calculate monthly staff count
$monthly_staff_count = "SELECT COUNT(*) as count 
    FROM staff 
    WHERE MONTH(created_at) = MONTH(CURRENT_DATE) 
    AND YEAR(created_at) = YEAR(CURRENT_DATE)";
$result = $conn->query($monthly_staff_count);
$monthly_staff_count = $result->fetch_assoc()['count'];

// Calculate monthly attendance percentage
$monthly_attendance_percentage = "SELECT 
    (COUNT(CASE WHEN status = 'present' THEN 1 END) * 100.0 / COUNT(*)) as percentage
    FROM attendance 
    WHERE MONTH(date) = MONTH(CURRENT_DATE) 
    AND YEAR(date) = YEAR(CURRENT_DATE)";
$result = $conn->query($monthly_attendance_percentage);
$monthly_attendance_percentage = number_format($result->fetch_assoc()['percentage'], 1);

// Get selected month from GET parameter, default to current month if not set
$selected_month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$selected_year = date('Y');

// Fetch all leave requests for selected month report
$leave_requests_query = "
    SELECT l.leave_id, s.student_name, l.reason, 
           l.fromDate as start_date, l.toDate as end_date,
           l.leave_type, l.status
    FROM leaves l 
    JOIN students s ON l.student_id = s.student_id 
    WHERE (MONTH(l.fromDate) = ? OR MONTH(l.toDate) = ?)
    AND YEAR(l.fromDate) = ?
    ORDER BY l.fromDate DESC";

$stmt = $conn->prepare($leave_requests_query);
$stmt->bind_param("iii", $selected_month, $selected_month, $selected_year);
$stmt->execute();
$leave_requests_result_report = $stmt->get_result();

// Handle export logic based on the type (PDF or CSV)
$type = isset($_GET['type']) ? $_GET['type'] : '';

if ($type === 'pdf') {
    // PDF Export Logic
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'B', 16);  // Bold, larger font for header
    
    // Header with business name
    $pdf->Cell(0, 10, 'MATHOLOGY', 0, 1, 'C');
    $pdf->SetFont('helvetica', 'I', 12);  // Italic for tagline
    $pdf->Cell(0, 10, 'MATHS LEARNING CENTER', 0, 1, 'C');
    $pdf->Ln(10);
    
    // Report title and date
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Monthly Performance Report', 0, 1, 'C');
    $pdf->SetFont('helvetica', 'I', 10);
    $pdf->Cell(0, 10, 'Generated on: ' . date('d/m/Y'), 0, 1, 'C');
    $pdf->Ln(10);

    // Summary section
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Summary', 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 11);
    $pdf->Cell(0, 8, 'Total Revenue: RM ' . number_format(array_sum($all_amounts), 2), 0, 1);
    $pdf->Cell(0, 8, 'Total Students: ' . $student_count, 0, 1);
    $pdf->Cell(0, 8, 'Total Staff: ' . $staff_count, 0, 1);
    $pdf->Cell(0, 8, 'Overall Attendance Rate: ' . $attendance_percentage . '%', 0, 1);
    $pdf->Ln(10);

    // Leave Requests section
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Monthly Leave Applications', 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 11);

    // Create table headers
    $pdf->Cell(20, 8, 'ID', 1, 0, 'C');
    $pdf->Cell(50, 8, 'Student Name', 1, 0, 'C');
    $pdf->Cell(35, 8, 'Start Date', 1, 0, 'C');
    $pdf->Cell(35, 8, 'End Date', 1, 0, 'C');
    $pdf->Cell(30, 8, 'Type', 1, 0, 'C');
    $pdf->Cell(20, 8, 'Status', 1, 1, 'C');

    while ($leave = $leave_requests_result_report->fetch_assoc()) {
        $pdf->Cell(20, 8, $leave['leave_id'], 1, 0, 'C');
        $pdf->Cell(50, 8, $leave['student_name'], 1, 0, 'L');
        $pdf->Cell(35, 8, date('d/m/Y', strtotime($leave['start_date'])), 1, 0, 'C');
        $pdf->Cell(35, 8, date('d/m/Y', strtotime($leave['end_date'])), 1, 0, 'C');
        $pdf->Cell(30, 8, ucfirst($leave['leave_type']), 1, 0, 'C');
        $pdf->Cell(20, 8, ucfirst($leave['status']), 1, 1, 'C');
    }

    $pdf->Output('report.pdf', 'I');
} elseif ($type === 'csv') {
    // CSV Export Logic
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="mathology_report.csv"');

    $output = fopen('php://output', 'w');
    
    // Add UTF-8 BOM for proper Excel encoding
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Updated headers and structure
    fputcsv($output, ['MATHOLOGY - MATHS LEARNING CENTER']);
    fputcsv($output, ['Monthly Performance Report - ' . date('d/m/Y')]);
    fputcsv($output, []);  // Empty line for spacing
    fputcsv($output, ['SUMMARY']);
    fputcsv($output, ['Total Revenue', 'RM ' . number_format(array_sum($all_amounts), 2)]);
    fputcsv($output, ['Total Students', $student_count]);
    fputcsv($output, ['Total Staff', $staff_count]);
    fputcsv($output, ['Overall Attendance Rate', $attendance_percentage . '%']);
    fputcsv($output, []);  // Empty line for spacing
    fputcsv($output, ['LEAVE APPLICATIONS']);
    
    // Add format prefix to ensure Excel treats these as text
    fputcsv($output, ['ID', 'Student Name', "'Start Date", "'End Date", 'Type', 'Status', 'Reason']);
    
    while ($leave = $leave_requests_result_report->fetch_assoc()) {
        fputcsv($output, [
            $leave['leave_id'],
            $leave['student_name'],
            "'" . date('d/m/Y', strtotime($leave['start_date'])),  // Add single quote prefix
            "'" . date('d/m/Y', strtotime($leave['end_date'])),    // Add single quote prefix
            ucfirst($leave['leave_type']),
            ucfirst($leave['status']),
            $leave['reason']
        ]);
    }

    fclose($output);
}

// Calculate monthly student count
$monthly_student_count = "SELECT COUNT(*) as count 
    FROM students 
    WHERE MONTH(created_at) = MONTH(CURRENT_DATE) 
    AND YEAR(created_at) = YEAR(CURRENT_DATE)";
$result = $conn->query($monthly_student_count);
$monthly_student_count = $result->fetch_assoc()['count'];

// Calculate monthly staff count
$monthly_staff_count = "SELECT COUNT(*) as count 
    FROM staff 
    WHERE MONTH(created_at) = MONTH(CURRENT_DATE) 
    AND YEAR(created_at) = YEAR(CURRENT_DATE)";
$result = $conn->query($monthly_staff_count);
$monthly_staff_count = $result->fetch_assoc()['count'];

// Calculate monthly attendance percentage
$monthly_attendance_percentage = "SELECT 
    (COUNT(CASE WHEN status = 'present' THEN 1 END) * 100.0 / COUNT(*)) as percentage
    FROM attendance 
    WHERE MONTH(date) = MONTH(CURRENT_DATE) 
    AND YEAR(date) = YEAR(CURRENT_DATE)";
$result = $conn->query($monthly_attendance_percentage);
$monthly_attendance_percentage = number_format($result->fetch_assoc()['percentage'], 1);
?>
