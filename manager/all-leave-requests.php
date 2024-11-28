<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: manager-login.php");
    exit;
}

// Include header
include "../includes/header.php";

// Include sidebar functionality
include "../includes/sidebar.php";

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mathologydb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize SQL query
$sql = "SELECT leaves.leave_id, students.student_name AS student_name, leaves.reason, leaves.fromDate as start_date, leaves.toDate as end_date 
        FROM leaves 
        JOIN students ON leaves.student_id = students.student_id";

// Handle Search functionality
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = "%" . $_GET['search'] . "%";
    $sql .= " WHERE students.student_name LIKE ?";
}

// Handle Sort functionality
if (isset($_GET['sort']) && !empty($_GET['sort'])) {
    $month = (int) $_GET['sort'];
    $sql .= (strpos($sql, 'WHERE') === false ? " WHERE" : " AND") . " MONTH(leaves.fromDate) = ?";
}

// Add ordering for the results by start_date
$sql .= " ORDER BY leaves.fromDate";

// Prepare and execute the query
$stmt = $conn->prepare($sql);

// Bind parameters based on the search and sort inputs
if (isset($searchTerm) && isset($month)) {
    $stmt->bind_param("si", $searchTerm, $month);
} elseif (isset($searchTerm)) {
    $stmt->bind_param("s", $searchTerm);
} elseif (isset($month)) {
    $stmt->bind_param("i", $month);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Leave Requests</title>
    <link rel="stylesheet" href="../assets/css/all-leave-request.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php renderSidebar('manager'); ?>
    
    <div class="main-content">
        <div class="leave-dashboard">
            <div class="dashboard-header">
                <h1>All Leave Requests</h1>
            </div>

            <div class="search-sort-container">
                <!-- Search Form -->
                <div class="search-form">
                    <form id="searchForm" onsubmit="return false;">
                        <input type="text" name="search" id="search" 
                            placeholder="Search by student name" 
                            value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    </form>
                </div>

                <!-- Sort Form -->
                <div class="sort-form">
                    <form action="all-leave-requests.php" method="GET">
                        <select name="sort" id="sort" onchange="this.form.submit()">
                            <option value="">Filter by Month</option>
                            <?php
                            $months = [
                                1 => 'January', 2 => 'February', 3 => 'March',
                                4 => 'April', 5 => 'May', 6 => 'June',
                                7 => 'July', 8 => 'August', 9 => 'September',
                                10 => 'October', 11 => 'November', 12 => 'December'
                            ];
                            foreach ($months as $value => $month) {
                                $selected = (isset($_GET['sort']) && $_GET['sort'] == $value) ? 'selected' : '';
                                echo "<option value='$value' $selected>$month</option>";
                            }
                            ?>
                        </select>
                    </form>
                </div>
            </div>

            <div class="latest-leave-requests">
                <?php while ($leave = $result->fetch_assoc()): ?>
                    <div class="leave-request">
                        <div class="leave-info">
                            <p><span class="label">Leave ID:</span> <?php echo htmlspecialchars($leave['leave_id']); ?></p>
                            <p><span class="label">Student:</span> <?php echo htmlspecialchars($leave['student_name']); ?></p>
                        </div>
                        <div class="leave-info">
                            <p><span class="label">Reason:</span> <?php echo htmlspecialchars($leave['reason']); ?></p>
                            <p><span class="label">Duration:</span> 
                                <?php echo date('d M Y', strtotime($leave['start_date'])); ?> - 
                                <?php echo date('d M Y', strtotime($leave['end_date'])); ?>
                            </p>
                        </div>
                        <a href="leave-request-details.php?leave_id=<?php echo urlencode($leave['leave_id']); ?>" 
                           class="view-btn">View Details</a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let searchTimer;
            
            // Detect search input change and update results
            $('#search').on('input', function() {
                clearTimeout(searchTimer);
                const searchTerm = $(this).val();
                const sortValue = $('#sort').val();
                
                // Add a small delay to prevent too many requests
                searchTimer = setTimeout(function() {
                    // Show loading state
                    $('.latest-leave-requests').html('<div class="loading">Searching...</div>');
                    
                    // AJAX request to fetch the filtered results
                    $.ajax({
                        url: 'all-leave-requests.php',
                        method: 'GET',
                        data: { 
                            search: searchTerm, 
                            sort: sortValue 
                        },
                        success: function(response) {
                            $('.latest-leave-requests').html($(response).find('.latest-leave-requests').html());
                        },
                        error: function() {
                            $('.latest-leave-requests').html('<div class="error">Error loading results</div>');
                        }
                    });
                }, 300); // 300ms delay
            });
        });
    </script>

</body>
</html>
