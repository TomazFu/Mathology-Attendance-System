<?php
function renderSidebar($role) {
    $sidebarItems = [
        'parent' => [
            ['icon' => 'fas fa-home', 'text' => 'Dashboard', 'link' => 'parent-dashboard.php'],
            ['icon' => 'fas fa-table', 'text' => 'Timetable', 'link' => 'parent-timetable.php'],
            ['icon' => 'fas fa-calendar-alt', 'text' => 'Leave', 'link' => 'parent-leave-view.php'],
            ['icon' => 'fas fa-chart-bar', 'text' => 'Attendance', 'link' => 'parent-attendance.php'],
            ['icon' => 'fas fa-th-large', 'text' => 'Package', 'link' => 'parent-package.php'],
        ],
        'manager' => [
            ['icon' => 'fas fa-home', 'text' => 'Dashboard', 'link' => 'managerDashboard.php'],
            ['icon' => 'fas fa-users', 'text' => 'Staff', 'link' => 'managerStaff.php'],
            ['icon' => 'fas fa-file-alt', 'text' => 'Report', 'link' => 'managerReport.php'],
            ['icon' => 'fas fa-sign-out-alt', 'text' => 'Logout', 'link' => 'logout.php'],
        ],
        // Add more roles as needed
    ];

    // Fetch items for the role or an empty array if role not found
    $items = $sidebarItems[$role] ?? [];

    // Get current page for active state
    $currentPage = basename($_SERVER['PHP_SELF']);

    echo '<aside class="sidebar">';
    echo '<nav><ul>';
    foreach ($items as $item) {
        // Determine if the item is active
        $isActive = $currentPage === $item['link'] ? 'class="active"' : '';
        echo "<li $isActive>
                <a href='" . htmlspecialchars($item['link'], ENT_QUOTES, 'UTF-8') . "'>
                    <i class='" . htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8') . "'></i> 
                    " . htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8') . "
                </a>
              </li>";
    }
    echo '</ul></nav>';
    echo '</aside>';
}
?>

