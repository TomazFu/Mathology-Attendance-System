document.addEventListener('DOMContentLoaded', function() {
    // Initialize Leave Form functionality
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const leaveForm = document.getElementById('leaveForm');
    const leaveHistory = document.getElementById('leaveHistory');
    const trackLeaveBtn = document.querySelector('.track-leave-btn');

    // Date validation
    if (startDate && endDate) {
        startDate.addEventListener('change', function() {
            endDate.min = this.value;
            if (endDate.value && endDate.value < this.value) {
                endDate.value = this.value;
            }
        });
    }

    // Form validation
    const form = document.getElementById('leaveApplicationForm');
    if (form) {
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

        // Initialize leave form view
        showView('leaveForm');
    }

    // Initialize Dashboard Elements
    if (document.querySelector('.dashboard-layout')) {
        // Quick Action Buttons
        const actionButtons = {
            'apply-leave': '../parent/parent-leave-view.php',
            'view-schedule': '../parent/parent-timetable.php',
            'contact-teacher': '../parent/message-teacher.php'
        };

        // Initialize action buttons
        document.querySelectorAll('.action-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const action = this.getAttribute('data-action');
                if (actionButtons[action]) {
                    window.location.href = actionButtons[action];
                }
            });
        });

        // View All Schedule Button
        const viewAllBtn = document.querySelector('.view-all-btn');
        if (viewAllBtn) {
            viewAllBtn.addEventListener('click', function() {
                window.location.href = '../parent/parent-timetable.php';
            });
        }

        // Schedule Item Links
        document.querySelectorAll('.schedule-item').forEach(item => {
            item.addEventListener('click', function() {
                const classId = this.getAttribute('data-class-id');
                if (classId) {
                    window.location.href = `../parent/parent-timetable.php?id=${classId}`;
                }
            });
        });

        // Initialize Charts and Dashboard Data
        initializeCharts();
        initializeDashboardData();
    }

    // Handle initial resize
    handleResize();
    window.addEventListener('resize', handleResize);
});

// Keep all utility functions outside
function handleResize() {
    if (window.innerWidth <= 768) {
        document.body.classList.add('mobile');
    } else {
        document.body.classList.remove('mobile');
    }
}

function validateDates() {
    const startDate = new Date(document.getElementById('start_date').value);
    const endDate = new Date(document.getElementById('end_date').value);
    return startDate <= endDate;
}

function resetForm() {
    const form = document.getElementById('leaveApplicationForm');
    const selectedFile = document.getElementById('selectedFile');
    const successMessage = document.getElementById('successMessage');
    
    if (form) form.reset();
    if (selectedFile) selectedFile.style.display = 'none';
    if (successMessage) successMessage.style.display = 'none';
}

function showError(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    
    const form = document.getElementById('leaveApplicationForm');
    if (form) {
        form.insertBefore(errorDiv, form.firstChild);
        setTimeout(() => errorDiv.remove(), 3000);
    }
}

function showView(viewId) {
    const leaveForm = document.getElementById('leaveForm');
    const leaveHistory = document.getElementById('leaveHistory');
    const trackBtn = document.querySelector('.track-leave-btn');

    if (leaveForm && leaveHistory && trackBtn) {
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
}

// Initialize Charts
function initializeCharts() {
    const ctx = document.getElementById('performanceChart').getContext('2d');
    
    const performanceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Performance',
                data: [75, 82, 88, 85],
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    });

    // Update chart on period change
    document.getElementById('performance-period').addEventListener('change', function(e) {
        updateChartData(performanceChart, e.target.value);
    });
}

// Update chart data based on selected period
function updateChartData(chart, period) {
    // Simulate different data for different periods
    const data = {
        'week': [75, 82, 88, 85],
        'month': [70, 75, 85, 82, 88, 85, 90, 87],
        'quarter': [65, 70, 75, 80, 85, 82, 88, 85, 90, 87, 92, 89]
    };

    chart.data.labels = Array.from({ length: data[period].length }, (_, i) => `Day ${i + 1}`);
    chart.data.datasets[0].data = data[period];
    chart.update();
}

// Initialize dashboard data
function initializeDashboardData() {
    // Update stats
    document.getElementById('total-classes').textContent = '12';
    document.getElementById('attendance-rate').textContent = '95%';

    // Add animation to stats
    const stats = document.querySelectorAll('.stat-value');
    stats.forEach(stat => {
        stat.style.opacity = '0';
        stat.style.transform = 'translateY(20px)';
        setTimeout(() => {
            stat.style.opacity = '1';
            stat.style.transform = 'translateY(0)';
            stat.style.transition = 'all 0.5s ease';
        }, 300);
    });
}

function toggleView() {
    const leaveForm = document.getElementById('leaveForm');
    const leaveHistory = document.getElementById('leaveHistory');
    const trackBtn = document.querySelector('.track-leave-btn');

    if (leaveForm && leaveHistory && trackBtn) {
        if (leaveForm.style.display === 'none') {
            leaveForm.style.display = 'block';
            leaveHistory.style.display = 'none';
            trackBtn.textContent = 'Track Leave';
        } else {
            leaveForm.style.display = 'none';
            leaveHistory.style.display = 'block';
            trackBtn.textContent = 'New Leave Request';
        }
    }
}