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

    // Add success message function
    function showSuccessMessage(message) {
        const successDiv = document.createElement('div');
        successDiv.className = 'success-message';
        successDiv.innerHTML = `
            <i class="fas fa-check-circle"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(successDiv);

        // Remove after 3 seconds
        setTimeout(() => {
            successDiv.classList.add('fade-out');
            setTimeout(() => successDiv.remove(), 3000);
        }, 3000);
    }

    // Update Add/Edit Staff submission
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
                showSuccessMessage(isEditing ? "Staff updated successfully!" : "Staff added successfully!");
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

    // Update Edit Staff handler
    window.handleEdit = async (staffId) => {
        try {
            const response = await fetch(`../manager/includes/fetch-add-staff.php?staff_id=${staffId}`);
            const data = await response.json();

            if (data.success) {
                modalTitle.textContent = "Edit Staff";
                staffIdInput.value = data.staff.staff_id;
                document.getElementById("staffEmail").value = data.staff.email;
                document.getElementById("staffPassword").value = ''; // Clear password field
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

    // Update fetchStaff function to include email in table
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
                        <td>${staff.email}</td>
                        <td>${staff.name}</td>
                        <td>${staff.qualification}</td>
                        <td>${staff.contact_number}</td>
                        <td>${staff.leave_left}</td>
                        <td>${staff.current_status}</td>
                        <td>
                            <button onclick="handleEdit(${staff.staff_id})" class="edit-btn">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button onclick="handleDelete(${staff.staff_id})" class="delete-btn">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>`;
            });
        } catch (error) {
            console.error("Error fetching staff:", error);
        }
    }

    // Add these styles to your CSS
    const styles = `
        .success-message {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 15px 25px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
        }

        .success-message.fade-out {
            animation: fadeOut 0.3s ease-out forwards;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .edit-btn, .delete-btn {
            padding: 5px 10px;
            margin: 0 2px;
            margin-bottom: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .edit-btn {
            background-color: #4CAF50;
            color: white;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
        }

        .edit-btn:hover {
            background-color: #45a049;
        }

        .delete-btn:hover {
            background-color: #da190b;
        }
    `;

    // Add styles to document
    const styleSheet = document.createElement('style');
    styleSheet.textContent = styles;
    document.head.appendChild(styleSheet);

    // Search and sort functionality
    searchInput.addEventListener("input", fetchStaff);
    sortSelect.addEventListener("change", fetchStaff);
});
