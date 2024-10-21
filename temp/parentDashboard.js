// Fetch data when the page loads
document.addEventListener('DOMContentLoaded', function() {
    fetch('fetchDashboardData.php')
        .then(response => response.json())  // Convert the response to JSON
        .then(data => {
            // Update the enrolled classes
            const classList = document.getElementById('enrolled-classes-list');
            classList.innerHTML = '';  // Clear previous content
            data.enrolledClasses.forEach(cls => {
                const li = document.createElement('li');
                li.textContent = cls;
                classList.appendChild(li);
            });

            // Update enrolled package
            document.getElementById('enrolled-package-text').textContent = data.enrolledPackage;

            // Update attendance
            document.getElementById('attendance-percent').textContent = data.attendancePercent + '%';

            // Update latest leave details
            document.getElementById('leave-id').textContent = `Leave ID: ${data.latestLeave.id}`;
            document.getElementById('student-name').textContent = `Student Name: ${data.latestLeave.studentName}`;
            document.getElementById('leave-reason').textContent = `Reason: ${data.latestLeave.reason}`;
            document.getElementById('leave-dates').textContent = `Date: ${data.latestLeave.fromDate} to ${data.latestLeave.toDate}`;
        })
        .catch(error => {
            console.error('Error fetching dashboard data:', error);
        });
});

// Event listener for the "View Details" button
document.getElementById('view-leave-btn').addEventListener('click', function() {
    alert('Viewing leave details...');
});
