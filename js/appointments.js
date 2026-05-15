// Appointments Form Handler

document.addEventListener('DOMContentLoaded', function() {
    const appointmentForm = document.getElementById('appointmentForm');
    if (appointmentForm) {
        appointmentForm.addEventListener('submit', handleAppointmentSubmit);
    }
    
    // Validate date is not in the past
    const dateInput = document.querySelector('input[name="date"]');
    if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
    }
    
    // Validate at least one meeting type is selected
    setupMeetingTypeValidation();
});

function handleAppointmentSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    
    // Validate meeting type selection
    const meetings = form.querySelectorAll('input[name="meeting"]:checked');
    if (meetings.length === 0) {
        showError('Please select at least one meeting type (Physical or Virtual)');
        return;
    }
    
    // Validate time inputs
    const hour = parseInt(form.querySelector('input[name="hour"]').value);
    const minute = parseInt(form.querySelector('input[name="minute"]').value);
    
    if (isNaN(hour) || hour < 1 || hour > 12) {
        showError('Please enter a valid hour (1-12)');
        return;
    }
    
    if (isNaN(minute) || minute < 0 || minute > 59) {
        showError('Please enter a valid minute (0-59)');
        return;
    }
    
    // Convert checkbox array to string
    const meetingTypes = Array.from(meetings).map(m => m.value).join(', ');
    formData.set('meeting', meetingTypes);
    
    // Submit form
    fetch('php/process_appointment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Your appointment request has been submitted successfully!');
            form.reset();
            // Optionally redirect after a delay
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 2000);
        } else {
            showError(data.message || 'An error occurred. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('An error occurred. Please try again.');
    });
}

function setupMeetingTypeValidation() {
    const meetingCheckboxes = document.querySelectorAll('input[name="meeting"]');
    meetingCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const errorMsg = document.querySelector('.meeting-error');
            if (errorMsg) {
                errorMsg.remove();
            }
        });
    });
}

function showSuccess(message) {
    const successMsg = document.getElementById('successMsg');
    if (successMsg) {
        successMsg.textContent = message;
        successMsg.style.display = 'block';
        successMsg.style.animation = 'slideIn 0.3s ease';
        
        setTimeout(() => {
            successMsg.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                successMsg.style.display = 'none';
            }, 300);
        }, 5000);
    }
}

function showError(message) {
    const errorMsg = document.getElementById('errorMsg');
    if (errorMsg) {
        errorMsg.textContent = message;
        errorMsg.style.display = 'block';
        errorMsg.style.animation = 'slideIn 0.3s ease';
    } else {
        // Create error message if it doesn't exist
        const form = document.getElementById('appointmentForm');
        const div = document.createElement('div');
        div.className = 'form-error';
        div.style.display = 'block';
        div.textContent = message;
        form.insertBefore(div, form.firstChild);
    }
}

// File upload validation
document.addEventListener('change', function(e) {
    if (e.target.name === 'documents') {
        const file = e.target.files[0];
        if (file) {
            const maxSize = 5 * 1024 * 1024; // 5MB
            if (file.size > maxSize) {
                showError('File size must be less than 5MB');
                e.target.value = '';
            }
        }
    }
});

