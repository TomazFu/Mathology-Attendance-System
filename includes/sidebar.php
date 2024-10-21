<?php
function renderSidebar($role) {
    $sidebarItems = [
        'parent' => [
            ['icon' => 'fas fa-home', 'text' => 'Dashboard', 'link' => '#'],
            ['icon' => 'fas fa-table', 'text' => 'Timetable', 'link' => '#'],
            ['icon' => 'fas fa-calendar-alt', 'text' => 'Leave', 'link' => '#'],
            ['icon' => 'fas fa-chart-bar', 'text' => 'Attendance', 'link' => '#'],
            ['icon' => 'fas fa-th-large', 'text' => 'Package', 'link' => '#'],
        ],
        'teacher' => [
            ['icon' => 'fas fa-home', 'text' => 'Dashboard', 'link' => '#'],
            ['icon' => 'fas fa-users', 'text' => 'Classes', 'link' => '#'],
            ['icon' => 'fas fa-book', 'text' => 'Lessons', 'link' => '#'],
            ['icon' => 'fas fa-calendar-check', 'text' => 'Attendance', 'link' => '#'],
            ['icon' => 'fas fa-chart-line', 'text' => 'Performance', 'link' => '#'],
        ],
        // Add more roles as needed
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

