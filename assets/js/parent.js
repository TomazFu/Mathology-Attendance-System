document.addEventListener('DOMContentLoaded', function() {
    console.log('Parent.js loaded');
    
    // Quick action buttons initialization
    initializeQuickActions();
    
    // Dashboard initialization
    const performanceChart = document.getElementById('performanceChart');
    if (performanceChart) {
        initializeCharts();
    }

    // Leave form initialization
    initializeLeaveForm();
});

function initializeQuickActions() {
    const applyLeaveBtn = document.querySelector('[data-action="apply-leave"]');
    const viewScheduleBtn = document.querySelector('[data-action="view-schedule"]');
    const viewAttendanceBtn = document.querySelector('[data-action="view-attendance"]');
    const viewPackageBtn = document.querySelector('[data-action="view-package"]');

    if (applyLeaveBtn) {
        applyLeaveBtn.addEventListener('click', () => {
            console.log('Apply Leave clicked');
            window.location.href = 'parent-leave-view.php';
        });
    }

    if (viewScheduleBtn) {
        viewScheduleBtn.addEventListener('click', () => {
            console.log('View Schedule clicked');
            window.location.href = 'parent-timetable.php';
        });
    }

    if (viewAttendanceBtn) {
        viewAttendanceBtn.addEventListener('click', () => {
            console.log('View Attendance clicked');
            window.location.href = 'parent-attendance.php';
        });
    }

    if (viewPackageBtn) {
        viewPackageBtn.addEventListener('click', () => {
            console.log('View Package clicked');
            window.location.href = 'parent-package.php';
        });
    }

    // Add leave view toggling functionality
    const trackLeaveBtn = document.querySelector('.track-leave-btn');
    if (trackLeaveBtn) {
        trackLeaveBtn.addEventListener('click', toggleLeaveView);
    }
}

function initializeLeaveForm() {
    const leaveForm = document.getElementById('leaveApplicationForm');
    if (!leaveForm) return;

    leaveForm.addEventListener('submit', submitLeaveForm);

    const leaveTypeCards = document.querySelectorAll('.leave-type-card');
    const leaveTypeInput = document.getElementById('leave_type');

    // Hide all form sections initially
    updateLeaveRequirements('');

    leaveTypeCards.forEach(card => {
        card.addEventListener('click', function() {
            selectLeaveType(this, leaveTypeCards, leaveTypeInput);
        });
    });

    // Initialize view state
    const currentView = sessionStorage.getItem('currentLeaveView') || 'leaveForm';
    showView(currentView);

    // Initialize file upload handlers
    const medicalCertInput = document.getElementById('medical_certificate');
    const supportingDocInput = document.getElementById('supporting_document');

    if (medicalCertInput) {
        medicalCertInput.addEventListener('change', function() {
            updateFileName(this, 'medical-file-name');
        });
    }

    if (supportingDocInput) {
        supportingDocInput.addEventListener('change', function() {
            updateFileName(this, 'support-file-name');
        });
    }
}

function selectLeaveType(selectedCard, allCards, input) {
    // Check if card is already selected
    if (selectedCard.classList.contains('selected')) {
        // Deselect the card
        selectedCard.classList.remove('selected');
        input.value = '';
        updateLeaveRequirements('');
        return;
    }

    // Remove selected class from all cards
    allCards.forEach(card => card.classList.remove('selected'));

    // Add selected class to clicked card
    selectedCard.classList.add('selected');

    // Update hidden input value
    const leaveType = selectedCard.dataset.type;
    input.value = leaveType;

    // Update form sections
    updateLeaveRequirements(leaveType);
}

function updateLeaveRequirements(leaveType) {
    const sections = {
        medical: document.querySelector('.medical-leave'),
        gap: document.querySelector('.gap-month'),
        date: document.querySelector('.date-selection'),
        document: document.querySelector('.document-section')
    };

    // Hide all sections first and remove required attributes
    Object.values(sections).forEach(section => {
        if (section) {
            section.style.display = 'none';
            // Remove required attribute from all date inputs
            const dateInputs = section.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => input.removeAttribute('required'));
        }
    });

    // If no leave type selected, return early
    if (!leaveType) return;

    // Show relevant sections based on leave type
    switch(leaveType) {
        case 'medical':
            sections.medical.style.display = 'block';
            sections.date.style.display = 'block';
            sections.document.style.display = 'block';
            // Add required attribute to date inputs
            const medicalDateInputs = sections.date.querySelectorAll('input[type="date"]');
            medicalDateInputs.forEach(input => input.setAttribute('required', ''));
            break;
        case 'normal':
            sections.date.style.display = 'block';
            sections.document.style.display = 'block';
            // Add required attribute to date inputs
            const normalDateInputs = sections.date.querySelectorAll('input[type="date"]');
            normalDateInputs.forEach(input => input.setAttribute('required', ''));
            break;
        case 'gap':
            sections.gap.style.display = 'block';
            break;
    }
}

