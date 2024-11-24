// Function to handle the toggling of attendance status (present or absent)
function toggleAttendance(studentId, status) {
    // Get the current date selected in the dropdown
    const selectedDate = document.getElementById('date-select').value;

    // Send the updated attendance status to the server via AJAX
    updateAttendanceStatus(studentId, status, selectedDate);

    // Update the checkbox UI
    const presentCheckbox = document.getElementById(`present_${studentId}`);
    const absentCheckbox = document.getElementById(`absent_${studentId}`);

    // Reset both checkboxes and then toggle the one that matches the status
    presentCheckbox.checked = false;
    absentCheckbox.checked = false;

    if (status === 'present') {
        presentCheckbox.checked = true;
    } else if (status === 'absent') {
        absentCheckbox.checked = true;
    }
}


// Function to update the attendance status in the database
function updateAttendanceStatus(studentId, status, selectedDate) {
    // Create the AJAX request to send data to the backend
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "includes/update-attendance.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText); // Optionally log success message for debugging
        }
    };

    // Send the data (student_id, attendance_status, and selected_date) to update the record
    xhr.send(`student_id=${studentId}&attendance_status=${status}&date=${selectedDate}`);
}

function toggleExpandRecord(recordId, imageUrl) {
    const record = document.getElementById(recordId);
    const expandedImage = document.getElementById('expanded-image');

    if (expandedImage.dataset.recordId === recordId) {
        // If the image is already expanded for this record, hide it
        expandedImage.style.display = 'none';
        expandedImage.dataset.recordId = '';
    } else {
        // Expand the image for the clicked record
        expandedImage.src = imageUrl;
        expandedImage.style.display = 'block';
        expandedImage.dataset.recordId = recordId;
    }
}

function showUpdateForm(studentId) {
    // Get the specific update form element for the student
    const updateForm = document.getElementById(`update-form-${studentId}`);
    
    if (updateForm) {
        // Check the current display state and toggle it
        if (updateForm.style.display === 'none' || updateForm.style.display === '') {
            updateForm.style.display = 'block'; // Show the form
        } else {
            updateForm.style.display = 'none'; // Hide the form
        }
    }
}

function submitPayment(studentId) {
    try {
        // Get all required elements
        const packageSelect = document.getElementById(`package-select-${studentId}`);
        const registrationCheckbox = document.getElementById(`registration-${studentId}`);
        const diagnosticCheckbox = document.getElementById(`diagnostic-${studentId}`);
        const depositInput = document.getElementById(`deposit_fee-${studentId}`);
        const statusSelect = document.getElementById(`status-${studentId}`);
        const paymentDateInput = document.getElementById(`payment-date-${studentId}`);
        const parentIdInput = document.getElementById(`parent-id-${studentId}`);
        const currentPackageInput = document.getElementById(`current-package-${studentId}`);

        // Get payment method first
        const selectedPaymentMethod = document.querySelector(`input[name="payment-method-${studentId}"]:checked`);
        if (!selectedPaymentMethod) {
            alert("Please select a payment method");
            return;
        }

        // Get current package ID from students table
        const currentPackageId = currentPackageInput.value;

        // Calculate total amount and fees
        let totalAmount = 0;
        let depositFee = parseFloat(depositInput.value) || 0; // Moved this up

        // Add package price if a package is selected
        if (packageSelect.value && packageSelect.value !== 'none') {
            const selectedOption = packageSelect.options[packageSelect.selectedIndex];
            const packagePrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            totalAmount += packagePrice;
        }

        // Add registration fee if checked (50)
        if (registrationCheckbox.checked) {
            totalAmount += 50;
        }

        // Add diagnostic test fee if checked (100)
        if (diagnosticCheckbox.checked) {
            totalAmount += 100;
        }

        // Add deposit fee
        totalAmount += depositFee;

        // Validation
        if (!paymentDateInput.value) {
            alert("Please select a payment date");
            return;
        }

        // Send the payment data
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "/Mathology-Attendance-System/staff/includes/update-payment.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert("Payment updated successfully. Total amount: RM" + totalAmount);
                        location.reload();
                    } else {
                        alert("Error: " + (response.message || "Unknown error occurred"));
                    }
                } catch (e) {
                    console.error('Response:', xhr.responseText);
                    console.error('Parse error:', e);
                    alert("Error processing payment response");
                }
            }
        };

        const data = new URLSearchParams({
            student_id: studentId,
            parent_id: parentIdInput.value,
            package_id: currentPackageId,
            amount: totalAmount,
            payment_method: selectedPaymentMethod.value,
            registration: registrationCheckbox.checked ? 1 : 0,
            deposit_fee: depositFee,
            diagnostic_test: diagnosticCheckbox.checked ? 1 : 0,
            status: statusSelect.value,
            date: paymentDateInput.value
        }).toString();

        console.log('Sending payment data:', data);
        xhr.send(data);

    } catch (error) {
        console.error('Error in submitPayment:', error);
        alert(`Error: ${error.message}`);
    }
}

