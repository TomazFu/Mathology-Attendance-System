document.addEventListener('DOMContentLoaded', function() {
    fetchAttendanceData();
});

async function fetchAttendanceData() {
    try {
        const response = await fetch('../parent/includes/fetch-attendance-data.php');
        const data = await response.json();
        
        if (!data.success) {
            console.error('Error:', data.error);
            displayError('Unable to load attendance data');
            return;
        }
        
        if (!data.attendance_data || data.attendance_data.length === 0) {
            displayError('No attendance records found');
            return;
        }
        
        updateStats(data.stats);
        initializeAttendanceChart(data.attendance_data);
        loadAttendanceLogs(data.attendance_data);
    } catch (error) {
        console.error('Error fetching attendance data:', error);
        displayError('Error loading attendance data');
    }
}

function initializeAttendanceChart(attendanceData) {
    const ctx = document.getElementById('attendanceChart');
    if (!ctx) {
        console.error('Cannot find attendance chart canvas');
        return;
    }

    // Process the attendance data
    const dates = attendanceData.map(record => new Date(record.date).toLocaleDateString());
    const values = attendanceData.map(record => record.status === 'present' ? 1 : 0);

    // Create gradient
    const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(37, 99, 235, 0.1)');
    gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

    // If a chart already exists, destroy it
    if (window.attendanceChart instanceof Chart) {
        window.attendanceChart.destroy();
    }

    // Create new chart
    window.attendanceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Attendance',
                data: values,
                borderColor: '#2563eb',
                backgroundColor: gradient,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#2563eb',
                pointBorderWidth: 2,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 1,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return value === 1 ? 'Present' : 'Absent';
                        }
                    },
                    grid: {
                        display: true,
                        color: '#e2e8f0',
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw === 1 ? 'Present' : 'Absent';
                        }
                    }
                }
            }
        }
    });
}

// Chart type toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const chartTypeButtons = document.querySelectorAll('.chart-type-btn');
    if (chartTypeButtons) {
        chartTypeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                if (!window.attendanceChart) return;
                
                const chartType = this.dataset.type;
                document.querySelectorAll('.chart-type-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Update chart type
                window.attendanceChart.config.type = chartType;
                window.attendanceChart.update();
            });
        });
    }
});

function updateStats(stats) {
    if (!stats) return;
    
    document.getElementById('total-days').textContent = stats.total_days || 0;
    document.getElementById('present-days').textContent = stats.present_days || 0;
    document.getElementById('absences').textContent = stats.absent_days || 0;
    
    const attendanceRate = stats.total_days > 0 
        ? ((stats.present_days / stats.total_days) * 100).toFixed(1) 
        : '0.0';
    document.getElementById('attendance-rate').textContent = `${attendanceRate}%`;
    
    animateStats();
}

function displayError(message) {
    const contentGrid = document.querySelector('.content-grid');
    if (!contentGrid) return;

    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    
    // Remove any existing error messages
    const existingError = contentGrid.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    contentGrid.prepend(errorDiv);
}

function animateStats() {
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

function loadAttendanceLogs(attendanceData) {
    const logContainer = document.querySelector('.attendance-log');
    logContainer.innerHTML = ''; // Clear existing logs
    
    if (!attendanceData || attendanceData.length === 0) {
        logContainer.innerHTML = '<div class="no-data">No attendance records found</div>';
        return;
    }
    
    attendanceData.forEach(log => {
        const logItem = createLogItem(log);
        logContainer.appendChild(logItem);
    });
}

function createLogItem(log) {
    const div = document.createElement('div');
    div.className = `attendance-log-item ${log.status}`;
    
    div.innerHTML = `
        <div class="log-date">
            <i class="material-icons">${log.status === 'present' ? 'check_circle' : 'cancel'}</i>
            ${formatDate(log.date)}
        </div>
        <div class="log-status">${log.status.charAt(0).toUpperCase() + log.status.slice(1)}</div>
    `;
    
    return div;
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric', 
        year: 'numeric' 
    });
}

// Add chart type toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const chartTypeButtons = document.querySelectorAll('.chart-type-btn');
    if (chartTypeButtons) {
        chartTypeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                if (!window.attendanceChart) return;
                
                const chartType = this.dataset.type;
                document.querySelectorAll('.chart-type-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                window.attendanceChart.config.type = chartType;
                window.attendanceChart.update();
            });
        });
    }
}); 