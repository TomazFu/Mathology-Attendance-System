document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchInput");
    const sortSelect = document.getElementById("sortSelect");
    const tableBody = document.querySelector("#studentTable tbody");

    // Function to fetch student data
    function fetchStudentData() {
        const searchQuery = searchInput.value.trim();
        const sortOption = sortSelect.value;

        // Show loading state
        tableBody.innerHTML = "<tr><td colspan='4'>Loading...</td></tr>";

        // Construct query parameters
        const params = new URLSearchParams();
        if (searchQuery) params.append("search", searchQuery);
        if (sortOption && sortOption !== "none") params.append("sort", sortOption);

        // Fetch data from backend
        fetch(`../manager/includes/fetch-student-report.php?${params.toString()}`)
            .then(response => response.json())
            .then(response => {
                if (!response.success) {
                    throw new Error(response.error || 'Unknown error occurred');
                }

                // Log debug information
                console.log('Debug Info:', response.debug);

                const data = response.data;
                tableBody.innerHTML = ""; // Clear existing rows

                if (data.length === 0) {
                    tableBody.innerHTML = "<tr><td colspan='4'>No data found.</td></tr>";
                    return;
                }

                data.forEach(student => {
                    const row = createTableRow(student);
                    tableBody.appendChild(row);
                });
            })
            .catch(error => {
                console.error("Error details:", error);
                tableBody.innerHTML = `<tr><td colspan='4'>Error: ${error.message}</td></tr>`;
            });
    }

    function createTableRow(student) {
        const row = document.createElement('tr');
        
        // Create cells
        const nameCell = document.createElement('td');
        const programmeCell = document.createElement('td');
        const attendanceCell = document.createElement('td');
        const paymentStatusCell = document.createElement('td');
        
        // Set cell content
        nameCell.textContent = student.name;
        programmeCell.textContent = student.programme || 'No Programme';
        attendanceCell.textContent = `${student.attendance}%`;
        
        // Style payment status
        paymentStatusCell.textContent = student.payment_status;
        paymentStatusCell.classList.add('status-badge', 
            student.payment_status === 'paid' ? 'paid' : 
            student.payment_status === 'unpaid' ? 'unpaid' : 'no-payment');
        
        // Append cells to row
        row.appendChild(nameCell);
        row.appendChild(programmeCell);
        row.appendChild(attendanceCell);
        row.appendChild(paymentStatusCell);
        
        return row;
    }

    // Event listeners for search and sort
    searchInput.addEventListener("input", fetchStudentData);
    sortSelect.addEventListener("change", fetchStudentData);

    // Initial fetch on page load
    fetchStudentData();
});
