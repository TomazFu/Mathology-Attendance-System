// Function to handle the toggling of attendance status (present or absent)
function toggleAttendance(studentId, status) {
    // Get the current date selected in the dropdown
    const selectedDate = document.getElementById('date-select').value;

    // Send the updated attendance status to the server via AJAX
    updateAttendanceStatus(studentId, status, selectedDate);

    // Update the checkbox UI
    const presentCheckbox = document.getElementById(`present_${studentId}`);
    const absentCheckbox = document.getElementById(`absent_${studentId}`);

    // Toggle opposite checkbox based on status
    if (status === 'present') {
        absentCheckbox.checked = false; // Uncheck absent
    } else {
        presentCheckbox.checked = false; // Uncheck present
    }
}

// Function to update the attendance status in the database
function updateAttendanceStatus(studentId, status, selectedDate) {
    // Create the AJAX request to send data to the backend
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "includes/update-attendance.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText); // Optionally log success message for debugging
        }
    };

    // Send the data (student_id, attendance_status, and selected_date) to update the record
    xhr.send(`student_id=${studentId}&attendance_status=${status}&date=${selectedDate}`);
}

function toggleExpandRecord(recordId, imageUrl) {
    const record = document.getElementById(recordId);
    const expandedImage = document.getElementById('expanded-image');

    if (expandedImage.dataset.recordId === recordId) {
        // If the image is already expanded for this record, hide it
        expandedImage.style.display = 'none';
        expandedImage.dataset.recordId = '';
    } else {
        // Expand the image for the clicked record
        expandedImage.src = imageUrl;
        expandedImage.style.display = 'block';
        expandedImage.dataset.recordId = recordId;
    }
}
