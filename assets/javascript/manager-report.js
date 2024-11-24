document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchInput");
    const sortSelect = document.getElementById("sortSelect");
    const tableBody = document.querySelector("#studentTable tbody");

    // Function to fetch student data
    function fetchStudentData() {
        const searchQuery = searchInput.value.trim();
        const sortOption = sortSelect.value;

        // Construct query parameters
        const params = new URLSearchParams();
        if (searchQuery) params.append("search", searchQuery);
        if (sortOption && sortOption !== "none") params.append("sort", sortOption);

        // Fetch data from backend
        fetch(`../manager/includes/fetch-student-report.php?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = ""; // Clear existing rows

                if (data.length === 0) {
                    tableBody.innerHTML = "<tr><td colspan='6'>No data found.</td></tr>";
                } else {
                    data.forEach(student => {
                        const row = `
                            <tr>
                                <td>${student.name || "N/A"}</td>
                                <td>${student.programme || "N/A"}</td>
                                <td>${student.attendance || "N/A"}</td>
                                <td>${student.remaining_payment || "N/A"}</td>
                            </tr>`;
                        tableBody.innerHTML += row;
                    });
                }
            })
            .catch(error => {
                console.error("Error fetching student data:", error);
                tableBody.innerHTML = "<tr><td colspan='6'>Error fetching data.</td></tr>";
            });
    }

    // Event listeners for search and sort
    searchInput.addEventListener("input", fetchStudentData);
    sortSelect.addEventListener("change", fetchStudentData);

    // Initial fetch on page load
    fetchStudentData();
});
