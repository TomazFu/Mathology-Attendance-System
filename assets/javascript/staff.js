// Function to handle the toggling of attendance status (present or absent)
function toggleAttendance(studentId, status) {
    // Get the current date selected in the dropdown
    const selectedDate = document.getElementById('date-select').value;

    // Send the updated attendance status to the server via AJAX
    updateAttendanceStatus(studentId, status, selectedDate);

    // Update the checkbox UI
    const presentCheckbox = document.getElementById(`present_${studentId}`);
    const absentCheckbox = document.getElementById(`absent_${studentId}`);

    // Reset both checkboxes and then toggle the one that matches the status
    presentCheckbox.checked = false;
    absentCheckbox.checked = false;

    if (status === 'present') {
        presentCheckbox.checked = true;
    } else if (status === 'absent') {
        absentCheckbox.checked = true;
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

function showUpdateForm(studentId) {
    // Get the specific update form element for the student
    const updateForm = document.getElementById(`update-form-${studentId}`);
    
    if (updateForm) {
        // Check the current display state and toggle it
        if (updateForm.style.display === 'none' || updateForm.style.display === '') {
            updateForm.style.display = 'block'; // Show the form
        } else {
            updateForm.style.display = 'none'; // Hide the form
        }
    }
}



function submitPayment(studentId) {
    if (window.isSubmitting) return;
    
    try {
        window.isSubmitting = true;

        // Get all required elements
        const packageSelect = document.getElementById(`package-select-${studentId}`);
        const registrationCheckbox = document.getElementById(`registration-${studentId}`);
        const diagnosticCheckbox = document.getElementById(`diagnostic-${studentId}`);
        const depositInput = document.getElementById(`deposit_fee-${studentId}`);
        const paymentMethodInputs = document.getElementsByName(`payment-method-${studentId}`);
        const statusSelect = document.getElementById(`status-${studentId}`);
        const paymentDateInput = document.getElementById(`payment-date-${studentId}`);
        const parentIdInput = document.getElementById(`parent-id-${studentId}`); // Add this line

        // Get parent_id
        const parentId = parentIdInput ? parentIdInput.value : null; // Add this line

        // Calculate total amount
        let totalAmount = 0;

        // ... rest of your calculation code ...

        const data = new URLSearchParams({
            student_id: studentId,
            parent_id: parentId, // Add this line
            amount: totalAmount,
            payment_method: paymentMethod,
            registration: registrationCheckbox.checked ? 1 : 0,
            deposit_fee: depositFee,
            diagnostic_test: diagnosticCheckbox.checked ? 1 : 0,
            status: statusSelect.value,
            date: paymentDateInput.value
        }).toString();

        console.log('Sending payment data:', data);
        xhr.send(data);

    } catch (error) {
        window.isSubmitting = false;
        console.error('Error in submitPayment:', error);
        alert(`Error: ${error.message}`);
    }
}

function updatePackage(studentId) {
    var packageSelect = document.getElementById('package-select-' + studentId);
    
    if (!packageSelect) {
        console.error('Package select element not found for student ID:', studentId);
        return;
    }

    var packageId = packageSelect.value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/Mathology-Attendance-System/staff/includes/update-package.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert("Error: " + response.message);
                }
            } catch (e) {
                console.error('Response:', xhr.responseText);
                console.error('Parse error:', e);
                alert("Error updating package: Invalid response from server");
            }
        } else {
            alert("Error updating package. Status: " + xhr.status);
        }
    };

    var data = "student_id=" + encodeURIComponent(studentId) + 
               "&package_id=" + encodeURIComponent(packageId);
    xhr.send(data);
}

// Add these new functions
function showPackagesModal() {
    document.getElementById('packagesModal').style.display = 'block';
}

function closePackagesModal() {
    document.getElementById('packagesModal').style.display = 'none';
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    var modal = document.getElementById('packagesModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}