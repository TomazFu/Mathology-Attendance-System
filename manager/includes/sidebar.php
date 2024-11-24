<?php
function renderSidebar($role) {
    $sidebarItems = [
        'manager' => [
            ['icon' => 'fas fa-home', 'text' => 'Dashboard', 'link' => 'managerDashboard.php'],
            ['icon' => 'fas fa-users', 'text' => 'Staff', 'link' => 'managerStaff.php'],
            ['icon' => 'fas fa-file-alt', 'text' => 'Report', 'link' => 'managerReport.php'],
            ['icon' => 'fas fa-sign-out-alt', 'text' => 'Logout', 'link' => 'logout.php'],
        ],
    ];

    // Fetch items for the role or an empty array if role not found
    $items = $sidebarItems[$role] ?? [];

    // HTML rendering
    echo '<aside class="sidebar">';
    echo '<nav><ul>';
    $currentPage = basename($_SERVER['PHP_SELF']); // Current page
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
