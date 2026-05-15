// Contact Form Handler

document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', handleContactSubmit);
    }
});

function handleContactSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    
    // Basic validation
    if (!formData.get('name').trim()) {
        showError('Please enter your name');
        return;
    }
    
    if (!formData.get('email').trim()) {
        showError('Please enter your email');
        return;
    }
    
    if (!formData.get('subject').trim()) {
        showError('Please enter a subject');
        return;
    }
    
    if (!formData.get('message').trim()) {
        showError('Please enter your message');
        return;
    }
    
    // Email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(formData.get('email'))) {
        showError('Please enter a valid email address');
        return;
    }
    
    // Submit form
    fetch('php/process_contact.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Your message has been sent successfully! We will contact you shortly.');
            form.reset();
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            // Optionally redirect after a delay
            setTimeout(() => {
                // Don't redirect, just show confirmation
            }, 3000);
        } else {
            showError(data.message || 'An error occurred. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('An error occurred while sending your message. Please try again.');
    });
}

function showSuccess(message) {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 120px;
        left: 50%;
        transform: translateX(-50%);
        padding: 20px 30px;
        background-color: #27ae60;
        color: white;
        border-radius: 5px;
        z-index: 1001;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        animation: slideIn 0.3s ease;
        max-width: 500px;
        text-align: center;
        word-wrap: break-word;
    `;
    notification.innerHTML = `<strong>Success!</strong><br>${message}`;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 4000);
}

function showError(message) {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 120px;
        left: 50%;
        transform: translateX(-50%);
        padding: 20px 30px;
        background-color: #e74c3c;
        color: white;
        border-radius: 5px;
        z-index: 1001;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        animation: slideIn 0.3s ease;
        max-width: 500px;
        text-align: center;
        word-wrap: break-word;
    `;
    notification.innerHTML = `<strong>Error!</strong><br>${message}`;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 4000);
}

// Add animation styles if not already present
if (!document.querySelector('style[data-contact-animations]')) {
    const style = document.createElement('style');
    style.setAttribute('data-contact-animations', 'true');
    style.textContent = `
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }
        
        @keyframes slideOut {
            from {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
            to {
                opacity: 0;
                transform: translateX(-50%) translateY(-20px);
            }
        }
    `;
    document.head.appendChild(style);
}
