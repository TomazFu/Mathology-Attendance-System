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
        'staff' => [
            ['icon' => 'fas fa-home', 'text' => 'Dashboard', 'link' => 'staff-dashboard.php'],
            ['icon' => 'fas fa-chart-bar', 'text' => 'Attendance', 'link' => 'staff-attendance.php'],
            ['icon' => 'fas fa-user', 'text' => 'Registration', 'link' => 'staff-registration.php'],
            ['icon' => 'fas fa-th-large', 'text' => 'Package', 'link' => 'staff-package.php'],
        ],
    ];

    $items = $sidebarItems[$role] ?? [];
    
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

