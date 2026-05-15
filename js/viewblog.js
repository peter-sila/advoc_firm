// View Blog Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    setupSearchFunctionality();
    setupTagSearch();
    highlightCurrentTag();
});

function setupSearchFunctionality() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchBlogs(this.value);
            }
        });
    }
}

function setupTagSearch() {
    const tags = document.querySelectorAll('.tags span');
    tags.forEach(tag => {
        tag.style.cursor = 'pointer';
        tag.addEventListener('click', function() {
            searchTag(this);
        });
    });
}

function searchTag(element) {
    const tag = element.textContent;
    const url = new URL('blogs.php', window.location.origin);
    url.searchParams.set('tag', tag);
    window.location.href = url.toString();
}

function searchBlogs(query) {
    if (query.trim() === '') {
        return;
    }
    const url = new URL('blogs.php', window.location.origin);
    url.searchParams.set('search', query);
    window.location.href = url.toString();
}

function highlightCurrentTag() {
    const params = new URLSearchParams(window.location.search);
    const currentTag = params.get('tag');
    
    if (currentTag) {
        const tags = document.querySelectorAll('.tags span');
        tags.forEach(tag => {
            if (tag.textContent.toLowerCase() === currentTag.toLowerCase()) {
                tag.style.backgroundColor = '#e74c3c';
                tag.style.color = 'white';
            }
        });
    }
}

// Social sharing functionality
document.addEventListener('click', function(e) {
    if (e.target.closest('.share-btn')) {
        const platform = e.target.closest('.share-btn').dataset.platform;
        shareArticle(platform);
    }
});

function shareArticle(platform) {
    const title = document.querySelector('.main-content h1').textContent;
    const url = window.location.href;
    const text = `Check out this legal article: ${title}`;
    
    let shareUrl;
    
    switch(platform) {
        case 'facebook':
            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
            break;
        case 'twitter':
            shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(text)}`;
            break;
        case 'linkedin':
            shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`;
            break;
        case 'email':
            shareUrl = `mailto:?subject=${encodeURIComponent(title)}&body=${encodeURIComponent(text + '\n\n' + url)}`;
            break;
        default:
            return;
    }
    
    if (platform === 'email') {
        window.location.href = shareUrl;
    } else {
        window.open(shareUrl, '_blank', 'width=600,height=400');
    }
}


function copyLink() {
    const url = window.location.href;
    navigator.clipboard.writeText(url)
        .then(() => alert('Link copied to clipboard'))
        .catch(() => alert('Unable to copy link.'));
}

function copyToClipboard() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        showNotification('Link copied to clipboard!', 'success');
    }).catch(err => {
        showNotification('Failed to copy link', 'error');
    });
}

// Related articles click handler
document.addEventListener('click', function(e) {
    if (e.target.closest('.related-card a')) {
        // Link will navigate naturally
        // You can add analytics here if needed
    }
});

// Table of contents generator (if needed)
function generateTableOfContents() {
    const headings = document.querySelectorAll('.main-content h2, .main-content h3');
    
    if (headings.length === 0) return;
    
    const toc = document.createElement('div');
    toc.className = 'table-of-contents';
    toc.innerHTML = '<h4>Table of Contents</h4><ul>';
    
    headings.forEach((heading, index) => {
        const id = `heading-${index}`;
        heading.id = id;
        const level = heading.tagName === 'H2' ? 0 : 1;
        const indent = 'margin-left: ' + (level * 20) + 'px';
        toc.innerHTML += `<li style="${indent}"><a href="#${id}">${heading.textContent}</a></li>`;
    });
    
    toc.innerHTML += '</ul>';
    
    const mainContent = document.querySelector('.main-content');
    if (mainContent && headings.length > 3) {
        mainContent.insertBefore(toc, mainContent.querySelector('.content'));
    }
}

// Call table of contents generator
generateTableOfContents();

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background-color: ${type === 'success' ? '#27ae60' : '#e74c3c'};
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

