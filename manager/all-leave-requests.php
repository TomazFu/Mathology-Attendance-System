<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mathlogydb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize SQL query
$sql = "SELECT leaves.leave_id, students.name AS student_name, leaves.reason, leaves.fromDate as start_date, leaves.toDate as end_date 
        FROM leaves 
        JOIN students ON leaves.student_id = students.student_id";

// Handle Search functionality
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = "%" . $_GET['search'] . "%";
    $sql .= " WHERE students.name LIKE ?";
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <h2>All Leave Requests</h2>

    <!-- Search Form (Auto-update on input) -->
    <form action="all-leave-requests.php" method="GET">
        <input type="text" name="search" id="search" placeholder="Search by student name" value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    </form>

    <!-- Sorting by Month (Optional, if you still want to allow month-based sorting) -->
    <form action="all-leave-requests.php" method="GET">
        <label for="sort">Sort by Month:</label>
        <select name="sort" id="sort">
            <option value="">Select Month</option>
            <option value="1" <?php echo (isset($_GET['sort']) && $_GET['sort'] == '1') ? 'selected' : ''; ?>>January</option>
            <option value="2" <?php echo (isset($_GET['sort']) && $_GET['sort'] == '2') ? 'selected' : ''; ?>>February</option>
            <option value="3" <?php echo (isset($_GET['sort']) && $_GET['sort'] == '3') ? 'selected' : ''; ?>>March</option>
            <option value="4" <?php echo (isset($_GET['sort']) && $_GET['sort'] == '4') ? 'selected' : ''; ?>>April</option>
            <option value="5" <?php echo (isset($_GET['sort']) && $_GET['sort'] == '5') ? 'selected' : ''; ?>>May</option>
            <option value="6" <?php echo (isset($_GET['sort']) && $_GET['sort'] == '6') ? 'selected' : ''; ?>>June</option>
            <option value="7" <?php echo (isset($_GET['sort']) && $_GET['sort'] == '7') ? 'selected' : ''; ?>>July</option>
            <option value="8" <?php echo (isset($_GET['sort']) && $_GET['sort'] == '8') ? 'selected' : ''; ?>>August</option>
            <option value="9" <?php echo (isset($_GET['sort']) && $_GET['sort'] == '9') ? 'selected' : ''; ?>>September</option>
            <option value="10" <?php echo (isset($_GET['sort']) && $_GET['sort'] == '10') ? 'selected' : ''; ?>>October</option>
            <option value="11" <?php echo (isset($_GET['sort']) && $_GET['sort'] == '11') ? 'selected' : ''; ?>>November</option>
            <option value="12" <?php echo (isset($_GET['sort']) && $_GET['sort'] == '12') ? 'selected' : ''; ?>>December</option>
        </select>
        <button type="submit">Sort</button>
    </form>

    <!-- Display leave requests -->
    <div id="latest-leave-requests">
        <?php while ($leave = $result->fetch_assoc()): ?>
            <div class="leave-request">
                <p>Leave ID: <?php echo htmlspecialchars($leave['leave_id'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p>Student Name: <?php echo htmlspecialchars($leave['student_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p>Reason: <?php echo htmlspecialchars($leave['reason'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p>From: <?php echo htmlspecialchars($leave['start_date'], ENT_QUOTES, 'UTF-8'); ?> to 
                        <?php echo htmlspecialchars($leave['end_date'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        <?php endwhile; ?>
    </div>

    <script>
        $(document).ready(function() {
            // Detect search input change and update results
            $('#search').on('input', function() {
                var searchTerm = $(this).val();  // Get the search term
                var sortValue = $('#sort').val();  // Get the selected month for sorting

                // AJAX request to fetch the filtered results
                $.ajax({
                    url: 'all-leave-requests.php',
                    method: 'GET',
                    data: { search: searchTerm, sort: sortValue },
                    success: function(response) {
                        // Update the displayed leave requests with the new response
                        $('#latest-leave-requests').html($(response).find('#latest-leave-requests').html());
                    }
                });
            });
        });
    </script>

</body>
</html>
