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

// Fetch students linked to the packages table
$sqlStudents = "
    SELECT s.student_id, s.student_name, p.package_name 
    FROM students s
    INNER JOIN packages p ON s.student_id = p.student_id
";
$studentsResult = mysqli_query($conn, $sqlStudents);

// Initialize an array to hold students with package info
$studentsWithPackages = array();

// Process all students and their linked package information
if ($studentsResult) {
    while ($student = mysqli_fetch_assoc($studentsResult)) {
        $studentsWithPackages[] = array(
            'student_id' => $student['student_id'],
            'student_name' => $student['student_name'],
            'package_name' => $student['package_name']
        );
    }
}

// Free result set
mysqli_free_result($studentsResult);

// Close connection
mysqli_close($conn);
?>

<head>
    <link rel="stylesheet" href="../assets/css/staff.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="dashboard-layout">
        <?php renderSidebar('staff'); ?>
        <div class="main-content">
            <h1>Manage Packages</h1>

            <!-- Display Students with Package Information -->
            <?php if (!empty($studentsWithPackages)): ?>
                <div class="student-package-container-list">
                    <?php foreach ($studentsWithPackages as $student): ?>
                        <div
                            class="student-package-record"
                            id="record_<?php echo $student['student_name']; ?>"
                            onclick="toggleExpandRecord(
                                'record_<?php echo $student['student_name']; ?>',
                                '../path/to/images/<?php echo $student['student_id']; ?>.jpg'
                            )"
                        >
                            <div class="student-name">
                                <?php echo htmlspecialchars($student['student_name']); ?>
                            </div>
                            <div class="package-name">
                                <?php echo htmlspecialchars($student['package_name']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Expanded Image Container -->
                <img id="expanded-image" style="display: none; width: 100%; height: auto; margin-top: 20px;" data-record-id="" />
            <?php else: ?>
                <p>No packages found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>


<?php
// Include footer
include "../includes/footer.php";
?>

<script src="../assets/javascript/staff.js"></script>