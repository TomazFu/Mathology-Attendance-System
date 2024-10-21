document.addEventListener('DOMContentLoaded', function() {
    const leaveForm = document.getElementById('leaveForm');
    const leaveHistory = document.getElementById('leaveHistory');
    const navItems = document.querySelectorAll('nav ul li');
    const trackLeaveBtn = document.querySelector('.track-leave-btn');

    function showView(viewId) {
        if (viewId === 'leaveForm') {
            
            leaveForm.style.display = 'block';
            leaveHistory.style.display = 'none';
            trackLeaveBtn.style.display = 'block';
        } else if (viewId === 'leaveHistory') {
            leaveForm.style.display = 'none';
            leaveHistory.style.display = 'block';
            trackLeaveBtn.style.display = 'none';
        }
    }

    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            navItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');

            const viewId = this.querySelector('a').getAttribute('data-view');
            if (viewId) {
                showView(viewId);
            }
        });
    });

    // Form submission
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        // Add your form submission logic here
        console.log('Form submitted');
        // Show a success message
        alert('Leave request submitted successfully!');
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

    // File upload preview
    const fileUpload = document.getElementById('file-upload');
    const fileUploadLabel = document.querySelector('.custom-file-upload');
    fileUpload.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            fileUploadLabel.textContent = e.target.files[0].name;
        } else {
            fileUploadLabel.innerHTML = '<i class="material-icons">cloud_upload</i> Upload Leave Application';
        }
    });

    // Initially show the Leave Form
    showView('leaveForm');
});