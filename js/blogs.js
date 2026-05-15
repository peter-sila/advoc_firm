
// Blogs Pagination Handler

let currentOffset = 0;
const postsPerPage = 4;

document.addEventListener('DOMContentLoaded', function() {
    const loadMoreButton = document.getElementById('loadMoreBtn');
    const blogContainer = document.getElementById('blog-container');
    
    if (loadMoreButton) {
        loadMoreButton.addEventListener('click', loadMoreBlogs);
    }
    
    // Check if there are more posts to load
    checkIfMorePostsExist();
});

function loadMoreBlogs() {
    const button = document.getElementById('loadMoreBtn');
    button.disabled = true;
    button.textContent = 'Loading...';
    
    currentOffset += postsPerPage;
    
    fetch('php/fetchblog.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `offset=${currentOffset}&limit=${postsPerPage}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.posts.length > 0) {
            displayBlogs(data.posts);
            button.disabled = false;
            button.textContent = 'Load More Articles';
            
            // Check if there are more posts available
            if (data.posts.length < postsPerPage) {
                button.disabled = true;
                button.textContent = 'No More Articles';
            }
        } else {
            button.disabled = true;
            button.textContent = 'No More Articles';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.disabled = false;
        button.textContent = 'Load More Articles';
        showNotification('Error loading articles. Please try again.', 'error');
    });
}

function displayBlogs(posts) {
    const container = document.getElementById('blog-container');
    
    posts.forEach(post => {
        const card = document.createElement('div');
        card.className = 'n-card';
        card.innerHTML = `
            <img src="${escapeHtml(post.image || 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?w=400')}" alt="${escapeHtml(post.title)}">
            <h3>${escapeHtml(post.title)}</h3>
            <p>${escapeHtml(post.excerpt || post.content.substring(0, 100))}...</p>
            <a href="viewblog.php?id=${post.id}">Read More</a>
        `;
        container.appendChild(card);
    });
    
    // Add smooth scroll animation
    const newCards = container.querySelectorAll('.n-card:last-child');
    newCards.forEach(card => {
        card.style.animation = 'slideIn 0.3s ease';
    });
}

function checkIfMorePostsExist() {
    // This function checks if there are more posts after the initial load
    // If the load more button isn't shown, it means all posts are already displayed
    const button = document.getElementById('loadMoreBtn');
    const container = document.getElementById('blog-container');
    
    if (container && container.children.length >= postsPerPage) {
        // Button should be visible if there are exactly or more than postsPerPage items
        if (button) {
            button.style.display = 'block';
        }
    } else if (button) {
        button.style.display = 'none';
    }
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 150px;
        left: 50%;
        transform: translateX(-50%);
        padding: 15px 20px;
        background-color: ${type === 'error' ? '#e74c3c' : '#27ae60'};
        color: white;
        border-radius: 5px;
        z-index: 1001;
        animation: slideIn 0.3s ease;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add animation styles
if (!document.querySelector('style[data-blog-animations]')) {
    const style = document.createElement('style');
    style.setAttribute('data-blog-animations', 'true');
    style.textContent = `
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }
    `;
    document.head.appendChild(style);
}
