document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const tabId = tab.getAttribute('data-tab');
            
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(content => content.style.display = 'none');
            
            tab.classList.add('active');
            document.getElementById(tabId).style.display = 'block';
        });
    });

    const leaveForm = document.getElementById('leaveForm');
    leaveForm.addEventListener('submit', function(e) {
        e.preventDefault();
        // Here you would typically send the form data to the server
        console.log('Form submitted');
        // You can add AJAX request here to send data to PHP backend
    });
});