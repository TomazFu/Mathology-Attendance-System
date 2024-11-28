<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../includes/PHPMailer-master/src/PHPMailer.php';
require '../../includes/PHPMailer-master/src/Exception.php';
require '../../includes/PHPMailer-master/src/SMTP.php';

function sendLeaveStatusEmail($leaveData, $parentEmail, $action, $reason) {
    $mail = new PHPMailer(true);

    try {
        // Set timezone to Malaysia
        date_default_timezone_set('Asia/Kuala_Lumpur');
        
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tomazfushaoyang@gmail.com'; // Replace with your email
        $mail->Password = 'hdwc pilp beqy vpqo'; // Replace with your app password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('tomazfushaoyang@gmail.com', 'Mathology');
        $mail->addAddress($parentEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Leave Request Update - Mathology';
        $mail->Body = generateLeaveStatusEmailHTML($leaveData, $action, $reason);
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
        return false;
    }
}

function generateLeaveStatusEmailHTML($leaveData, $action, $reason) {
    $statusColor = $action === 'approved' ? '#4CAF50' : '#f44336';
    $statusText = ucfirst($action);
    
    // Only include reason section if the leave is rejected
    $reasonSection = ($action === 'rejected' && $reason) ? "<p><strong>Reason for Rejection:</strong> {$reason}</p>" : "";
    
    return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <h1 style='text-align: center; color: #333;'>Mathology</h1>
            <h2 style='color: #666;'>Leave Request Update</h2>
            
            <div style='background-color: #f9f9f9; padding: 20px; border-radius: 5px; margin: 20px 0;'>
                <p><strong>Student Name:</strong> {$leaveData['student_name']}</p>
                <p><strong>Leave Type:</strong> " . ucfirst($leaveData['leave_type']) . "</p>
                <p><strong>Duration:</strong> " . date('d/m/Y', strtotime($leaveData['fromDate'])) . " - " . date('d/m/Y', strtotime($leaveData['toDate'])) . "</p>
                <p><strong>Status:</strong> <span style='color: {$statusColor};'>{$statusText}</span></p>
                {$reasonSection}
            </div>
            
            <div style='text-align: center; margin-top: 30px; font-size: 12px; color: #666;'>
                <p>Mathology Kuchai Lama (LLP0022441)</p>
                <p>2-4, Jalan 3/114, Kuchai Business Centre, 58200 KL</p>
            </div>
        </div>
    ";
} 