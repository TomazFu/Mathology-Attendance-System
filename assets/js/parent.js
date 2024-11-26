document.addEventListener('DOMContentLoaded', function() {
    console.log('Parent.js loaded');
    
    // Quick action buttons
    const applyLeaveBtn = document.querySelector('[data-action="apply-leave"]');
    const viewScheduleBtn = document.querySelector('[data-action="view-schedule"]');
    const viewAttendanceBtn = document.querySelector('[data-action="view-attendance"]');
    const viewPackageBtn = document.querySelector('[data-action="view-package"]');

    console.log('Buttons found:', {
        applyLeave: applyLeaveBtn,
        viewSchedule: viewScheduleBtn,
        viewAttendance: viewAttendanceBtn,
        viewPackage: viewPackageBtn
    });

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
});

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