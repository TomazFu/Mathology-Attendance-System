document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    const toggle = document.querySelector('.sidebar-toggle');
    const mainContent = document.querySelector('.main-content');
    const footer = document.querySelector('.site-footer');
    const sidebarItems = document.querySelectorAll('.sidebar a span');
    
    // Function to handle sidebar state
    function toggleSidebar(collapse) {
        if (collapse) {
            sidebar.classList.add('collapsed');
            toggle.classList.add('collapsed');
            mainContent.classList.add('collapsed');
            footer.classList.add('collapsed');
            // Add a small delay to hide text for smooth transition
            setTimeout(() => {
                sidebarItems.forEach(item => {
                    item.style.display = 'none';
                });
            }, 300);
        } else {
            sidebar.classList.remove('collapsed');
            toggle.classList.remove('collapsed');
            mainContent.classList.remove('collapsed');
            footer.classList.remove('collapsed');
            // Show text immediately when expanding
            sidebarItems.forEach(item => {
                item.style.display = '';
            });
        }
        // Save state to localStorage
        localStorage.setItem('sidebarCollapsed', collapse);
    }

    // Initialize sidebar state from localStorage
    const isSidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    toggleSidebar(isSidebarCollapsed);

    // Handle click event with debounce
    let isToggling = false;
    toggle.addEventListener('click', function(e) {
        e.preventDefault();
        if (!isToggling) {
            isToggling = true;
            const willCollapse = !sidebar.classList.contains('collapsed');
            toggleSidebar(willCollapse);
            // Prevent multiple clicks for 500ms
            setTimeout(() => {
                isToggling = false;
            }, 500);
        }
    });

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
