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
            // Remove the appear class to reset
            element.classList.remove('appear');
            // Force a reflow to trigger the animation again
            void element.offsetWidth;
            // Add the appear class back to trigger the animation
            element.classList.add('appear');
        });
    };

    // 4. Full-screen sliding menu panel
    const menuSection = document.getElementById('menu');
    const menuPopup = document.getElementById('menu-popup');
    const openMenuPanel = () => {
        if (!menuPopup) {
            return;
        }
        menuPopup.classList.add('is-open');
        menuPopup.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-open');
        if (menuSection) {
            revealFadeIns(menuSection);
        }
    };

    const closeMenuPanel = () => {
        if (!menuPopup) {
            return;
        }
        menuPopup.classList.remove('is-open');
        menuPopup.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('modal-open');
        
        // Reset fade-in animations for menu section
        if (menuSection) {
            menuSection.querySelectorAll('.fade-in').forEach(element => {
                element.classList.remove('appear');
            });
        }
    };

    document.querySelectorAll('.js-open-menu, #view-menu-btn').forEach(btn => {
        btn.addEventListener('click', event => {
            event.preventDefault();
            openMenuPanel();
        });
    });

    document.querySelectorAll('[data-menu-popup-close]').forEach(control => {
        control.addEventListener('click', closeMenuPanel);
    });

    if (window.location.hash === '#menu' && menuPopup) {
        openMenuPanel();
    }

    document.querySelector('.scroll-indicator')?.addEventListener('click', event => {
        event.preventDefault();
        document.getElementById('discover')?.scrollIntoView({ behavior: 'smooth' });
    });

    document.querySelectorAll('a.reservation-link').forEach(link => {
        link.addEventListener('click', event => {
            event.preventDefault();
            window.location.href = 'reservation.php';
        });
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

    // 5. Menu Tabs Navigation
    const setupMenuTabs = () => {
        const menuTabs = document.querySelectorAll('.menu-tab');
        const menuCategories = document.querySelectorAll('.menu-category');

        if (!menuTabs.length || !menuCategories.length) {
            return;
        }

        const showCategory = (tabName) => {
            // Hide all categories
            menuCategories.forEach(cat => {
                cat.classList.remove('active');
                const content = cat.querySelector('.category-content');
                if (content) {
                    content.style.display = 'none';
                }
            });

            // Remove active from all tabs
            menuTabs.forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected category
            const selectedCategory = document.querySelector(`[data-category="${tabName}"]`);
            if (selectedCategory) {
                selectedCategory.classList.add('active');
                const content = selectedCategory.querySelector('.category-content');
                if (content) {
                    content.style.display = 'block';
                }
            }

            // Set active tab
            const activeTab = document.querySelector(`[data-tab="${tabName}"]`);
            if (activeTab) {
                activeTab.classList.add('active');
                // Scroll tab into view
                activeTab.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
        };

        // Add click handlers to tabs
        menuTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const tabName = tab.getAttribute('data-tab');
                showCategory(tabName);
            });
        });

        // Show first tab by default
        if (menuTabs[0]) {
            showCategory(menuTabs[0].getAttribute('data-tab'));
        }
    };

    // Call setup when menu opens
    const setupMenuObserver = () => {
        const menuPopup = document.getElementById('menu-popup');
        if (!menuPopup) return;

        const observer = new MutationObserver(() => {
            if (!menuPopup.hasAttribute('aria-hidden') || menuPopup.getAttribute('aria-hidden') === 'false') {
                setupMenuTabs();
            }
        });

        observer.observe(menuPopup, { attributes: true, attributeFilter: ['aria-hidden'] });
        
        // Initial setup if menu is already visible
        if (!menuPopup.hasAttribute('aria-hidden') || menuPopup.getAttribute('aria-hidden') === 'false') {
            setupMenuTabs();
        }
    };

    setupMenuObserver();

    // 6. Classic card-style menu formatting
    const menuPopupElement = document.getElementById('menu-popup');
    const menuCategories = document.querySelectorAll('.menu-popup .menu-category');

    const applyClassicMenuFormat = () => {
        if (!menuPopupElement || !menuCategories.length) {
            return;
        }

        menuPopupElement.classList.add('menu-format-classic');

        menuCategories.forEach(category => {
            const categoryContent = category.querySelector('.category-content');
            const menuItems = category.querySelectorAll('.menu-item');

            if (categoryContent) {
                // Content display is handled by tabs now
            }

            menuItems.forEach(item => {
                const itemInfo = item.querySelector('.menu-item-info');
                const itemPrice = item.querySelector('.menu-item-price');

                if (itemInfo && !itemInfo.querySelector('.menu-item-stars')) {
                    const stars = document.createElement('div');
                    stars.className = 'menu-item-stars';
                    stars.textContent = '★★★★★';
                    itemInfo.insertBefore(stars, itemInfo.firstChild);
                }

                if (itemInfo && !itemInfo.querySelector('.menu-item-cta')) {
                    const cta = document.createElement('button');
                    cta.type = 'button';
                    cta.className = 'menu-item-cta';
                    cta.textContent = 'Read More';
                    itemInfo.appendChild(cta);
                }

                if (itemPrice) {
                    itemPrice.classList.add('menu-item-price-tag');
                }
            });
        });
    };

    applyClassicMenuFormat();

    document.addEventListener('keydown', event => {
        if (event.key !== 'Escape') {
            return;
        }
        closeMenuPanel();
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
