document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const sortSelect = document.getElementById('sortSelect');
    const packageCards = document.getElementById('packageCards');

    // Function to fetch and update package data
    async function fetchPackageData() {
        try {
            const searchTerm = searchInput.value.trim();
            const sortOption = sortSelect.value;

            // Show loading state
            packageCards.innerHTML = '<div class="loading">Loading...</div>';

            // Construct query parameters
            const params = new URLSearchParams();
            if (searchTerm) params.append('search', searchTerm);
            if (sortOption) params.append('sort', sortOption);

            // Fetch data from backend
            const response = await fetch(`../manager/includes/fetch-package-usage.php?${params.toString()}`);
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Failed to fetch package data');
            }

            // Update the cards
            packageCards.innerHTML = '';
            data.packages.forEach(package => {
                const card = createPackageCard(package);
                packageCards.appendChild(card);
            });

        } catch (error) {
            console.error('Error fetching package data:', error);
            packageCards.innerHTML = `<div class="error">Error: ${error.message}</div>`;
        }
    }

    // Function to create a package card
    function createPackageCard(package) {
        const div = document.createElement('div');
        div.className = 'stat-card';
        div.innerHTML = `
            <h3>${package.package_name}</h3>
            <div class="stat-details">
                <div class="stat-item">
                    <span class="stat-label">Students:</span>
                    <span class="stat-value">${package.student_count}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Usage:</span>
                    <span class="stat-value">${package.usage_percentage}%</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Price:</span>
                    <span class="stat-value">RM ${package.package_price}</span>
                </div>
            </div>
        `;
        return div;
    }

    // Add event listeners
    searchInput.addEventListener('input', fetchPackageData);
    sortSelect.addEventListener('change', fetchPackageData);

    // Initial fetch
    fetchPackageData();
});