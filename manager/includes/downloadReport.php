<?php
// Include the process file to fetch data
include('fetch-managerDashboard.php');
require_once('C:\xampp\htdocs\Mathology-Attendance-System\manager\TCPDF-main\TCPDF-main\tcpdf.php'); 

// Handle export logic based on the type (PDF or CSV)
$type = isset($_GET['type']) ? $_GET['type'] : '';

if ($type === 'pdf') {
    // PDF Export Logic
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);

    // Title and Summary
    $pdf->Cell(0, 10, 'Monthly Report', 0, 1, 'C');
    $pdf->Ln(5);
    $pdf->Cell(0, 10, 'Total Fees Collection: RM ' . number_format($total_fees, 2), 0, 1);
    $pdf->Cell(0, 10, 'Total Fees Outstanding: RM ' . number_format($outstanding_fees, 2), 0, 1);
    $pdf->Cell(0, 10, 'Total Students: ' . $student_count, 0, 1);
    $pdf->Cell(0, 10, 'Total Staff: ' . $staff_count, 0, 1);
    $pdf->Cell(0, 10, 'Overall Attendance Today: ' . $attendance_percentage . '%', 0, 1);
    $pdf->Ln(10);

    // Leave Requests
    $pdf->Cell(0, 10, 'Latest Leave Requests:', 0, 1);
    while ($leave = $leave_requests_result->fetch_assoc()) {
        $pdf->Cell(0, 10, 'Leave ID: ' . $leave['leave_id'], 0, 1);
        $pdf->Cell(0, 10, 'Student Name: ' . $leave['student_name'], 0, 1);
        $pdf->Cell(0, 10, 'Reason: ' . $leave['reason'], 0, 1);
        $pdf->Cell(0, 10, 'From: ' . $leave['start_date'] . ' to ' . $leave['end_date'], 0, 1);
        $pdf->Ln(5);
    }

    $pdf->Output('report.pdf', 'I');
} elseif ($type === 'csv') {
    // CSV Export Logic
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="report.csv"');

    $output = fopen('php://output', 'w');
    // Column headers
    fputcsv($output, ['Report Data']);
    fputcsv($output, ['Total Fees Collection', 'RM ' . number_format($total_fees, 2)]);
    fputcsv($output, ['Total Fees Outstanding', 'RM ' . number_format($outstanding_fees, 2)]);
    fputcsv($output, ['Total Students', $student_count]);
    fputcsv($output, ['Total Staff', $staff_count]);
    fputcsv($output, ['Overall Attendance Today', $attendance_percentage . '%']);
    fputcsv($output, ['Leave Requests']);
    
    while ($leave = $leave_requests_result->fetch_assoc()) {
        fputcsv($output, [
            $leave['leave_id'],
            $leave['student_name'],
            $leave['reason'],
            $leave['start_date'],
            $leave['end_date']
        ]);
    }

    fclose($output);
}
?>
