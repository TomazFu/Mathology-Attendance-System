async function handleLeaveAction(leaveId, action) {
    const reasonElement = document.getElementById(`reason_${leaveId}`);
    const reason = reasonElement.value.trim();
    
    if (action === 'rejected' && !reason) {
        alert('Please provide a reason for rejection.');
        return;
    }

    try {
        const response = await fetch('../staff/includes/process-leave-action.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                leave_id: leaveId,
                action: action,
                reason: reason || 'Approved'
            })
        });

        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            // Remove the leave request card from the UI
            const card = reasonElement.closest('.leave-request-card');
            card.remove();
            
            // If no more leave requests, show the no requests message
            const container = document.querySelector('.leave-requests-container');
            if (!container.querySelector('.leave-request-card')) {
                container.innerHTML = `
                    <div class="no-requests">
                        <p>No pending leave requests to review.</p>
                    </div>`;
            }
        } else {
            alert(data.message || 'An error occurred');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while processing your request');
    }
} 