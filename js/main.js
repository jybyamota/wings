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
        const updateNavbarState = () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        };

        updateNavbarState();
        window.addEventListener('scroll', updateNavbarState, { passive: true });
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
            window.location.href = 'menu.php';
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

        if (menuSection) {
            menuSection.querySelectorAll('.fade-in').forEach(element => {
                element.classList.remove('appear');
            });
        }
    };

    document.querySelectorAll('.js-open-menu').forEach(btn => {
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

    // 5. Classic card-style menu formatting
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

            // Accordion open state is managed by animations.js via the is-open class

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
