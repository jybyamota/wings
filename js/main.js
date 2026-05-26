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
                openMenuPopup();
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

    // 5. Floating Menu Category Viewer
    const categoryCards = document.querySelectorAll('.category-card');
    const menuModal = document.querySelector('.menu-modal');
    const modalTitle = document.getElementById('menu-modal-title');
    const modalImage = document.querySelector('.menu-modal-image');
    const modalItems = document.querySelector('.menu-modal-items');
    let activeCategoryCard = null;

    const closeMenuModal = () => {
        if (!menuModal) {
            return;
        }

        menuModal.classList.remove('is-open');
        menuModal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('modal-open');

        if (activeCategoryCard) {
            activeCategoryCard.focus();
            activeCategoryCard = null;
        }
    };

    const openMenuModal = category => {
        const card = category.querySelector('.category-card');
        const title = category.querySelector('.category-card-title');
        const content = category.querySelector('.category-content');

        if (!menuModal || !modalTitle || !modalImage || !modalItems || !card || !title || !content) {
            return;
        }

        activeCategoryCard = card;
        modalTitle.textContent = title.textContent;
        modalImage.style.backgroundImage = `url('${card.dataset.categoryImage}')`;
        modalItems.innerHTML = content.innerHTML;
        menuModal.classList.add('is-open');
        menuModal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-open');

        const closeButton = menuModal.querySelector('.menu-modal-close');
        if (closeButton) {
            closeButton.focus();
        }
    };

    categoryCards.forEach(card => {
        card.addEventListener('click', () => {
            const category = card.closest('.menu-category');
            if (category) {
                openMenuModal(category);
            }
        });
    });

    document.querySelectorAll('[data-menu-close]').forEach(closeControl => {
        closeControl.addEventListener('click', closeMenuModal);
    });

    document.addEventListener('keydown', event => {
        if (event.key !== 'Escape') {
            return;
        }

        if (menuModal && menuModal.classList.contains('is-open')) {
            closeMenuModal();
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
