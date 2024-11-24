<?php
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: staff-login.php");
    exit;
}

// Include database connection
require_once "../config/connect.php";

// Include header
include "../includes/header.php";

// Include sidebar
require_once "../includes/sidebar.php";

require_once "includes/fetch-student-package-process.php";
?>

<head>
    <link rel="stylesheet" href="../assets/css/staff.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/javascript/staff.js"></script>
</head>

<body>
    <div class="dashboard-layout">
        <?php renderSidebar('staff'); ?>

        <div class="main-content">
            <h1>Manage Packages</h1>
            <div class="attendance-container-list">
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $student): ?>
                        <div class="student-package-record">
                            <div class="student-header" onclick="showUpdateForm(<?php echo $student['student_id']; ?>)">
                                <div class="student-name"><?php echo htmlspecialchars($student['student_name']); ?></div>
                            </div>
                            <!-- Update form specific to each student -->
                            <div id="update-form-<?php echo $student['student_id']; ?>" class="update-form" style="display:none;">
                                <input type="hidden" id="student-id-<?php echo $student['student_id']; ?>" value="<?php echo $student['student_id']; ?>">

                                <table>
                                    <tr>
                                        <td>Current Package</td>
                                        <td><?php echo htmlspecialchars($student['package']['package_name']); ?> </td>
                                    </tr>
                                    <tr>
                                        <td>Change Package</td>
                                        <td>
                                            <select id="package-select-<?php echo $student['student_id']; ?>">
                                                <!-- "None" option for students without a package -->
                                                <option value="none" <?php echo (empty($student['package']['package_id']) ? 'selected' : ''); ?>>None</option>
                                                <?php
                                                // Query to get available packages
                                                $sql = "SELECT id, package_name FROM packages";
                                                $result = $conn->query($sql);
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        // Check if the student has a package assigned and select the correct option
                                                        $selected = ($row['id'] == $student['package']['package_id']) ? 'selected' : '';
                                                        echo "<option value='" . $row['id'] . "' " . $selected . ">" . htmlspecialchars($row['package_name']) . "</option>";
                                                    }
                                                } 
                                                ?>
                                            </select>
                                            <button onclick="updatePackage(<?php echo $student['student_id']; ?>)">Update</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Registration</td>
                                        <td><input type="checkbox" id="registration-<?php echo $student['student_id']; ?>" value="1"></td>
                                    </tr>
                                    <tr>
                                        <td>Deposit</td>
                                        <td><input type="checkbox" id="deposit-<?php echo $student['student_id']; ?>" value="1"></td>
                                    </tr>
                                    <tr>
                                        <td>Diagnostic Test/ Mathology Assessment</td>
                                        <td>
                                            <input type="checkbox" id="diagnostic-<?php echo $student['student_id']; ?>" value="1">
                                            <input type="text" id="diagnostic-amount-<?php echo $student['student_id']; ?>" placeholder="Enter diagnostic test price">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Payment Method</td>
                                        <td>
                                            <input type="radio" name="payment-method-<?php echo $student['student_id']; ?>" value="cash"> Cash
                                            <input type="radio" name="payment-method-<?php echo $student['student_id']; ?>" value="credit-card"> Credit Card
                                            <input type="radio" name="payment-method-<?php echo $student['student_id']; ?>" value="cheque"> Cheque
                                            <input type="radio" name="payment-method-<?php echo $student['student_id']; ?>" value="bank-in"> Bank-in
                                            <input type="radio" name="payment-method-<?php echo $student['student_id']; ?>" value="deposit"> Deposit
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Amount</td>
                                        <td><input type="number" id="amount-<?php echo $student['student_id']; ?>" placeholder="Enter amount"></td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>
                                            <select id="status-<?php echo $student['student_id']; ?>">
                                                <option value="paid">Paid</option>
                                                <option value="unpaid">Unpaid</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Date</td>
                                        <td><input type="date" id="payment-date-<?php echo $student['student_id']; ?>"></td>
                                    </tr>
                                </table>
                                <button onclick="submitPayment(<?php echo $student['student_id']; ?>)">Generate Payment</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No student records found.</p>
                <?php endif; ?>

            </div>

        </div>

    </div>
</body>

<?php
// Include footer
include "../includes/footer.php";
?>