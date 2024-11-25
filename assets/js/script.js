document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    const toggle = document.querySelector('.sidebar-toggle');
    const mainContent = document.querySelector('.main-content');
    const footer = document.querySelector('.site-footer');
    const sidebarItems = document.querySelectorAll('.sidebar a span');
    const leaveForm = document.getElementById('leaveForm');
    const leaveHistory = document.getElementById('leaveHistory');
    const navItems = document.querySelectorAll('.leave-nav ul li'); // Change selector to target only leave nav items
    const trackLeaveBtn = document.querySelector('.track-leave-btn');
    
    // Add this line to select the back button
    const backBtn = document.createElement('button');
    backBtn.textContent = 'Back';
    backBtn.className = 'back-btn';
    document.querySelector('.main-content').appendChild(backBtn);

    function showView(viewId) {
        if (viewId === 'leaveForm') {
            leaveForm.style.display = 'block';
            leaveHistory.style.display = 'none';
            trackLeaveBtn.style.display = 'block';
            backBtn.style.display = 'none';
        } else if (viewId === 'leaveHistory') {
            leaveForm.style.display = 'none';
            leaveHistory.style.display = 'block';
            trackLeaveBtn.style.display = 'none';
            backBtn.style.display = 'block';
        }
    }

    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            const viewId = this.querySelector('a').getAttribute('data-view');
            if (viewId) {
                e.preventDefault();
                navItems.forEach(i => i.classList.remove('active'));
                this.classList.add('active');
                showView(viewId);
            }
            // If no viewId, allow default link behavior
        });
    });

    // Form submission (you'll need to implement the actual submission logic)
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        // Add your form submission logic here
        console.log('Form submitted');
    });

    // Track Leave button functionality
    trackLeaveBtn.addEventListener('click', function() {
        showView('leaveHistory');
        navItems.forEach(item => {
            if (item.querySelector('a').getAttribute('data-view') === 'leaveHistory') {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    });

    // Add this: Back button functionality
    backBtn.addEventListener('click', function() {
        showView('leaveForm');
        navItems.forEach(item => {
            if (item.querySelector('a').getAttribute('data-view') === 'leaveForm') {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    });

    // Initially show the Leave Form
    showView('leaveForm');

    // Function to handle window resize
    function handleResize() {
        if (window.innerWidth <= 768) {
            document.body.classList.add('mobile');
        } else {
            document.body.classList.remove('mobile');
        }
    }

    // Initial check
    handleResize();

    // Add resize event listener
    window.addEventListener('resize', handleResize);

//------------------------------------------------------------------------- (tmp)

    // Parent Dashboard functionality
    if (document.getElementById('enrolled-classes-list')) {
        fetch('../includes/fetch-dashboard-data-process.php')
            .then(response => response.json())
            .then(data => {
                const classList = document.getElementById('enrolled-classes-list');
                classList.innerHTML = '';
                data.enrolledClasses.forEach(cls => {
                    const li = document.createElement('li');
                    li.textContent = cls;
                    classList.appendChild(li);
                });

                document.getElementById('enrolled-package-text').textContent = data.enrolledPackage;
                document.getElementById('attendance-percent').textContent = data.attendancePercent + '%';
                document.getElementById('leave-id').textContent = `Leave ID: ${data.latestLeave.id}`;
                document.getElementById('student-name').textContent = `Student Name: ${data.latestLeave.studentName}`;
                document.getElementById('leave-reason').textContent = `Reason: ${data.latestLeave.reason}`;
                document.getElementById('leave-dates').textContent = `Date: ${data.latestLeave.fromDate} to ${data.latestLeave.toDate}`;
            })
            .catch(error => {
                console.error('Error fetching dashboard data:', error);
            });

        document.getElementById('view-leave-btn').addEventListener('click', function() {
            alert('Viewing leave details...');
        });
    }

    // Parent Timetable functionality
    if (document.querySelector('#timetable tbody')) {
        fetch('../includes/fetch-timetable-data-process.php')
            .then(response => response.json())
            .then(data => {
                const timetableBody = document.querySelector('#timetable tbody');
                timetableBody.innerHTML = '';

                data.timetable.forEach(entry => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${entry.subject_id}</td>
                        <td>${entry.title}</td>
                        <td>${entry.room}</td>
                        <td>${entry.instructor}</td>
                        <td>${entry.time}</td>
                    `;
                    timetableBody.appendChild(row);
                });

                const classList = document.getElementById('enrolled-classes-list');
                if (classList) {
                    classList.innerHTML = '';
                    data.enrolledClasses.forEach(cls => {
                        const li = document.createElement('li');
                        li.textContent = cls;
                        classList.appendChild(li);
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching timetable data:', error);
            });
    }

    // Handle window resize with debounce
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            if (window.innerWidth < 768) {
                toggleSidebar(true);
            }
        }, 250);
    });
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