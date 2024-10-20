<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Leave View - Mathology</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="main-content">
        <div class="top-bar">
            <div class="search-bar">
                <input type="text" placeholder="Search">
            </div>
            <div class="notification-icons">
                <button>🔔</button>
                <button>💬</button>
            </div>
        </div>
        <div class="card">
            <div class="tabs">
                <div class="tab active" data-tab="leave-form">Leave Form</div>
                <div class="tab" data-tab="leave-history">Leave History</div>
            </div>
            <div id="leave-form" class="tab-content">
                <form id="leaveForm">
                    <div class="form-group">
                        <select name="student" required>
                            <option value="">Select student</option>
                            <option value="1">Student 1</option>
                            <option value="2">Student 2</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea name="reason" placeholder="Reason for Leave" rows="4" required></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="file-upload">Upload Leave Application</label>
                            <input type="file" id="file-upload" name="leave_application">
                        </div>
                        <div>
                            <div class="form-group">
                                <label for="start-date">From</label>
                                <input type="date" id="start-date" name="start_date" required>
                            </div>
                            <div class="form-group">
                                <label for="end-date">To</label>
                                <input type="date" id="end-date" name="end_date" required>
                            </div>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <button type="button" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary">Grant Leave</button>
                    </div>
                </form>
            </div>
            <div id="leave-history" class="tab-content" style="display: none;">
                <h2>Leave History</h2>
                <div class="leave-history-item">
                    <div class="header">
                        <div>
                            <strong>Leave ID: P120</strong>
                            <p>Student Name: John Doe</p>
                        </div>
                        <div>
                            <p>12-01-2023 to 12-01-2023</p>
                            <button>👁️</button>
                        </div>
                    </div>
                    <p>Reason: Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</p>
                </div>
                <!-- More leave history items can be added here -->
            </div>
        </div>
    </div>
    <button class="track-leave-btn">Track Leave</button>

    <?php include 'footer.php'; ?>

    <script src="script.js"></script>
</body>
</html>