function updateFileName(input, displayId) {
    const fileDisplay = document.getElementById(displayId);
    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2); // Convert to MB

        // Validate file size
        if (fileSize > 5) {
            alert('File size exceeds 5MB limit. Please choose a smaller file.');
            input.value = '';
            fileDisplay.style.display = 'none';
            return;
        }

        // Validate file type
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
        if (!allowedTypes.includes(input.files[0].type)) {
            alert('Only PDF, JPG, and PNG files are allowed');
            input.value = '';
            fileDisplay.style.display = 'none';
            return;
        }

        // Create new file display element
        const fileInfo = document.createElement('div');
        fileInfo.className = 'file-info';
        fileInfo.innerHTML = `
            <span>Selected: ${fileName} (${fileSize}MB)</span>
            <button type="button" class="remove-file" onclick="removeFile('${input.id}', '${displayId}')">
                <i class="material-icons">close</i>
            </button>`;

        // Clear previous files
        fileDisplay.innerHTML = '';
        fileDisplay.appendChild(fileInfo);
        fileDisplay.style.display = 'block';
    }
}

function removeFile(inputId, displayId) {
    const input = document.getElementById(inputId);
    const fileDisplay = document.getElementById(displayId);
    
    input.value = '';
    fileDisplay.innerHTML = '';
    fileDisplay.style.display = 'none';
}

function showView(viewName) {
    const views = {
        leaveForm: document.getElementById('leaveForm'),
        leaveHistory: document.getElementById('leaveHistory')
    };

    // Hide all views
    Object.values(views).forEach(view => {
        if (view) view.style.display = 'none';
    });

    // Show selected view
    if (views[viewName]) {
        views[viewName].style.display = 'block';
        sessionStorage.setItem('currentLeaveView', viewName);
    }
}

function toggleLeaveView() {
    const leaveForm = document.getElementById('leaveForm');
    const leaveHistory = document.getElementById('leaveHistory');
    const trackLeaveBtn = document.querySelector('.track-leave-btn');

    if (leaveHistory.style.display === 'none') {
        leaveForm.style.display = 'none';
        leaveHistory.style.display = 'block';
        trackLeaveBtn.textContent = 'Apply Leave';
    } else {
        leaveForm.style.display = 'block';
        leaveHistory.style.display = 'none';
        trackLeaveBtn.textContent = 'Track Leave';
    }
}

function submitLeaveForm(event) {
    event.preventDefault();

    const form = document.getElementById('leaveApplicationForm');
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    
    submitButton.disabled = true;
    submitButton.textContent = 'Submitting...';

    fetch('/Mathology-Attendance-System/parent/includes/submit-leave.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(async response => {
        const text = await response.text();
        try {
            const data = JSON.parse(text);
            
            // Show alert
            const alertDiv = document.getElementById('leaveAlert');
            const alertMessage = alertDiv.querySelector('.alert-message');

            alertDiv.className = 'alert ' + (data.success ? 'success' : 'error');
            alertMessage.textContent = data.message;
            alertDiv.style.display = 'flex';

            if (data.success) {
                form.reset();
                resetFileInputs();
                updateLeaveRequirements('');
                document.querySelectorAll('.leave-type-card').forEach(card => {
                    card.classList.remove('selected');
                });
                
                if (data.leave) {
                    addLeaveToHistory(data.leave);
                }

                // Show success message and reload page after 1 second
                setTimeout(() => {
                    alertDiv.style.display = 'none';
                    window.location.reload(); // Reload the page to show updated leave history
                }, 1000);
            }

            return data;
        } catch (e) {
            console.error('Server response:', text);
            throw new Error('Invalid server response');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('error', error.message || 'An error occurred while submitting the leave request');
    })
    .finally(() => {
        submitButton.disabled = false;
        submitButton.textContent = 'Submit Leave Request';
    });
}

function resetFileInputs() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.value = '';
        const displayId = input.getAttribute('data-display');
        if (displayId) {
            const display = document.getElementById(displayId);
            if (display) {
                display.innerHTML = '';
                display.style.display = 'none';
            }
        }
    });
}

function showMessage(type, message) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `alert ${type}`;
    messageDiv.textContent = message;

    const form = document.getElementById('leaveApplicationForm');
    form.insertBefore(messageDiv, form.firstChild);

    setTimeout(() => {
        messageDiv.remove();
    }, 5000);
}

function addLeaveToHistory(leave) {
    const leaveHistory = document.querySelector('#leaveHistory .leave-history-items');
    if (!leaveHistory) return;

    const noRecords = leaveHistory.querySelector('.no-records');
    if (noRecords) {
        noRecords.remove();
    }

    const leaveItem = document.createElement('div');
    leaveItem.className = 'leave-history-item';
    leaveItem.innerHTML = `
        <div class="leave-details">
            <p><strong>Leave ID:</strong> ${leave.leave_id}</p>
            <p><strong>Student Name:</strong> ${leave.student_name}</p>
            <p><strong>Reason:</strong> ${leave.reason}</p>
            <p>
                <strong>Status:</strong> 
                <span class="status-badge pending">Pending</span>
            </p>
        </div>
        <div class="leave-dates">
            <p>${formatDate(leave.fromDate)} to ${formatDate(leave.toDate)}</p>
            <button class="btn-icon" onclick="viewLeaveDetails(${leave.leave_id})">
                <i class="material-icons">visibility</i>
            </button>
        </div>
    `;

    // Add new leave item at the top of the history
    const firstItem = leaveHistory.querySelector('.leave-history-item');
    if (firstItem) {
        leaveHistory.insertBefore(leaveItem, firstItem);
    } else {
        leaveHistory.appendChild(leaveItem);
    }
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-GB');
}

// Add close button functionality for alerts
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('close-alert')) {
        const alertDiv = e.target.closest('.alert');
        if (alertDiv) {
            alertDiv.style.display = 'none';
        }
    }
});