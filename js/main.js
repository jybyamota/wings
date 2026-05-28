document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Menu Toggle
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            hamburger.classList.toggle('active');
        });

        // Close menu when a link is clicked
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                hamburger.classList.remove('active');
            });
        });
    }

    // 2. Sticky Navbar Effect on Scroll
    const navbar = document.getElementById('navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }, { passive: true });
    }

    // 3. Scroll Animations (Intersection Observer)
    const fadeElements = document.querySelectorAll('.fade-in');
    
    const appearOptions = {
        threshold: 0.15,
        rootMargin: "0px 0px -50px 0px"
    };

    const appearOnScroll = new IntersectionObserver(function(entries, observer) {
        entries.forEach(entry => {
            if (!entry.isIntersecting) {
                return;
            } else {
                entry.target.classList.add('appear');
                observer.unobserve(entry.target);
            }
        });
    }, appearOptions);

    fadeElements.forEach(element => {
        appearOnScroll.observe(element);
    });

    const revealFadeIns = container => {
        if (!container) {
            return;
        }

        container.querySelectorAll('.fade-in').forEach(element => {
            element.classList.add('appear');
        });
    };

    // 4. View Menu button — pop-out overlay
    const viewMenuBtn = document.getElementById('view-menu-btn');
    const menuPopup = document.getElementById('menu-popup');
    const menuSection = document.getElementById('menu');

    const openMenuPopup = () => {
        if (!menuPopup) {
            return;
        }

        menuPopup.classList.add('is-open');
        menuPopup.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-open');
        revealFadeIns(menuSection);

        const closeBtn = menuPopup.querySelector('.menu-popup-close');
        if (closeBtn) {
            closeBtn.focus();
        }
    };

    const closeMenuPopup = () => {
        if (!menuPopup) {
            return;
        }

        menuPopup.classList.remove('is-open');
        menuPopup.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('modal-open');

        if (viewMenuBtn) {
            viewMenuBtn.focus();
        }
    };

    if (menuPopup) {
        document.querySelectorAll('.js-open-menu, #view-menu-btn').forEach(btn => {
            btn.addEventListener('click', event => {
                event.preventDefault();
                if (menuPopup.classList.contains('is-open')) {
                    closeMenuPopup();
                } else {
                    openMenuPopup();
                }
            });
        });

        document.querySelectorAll('[data-menu-popup-close]').forEach(control => {
            control.addEventListener('click', closeMenuPopup);
        });

        if (window.location.hash === '#menu') {
            openMenuPopup();
        }
    }

    document.querySelector('.scroll-indicator')?.addEventListener('click', event => {
        event.preventDefault();
        document.getElementById('discover')?.scrollIntoView({ behavior: 'smooth' });
    });

    const subscribeForm = document.querySelector('.subscribe-form');
    const subscribeNote = document.querySelector('.subscribe-note');

    if (subscribeForm && subscribeNote) {
        subscribeForm.addEventListener('submit', event => {
            event.preventDefault();
            subscribeNote.classList.add('is-visible');
            subscribeForm.reset();
        });
    }

    // 5. Menu Category Sidebar Navigation
    const categoryCards = document.querySelectorAll('.menu-popup .category-card');
    const menuCategories = document.querySelectorAll('.menu-popup .menu-category');
    let activeCardIndex = -1;
    
    const switchCategory = (activeCard) => {
        const clickedIndex = Array.from(categoryCards).indexOf(activeCard);
        
        // If clicking the same category, toggle it off
        if (activeCardIndex === clickedIndex) {
            categoryCards.forEach(card => card.classList.remove('active'));
            menuCategories.forEach((category) => {
                const content = category.querySelector('.category-content');
                if (content) {
                    content.style.display = 'none';
                }
            });
            activeCardIndex = -1;
            return;
        }
        
        // Remove active class from all cards
        categoryCards.forEach(card => card.classList.remove('active'));
        
        // Add active class to clicked card
        activeCard.classList.add('active');
        activeCardIndex = clickedIndex;
        
        // Hide all category contents and show only the active one
        menuCategories.forEach((category, index) => {
            const content = category.querySelector('.category-content');
            if (content) {
                content.style.display = index === clickedIndex ? 'block' : 'none';
            }
        });
    };
    
    categoryCards.forEach((card, index) => {
        card.addEventListener('click', (e) => {
            e.preventDefault();
            switchCategory(card);
        });
    });
    
    // Activate first category when menu opens
    const menuPopupElement = document.getElementById('menu-popup');
    if (menuPopupElement) {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'aria-hidden') {
                    if (menuPopupElement.getAttribute('aria-hidden') === 'false') {
                        // Menu is opening
                        if (categoryCards.length > 0) {
                            switchCategory(categoryCards[0]);
                        }
                    } else {
                        // Menu is closing - reset
                        activeCardIndex = -1;
                    }
                }
            });
        });
        observer.observe(menuPopupElement, { attributes: true, attributeFilter: ['aria-hidden'] });
    }

    document.addEventListener('keydown', event => {
        if (event.key !== 'Escape') {
            return;
        }

        if (menuPopup && menuPopup.classList.contains('is-open')) {
            closeMenuPopup();
        }
    });

    // Trigger initial check for elements already in viewport on load
    setTimeout(() => {
        fadeElements.forEach(element => {
            const rect = element.getBoundingClientRect();
            if(rect.top < window.innerHeight) {
                element.classList.add('appear');
            }
        });
    }, 100);

    // 6. Scroll Fade-Out Effect
    const fadeOutOnScroll = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.target.classList.contains('appear')) {
                return;
            }
            if (!entry.isIntersecting) {
                entry.target.classList.add('fade-out-up');
            } else {
                entry.target.classList.remove('fade-out-up');
            }
        });
    }, {
        threshold: [0.1],
        rootMargin: '0px 0px 0px 0px'
    });

    fadeElements.forEach(element => {
        fadeOutOnScroll.observe(element);
    });
});
