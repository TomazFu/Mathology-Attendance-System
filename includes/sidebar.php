<?php
function renderSidebar($role) {
    $currentPage = basename($_SERVER['PHP_SELF']);
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
        'staff' => [
            ['icon' => 'fas fa-home', 'text' => 'Dashboard', 'link' => 'staff-dashboard.php'],
            ['icon' => 'fas fa-chart-bar', 'text' => 'Attendance', 'link' => 'staff-attendance.php'],
            ['icon' => 'fas fa-user', 'text' => 'Registration', 'link' => 'staff-registration.php'],
            ['icon' => 'fas fa-th-large', 'text' => 'Package', 'link' => 'staff-package.php'],
        ],
    ];

    // Fetch items for the role or an empty array if role not found
    $items = $sidebarItems[$role] ?? [];
    
    // Get current page for active state
    $currentPage = basename($_SERVER['PHP_SELF']);

    echo "<aside class='sidebar' id='sidebar'>";
    echo '<nav><ul>';
    foreach ($items as $item) {
        $isActive = ($currentPage === $item['link']) ? 'active' : '';
        echo "<li><a href='{$item['link']}' class='{$isActive}'>";
        echo "<i class='{$item['icon']}'></i>";
        echo "<span>{$item['text']}</span>";
        echo "</a></li>";
    }
    echo '</ul></nav>';
    echo '</aside>';
}
?>

