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
    const leaveForm = document.getElementById('leaveForm');
    if (!leaveForm) return; // Exit if not on leave form page

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
    
    // Hide all sections first
    Object.values(sections).forEach(section => {
        if (section) section.style.display = 'none';
    });
    
    // If no leave type selected, return early
    if (!leaveType) return;
    
    // Show relevant sections based on leave type
    switch(leaveType) {
        case 'medical':
            sections.medical.style.display = 'block';
            sections.date.style.display = 'block';
            sections.document.style.display = 'block';
            break;
        case 'normal':
            sections.date.style.display = 'block';
            sections.document.style.display = 'block';
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
        
        // Add the new file info to display
        fileDisplay.appendChild(fileInfo);
        fileDisplay.style.display = 'block';
        
        // Validate file size
        if (fileSize > 5) {
            alert('File size exceeds 5MB limit. Please choose a smaller file.');
            fileInfo.remove();
            input.value = '';
        }
        
        // Reset input to allow selecting the same file again
        input.value = '';
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