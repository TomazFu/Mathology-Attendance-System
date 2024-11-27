<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../includes/PHPMailer-master/src/PHPMailer.php';
require '../../includes/PHPMailer-master/src/Exception.php';
require '../../includes/PHPMailer-master/src/SMTP.php';

function sendReceiptEmail($paymentData, $parentEmail) {
    $mail = new PHPMailer(true);

    try {
        // Set timezone to Malaysia
        date_default_timezone_set('Asia/Kuala_Lumpur');
        
        $totalAmount = floatval($paymentData['amount']);
        $currentTime = date('h:i A'); // This will now use Malaysia time
        
        $mail->Body = generateEmailHTML($paymentData, $totalAmount, $currentTime);

        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Replace with your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'tomazfushaoyang@gmail.com'; // Replace with your email
        $mail->Password = 'hdwc pilp beqy vpqo'; // Replace with your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('tomazfushaoyang@gmail.com');
        $mail->addAddress($parentEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Payment Receipt - Mathology';
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
        return false;
    }
}

function generateEmailHTML($paymentData, $totalAmount, $currentTime) {
    return "
        <div style='font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto;'>
            <h1 style='text-align: center;'>Mathology</h1>
            <h2>Official Receipt</h2>
            
            <div style='margin-bottom: 20px;'>
                <p><strong>Receipt No:</strong> RCPT-" . sprintf('%05d', $paymentData['id']) . "</p>
                <p><strong>Date:</strong> " . date('d/m/Y') . "</p>
                <p><strong>Time:</strong> {$currentTime}</p>
            </div>
            
            <div style='margin-bottom: 20px;'>
                <p><strong>Student:</strong> {$paymentData['student_name']}</p>
                <p><strong>Guardian:</strong> {$paymentData['parent_name']}</p>
            </div>
            
            <table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>
                <tr style='background-color: #f2f2f2;'>
                    <th style='padding: 8px; text-align: left; border: 1px solid #ddd;'>Item</th>
                    <th style='padding: 8px; text-align: right; border: 1px solid #ddd;'>Amount (RM)</th>
                </tr>
                " . generateItemRowsHTML($paymentData) . "
                <tr>
                    <td style='padding: 8px; text-align: right; border: 1px solid #ddd;'><strong>Total Amount:</strong></td>
                    <td style='padding: 8px; text-align: right; border: 1px solid #ddd;'><strong>RM {$totalAmount}</strong></td>
                </tr>
            </table>
            
            <div style='margin-top: 20px; font-size: 12px;'>
                <p>Thank you for your payment!</p>
                <p>1. All registration fees, diagnostic test and program fees paid are non-refundable with exception of Deposit.</p>
                <p>2. Cancellation of program requires one (1) month advance written notice.</p>
            </div>
            
            <div style='text-align: center; margin-top: 30px; font-size: 10px;'>
                <p>Mathology Kuchai Lama (LLP0022441)</p>
                <p>2-4, Jalan 3/114, Kuchai Business Centre, 58200 KL</p>
            </div>
        </div>
    ";
}

function generateItemRowsHTML($paymentData) {
    $html = '';
    
    if ($paymentData['package_name']) {
        $html .= "<tr>
            <td style='padding: 8px; border: 1px solid #ddd;'>{$paymentData['package_name']}</td>
            <td style='padding: 8px; text-align: right; border: 1px solid #ddd;'>RM {$paymentData['package_price']}</td>
        </tr>";
    }
    
    if ($paymentData['registration']) {
        $html .= "<tr>
            <td style='padding: 8px; border: 1px solid #ddd;'>Registration Fee</td>
            <td style='padding: 8px; text-align: right; border: 1px solid #ddd;'>RM 50.00</td>
        </tr>";
    }
    
    if ($paymentData['diagnostic_test']) {
        $html .= "<tr>
            <td style='padding: 8px; border: 1px solid #ddd;'>Diagnostic Test</td>
            <td style='padding: 8px; text-align: right; border: 1px solid #ddd;'>RM 100.00</td>
        </tr>";
    }
    
    if ($paymentData['deposit_fee']) {
        $html .= "<tr>
            <td style='padding: 8px; border: 1px solid #ddd;'>Deposit Fee</td>
            <td style='padding: 8px; text-align: right; border: 1px solid #ddd;'>RM {$paymentData['deposit_fee']}</td>
        </tr>";
    }
    
    return $html;
}
?>