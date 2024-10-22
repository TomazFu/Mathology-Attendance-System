<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Timetable</title>
    <link rel="stylesheet" href="parentTimetable.css">
</head>
<body>
    <div class="timetable-container">
        <div class="header">
            <h1>Your Timetable</h1>
        </div>
        
        <!-- Timetable Section -->
        <div class="timetable-section">
            <table id="timetable">
                <thead>
                    <tr>
                        <th>Subject ID</th>
                        <th>Title</th>
                        <th>Room</th>
                        <th>Instructor</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data to be filled by JS -->
                </tbody>
            </table>
        </div>

        <!-- Enrolled Classes Section -->
        <div class="enrolled-classes-section">
            <h2>Enrolled Classes</h2>
            <ul id="enrolled-classes-list">
                <!-- Enrolled Classes will be populated here by JS -->
            </ul>
        </div>
    </div>

    <script src="parentTimetable.js"></script>
</body>
</html>
