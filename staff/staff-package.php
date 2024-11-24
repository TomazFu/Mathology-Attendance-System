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
            <button class="view-packages-btn" onclick="showPackagesModal()">View All Packages</button>
            <div id="packagesModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closePackagesModal()">&times;</span>
                    <h2>Available Packages</h2>
                    <div class="packages-list">
                        <?php
                        // Query to get all packages
                        $sql = "SELECT package_name, price, details FROM packages ORDER BY id";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while ($package = $result->fetch_assoc()) {
                                echo "<div class='package-item'>";
                                echo "<h3>" . htmlspecialchars($package['package_name']) . "</h3>";
                                echo "<p class='package-price'>RM " . htmlspecialchars($package['price']) . "</p>";
                                echo "<p class='package-details'>" . htmlspecialchars($package['details']) . "</p>";
                                echo "</div>";
                            }
                        } else {
                            echo "<p>No packages found</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
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
                                <h2>Package Details</h2>
                                <table>
                                    <tr>
                                        <td>Current Package</td>
                                        <td><?php echo htmlspecialchars($student['package']['package_name']); ?> </td>
                                    </tr>
                                    <tr>
                                        <td>Change Package</td>
                                        <td>
                                            <select id="package-select-<?php echo $student['student_id']; ?>">
                                                <option value="none" data-price="0" <?php echo (empty($student['package']['package_id']) ? 'selected' : ''); ?>>None</option>
                                                <?php
                                                // Query to get available packages with prices
                                                $sql = "SELECT id, package_name, price FROM packages";
                                                $result = $conn->query($sql);
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $selected = ($row['id'] == $student['package']['package_id']) ? 'selected' : '';
                                                        echo "<option value='" . $row['id'] . "' data-price='" . $row['price'] . "' " . $selected . ">"
                                                            . htmlspecialchars($row['package_name']) . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <button onclick="updatePackage(<?php echo $student['student_id']; ?>)">Update</button>
                                        </td>
                                    </tr>
                                </table>
                                <h2>Generate New Payment</h2>
                                <table>
                                    <tr>
                                        <td>Registration</td>
                                        <td><input type="checkbox" id="registration-<?php echo $student['student_id']; ?>" value="1"></td>
                                    </tr>
                                    <tr>
                                        <td>Diagnostic Test/ Mathology Assessment</td>
                                        <td>
                                            <input type="checkbox" id="diagnostic-<?php echo $student['student_id']; ?>" value="1">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Deposit</td>
                                        <td><input type="text" id="deposit_fee-<?php echo $student['student_id']; ?>" value="0"></td>
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
                                    <input type="hidden" id="parent-id-<?php echo $student['student_id']; ?>"
                                        value="<?php echo $student['parent_id']; ?>">
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