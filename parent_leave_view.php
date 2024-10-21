<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Leave View - Mathology</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <div class="logo-icon">
                    <i class="material-icons">person</i>
                </div>
                <div class="logo-text">
                    <h2>Mathology</h2>
                    <p>Parent</p>
                </div>
            </div>
            <nav>
                <ul>
                    <li><a href="#"><i class="material-icons">dashboard</i> Dashboard</a></li>
                    <li><a href="#"><i class="material-icons">calendar_today</i> Timetable</a></li>
                    <li class="active"><a href="#" data-view="leaveForm"><i class="material-icons">event_note</i> Leave</a></li>
                    <li><a href="#"><i class="material-icons">assignment_turned_in</i> Attendance</a></li>
                    <li><a href="#"><i class="material-icons">card_membership</i> Package</a></li>
                </ul>
            </nav>
            <div class="logout">
                <a href="#"><i class="material-icons">exit_to_app</i> Log Out</a>
            </div>
        </aside>
        <main>
            <header>
                <div class="search-bar">
                    <input type="text" placeholder="Search">
                    <i class="material-icons">search</i>
                </div>
                <div class="icons">
                    <button class="icon-button"><i class="material-icons">notifications</i></button>
                    <button class="icon-button"><i class="material-icons">chat</i></button>
                </div>
            </header>
            <div class="content">
                <div class="card" id="leaveForm">
                    <h2>Leave Form</h2>
                    <form>
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
                                <label for="file-upload" class="custom-file-upload">
                                    <i class="material-icons">cloud_upload</i> Upload Leave Application
                                </label>
                                <input type="file" id="file-upload" name="leave_application">
                            </div>
                            <div class="form-group date-range">
                                <div class="date-input">
                                    <i class="material-icons">calendar_today</i>
                                    <input type="date" name="start_date" required>
                                </div>
                                <span>to</span>
                                <div class="date-input">
                                    <i class="material-icons">calendar_today</i>
                                    <input type="date" name="end_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary">Cancel</button>
                            <button type="submit" class="btn btn-primary">Grant Leave</button>
                        </div>
                    </form>
                </div>
                <div class="card" id="leaveHistory" style="display: none;">
                    <h2>Leave History</h2>
                    <div class="leave-history-item">
                        <div class="leave-details">
                            <p><strong>Leave ID:</strong> P120</p>
                            <p><strong>Student Name:</strong> John Doe</p>
                            <p><strong>Reason:</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</p>
                        </div>
                        <div class="leave-dates">
                            <p>12-01-2023 to 12-01-2023</p>
                            <button class="btn-icon"><i class="material-icons">visibility</i></button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <button class="track-leave-btn">Track Leave</button>
    <script src="script.js"></script>
</body>
</html>