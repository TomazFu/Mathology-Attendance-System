document.addEventListener('DOMContentLoaded', function() {
    initializeStudentSelector();
});

async function initializeStudentSelector() {
    try {
        const response = await fetch('../parent/includes/fetch-students.php');
        const data = await response.json();
        
        if (!data.success) {
            console.error('Error:', data.error);
            displayError('Unable to load students data');
            return;
        }
        
        const selectorHtml = `
            <select id="student-select">
                ${data.students.map(student => 
                    `<option value="${student.student_id}">${student.student_name}</option>`
                ).join('')}
            </select>
        `;
        
        const heroContent = document.querySelector('.hero-content .student-selector');
        if (heroContent) {
            heroContent.innerHTML = selectorHtml;
            
            const studentSelect = document.getElementById('student-select');
            studentSelect.addEventListener('change', function() {
                fetchAttendanceData(this.value);
                updateClassSelector(this.value);
            });
            
            const classSelect = document.getElementById('class-select');
            classSelect.addEventListener('change', function() {
                const studentId = document.getElementById('student-select').value;
                const period = document.getElementById('attendance-period').value;
                fetchAttendanceData(studentId, period, this.value);
            });
            
            const periodSelect = document.getElementById('attendance-period');
            periodSelect.addEventListener('change', function() {
                const studentId = document.getElementById('student-select').value;
                const classId = document.getElementById('class-select').value;
                fetchAttendanceData(studentId, this.value, classId);
            });
            
            if (data.students.length > 0) {
                const firstStudentId = data.students[0].student_id;
                updateClassSelector(firstStudentId);
                fetchAttendanceData(firstStudentId);
            }
        }
    } catch (error) {
        console.error('Error initializing student selector:', error);
        displayError('Error loading student data');
    }
}

async function updateClassSelector(studentId) {
    try {
        const response = await fetch(`../parent/includes/fetch-enrolled-classes.php?student_id=${studentId}`);
        const data = await response.json();
        
        if (!data.success) {
            console.error('Error:', data.error);
            return;
        }
        
        const classSelect = document.getElementById('class-select');
        const classOptions = `
            <option value="all">All Classes</option>
            ${data.classes.map(cls => 
                `<option value="${cls.id}">${cls.title}</option>`
            ).join('')}
        `;
        
        classSelect.innerHTML = classOptions;
    } catch (error) {
        console.error('Error updating class selector:', error);
    }
}

async function fetchAttendanceData(studentId, period = 'month', classId = 'all') {
    try {
        const response = await fetch(
            `../parent/includes/fetch-attendance-data.php?student_id=${studentId}&period=${period}&class_id=${classId}`
        );
        const data = await response.json();
        
        if (!data.success) {
            console.error('Error:', data.error);
            displayError('Unable to load attendance data');
            return;
        }
        
        document.getElementById('total-classes').textContent = data.overall_stats.total_classes;
        document.getElementById('classes-attended').textContent = data.overall_stats.classes_attended;
        document.getElementById('classes-missed').textContent = data.overall_stats.classes_missed;
        
        updateAttendanceList(data.attendance_data);
        
    } catch (error) {
        console.error('Error fetching attendance data:', error);
        displayError('Error loading attendance data');
    }
}

