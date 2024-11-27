<?php
// Connect to the MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mathologydb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (isset($_GET['staff_id'])) {
        $staff_id = intval($_GET['staff_id']);
        $stmt = $conn->prepare("SELECT * FROM staff WHERE staff_id = ?");
        $stmt->bind_param("i", $staff_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $staff = $result->fetch_assoc();

        if ($staff) {
            echo json_encode(['success' => true, 'staff' => $staff]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Staff not found']);
        }
        $stmt->close();
    } else {
        $search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
        $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
        
        $query = "SELECT * FROM staff WHERE name LIKE ? OR qualification LIKE ? OR contact_number LIKE ?";
        
        if ($sort === 'leave_asc') {
            $query .= " ORDER BY leave_left ASC";
        } elseif ($sort === 'leave_desc') {
            $query .= " ORDER BY leave_left DESC";
        } else {
            $query .= " ORDER BY staff_id DESC";
        }
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $search, $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
        $staff = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['success' => true, 'staff' => $staff]);
        $stmt->close();
    }
} elseif ($method === 'POST') {
    $name = $_POST['name'] ?? '';
    $qualification = $_POST['qualification'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $leave = intval($_POST['leave'] ?? 0);
    $status = $_POST['status'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO staff (name, qualification, contact_number, leave_left, current_status, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdsss", $name, $qualification, $contact, $leave, $status, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }
    $stmt->close();
} elseif ($method === 'PUT') {
    parse_str(file_get_contents("php://input"), $input);
    $staff_id = intval($input['id'] ?? 0);
    $name = $input['name'] ?? '';
    $qualification = $input['qualification'] ?? '';
    $contact = $input['contact'] ?? '';
    $leave = intval($input['leave'] ?? 0);
    $status = $input['status'] ?? '';
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';

    if (!empty($password)) {
        // Hash the new password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE staff SET name = ?, qualification = ?, contact_number = ?, leave_left = ?, current_status = ?, email = ?, password = ? WHERE staff_id = ?");
        $stmt->bind_param("sssdsssi", $name, $qualification, $contact, $leave, $status, $email, $hashedPassword, $staff_id);
    } else {
        $stmt = $conn->prepare("UPDATE staff SET name = ?, qualification = ?, contact_number = ?, leave_left = ?, current_status = ?, email = ? WHERE staff_id = ?");
        $stmt->bind_param("sssdssi", $name, $qualification, $contact, $leave, $status, $email, $staff_id);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }
    $stmt->close();
} elseif ($method === 'DELETE') {
    parse_str(file_get_contents("php://input"), $input);
    $staff_id = intval($input['staff_id'] ?? 0);

    $stmt = $conn->prepare("DELETE FROM staff WHERE staff_id = ?");
    $stmt->bind_param("i", $staff_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>
