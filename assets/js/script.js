console.log('Script loaded');

document.addEventListener('DOMContentLoaded', function() {
    // Dashboard functionality
    if (document.getElementById('total-classes')) {
        console.log('Found dashboard elements');
        
        const studentSelect = document.getElementById('student-select');
        console.log('Student select element:', studentSelect);
        
        const totalClassesElement = document.getElementById('total-classes');
        const attendanceRateElement = document.getElementById('attendance-rate');
        
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

        function fetchDashboardData(studentId) {
            console.log('Fetching data for student:', studentId);
            
            // Keep the previous values during loading
            const previousClasses = totalClassesElement.textContent;
            const previousAttendance = attendanceRateElement ? attendanceRateElement.textContent : '0%';
            const previousLeaves = document.getElementById('leave-status').textContent;
            
            const url = './includes/fetch-dashboard-data-process.php?student_id=' + studentId;
            console.log('Fetch URL:', url);
            
            fetch(url)
                .then(response => {
                    console.log('Response received:', response);
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Dashboard data received:', data);
                    
                    // Update total classes count
                    totalClassesElement.textContent = data.totalClasses || '0';
                    
                    // Update attendance rate
                    if (attendanceRateElement) {
                        const attendanceRate = data.attendanceRate || 0;
                        attendanceRateElement.textContent = `${attendanceRate}%`;
                    }

                    // Update total leave requests
                    const leaveStatusElement = document.getElementById('leave-status');
                    if (leaveStatusElement) {
                        leaveStatusElement.textContent = data.totalLeaves || '0';
                    }

                    // Update upcoming classes
                    console.log('Upcoming classes:', data.upcomingClasses);
                    updateUpcomingClasses(data.upcomingClasses || []);

                    // Animate the stats after updating
                    animateStats();
                })
                .catch(error => {
                    console.error('Error fetching dashboard data:', error);
                    totalClassesElement.textContent = previousClasses;
                    if (attendanceRateElement) {
                        attendanceRateElement.textContent = previousAttendance;
                    }
                    if (document.getElementById('leave-status')) {
                        document.getElementById('leave-status').textContent = previousLeaves;
                    }
                    // Still animate even on error to show the values smoothly
                    animateStats();
                });
        }

        function updateUpcomingClasses(classes) {
            const scheduleList = document.getElementById('upcoming-classes');
            console.log('Updating schedule list:', scheduleList);
            console.log('Classes data:', classes);
            
            // Clear existing schedule items
            scheduleList.innerHTML = '';
            
            if (!classes || classes.length === 0) {
                console.log('No classes found');
                scheduleList.innerHTML = `
                    <div class="no-classes-message">
                        No upcoming classes scheduled
                    </div>`;
                return;
            }

            classes.forEach(classItem => {
                console.log('Processing class item:', classItem);
                const classDate = new Date(classItem.date + ' ' + classItem.time);
                const formattedTime = classDate.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                const scheduleItem = document.createElement('div');
                scheduleItem.className = 'schedule-item';
                scheduleItem.innerHTML = `
                    <div class="schedule-time">
                        <i class="material-icons">access_time</i>
                        <span>${formattedTime}</span>
                    </div>
                    <div class="schedule-info">
                        <h4>${classItem.name}</h4>
                        <p>${classDate.toLocaleDateString('en-US', { 
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        })}</p>
                    </div>
                    <div class="schedule-status">
                        <span class="status-badge upcoming">Upcoming</span>
                    </div>
                `;
                
                scheduleList.appendChild(scheduleItem);
            });
        }

        // Fetch initial data
        if (studentSelect) {
            console.log('Initial student value:', studentSelect.value);
            if (studentSelect.value) {
                fetchDashboardData(studentSelect.value);
            }

            // Add change event listener
            studentSelect.addEventListener('change', (e) => {
                console.log('Student selected:', e.target.value);
                fetchDashboardData(e.target.value);
            });
        } else {
            console.error('Student select element not found');
        }
    } else {
        console.log('Dashboard elements not found');
    }

    // Sidebar functionality (if needed)
    const sidebar = document.querySelector('.sidebar');
    const toggle = document.querySelector('.sidebar-toggle');
    if (toggle) {
        toggle.addEventListener('click', () => {
            if (sidebar) sidebar.classList.toggle('collapsed');
        });
    }

    // Profile dropdown functionality
    const dropdown = document.querySelector('.profile-dropdown');
    if (dropdown) {
        dropdown.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('show');
        });

        document.addEventListener('click', (e) => {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    }
});

function toggleDropdown() {
    const dropdown = document.getElementById('profileDropdown');
    dropdown.classList.toggle('show');

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.profile-dropdown')) {
            dropdown.classList.remove('show');
        }
    });
    
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.profile-icon')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.style.display === "block") {
                openDropdown.style.display = "none";
            }
        }
    }
}