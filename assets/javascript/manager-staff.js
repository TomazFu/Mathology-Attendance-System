document.addEventListener("DOMContentLoaded", () => {
    // Fetch and display staff data when the page loads
    fetchStaff();

    // Get references to modal, form, and button elements
    const addStaffButton = document.getElementById("addStaffButton");
    const addStaffModal = document.getElementById("addStaffModal");
    const addStaffForm = document.getElementById("addStaffForm");
    const closeModalButton = document.getElementById("closeModalButton");
    const modalTitle = document.getElementById("modalTitle");
    const staffIdInput = document.getElementById("staffId"); 

    // Show the Add Staff modal when the button is clicked
    addStaffButton.addEventListener("click", () => {
        modalTitle.textContent = "Add Staff";
        addStaffForm.reset();
        staffIdInput.value = ""; 
        addStaffModal.style.display = "flex";
    });

    // Close the modal when the close button is clicked
    closeModalButton.addEventListener("click", () => {
        addStaffModal.style.display = "none";
    });

    // Close the modal when clicking outside the modal content
    addStaffModal.addEventListener("click", (event) => {
        if (event.target === addStaffModal) {
            addStaffModal.style.display = "none";
        }
    });

    // Handle form submission to add or edit staff
    addStaffForm.addEventListener("submit", async (event) => {
        event.preventDefault();
        const formData = new FormData(addStaffForm);
        const isEditing = staffIdInput.value;

        try {
            const response = await fetch("../manager/includes/fetch-add-staff.php", {
                method: isEditing ? "PUT" : "POST",
                body: new URLSearchParams([...formData.entries()]), // Serialize data for PUT
            });

            const data = await response.json();

            if (data.success) {
                fetchStaff(); // Refresh the staff table
                addStaffModal.style.display = "none";
                addStaffForm.reset();
            } else {
                alert(data.message || "Failed to save staff. Please try again.");
            }
        } catch (error) {
            console.error("Error saving staff:", error);
            alert("An error occurred while saving staff. Please try again.");
        }
    });

    // Function to handle editing staff
    async function handleEdit(staff_id) {
        try {
            const response = await fetch(`../manager/includes/fetch-add-staff.php?staff_id=${staff_id}`);
            const staff = await response.json();

            if (!staff.success) {
                alert("Staff not found");
                return;
            }

            // Set the modal to edit state
            modalTitle.textContent = "Edit Staff";
            staffIdInput.value = staff.staff_id; // Set staff_id for editing
            document.getElementById("staffName").value = staff.name;
            document.getElementById("staffQualification").value = staff.qualification;
            document.getElementById("staffContact").value = staff.contact_number;
            document.getElementById("staffLeave").value = staff.leave_left;
            document.getElementById("staffStatus").value = staff.current_status;

            addStaffModal.style.display = "flex"; 
        } catch (error) {
            console.error("Error fetching staff data for editing:", error);
            alert("An error occurred while loading staff details. Please try again.");
        }
    }


    // Function to handle deleting staff
    async function handleDelete(staff_id) {
        if (!confirm("Are you sure you want to delete this staff member?")) return;

        try {
            const response = await fetch("../manager/includes/fetch-add-staff.php", {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({ staff_id }),
            });

            const data = await response.json();

            if (data.success) {
                fetchStaff(); // Refresh staff table
            } else {
                alert(data.message || "Failed to delete staff. Please try again.");
            }
        } catch (error) {
            console.error("Error deleting staff:", error);
            alert("An error occurred while deleting staff. Please try again.");
        }
    }

    // Function to fetch and display staff data
    async function fetchStaff() {
        try {
            const response = await fetch("../manager/includes/fetch-add-staff.php");
            const data = await response.json();

            // Get table body element and clear any existing rows
            const tbody = document.querySelector("#staffTable tbody");
            tbody.innerHTML = "";

            // Generate and append table rows with fetched data
            data.forEach((staff) => {
                const row = `
                    <tr>
                        <td>${staff.staff_id}</td> 
                        <td>${staff.name}</td>
                        <td>${staff.qualification}</td>
                        <td>${staff.contact_number}</td>
                        <td>${staff.leave_left}</td>
                        <td>${staff.current_status}</td>
                        <td>
                            <button class="edit-button" onclick="handleEdit(${staff.staff_id})">Edit</button>
                            <button class="delete-button" onclick="handleDelete(${staff.staff_id})">Delete</button>
                        </td>
                    </tr>`;
                tbody.innerHTML += row;
            });
        } catch (error) {
            console.error("Error fetching staff data:", error);
            alert("An error occurred while loading staff data. Please try again.");
        }
    }

    // Expose functions to the global scope for button handlers
    window.handleEdit = handleEdit;
    window.handleDelete = handleDelete;
});
