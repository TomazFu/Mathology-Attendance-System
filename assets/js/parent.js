document.addEventListener('DOMContentLoaded', function() {
    // Dashboard initialization
    const performanceChart = document.getElementById('performanceChart');
    if (performanceChart) {
        initializeCharts();
    }

    // Leave form initialization
    initializeLeaveForm();
});

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

function updateDateValidation(leaveType) {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    if (!startDate || !endDate) return;

    const today = new Date().toISOString().split('T')[0];
    
    if (leaveType === 'medical') {
        startDate.min = '';
        startDate.max = today;
    } else if (leaveType === 'normal') {
        const minDate = new Date();
        minDate.setHours(minDate.getHours() + 48);
        startDate.min = minDate.toISOString().split('T')[0];
        startDate.max = '';
    }
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

function toggleView() {
    const currentView = sessionStorage.getItem('currentLeaveView') || 'leaveForm';
    const newView = currentView === 'leaveForm' ? 'leaveHistory' : 'leaveForm';
    showView(newView);
}

function resetForm() {
    const form = document.querySelector('form');
    if (form) {
        form.reset();
        const leaveTypeCards = document.querySelectorAll('.leave-type-card');
        leaveTypeCards.forEach(card => card.classList.remove('selected'));
        updateLeaveRequirements('');
    }
}

function updateFileName(input, displayId) {
    const fileDisplay = document.getElementById(displayId);
    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2); // Convert to MB
        
        // Create new file display element
        const fileInfo = document.createElement('div');
        fileInfo.className = 'file-info';
        fileInfo.innerHTML = `
            <span>Selected: ${fileName} (${fileSize}MB)</span>
            <button type="button" class="remove-file" onclick="removeFile('${input.id}', '${displayId}', this.parentElement)">
                <i class="material-icons">close</i>
            </button>`;
        
        // Clear previous files
        fileDisplay.innerHTML = '';
        
        // Add the new file info to display
        fileDisplay.appendChild(fileInfo);
        fileDisplay.style.display = 'block';
        
        // Validate file size
        if (fileSize > 5) {
            alert('File size exceeds 5MB limit. Please choose a smaller file.');
            fileInfo.remove();
            input.value = '';
            fileDisplay.style.display = 'none';
        }
    }
}

function removeFile(inputId, displayId, fileInfoElement) {
    fileInfoElement.remove();
    const fileDisplay = document.getElementById(displayId);
    
    // Hide the container if no files left
    if (!fileDisplay.querySelector('.file-info')) {
        fileDisplay.style.display = 'none';
    }
}

function submitLeaveForm(event) {
    event.preventDefault();
    
    const form = document.getElementById('leaveApplicationForm');
    const formData = new FormData(form);
    
    // Log form data for debugging
    console.log('Form data being sent:', Object.fromEntries(formData));

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
            if (!response.ok) {
                throw new Error(data.message || 'Server error');
            }
            return data;
        } catch (e) {
            console.error('Server response:', text);
            throw new Error('Invalid server response');
        }
    })
    .then(data => {
        if (data.success) {
            showMessage('success', data.message);
            if (data.leave) {
                addLeaveToHistory(data.leave);
            }
            form.reset();
            resetFileInputs();
            updateLeaveRequirements('');
            document.querySelectorAll('.leave-type-card').forEach(card => {
                card.classList.remove('selected');
            });
            setTimeout(() => {
                showView('leaveHistory');
            }, 2000);
        } else {
            showMessage('error', data.message || 'Error submitting leave request');
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

function showMessage(type, message) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `${type}-message`;
    messageDiv.textContent = message;
    
    // Insert message at the top of the form
    const form = document.getElementById('leaveApplicationForm');
    form.insertBefore(messageDiv, form.firstChild);
    
    // Remove message after 3 seconds
    setTimeout(() => {
        messageDiv.remove();
    }, 3000);
}

function addLeaveToHistory(leave) {
    const leaveHistory = document.querySelector('#leaveHistory');
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
        firstItem.parentNode.insertBefore(leaveItem, firstItem);
    } else {
        leaveHistory.appendChild(leaveItem);
    }
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-GB');
}

// Add helper function to reset file inputs
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