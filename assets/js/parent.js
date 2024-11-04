document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const leaveForm = document.getElementById('leaveForm');
    const leaveHistory = document.getElementById('leaveHistory');
    const trackLeaveBtn = document.querySelector('.track-leave-btn');

    // Date validation
    startDate.addEventListener('change', function() {
        endDate.min = this.value;
        if (endDate.value && endDate.value < this.value) {
            endDate.value = this.value;
        }
    });

    // Form validation
    const form = document.getElementById('leaveApplicationForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!validateDates()) {
            showError('Please select valid dates');
            return;
        }

        const fileInput = document.getElementById('file-upload');
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            if (file.size > 5 * 1024 * 1024) {
                showError('File size must be less than 5MB');
                return;
            }
        }

        this.submit();
    });

    // Initialize view
    showView('leaveForm');
});

// Parent Dashboard functionality
// Function to handle window resize
function handleResize() {
    if (window.innerWidth <= 768) {
        document.body.classList.add('mobile');
    } else {
        document.body.classList.remove('mobile');
    }
}

// Initial check and event listener for resize
handleResize();
window.addEventListener('resize', handleResize);

// Dashboard data fetching
if (document.getElementById('enrolled-classes-list')) {
    fetch('../includes/fetch-dashboard-data-process.php')
        .then(response => response.json())
        .then(data => {
            // Dashboard data handling code from script.js
            // Reference lines 115-136 from original script.js
        })
        .catch(error => {
            console.error('Error fetching dashboard data:', error);
        });
}

function validateDates() {
    const startDate = new Date(document.getElementById('start_date').value);
    const endDate = new Date(document.getElementById('end_date').value);
    return startDate <= endDate;
}

function resetForm() {
    document.getElementById('leaveApplicationForm').reset();
    document.getElementById('selectedFile').style.display = 'none';
    document.getElementById('successMessage').style.display = 'none';
}

function updateFileName(input) {
    const selectedFile = document.getElementById('selectedFile');
    if (input.files && input.files[0]) {
        selectedFile.textContent = input.files[0].name;
        selectedFile.style.display = 'block';
    } else {
        selectedFile.style.display = 'none';
    }
}

function showError(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    
    const form = document.getElementById('leaveApplicationForm');
    form.insertBefore(errorDiv, form.firstChild);
    
    setTimeout(() => {
        errorDiv.remove();
    }, 3000);
}

function toggleView() {
    const leaveForm = document.getElementById('leaveForm');
    const leaveHistory = document.getElementById('leaveHistory');
    const trackBtn = document.querySelector('.track-leave-btn');

    if (leaveForm.style.display !== 'none') {
        leaveForm.style.display = 'none';
        leaveHistory.style.display = 'block';
        trackBtn.textContent = 'New Leave Request';
    } else {
        leaveForm.style.display = 'block';
        leaveHistory.style.display = 'none';
        trackBtn.textContent = 'Track Leave';
    }
}

function viewLeaveDetails(leaveId) {
    // Implement leave details view functionality
    window.location.href = `leave-details.php?id=${leaveId}`;
}

function showView(viewId) {
    const leaveForm = document.getElementById('leaveForm');
    const leaveHistory = document.getElementById('leaveHistory');
    const trackBtn = document.querySelector('.track-leave-btn');

    if (viewId === 'leaveForm') {
        leaveForm.style.display = 'block';
        leaveHistory.style.display = 'none';
        trackBtn.textContent = 'Track Leave';
    } else if (viewId === 'leaveHistory') {
        leaveForm.style.display = 'none';
        leaveHistory.style.display = 'block';
        trackBtn.textContent = 'New Leave Request';
    }
} 