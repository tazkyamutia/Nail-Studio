// Filter functionality
function filterReviews(filter) {
    const cards = document.querySelectorAll('.review-card');
    const buttons = document.querySelectorAll('.filter-btn');
    
    // Update active button
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    // Filter cards with animation
    cards.forEach((card, index) => {
        card.style.animation = 'none';
        card.offsetHeight; // Trigger reflow
        
        let shouldShow = false;
        
        switch(filter) {
            case 'all':
                shouldShow = true;
                break;
            case '5-star':
                shouldShow = card.dataset.rating === '5';
                break;
            case '4-star':
                shouldShow = card.dataset.rating === '4';
                break;
            case 'skincare':
                shouldShow = card.dataset.category === 'skincare';
                break;
            case 'makeup':
                shouldShow = card.dataset.category === 'makeup';
                break;
            case 'verified':
                shouldShow = card.dataset.verified === 'true';
                break;
        }
        
        if (shouldShow) {
            card.style.display = 'block';
            setTimeout(() => {
                card.style.animation = `fadeInUp 0.6s ease forwards`;
            }, index * 50);
        } else {
            card.style.display = 'none';
        }
    });
}

// Pagination functionality
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.page-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!this.textContent.includes('←') && !this.textContent.includes('→')) {
                document.querySelectorAll('.page-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Animate cards
                const cards = document.querySelectorAll('.review-card:not([style="display: none;"])');
                cards.forEach((card, index) => {
                    card.style.animation = 'none';
                    card.offsetHeight;
                    setTimeout(() => {
                        card.style.animation = `fadeInUp 0.6s ease forwards`;
                    }, index * 100);
                });
            }
        });
    });

    // Add hover effects for enhanced interactivity
    document.querySelectorAll('.review-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Smooth scrolling for navigation
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add loading animation on page load
    window.addEventListener('load', function() {
        const reviewCards = document.querySelectorAll('.review-card');
        reviewCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });

    // Add scroll effects
    window.addEventListener('scroll', function() {
        const header = document.querySelector('header');
        if (window.scrollY > 100) {
            header.style.background = 'rgba(255, 255, 255, 0.98)';
            header.style.backdropFilter = 'blur(15px)';
        } else {
            header.style.background = 'rgba(255, 255, 255, 0.95)';
            header.style.backdropFilter = 'blur(10px)';
        }
    });

    // Mobile menu toggle (for future enhancement)
    function toggleMobileMenu() {
        const navLinks = document.querySelector('.nav-links');
        navLinks.classList.toggle('mobile-active');
    }

    // Add click effects to buttons
    document.querySelectorAll('.filter-btn, .page-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
});