function updateOverallStats(stats) {
    const summaryContainer = document.querySelector('.stats-grid');
    if (!summaryContainer) return;

    const totalClasses = stats.total_classes || 0;
    const attendedClasses = stats.classes_attended || 0;
    const missedClasses = stats.classes_missed || 0;
    const attendanceRate = totalClasses ? Math.round((attendedClasses / totalClasses) * 100) : 0;

    const summaryHtml = `
        <div class="stat-card">
            <div class="stat-icon">
                <i class="material-icons">calendar_today</i>
            </div>
            <div class="stat-info">
                <span class="stat-value" id="total-classes">${totalClasses}</span>
                <span class="stat-label">Total Classes</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="material-icons">check_circle</i>
            </div>
            <div class="stat-info">
                <span class="stat-value" id="attended-classes">${attendedClasses}</span>
                <span class="stat-label">Classes Attended</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="material-icons">warning</i>
            </div>
            <div class="stat-info">
                <span class="stat-value" id="missed-classes">${missedClasses}</span>
                <span class="stat-label">Classes Missed</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon ${attendanceRate >= 75 ? 'success' : 'warning'}">
                <i class="material-icons">percent</i>
            </div>
            <div class="stat-info">
                <span class="stat-value">${attendanceRate}%</span>
                <span class="stat-label">Attendance Rate</span>
            </div>
        </div>
    `;

    summaryContainer.innerHTML = summaryHtml;
}

function updateClassStats(classStats) {
    const classStatsContainer = document.querySelector('.class-stats');
    if (!classStatsContainer) return;

    const statsHtml = Object.entries(classStats).map(([className, stats]) => `
        <div class="class-stat-card">
            <div class="class-header">
                <h3>${className}</h3>
                <span class="class-schedule">${stats.schedule}</span>
            </div>
            <div class="class-details">
                <div class="stat-item">
                    <span class="stat-label">Total Classes</span>
                    <span class="stat-value">${stats.total_classes}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Attended</span>
                    <span class="stat-value attended">${stats.classes_attended}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Missed</span>
                    <span class="stat-value missed">${stats.classes_missed}</span>
                </div>
            </div>
        </div>
    `).join('');

    classStatsContainer.innerHTML = statsHtml;
}

function updateAttendanceList(attendanceData) {
    const listContainer = document.querySelector('.attendance-list');
    if (!listContainer) return;
    
    if (!attendanceData || attendanceData.length === 0) {
        listContainer.innerHTML = '<div class="no-data">No attendance records found</div>';
        return;
    }
    
    // Group attendance by date
    const groupedByDate = attendanceData.reduce((acc, record) => {
        const date = record.date;
        if (!acc[date]) {
            acc[date] = [];
        }
        acc[date].push(record);
        return acc;
    }, {});
    
    const listHtml = Object.entries(groupedByDate).map(([date, records]) => `
        <div class="attendance-date-group">
            <div class="date-header">
                <span class="date">${formatDate(date)}</span>
            </div>
            ${records.map(record => `
                <div class="attendance-item">
                    <div class="class-info">
                        <span class="subject-name">${record.subject_name}</span>
                        <span class="class-time">
                            <i class="material-icons">schedule</i>
                            ${record.time}
                        </span>
                    </div>
                    <div class="attendance-status ${record.status}">
                        <i class="material-icons">
                            ${getStatusIcon(record.status)}
                        </i>
                        ${capitalizeFirst(record.status)}
                    </div>
                </div>
            `).join('')}
        </div>
    `).join('');
    
    listContainer.innerHTML = listHtml;
}

function getStatusIcon(status) {
    switch(status) {
        case 'present': return 'check_circle';
        case 'absent': return 'cancel';
        case 'late': return 'schedule';
        default: return 'help';
    }
}

function animateValue(elementId, value) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const start = parseInt(element.textContent) || 0;
    const duration = 1000;
    const steps = 20;
    const increment = (value - start) / steps;
    let current = start;
    let step = 0;
    
    const animation = setInterval(() => {
        step++;
        current += increment;
        element.textContent = Math.round(current);
        
        if (step >= steps) {
            clearInterval(animation);
            element.textContent = value;
        }
    }, duration / steps);
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', { 
        weekday: 'short',
        month: 'short', 
        day: 'numeric',
        year: 'numeric'
    });
}

function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function displayError(message) {
    const contentGrid = document.querySelector('.content-grid');
    if (!contentGrid) return;

    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    
    const existingError = contentGrid.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    contentGrid.prepend(errorDiv);
} 