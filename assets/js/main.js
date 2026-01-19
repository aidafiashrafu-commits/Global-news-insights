// Global News Insights - Main JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const primaryMenu = document.querySelector('.primary-menu');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            primaryMenu.classList.toggle('active');
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Add active class to current nav item
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    document.querySelectorAll('.primary-menu a').forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage || (currentPage === '' && href === 'index.html')) {
            link.parentElement.classList.add('active');
        }
    });

    // Search form handling
    const searchForms = document.querySelectorAll('.search-form');
    searchForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const searchQuery = this.querySelector('input[name="s"]').value;
            if (searchQuery.trim()) {
                // In a real implementation, this would redirect to a search results page
                console.log('Searching for:', searchQuery);
                alert('Search functionality would search for: ' + searchQuery);
            }
        });
    });

    // Newsletter form handling
    const newsletterForms = document.querySelectorAll('.newsletter-form');
    newsletterForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            if (email) {
                // In a real implementation, this would send to a backend
                alert('Thank you for subscribing! Confirmation sent to: ' + email);
                this.reset();
            }
        });
    });

    // Image lazy loading
    const images = document.querySelectorAll('img[loading="lazy"]');
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src || img.src;
                    imageObserver.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    }

    // Add read time estimate to posts
    const postBody = document.querySelector('.post-body');
    if (postBody) {
        const wordCount = postBody.innerText.split(/\s+/).length;
        const readTime = Math.ceil(wordCount / 200); // Average reading speed
        const readTimeElement = document.createElement('span');
        readTimeElement.className = 'read-time';
        readTimeElement.textContent = readTime + ' min read';
        readTimeElement.style.color = '#5f6368';
        readTimeElement.style.fontSize = '14px';
        readTimeElement.style.marginLeft = '10px';
        
        const metaElement = document.querySelector('.post-meta');
        if (metaElement) {
            metaElement.appendChild(readTimeElement);
        }
    }
});

// Ticker animation
function initializeTicker() {
    const ticker = document.querySelector('.ticker');
    if (ticker) {
        const tickerItems = ticker.querySelectorAll('li');
        const tickerWidth = ticker.offsetWidth;
        const itemWidth = tickerItems[0]?.offsetWidth || 0;
        
        // Duplicate items for continuous loop
        if (tickerItems.length > 0) {
            tickerItems.forEach(item => {
                const clone = item.cloneNode(true);
                ticker.appendChild(clone);
            });
        }
    }
}

// Initialize on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeTicker);
} else {
    initializeTicker();
}
