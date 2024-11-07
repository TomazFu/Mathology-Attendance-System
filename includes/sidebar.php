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
        'staff' => [
            ['icon' => 'fas fa-home', 'text' => 'Dashboard', 'link' => 'staff-dashboard.php'],
            ['icon' => 'fas fa-chart-bar', 'text' => 'Attendance', 'link' => 'staff-attendance.php'],
            ['icon' => 'fas fa-user', 'text' => 'Registration', 'link' => 'staff-registration.php'],
            ['icon' => 'fas fa-th-large', 'text' => 'Package', 'link' => 'staff-package.php'],
        ],
    ];

    $items = $sidebarItems[$role] ?? [];

    echo '<aside class="sidebar">';
    echo '<nav><ul>';
    foreach ($items as $item) {
        echo "<li><a href='{$item['link']}'><i class='{$item['icon']}'></i> {$item['text']}</a></li>";
    }
    echo '</ul></nav>';
    echo '</aside>';
}
?>