function updatePackage(studentId) {
    var packageSelect = document.getElementById('package-select-' + studentId);
    
    if (!packageSelect) {
        console.error('Package select element not found for student ID:', studentId);
        return;
    }

    var packageId = packageSelect.value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/Mathology-Attendance-System/staff/includes/update-package.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert("Error: " + response.message);
                }
            } catch (e) {
                console.error('Response:', xhr.responseText);
                console.error('Parse error:', e);
                alert("Error updating package: Invalid response from server");
            }
        } else {
            alert("Error updating package. Status: " + xhr.status);
        }
    };

    var data = "student_id=" + encodeURIComponent(studentId) + 
               "&package_id=" + encodeURIComponent(packageId);
    xhr.send(data);
}

// Add these new functions
function showPackagesModal() {
    document.getElementById('packagesModal').style.display = 'block';
}

function closePackagesModal() {
    document.getElementById('packagesModal').style.display = 'none';
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    var modal = document.getElementById('packagesModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

function printInvoice(paymentData) {
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    
    const invoiceHtml = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Official Receipt - ${paymentData.payment_id}</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 40px;
                    font-size: 12px;
                }
                .logo {
                    width: 200px;
                    margin-bottom: 10px;
                }
                .header {
                    margin-bottom: 20px;
                }
                .receipt-title {
                    font-size: 16px;
                    font-weight: bold;
                    margin: 10px 0;
                }
                .receipt-details {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }
                .left-details, .right-details {
                    width: 48%;
                }
                .detail-row {
                    display: flex;
                    margin-bottom: 5px;
                }
                .detail-label {
                    width: 100px;
                    font-weight: bold;
                }
                .items-table {
                    width: 100%;
                    margin-bottom: 20px;
                    border-collapse: collapse;
                }
                .items-table th, .items-table td {
                    padding: 5px;
                    text-align: left;
                }
                .amount-column {
                    text-align: right;
                }
                .totals {
                    width: 100%;
                    margin-top: 20px;
                }
                .total-row {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 5px;
                }
                .payment-method {
                    margin-top: 20px;
                }
                .checkbox {
                    width: 15px;
                    height: 15px;
                    border: 1px solid #000;
                    display: inline-block;
                    margin-right: 5px;
                }
                .checked {
                    background-color: #000;
                }
                .footer {
                    margin-top: 30px;
                    font-size: 10px;
                }
                .address {
                    text-align: center;
                    margin-top: 50px;
                    font-size: 10px;
                }
                @media print {
                    .no-print {
                        display: none;
                    }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <img src="/Mathology-Attendance-System/assets/images/logo.png" class="logo" alt="Mathology">
                <div class="receipt-title">Official Receipt</div>
            </div>
            
            <div class="receipt-details">
                <div class="left-details">
                    <div class="detail-row">
                        <div class="detail-label">Student:</div>
                        <div>${paymentData.student_name}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Guardian:</div>
                        <div>${paymentData.parent_name}</div>
                    </div>
                </div>
                <div class="right-details">
                    <div class="detail-row">
                        <div class="detail-label">Receipt No:</div>
                        <div>RCPT-${paymentData.payment_id}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Invoice No:</div>
                        <div>INV-${paymentData.payment_id}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Date:</div>
                        <div>${new Date(paymentData.date).toLocaleDateString()}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Time:</div>
                        <div>${new Date(paymentData.date).toLocaleTimeString()}</div>
                    </div>
                </div>
            </div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 30px;">No</th>
                        <th>Particulars</th>
                        <th style="width: 50px;">RM</th>
                        <th style="width: 100px;" class="amount-column">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    ${generateItemRows(paymentData)}
                </tbody>
            </table>

            <div class="totals">
                <div class="total-row">
                    <div>Total Amount:</div>
                    <div>RM ${parseFloat(paymentData.amount).toFixed(2)}</div>
                </div>
                <div class="total-row">
                    <div>Total Paid to Date:</div>
                    <div>RM ${parseFloat(paymentData.amount).toFixed(2)}</div>
                </div>
                <div class="total-row">
                    <div>Paid Today:</div>
                    <div>RM ${parseFloat(paymentData.amount).toFixed(2)}</div>
                </div>
                <div class="total-row">
                    <div>Outstanding Amount:</div>
                    <div>RM 0.00</div>
                </div>
            </div>

            <div class="payment-method">
                <div>Payment Method</div>
                <div>
                    <span class="checkbox ${paymentData.payment_method === 'cash' ? 'checked' : ''}"></span> Cash
                    <span class="checkbox ${paymentData.payment_method === 'credit-card' ? 'checked' : ''}"></span> Credit Card
                    <span class="checkbox ${paymentData.payment_method === 'cheque' ? 'checked' : ''}"></span> Cheque
                    <span class="checkbox ${paymentData.payment_method === 'bank-in' ? 'checked' : ''}"></span> Bank In
                    <span class="checkbox ${paymentData.payment_method === 'deposit' ? 'checked' : ''}"></span> Deposit
                </div>
            </div>

            <div class="footer">
                <p>Thank you! It has been great working with you and your child!</p>
                <p>1. Please be advised that all registration fees, diagnostic test and program fees paid are non - refundable with exception of Deposit.</p>
                <p>2. Cancellation of program by the customer shall be notified through written notice one (1) month in advance.</p>
                <p>3. This is a computer generated document therefore no signature is required.</p>
            </div>

            <div class="address">
                Mathology Kuchai Lama (LLP0022441)<br>
                2-4, Jalan 3/114, Kuchai Business Centre, 58200 KL
            </div>

            <button class="no-print" onclick="window.print()">Print Receipt</button>
        </body>
        </html>
    `;

    printWindow.document.write(invoiceHtml);
    printWindow.document.close();
}

function generateItemRows(paymentData) {
    let rows = '';
    let rowNumber = 1;

    if (paymentData.diagnostic_test) {
        rows += `
            <tr>
                <td>${rowNumber++}</td>
                <td>Diagnostic Test</td>
                <td>RM</td>
                <td class="amount-column">100.00</td>
            </tr>`;
    }

    if (paymentData.registration) {
        rows += `
            <tr>
                <td>${rowNumber++}</td>
                <td>Registration</td>
                <td>RM</td>
                <td class="amount-column">50.00</td>
            </tr>`;
    }

    if (paymentData.deposit_fee > 0) {
        rows += `
            <tr>
                <td>${rowNumber++}</td>
                <td>Deposit</td>
                <td>RM</td>
                <td class="amount-column">${parseFloat(paymentData.deposit_fee).toFixed(2)}</td>
            </tr>`;
    }

    if (paymentData.package_name) {
        rows += `
            <tr>
                <td>${rowNumber++}</td>
                <td>Programme Duration ${new Date(paymentData.date).toLocaleDateString()} - ${getEndDate(paymentData.date)}<br>2.00 Hours(s) Per Week</td>
                <td>RM</td>
                <td class="amount-column">${parseFloat(paymentData.amount).toFixed(2)}</td>
            </tr>`;
    }

    return rows;
}

function getEndDate(startDate) {
    const date = new Date(startDate);
    date.setMonth(date.getMonth() + 1);
    date.setDate(date.getDate() - 1);
    return date.toLocaleDateString();
}