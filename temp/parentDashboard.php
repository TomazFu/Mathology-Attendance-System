<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard</title>
    <link rel="stylesheet" href="parentDashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="header">
            <img src="../assets/img/booking.jpeg" alt="Book Image">
            <h1>Welcome to Mathology!</h1>
        </div>
        
        <div class="dashboard-sections">
            <!-- Enrolled Classes -->
            <div class="enrolled-classes">
                <h3>Enrolled Classes</h3>
                <ul id="enrolled-classes-list">
                    <!-- To be filled by JS -->
                </ul>
            </div>
            
            <!-- Enrolled Package -->
            <div class="enrolled-package">
                <h3>Enrolled Package</h3>
                <p id="enrolled-package-text">Loading...</p>
            </div>

            <!-- Attendance Chart -->
            <div class="attendance-chart">
                <h3>Attendance</h3>
                <div class="chart">
                    <span id="attendance-percent">0%</span>
                    <ul>
                        <li class="present">Present</li>
                        <li class="late">Late</li>
                        <li class="leaves">Leaves</li>
                    </ul>
                </div>
            </div>

            <!-- Latest Leave -->
            <div class="latest-leave">
                <h3>Latest Leave</h3>
                <p id="leave-id">Leave ID: Loading...</p>
                <p id="student-name">Student Name: Loading...</p>
                <p id="leave-reason">Reason: Loading...</p>
                <p id="leave-dates">Date: Loading...</p>
                <button class="view-btn" id="view-leave-btn">View Details</button>
            </div>
        </div>
    </div>

    <script src="parentDashboard.js"></script>
</body>
</html>
