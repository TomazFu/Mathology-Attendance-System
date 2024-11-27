document.addEventListener("DOMContentLoaded", () => {
    const addStaffButton = document.getElementById("addStaffButton");
    const addStaffModal = document.getElementById("addStaffModal");
    const addStaffForm = document.getElementById("addStaffForm");
    const closeModalButton = document.getElementById("closeModalButton");
    const modalTitle = document.getElementById("modalTitle");
    const staffIdInput = document.getElementById("staffId");
    const searchInput = document.getElementById("searchInput");
    const sortSelect = document.getElementById("sortSelect");

    // Fetch and display staff data
    fetchStaff();

    // Open Add Staff modal
    addStaffButton.addEventListener("click", () => {
        modalTitle.textContent = "Add Staff";
        addStaffForm.reset();
        staffIdInput.value = "";
        addStaffModal.style.display = "flex";
    });

    // Close modal
    closeModalButton.addEventListener("click", () => {
        addStaffModal.style.display = "none";
    });

    // Close modal when clicking outside
    addStaffModal.addEventListener("click", (event) => {
        if (event.target === addStaffModal) {
            addStaffModal.style.display = "none";
        }
    });

    // Add/Edit Staff
    addStaffForm.addEventListener("submit", async (event) => {
        event.preventDefault();
        const formData = new FormData(addStaffForm);
        const isEditing = staffIdInput.value;

        try {
            const response = await fetch("../manager/includes/fetch-add-staff.php", {
                method: isEditing ? "PUT" : "POST",
                body: new URLSearchParams([...formData.entries()]),
            });

            const data = await response.json();
            if (data.success) {
                fetchStaff();
                addStaffModal.style.display = "none";
                addStaffForm.reset();
            } else {
                alert(data.message || "Failed to save staff.");
            }
        } catch (error) {
            console.error("Error saving staff:", error);
            alert("Error occurred while saving staff.");
        }
    });

    // Edit Staff
    window.handleEdit = async (staffId) => {
        try {
            const response = await fetch(`../manager/includes/fetch-add-staff.php?staff_id=${staffId}`);
            const data = await response.json();

            if (data.success) {
                modalTitle.textContent = "Edit Staff";
                staffIdInput.value = data.staff.staff_id;
                document.getElementById("staffName").value = data.staff.name;
                document.getElementById("staffQualification").value = data.staff.qualification;
                document.getElementById("staffContact").value = data.staff.contact_number;
                document.getElementById("staffLeave").value = data.staff.leave_left;
                document.getElementById("staffStatus").value = data.staff.current_status;

                addStaffModal.style.display = "flex";
            } else {
                alert("Staff not found.");
            }
        } catch (error) {
            console.error("Error fetching staff details:", error);
        }
    };

    // Delete Staff
    window.handleDelete = async (staffId) => {
        if (!confirm("Are you sure you want to delete this staff?")) return;

        try {
            const response = await fetch("../manager/includes/fetch-add-staff.php", {
                method: "DELETE",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({ staff_id: staffId }),
            });

            const data = await response.json();
            if (data.success) {
                fetchStaff();
            } else {
                alert("Failed to delete staff.");
            }
        } catch (error) {
            console.error("Error deleting staff:", error);
        }
    };

    // Fetch staff
    async function fetchStaff() {
        try {
            const searchTerm = searchInput.value;
            const sortValue = sortSelect.value;
            
            const url = new URL("../manager/includes/fetch-add-staff.php", window.location.href);
            if (searchTerm) url.searchParams.append("search", searchTerm);
            if (sortValue) url.searchParams.append("sort", sortValue);

            const response = await fetch(url);
            const data = await response.json();

            const tbody = document.querySelector("#staffTable tbody");
            tbody.innerHTML = "";

            data.staff.forEach((staff) => {
                tbody.innerHTML += `
                    <tr>
                        <td>${staff.staff_id}</td>
                        <td>${staff.name}</td>
                        <td>${staff.qualification}</td>
                        <td>${staff.contact_number}</td>
                        <td>${staff.leave_left}</td>
                        <td>${staff.current_status}</td>
                        <td>
                            <button onclick="handleEdit(${staff.staff_id})">Edit</button>
                            <button onclick="handleDelete(${staff.staff_id})">Delete</button>
                        </td>
                    </tr>`;
            });
        } catch (error) {
            console.error("Error fetching staff:", error);
        }
    }

    // Search and sort functionality
    searchInput.addEventListener("input", fetchStaff);
    sortSelect.addEventListener("change", fetchStaff);
});
