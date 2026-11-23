const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
const navLinks = document.querySelector('.nav-bar .nav-links');

if (mobileMenuToggle && navLinks) {
    function setMenuOpen(isOpen) {
        navLinks.classList.toggle('active', isOpen);
        mobileMenuToggle.setAttribute('aria-expanded', String(isOpen));
        mobileMenuToggle.setAttribute(
            'aria-label',
            isOpen ? 'Close navigation menu' : 'Open navigation menu'
        );
    }

    mobileMenuToggle.addEventListener('click', function() {
        setMenuOpen(mobileMenuToggle.getAttribute('aria-expanded') !== 'true');
    });

    navLinks.querySelectorAll('a').forEach(function(link) {
        link.addEventListener('click', function() {
            setMenuOpen(false);
        });
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            setMenuOpen(false);
        }
    });
}
