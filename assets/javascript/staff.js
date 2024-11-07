function toggleAttendance(studentId, status) {
    // Get the present and absent checkboxes by their IDs
    const presentCheckbox = document.getElementById(`present_${studentId}`);
    const absentCheckbox = document.getElementById(`absent_${studentId}`);
    
    // Uncheck the opposite checkbox based on the selected status
    if (status === 'present') {
        absentCheckbox.checked = false; // Uncheck absent
    } else {
        presentCheckbox.checked = false; // Uncheck present
    }

    // Update attendance status in the database (AJAX)
    updateAttendanceStatus(studentId, status);
}

function updateAttendanceStatus(studentId, status) {
    // Send AJAX request to update attendance status in the database
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "includes/update-attendance.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText); // For debugging purposes
        }
    };
    xhr.send(`student_id=${studentId}&attendance_status=${status}`);
}
